<?php
/**
 * Comment controller.
 */

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Comment;
use App\Form\Type\CommentType;
use App\Repository\CommentRepository;
use App\Service\CommentServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class CommentController.
 */
#[Route('/comment')]
class CommentController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param CommentServiceInterface $commentService Comment service interface
     * @param TranslatorInterface     $translator     Translator interface
     */
    public function __construct(private readonly CommentServiceInterface $commentService, private readonly TranslatorInterface $translator)
    {
    }

    /**
     * Show action.
     *
     * @param Comment $comment Comment
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'comment_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function show(Comment $comment): Response
    {
        return $this->render('book/show.html.twig', ['comment' => $comment]);
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Comment $comment Comment entity
     *
     * @return Response HTTP response
     */
    #[Route('/comment/{id}/delete', name: 'comment_delete', requirements: ['id' => '[1-9]\d*'], methods: ['GET', 'DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Comment $comment): Response
    {
        $book = $comment->getBook();

        // If the request is GET, render the form
        if ($request->isMethod('GET')) {
            $form = $this->createForm(CommentType::class, $comment, [
                'method' => 'DELETE',
                'action' => $this->generateUrl('comment_delete', ['id' => $comment->getId()]),
            ]);

            return $this->render('comment/delete.html.twig', [
                'form' => $form->createView(),
                'book' => $book,
            ]);
        }

        // If the request is DELETE, handle the form submission
        $form = $this->createForm(CommentType::class, $comment, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('comment_delete', ['id' => $comment->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commentService->delete($comment);

            $this->addFlash('success', $this->translator->trans('message.deleted_successfully'));

            return $this->redirectToRoute('book_show', ['id' => $book->getId()]);
        }

        // In case of invalid form (though rare in DELETE), re-render the form
        return $this->render('comment/delete.html.twig', [
            'form' => $form->createView(),
            'book' => $book,
        ]);
    }
}
