<?php
/**
 * Comment repository.
 */

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 */
class CommentRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * Find all comments to book.
     *
     * @param Book $book Book entity
     *
     * @return array Comments
     */
    public function findByBook(Book $book): array
    {
        return $this->getOrCreateQueryBuilder()
            ->andWhere('comment.book = :book')
            ->setParameter('book', $book)
            ->orderBy('comment.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Save entity.
     *
     * @param Comment $comment Comment entity
     *
     * @throws ORMException
     */
    public function save(Comment $comment): void
    {
        assert($this->_em instanceof EntityManager);
        $this->_em->persist($comment);
        $this->_em->flush();
    }

    /**
     * Delete entity.
     *
     * @param Comment $comment Comment entity
     *
     * @throws ORMException
     */
    public function delete(Comment $comment): void
    {
        assert($this->_em instanceof EntityManager);
        $this->_em->remove($comment);
        $this->_em->flush();
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(?QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('comment');
    }
}
