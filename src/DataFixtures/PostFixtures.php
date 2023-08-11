<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PostFixtures extends Fixture
{

    public $titles = ['My summer vacation', 'native PHP for Desktop Apps', 'The impact of JS in Development'];
    public $contents = ['my summer vacation was quite interesting...', 'PHP can now be used to build desktop apps, wanna know how? ...', 'MERN stack is so in demand'];


    public function load(ObjectManager $manager): void
    {
        $max_count = count($this->titles) - 1;
        $i = 0;
        while ($i < 30){
            $randomIndex = rand(0, $max_count);
            $post = new Post();
            $post->setContent(" content $i - " . $this->contents[$randomIndex]);
            $post->setTitle(" title $i - " . $this->titles[$randomIndex]);
            $post->setCreatedAt();
            $manager->persist($post);
            $i++;
        }

        $manager->flush();
    }
}
