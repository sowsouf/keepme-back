<?php

namespace KeepMe\Controllers;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\Api\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

Use KeepMe\Entities\Children;


class ChildrenController implements ControllerProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function connect(Application $app)
    {
        /* @var $controllers ControllerCollection */
        $controllers = $app['controllers_factory'];

        $controllers->get('/', [$this, 'index']);

        $controllers->get('/childrens', [$this, 'getAllChildrens']);

        $controllers->get('/children/{children_id}', [$this, 'getChildrenById']);

        $controllers->post('/children', [$this, 'createChildren']);

        return $controllers;
    }

    public function index(Application $app)
    {
        return $app->json("home", 200);
    }


    /**
     * Récupère tous les childrens
     *
     * @param Application    $app             Silex Application
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getAllChildrens(Application $app)
    {
        $childrens = $app["repositories"]("Children")->findAll();

        return $app->json($childrens, 200);
    }

    /**
     * Récupère un children selon son id
     *
     * @param Application    $app             Silex Application
     * @param integer        $children_id     id du children
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getChildrenById(Application $app, $children_id)
    {
        $children = $app["repositories"]("Children")->findOneById($children_id);

        return $app->json($children, 200);
    }


    /**
     * Créer un children
     *
     * @param Application    $app      Silex Application
     * @param Request        $req      Request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
     /*
      ----- ici il faudra récupérer le user qui est connecté pour l'instant -----
      ----- on set à la main un id dans le body de la request               -----
     {
        "firstname": "firstname",
        "birthdate": "2000-01-01",
        "description": "description",
        "user_id": 1
     }
     */
    public function createChildren(Application $app, Request $req)
    {
        $datas = $req->request->all();
        $datas["user"] = $app["repositories"]("User")->findOneById($datas["user"]);

        $children = new Children();
        $children->setProperties($datas);

        $app["orm.em"]->persist($children);
        $app["orm.em"]->flush();

        return $app->json($children, 200);
    }
}
