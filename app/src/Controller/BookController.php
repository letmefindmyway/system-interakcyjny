<?php
/**
 * Book controller.
 */

namespace App\Controller;

use App\Dto\BookListInputFiltersDto;
use App\Entity\Book;
use App\Entity\Comment;
use App\Entity\User;
use App\Form\Type\BookType;
use App\Form\Type\CommentType;
use App\Repository\CommentRepository;
use App\Resolver\BookListInputFiltersDtoResolver;
use App\Service\BookServiceInterface;
use App\Service\CommentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class BookController.
 */
#[Route('/book')]
class BookController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param BookServiceInterface $bookService    Book service
     * @param TranslatorInterface  $translator     Translator
     * @param CommentService       $commentService Comment service
     */
    public function __construct(private readonly BookServiceInterface $bookService, private readonly TranslatorInterface $translator, private readonly CommentService $commentService)
    {
    }

    /**
     * Index action.
     *
     * @param BookListInputFiltersDto $filters Input filters
     * @param int                     $page    Page number
     *
     * @return Response HTTP response
     */
    #[Route(name: 'book_index', methods: 'GET')]
    public function index(#[MapQueryString(resolver: BookListInputFiltersDtoResolver::class)] BookListInputFiltersDto $filters, #[MapQueryParameter] int $page = 1): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $pagination = $this->bookService->getPaginatedList($page, $user, $filters);

        return $this->render('book/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param Book $book Book entity
     * @param int  $page Page number
     *
     * @return Response HTTP response
     */
    #[Route('/{id}', name: 'book_show', requirements: ['id' => '[1-9]\d*'], methods: 'GET')]
    #[IsGranted('VIEW', subject: 'book')]
    public function show(Book $book, #[MapQueryParameter] int $page = 1): Response
    {
        $comment = $this->commentService->getPaginatedList($book, $page);

        return $this->render('book/show.html.twig', ['book' => $book, 'comments' => $comment]);
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route('/create', name: 'book_create', methods: 'GET|POST')]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $book = new Book();
        $book->setCreator($user);
        $form = $this->createForm(
            BookType::class,
            $book,
            ['action' => $this->generateUrl('book_create')]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->bookService->save($book);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('book_index');
        }

        return $this->render(
            'book/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Book    $book    Book entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'book_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    #[IsGranted('EDIT', subject: 'book')]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Book $book): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('No access for you!');
        }
        $form = $this->createForm(
            BookType::class,
            $book,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('book_edit', ['id' => $book->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->bookService->save($book);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('book_index');
        }

        return $this->render(
            'book/edit.html.twig',
            [
                'form' => $form->createView(),
                'book' => $book,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Book    $book    Book entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'book_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    #[IsGranted('DELETE', subject: 'book')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Book $book): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('No access for you!');
        }
        $form = $this->createForm(
            FormType::class,
            $book,
            [
                'method' => 'DELETE',
                'action' => $this->generateUrl('book_delete', ['id' => $book->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->bookService->delete($book);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('book_index');
        }

        return $this->render(
            'book/delete.html.twig',
            [
                'form' => $form->createView(),
                'book' => $book,
            ]
        );
    }
}
