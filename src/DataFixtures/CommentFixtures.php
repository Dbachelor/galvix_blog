<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use App\Service\PostService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public $authors = ['Joachim', 'Janny', 'Marlon'];
    public $texts = ['summer is the best', 'PHP will remain for life', 'React JS does it'];
    public $postService;
    public function __construct(PostService $postService){
        $this->postService = $postService;
    }
    /**
     * seed comment table with dummy data
     */
    public function load(ObjectManager $objectManager): void
    {
        //get the correct ids in post table
        $firstPostId = $this->postService->getPostId("ASC");
        $lastPostId = $this->postService->getPostId('DESC');
        $i = 0;
        $max_count = count($this->authors) - 1;
        //insert an averge of 3 comments per post
        while ($i  < 90){
            $post_id = rand($firstPostId['id'], $lastPostId['id']);
            $post = $objectManager->getRepository(Post::class)->find($post_id);
            $random_index = rand(0, $max_count);
            $comment = new Comment();
            $comment->setPost($post);
            $comment->setAuthor($this->authors[$random_index]);
            $comment->setText($this->texts[$random_index]);
            $comment->setCreatedAt();
            $objectManager->persist($comment);
            $i++;
        }
        
        $objectManager->flush();
    }

    public function getDependencies(){
        return [PostFixtures::class];
    }
}
