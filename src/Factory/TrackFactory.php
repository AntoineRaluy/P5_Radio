<?php

namespace App\Factory;

use App\Entity\Track;
use App\Repository\TrackRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Track|Proxy findOrCreate(array $attributes)
 * @method static Track|Proxy random()
 * @method static Track[]|Proxy[] randomSet(int $number)
 * @method static Track[]|Proxy[] randomRange(int $min, int $max)
 * @method static TrackRepository|RepositoryProxy repository()
 * @method Track|Proxy create($attributes = [])
 * @method Track[]|Proxy[] createMany(int $number, $attributes = [])
 */
final class TrackFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'title' => self::faker()->realText(35),
            'artist' => self::faker()->name($title = null),
            'genre' => self::faker()->text($maxNbChars = 10),
            'year' => self::faker()->year($max = 'now'),
            'contributor' => UserFactory::new()
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->beforeInstantiate(function(Track $track) {})
        ;
    }

    protected static function getClass(): string
    {
        return Track::class;
    }
}
