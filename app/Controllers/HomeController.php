<?php

namespace KeepMe\Controllers;

use Ofat\SilexJWT\JWTAuth;

use Silex\Application;

use Silex\ControllerCollection;
use Silex\Api\ControllerProviderInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use KeepMe\Entities\User;

class HomeController implements ControllerProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function connect(Application $app)
    {
        /* @var $controllers ControllerCollection */
        $controllers = $app['controllers_factory'];

        $controllers->get('/', [$this, 'index']);

        $controllers->post('/login', [$this, 'login']);

        return $controllers;
    }

    public function index(Application $app)
    {
        // $token = $app['jwt_auth']->getToken();

        return $app->json("home", 200);
    }

    public function login(Application $app, Request $req) 
    {
        $email    = $req->request->get('email', null);
        $password = sha1($req->request->get('password', null));

        if (null === $email || null === $password) {
            return $app->abort(400, "Empty email or password");
        }

        $user = $app["repositories"]("User")->findOneBy(["email" => $email,"password" => $password]);

        $token = $app['jwt_auth']->generateToken($user->toArray());

        return $app->json(['token' => $token], 200);
    }

}
