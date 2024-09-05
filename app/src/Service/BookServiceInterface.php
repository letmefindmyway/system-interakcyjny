<?php
/**
 * Book service interface.
 */

namespace App\Service;

use App\Dto\BookListInputFiltersDto;
use App\Entity\Book;
use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface BookServiceInterface.
 */
interface BookServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int                     $page    Page number
     * @param User                    $user    user
     * @param BookListInputFiltersDto $filters BookListInputFiltersDto
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page, User $user, BookListInputFiltersDto $filters): PaginationInterface;

    /**
     * Save entity.
     *
     * @param Book $book Book entity
     */
    public function save(Book $book): void;

    /**
     * Delete entity.
     *
     * @param Book $book Book entity
     */
    public function delete(Book $book): void;
}
