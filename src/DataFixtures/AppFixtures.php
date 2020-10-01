<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use App\Factory\ArticleFactory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        UserFactory::new()->createMany(10);
        ArticleFactory::new()->create(6);

        $manager->flush();
    }
}
