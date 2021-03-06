<?php

namespace App\Factory;

use App\Entity\User;
use App\Repository\UserRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

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
    protected function getDefaults(): array
    {
        return [
            'userName' => self::faker()->userName,
            'email' => self::faker()->email,
            'roles' => ['ROLE_USER'],
            'firstName' => self::faker()->firstName,
            'agreedTermsAt' => self::faker()->dateTime,
             /* password = password */ 
            'password' => '$argon2id$v=19$m=65536,t=4,p=1$UG9ySFpqT2pUYmVLeVZxdw$cq2kq/0sAqL9m3dAA7Vhz5SpVMfGmtmziSgl5aQZI9w',
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
        //     ->afterInstantiate(function(User $user) {
        ;
    }

    protected static function getClass(): string
    {
        return User::class;
    }
}
