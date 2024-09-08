<?php
/**
 * Index controller.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class IndexController.
 */
class IndexController extends AbstractController
{
    /**
     * Index action.
     *
     * @return Response HTTP Response
     */
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->redirectToRoute('book_index');
    }
}