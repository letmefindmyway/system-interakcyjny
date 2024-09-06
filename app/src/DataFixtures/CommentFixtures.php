<?php
/**
 * Comment fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\Comment;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;

/**
 * Class CommentFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class CommentFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        if (!$this->manager instanceof ObjectManager || !$this->faker instanceof Generator) {
            return;
        }

        $this->createMany(1000, 'comments', function ($i) {
            $comment = new Comment();
            $comment->setEmail(sprintf('user%d@example.com', $i));
            $comment->setNickname($this->faker->unique()->userName);
            $comment->setContent($this->faker->realTextBetween(2, 255));
            /** @var Book $book */
            $book = $this->getRandomReference('books');
            $comment->setBook($book);

            return $comment;
        });

        $this->manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[] of dependencies
     *
     * @psalm-return array{0: ReportFixtures::class}
     */
    public function getDependencies(): array
    {
        return [
            BookFixtures::class,
        ];
    }
}
