<?php
/**
 * Book repository.
 */

namespace App\Repository;

use App\Dto\BookListFiltersDto;
use App\Entity\Book;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class BookRepository.
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * Query all records.
     *
     * @param BookListFiltersDto $filters Filters
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(BookListFiltersDto $filters): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder()
            ->select(
                'partial book.{id, createdAt, updatedAt, author, title}',
                'partial category.{id, title}',
            )
            ->join('book.category', 'category')
            ->orderBy('book.updatedAt', 'DESC');

        return $this->applyFiltersToList($queryBuilder, $filters);
    }

    /**
     * Save entity.
     *
     * @param Book $book Book entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Book $book): void
    {
        assert($this->_em instanceof EntityManager);
        $this->_em->persist($book);
        $this->_em->flush();
    }

    /**
     * Delete entity.
     *
     * @param Book $book Book entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Book $book): void
    {
        assert($this->_em instanceof EntityManager);
        $this->_em->remove($book);
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
        return $queryBuilder ?? $this->createQueryBuilder('book');
    }

    /**
     * Apply filters to paginated list.
     *
     * @param QueryBuilder       $queryBuilder Query builder
     * @param BookListFiltersDto $filters      Filters
     *
     * @return QueryBuilder Query builder
     */
    private function applyFiltersToList(QueryBuilder $queryBuilder, BookListFiltersDto $filters): QueryBuilder
    {
        if ($filters->category instanceof Category) {
            $queryBuilder->andWhere('category = :category')
                ->setParameter('category', $filters->category);
        }

        return $queryBuilder;
    }
}
