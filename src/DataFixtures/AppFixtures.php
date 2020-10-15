<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use App\Factory\ArticleFactory;
use App\Factory\CommentFactory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{   
    public function load(ObjectManager $manager)
    {   
        UserFactory::new()->createMany(8);
        ArticleFactory::new()->createMany(5, [
            'author' => UserFactory::repository()->random(),
        ]);
        
        CommentFactory::new()->createMany(4, [
            'author' => UserFactory::repository()->random(),
            'article' => ArticleFactory::repository()->random(),
        ]);
        
        $manager->flush();
    }
}
