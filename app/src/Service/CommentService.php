<?php
/**
 * Comment service.
 */

namespace App\Service;

use App\Entity\Book;
use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\ORM\Exception\ORMException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class CommentService.
 */
class CommentService implements CommentServiceInterface
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
     * @param CommentRepository  $commentRepository Comment repository
     * @param PaginatorInterface $paginator         Paginator
     */
    public function __construct(private readonly CommentRepository $commentRepository, private readonly PaginatorInterface $paginator)
    {
    }

    /**
     * Get paginated list.
     *
     * @param Book $book Book entity
     * @param int  $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(Book $book, int $page = 1): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->commentRepository->findBy(['book' => $book]),
            $page ?? 1,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
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
        $this->commentRepository->save($comment);
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
        $this->commentRepository->delete($comment);
    }

    /**
     * Delete comments by book.
     *
     * @param Book $book Book entity
     *
     * @throws ORMException
     */
    public function deleteByBook(Book $book): void
    {
        $comments = $this->commentRepository->findBy(['book' => $book]);
        foreach ($comments as $comment) {
            $this->commentRepository->delete($comment);
        }
    }
}
