<?php
/**
 * Category service.
 */

namespace App\Service;

use App\Entity\Category;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class CategoryService.
 */
class CategoryService implements CategoryServiceInterface
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
     * @param CategoryRepository $categoryRepository Category repository
     * @param BookRepository     $bookRepository     Book repository
     * @param PaginatorInterface $paginator          Paginator
     */
    public function __construct(private readonly CategoryRepository $categoryRepository, private readonly BookRepository $bookRepository, private readonly PaginatorInterface $paginator)
    {
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->categoryRepository->queryAll(),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Get paginated list of books.
     *
     * @param Category $category Category entity
     * @param int|null $page     Page number
     *
     * @return PaginationInterface Pagination interface
     */
    public function getPaginatedListOfBooks(Category $category, ?int $page = 1): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->bookRepository->findByCategory($category),
            $page ?? 1,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Find by id.
     *
     * @param int $id Category id
     *
     * @return Category|null Category entity
     *
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id): ?Category
    {
        return $this->categoryRepository->findOneById($id);
    }

    /**
     * Save entity.
     *
     * @param Category $category Category entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Category $category): void
    {
        $category->setCreatedAt(new \DateTimeImmutable());
        if (null !== $category->getId()) {
            $category->setUpdatedAt(new \DateTimeImmutable());
        }
        $this->categoryRepository->save($category);
    }

    /**
     * Delete entity.
     *
     * @param Category $category Category entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Category $category): void
    {
        $this->categoryRepository->delete($category);
    }

    /**
     * Can Category be deleted?
     *
     * @param Category $category Category entity
     *
     * @return bool Result
     */
    public function canBeDeleted(Category $category): bool
    {
        try {
            $result = $this->bookRepository->countByCategory($category);

            return $result <= 0;
        } catch (NoResultException|NonUniqueResultException) {
            return false;
        }
    }
}
