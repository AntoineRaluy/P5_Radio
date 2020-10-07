<?php

namespace App\Factory;

use App\Entity\Article;
use App\Factory\UserFactory;
use App\Repository\ArticleRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Article|Proxy findOrCreate(array $attributes)
 * @method static Article|Proxy random()
 * @method static Article[]|Proxy[] randomSet(int $number)
 * @method static Article[]|Proxy[] randomRange(int $min, int $max)
 * @method static ArticleRepository|RepositoryProxy repository()
 * @method Article|Proxy create($attributes = [])
 * @method Article[]|Proxy[] createMany(int $number, $attributes = [])
 */
final class ArticleFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'title' => self::faker()->realText(50),
            'content' => self::faker()->paragraphs(
                self::faker()->numberBetween(1, 4),
                true
            ),
            'postDate' => self::faker()->dateTimeThisYear($max = 'now'),
            'author' => UserFactory::new()
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this;
            // ->afterInstantiate(function(Article $article) { });
    }

    protected static function getClass(): string
    {
        return Article::class;
    }
}
