<?php
/**
 * Book controller.
 */

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;

/**
 * Class BookController.
 */
#[Route('/book')]
class BookController extends AbstractController
{
    /**
     * Index action.
     *
     * @param BookRepository     $bookRepository Book repository
     * @param PaginatorInterface $paginator      Paginator
     * @param int                $page           page
     *
     * @return Response HTTP response
     */
    #[Route(name: 'book_index', methods: 'GET')]
    public function index(BookRepository $bookRepository, PaginatorInterface $paginator, #[MapQueryParameter] int $page = 1): Response
    {
        $pagination = $paginator->paginate(
            $bookRepository->queryAll(),
            $page,
            BookRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render('book/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param BookRepository $repository Book repository
     * @param int            $id         Book identifier
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'book_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function show(BookRepository $repository, int $id): Response
    {
        $book = $repository->findOneById($id);

        return $this->render(
            'book/show.html.twig',
            ['book' => $book]
        );
    }
}
