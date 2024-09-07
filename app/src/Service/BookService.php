<?php
/**
 * Book service.
 */

namespace App\Service;

use App\Dto\BookListFiltersDto;
use App\Dto\BookListInputFiltersDto;
use App\Entity\Book;
use App\Entity\User;
use App\Repository\BookRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class BookService.
 */
class BookService implements BookServiceInterface
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    private const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * Constructor.
     *
     * @param BookRepository           $bookRepository  Book repository
     * @param CategoryServiceInterface $categoryService Category service
     * @param CommentService           $commentService  Comment service
     * @param PaginatorInterface       $paginator       Paginator
     */
    public function __construct(private readonly BookRepository $bookRepository, private readonly CategoryServiceInterface $categoryService, private readonly CommentService $commentService, private readonly PaginatorInterface $paginator)
    {
    }

    /**
     * Get paginated list.
     *
     * @param int                     $page    Page number
     * @param ?User                   $user    User
     * @param BookListInputFiltersDto $filters Filters
     *
     * @return PaginationInterface<SlidingPagination> Paginated list
     */
    public function getPaginatedList(int $page, ?User $user, BookListInputFiltersDto $filters): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->bookRepository->queryAll($filters),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
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
        $book->setCreatedAt(new \DateTimeImmutable());
        if (null !== $book->getId()) {
            $book->setUpdatedAt(new \DateTimeImmutable());
        }
        $this->bookRepository->save($book);
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
        $this->commentService->deleteByBook($book);
        $this->bookRepository->delete($book);
    }

    /**
     * Prepare filters for the books list.
     *
     * @param BookListInputFiltersDto $filters Raw filters from request
     *
     * @return BookListFiltersDto Result filters
     *
     * @throws NonUniqueResultException
     */
    private function prepareFilters(BookListInputFiltersDto $filters): BookListFiltersDto
    {
        return new BookListFiltersDto(null !== $filters->categoryId ? $this->categoryService->findOneById($filters->categoryId) : null);
    }
}
