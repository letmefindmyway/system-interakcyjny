<?php
/**
 * Comment service interface.
 */

namespace App\Service;

use App\Entity\Book;
use App\Entity\Comment;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface CommentServiceInterface.
 */
interface CommentServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param Book $book Book entity
     * @param int  $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(Book $book, int $page): PaginationInterface;

    /**
     * Save entity.
     *
     * @param Comment $comment Comment entity
     */
    public function save(Comment $comment): void;

    /**
     * Delete entity.
     *
     * @param Comment $comment Comment entity
     */
    public function delete(Comment $comment): void;

    /**
     * Delete comments by book.
     *
     * @param Book $book Book entity
     */
    public function deleteByBook(Book $book): void;
}
