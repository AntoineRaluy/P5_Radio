<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends BaseFixture
{   
    private $passwordEncoder;
    
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function loadData(ObjectManager $manager)
    {
        $this->createMany(10, 'main_users', function($i) {
            $user = new User();
            $user->setUsername($this->faker->firstName);
            $user->setEmail(sprintf('radio%d@example.com', $i));
            $user->setFirstName($this->faker->firstName);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'the_new_password'
            ));
            $user->setJoinDate($this->faker->dateTimeThisYear($max = 'now'));

            return $user;
        });

        $manager->flush();
    }
}