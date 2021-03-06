<?php

namespace KeepMe\Controllers;

use Ofat\SilexJWT\JWTAuth;
use Ofat\SilexJWT\Middleware\JWTTokenCheck;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\Api\ControllerProviderInterface;

Use KeepMe\Entities\Nurse;
Use KeepMe\Entities\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class NurseController implements ControllerProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function connect(Application $app)
    {
        /* @var $controllers ControllerCollection */
        $controllers = $app['controllers_factory'];

        // On récupère toutes les nurses
        $controllers->get('/nurses', [$this, 'getAllNurses'])
                    ->before(new JWTTokenCheck());

        // On récupère une nurse selon son id
        $controllers->get('/nurse/{nurse_id}', [$this, 'getNurseById'])
                    ->before(new JWTTokenCheck());

        // On crée un utilisateur
        $controllers->post('/nurse', [$this, 'createNurse'])
                    ->before(new JWTTokenCheck()); // WORK IN PROGRESS

        // On valide un utilisateur
        $controllers->put('/nurse/{nurse_id}', [$this, 'validateNurse'])
                    ->before(new JWTTokenCheck());

        return $controllers;
    }

    /**
     * Récupère toutes les nurses
     *
     * @param Application $app      Silex Application
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getAllNurses(Application $app)
    {
        $all_nurses = $app["repositories"]("Nurse")->findAll();

        return $app->json($all_nurses, 200);
    }

    /**
     * Récupère toutes les nurses
     *
     * @param Application $app       Silex Application
     * @param integer     $nurse_id  id de la nurse
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getNurseById(Application $app, $nurse_id)
    {
        $nurse = $app["repositories"]("Nurse")->findOneById($nurse_id);

        return $app->json($nurse, 200);
    }

    /**
    * Création d'une nurse
    *
    * @param Application $app       Silex Application
    * @param Request     $req       Request
    * @param integer     $user_id   id du user
    *
    * @return \Symfony\Component\HttpFoundation\JsonResponse
    */
   public function createNurse(Application $app, Request $req)
   {
        $token      = substr($request->headers->get('authorization'), 7);
        $token_user = $app['jwt_auth']->getPayload($token)['sub'];

        $user = $app["repositories"]("User")->findOneById($token_user->id);
        if (null === $user) {
            return $app->abort(404, "User not found");
        }

        $datas = $req->request->all();

        $nurse = new Nurse();
        $nurse->setBirthdate($datas["birthdate"]);
        $nurse->setUser($user);
        $nurse->setValidate(0);


        $app["orm.em"]->persist($nurse);
        $app["orm.em"]->flush();

        return $app->json($nurse, 200);
   }

   /**
   * Validation d'une nurse
   *
   * @param Application $app       Silex Application
   * @param integer     $nurse_id  id de la nurse
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function validateNurse(Application $app, $nurse_id)
  {
      $nurse = $app["repositories"]("Nurse")->findOneById($nurse_id);

      $nurse->setValidate(1);

      $app["orm.em"]->persist($nurse);
      $app["orm.em"]->flush();

      return $app->json($nurse, 200);
  }
}
