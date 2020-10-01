<?php

namespace App\Factory;

use App\Entity\User;
use App\Repository\UserRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @method static User|Proxy findOrCreate(array $attributes)
 * @method static User|Proxy random()
 * @method static User[]|Proxy[] randomSet(int $number)
 * @method static User[]|Proxy[] randomRange(int $min, int $max)
 * @method static UserRepository|RepositoryProxy repository()
 * @method User|Proxy create($attributes = [])
 * @method User[]|Proxy[] createMany(int $number, $attributes = [])
 */
final class UserFactory extends ModelFactory
{
    // private $passwordEncoder;

    // public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    // {
    //     $this->passwordEncoder = $passwordEncoder;
    // }

    protected function getDefaults(): array
    {
        return [
            'userName' => self::faker()->userName,
            'email' => self::faker()->email,
            'roles' => ['ROLE_USER'],
            'firstName' => self::faker()->firstName,
            'joinDate' => self::faker()->dateTimeThisYear($max = 'now'),
            'password' => self::faker()->password,
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
        //     ->afterInstantiate(function(User $user) {
        //         if (!$user->getPassword()) {
        //             $user->setPassword($this->passwordEncoder->encodePassword($user, 'the_new_password'));
        //         }
        //     })
        ;
    }

    protected static function getClass(): string
    {
        return User::class;
    }
}
