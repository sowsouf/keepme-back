<?php

namespace KeepMe\Controllers;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\Api\ControllerProviderInterface;

Use KeepMe\Entities\Nurse;

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
        $controllers->get('/nurses', [$this, 'getAllNurses']);

        // On récupère une nurse selon son id
        $controllers->get('/nurse/{nurse_id}', [$this, 'getNurseById']);

        // On crée un utilisateur
        $controllers->post('/nurse', [$this, 'createNurse']); // WORK IN PROGRESS

        // On valide un utilisateur
        $controllers->put('/nurse/{nurse_id}', [$this, 'validateNurse']);

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

    /** //!!\\ WORK IN PROGRESS //!!\\
    * Création d'une nurse
    *
    * @param Application $app       Silex Application
    * @param Request     $req       Request
    *
    * @return \Symfony\Component\HttpFoundation\JsonResponse
    */
   public function createNurse(Application $app, Request $req)
   {
       $datas = $req->request->all();

       $nurse = new Nurse();



       return $app->json($nurse, 200);
   }
   //!!\\ WORK IN PROGRESS //!!\\

   /**
   * Création d'une nurse
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
