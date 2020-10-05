<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\UserFactory;
use App\Factory\ArticleFactory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{   
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {   
        $user = UserFactory::new([
            'password' => $this->passwordEncoder->encodePassword(new User(), 'password'),
        ]);

        $articleFactory = ArticleFactory::new([
            'author' => $user,
        ]);
        

        $articleFactory->createMany(6);
        $manager->flush();
    }
}
