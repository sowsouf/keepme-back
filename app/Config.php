<?php

namespace KeepMe;

use Ofat\SilexJWT\JWTAuth;

use Silex\Application;
use Silex\Provider;


use Pimple\Container;
use Pimple\ServiceProviderInterface;

use Silex\Provider\DoctrineServiceProvider;
use JDesrosiers\Silex\Provider\CorsServiceProvider;
use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Saxulum\Console\Provider\ConsoleProvider;
use Saxulum\DoctrineOrmManagerRegistry\Provider\DoctrineOrmManagerRegistryProvider;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ParameterBag;


/**
 * Configuration principale de l'application
 */
class Config implements ServiceProviderInterface
{
    private $env = "production";

    public function __construct($env = null)
    {
        if (null !== $env) {
            $this->env = $env;
            if (true === file_exists(__DIR__ . "/Env/{$this->env}.php")) {
                require_once __DIR__ . "/Env/{$this->env}.php";
            }
        }
    }

    public function authBeforeFunction(Request $req, Application $app)
    {
        // On autorise les OPTIONS sans auth
        if ('OPTIONS' === $req->getMethod()) {
            return;
        }

        // On saute les droits et l'authentification 
        $allowed = [];

        //Register silex jwt service provider. Add jwt secret key
        $app->register(new JWTAuth(), [
            'jwt.secret' => 'azertyuiopqsdfghjklmwxcvbn'
        ]);

        //Example of jwt generating
        // $app->get('/', function (Request $request) use ($app) {
        //     $req->request->all();
        //     $userId = 1;
        //     $test = $app['jwt_auth']->generateToken($userId);

        //     return $app->json(['token' => $app['jwt_auth']->getTokeb()]);
        // });


    }

    /**
     * @{inherit doc}
     */
    public function register(Container $app)
    {
        $this->registerEnvironmentParams($app);
        $this->registerServiceProviders($app);
        $this->registerRoutes($app);
        //$app->after($app["cors"]);

        $app->after(function (Request $request, Response $response) {
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
        });

        

        // On peut faire $req->request->all() ou $req->request->get('mavariable')
        // au lieu de faire un json_decode($req->getContent(), true)
        $app->before(function (Request $request) use ($app) {
            // on ne s'interese qu'aux requêtes de type "application/json"
            if (0 !== strpos($request->headers->get('Content-Type'), 'application/json')) {
                return;
            }

            $params = json_decode($request->getContent(), true);
            if (false === is_array($params)) {
                $app->abort(400, "Invalid JSON data");
            }

            $request->request->replace($params);
        });

        $app["dispatcher"]->addSubscriber(new Utils\Silex\EventSubscriber\ExceptionListener($app));

    }

    /**
     * Set up environmental variables
     *
     * @param Application $app Silex Application
     *
     */
    private function registerEnvironmentParams(Application $app)
    {
        include "Utils/Silex/Middlewares.php";

        $app['application_name']      = 'keepme-api';
        $app['application_env']       = $this->env;
        $app['application_path']      = realpath(__DIR__ . "/../");
        $app['application_namespace'] = __NAMESPACE__;

        $app['db_host']     = getenv("KEEPME_DATABASE_HOST");
        $app['db_name']     = getenv("KEEPME_DATABASE_NAME");
        $app['db_user']     = getenv("KEEPME_DATABASE_USER");
        $app['db_password'] = getenv("KEEPME_DATABASE_PWD");
    }

    /**
     * Register Silex service providers
     *
     * @param  Application $app Silex Application
     */
    private function registerServiceProviders(Application $app)
    {
        $app->register(new Provider\ServiceControllerServiceProvider());
        $app->register(new JWTAuth(), [
            'jwt.secret' => 'azertyuiopqsdfghjklmwxcvbn'
        ]);

        $app->register(new DoctrineServiceProvider());
        $app->register(new DoctrineOrmServiceProvider());

        // Doctrine (db)
        $app['db.options'] = array(
            'driver'   => 'pdo_mysql',
            'charset'  => 'utf8',
            'host'     => $app['db_host'],
            'dbname'   => $app['db_name'],
            'user'     => $app['db_user'],
            'password' => $app['db_password'],
        );

        // Doctrine (orm)
        $app['orm.proxies_dir'] = $app['application_path'] . '/cache/doctrine/proxies';
        $app['orm.default_cache'] = 'array';
        $app['orm.em.options'] = array(
            'mappings' => array(
                array(
                    'type' => 'annotation',
                    'path' => $app['application_path'] . '/app',
                    'namespace' => "{$app['application_namespace']}\\Entities",
                ),
            ),
        );

        // Connect repositories
        // do $app["repositories"]("MyClass") instead of $app["orm.em"]->getRepository("MyClass")
        $app["repositories"] = $app->protect(
            function ($repository_name) use ($app) {
                $class_name = "\\{$app['orm.em.options']['mappings'][0]['namespace']}\\". $repository_name;
                if (class_exists($class_name)) {
                    return $app['orm.em']->getRepository($class_name);
                }
                return null;
            }
        );

        $app->register(new ConsoleProvider());
        $app->register(new DoctrineOrmManagerRegistryProvider());
        $app->register(new CorsServiceProvider());
    }

    /**
     * Mount all controllers and routes
     *
     * @param  Application $app Silex Application
     *
     */
    private function registerRoutes(Application $app)
    {
        // Recherche tous les controllers pour les loader dans $app
        foreach (glob(__DIR__ . "/Controllers/*.php") as $controller_name) {
            $controller_name = pathinfo($controller_name)["filename"];
            $class_name      = "\\KeepMe\\Controllers\\{$controller_name}";
            if (class_exists($class_name)
                && in_array("Silex\Api\ControllerProviderInterface", class_implements($class_name))
            ) {
                $app[$controller_name] = function () use ($class_name) {
                    return new $class_name();
                };
                $app->mount('/', $app[$controller_name]);
            }
        }
    }
}
