<?php

namespace KeepMe\Controllers;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\Api\ControllerProviderInterface;

Use KeepMe\Entities\Nurse;
Use KeepMe\Entities\User;
Use KeepMe\Entities\Post;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class PostController implements ControllerProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function connect(Application $app)
    {
        /* @var $controllers ControllerCollection */
        $controllers = $app['controllers_factory'];

        // On récupère toutes les posts
        $controllers->get('/posts', [$this, 'getAllPosts']);

        // On récupère une nurse selon son id
        $controllers->get('/post/{post_id}', [$this, 'getPostById']);

        // On crée un utilisateur
        $controllers->post('{user_id}/post', [$this, 'createPost']);

        return $controllers;
    }

    /**
     * Récupère toutes les posts
     *
     * @param Application $app      Silex Application
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getAllPosts(Application $app)
    {
        $all_post = $app["repositories"]("Post")->findAll();

        return $app->json($all_post, 200);
    }

    /**
     * Récupère un post selon son id
     *
     * @param Application $app       Silex Application
     * @param integer     $post_id  id de la nurse
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getPostById(Application $app, $post_id)
    {
        $post = $app["repositories"]("Post")->findOneById($post_id);

        return $app->json($post, 200);
    }

    /**
    * Création d'un post
    *
    * @param Application $app       Silex Application
    * @param Request     $req       Request
    * @param integer     $user_id   id du user
    *
    * @return \Symfony\Component\HttpFoundation\JsonResponse
    * Exemple de body :
    * {
    *  	"title": "le titre frero",
    *   "description": "ladescription frero",
    *   "longitude": "2.2",
    *   "latitude": "4.55555",
    *   "start": "2000-01-01",
    *   "end": "2000-01-01",
    *   "nb_children": 2,
    *   "hourly_rate": 9
    * }
    */
   public function createPost(Application $app, Request $req, $user_id)
   {
       $user = $app["repositories"]("User")->findOneById($user_id);
       if (null === $user) {
           return $app->abort(404, "User not found");
       }

       $datas = $req->request->all();

       $post = new Post();
       $post->setProperties($datas);
       $post->setUser($user);
       $post->setNbChildren($datas["nb_children"]);
       $post->setHourlyRate($datas["hourly_rate"]);

       $app["orm.em"]->persist($post);
       $app["orm.em"]->flush();
       
       return $app->json($post, 200);
   }
}
