<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;

class ArticleFixture extends BaseFixture
{
    public function load(ObjectManager $manager)
    {
        $this->createMany(10, 'articles', function($i) {
            $article = new Article();
            $article->setTitle($this->faker->title);
            $article->setPostDate($this->faker->dateTimeThisYear($max = 'now'));

            return $article;
        });

        $manager->flush();
    }
}
