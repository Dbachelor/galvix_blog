<?php

namespace App\Controller;

use App\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{

    public $postService;

    public function __construct(PostService $postService){
        $this->postService = $postService;
    }

    #[Route('/posts', name: 'app_post', methods:['GET'])]
    public function index(): JsonResponse
    {
        $posts = $this->postService->getPosts(isset($_GET['page'])? $_GET['page']:1, isset($_GET['search'])? $_GET['search']:null);
        //check for errors

        if (!is_array($posts)){
            return $this->json(['success'=>false, 'message'=>'something went wrong'], 500);
        }

        // check if no record is returned
        if (count($posts) == 0){
            $message = 'no record found';
            if (isset($_GET['search'])){
                $message = 'no record found for ' . $_GET['search'];
            }
            return $this->json(['success'=>false, 'message'=>$message], 404);
        }else{
            return $this->json(['success'=>true, 'data'=>$posts], 200);
        }
       
    }
}
