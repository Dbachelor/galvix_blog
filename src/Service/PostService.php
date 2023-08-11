<?php 

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class PostService
{
    public $entityManagerInterface;
    public function __construct(EntityManagerInterface $entityManagerInterface){
        $this->entityManagerInterface = $entityManagerInterface;
    }


    public function getPosts($page=1, $search=null){
        $data = [];
        if ($page < 1){
            $page = 1;
        }
        $offset = ($page-1) * 10;
        //using SQL to boost performance optimization
        $sql = "SELECT * FROM post ";
        if ($search){
            $sql .= " WHERE title LIKE '%$search%' OR content LIKE '%$search%' ";
        }
        $sql .= "ORDER BY id DESC LIMIT $offset, 10";
        $posts = $this->entityManagerInterface->getConnection()->executeQuery($sql)->fetchAllAssociative();
        foreach ($posts as $post){
            $data[] = ['id'=>$post['id'], 'title'=>$post['title'], 'content'=>$post['content'], 'comments'=>$this->getPostComments($post['id'])];
        }
        return $data;
    }


    public function getPostComments($post_id){
        return $this->entityManagerInterface->getConnection()->executeQuery("SELECT * FROM comment WHERE post_id = :post_id", ['post_id'=>$post_id])->fetchAllAssociative();
    }

    public function getPostId($order){
        return $this->entityManagerInterface->getConnection()->executeQuery("SELECT id FROM post order by id $order")->fetchAllAssociative()[0];
    }
}