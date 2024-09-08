<?php
/**
 * User Controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\User\UserEmailType;
use App\Form\Type\User\UserNicknameType;
use App\Form\Type\User\UserPasswordType;
use App\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class UserController.
 */
#[Route('/user')]
class UserController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param TranslatorInterface         $translator     Translator interface
     * @param UserPasswordHasherInterface $passwordHasher PasswordHasher interface
     */
    public function __construct(private readonly TranslatorInterface $translator, private readonly UserPasswordHasherInterface $passwordHasher, private readonly UserServiceInterface $userService)
    {
    }

    /**
     * Show action.
     *
     * @param User|null $user User entity
     *
     * @return Response HTTP Response
     */
    #[Route('/{id}', name: 'user_show', requirements: ['id' => '[1-9]\d*'], methods: 'GET')]
    public function show(?User $user = null): Response
    {
        return $this->render('user/show.html.twig', ['user' => $user]);
    }

    /**
     * Edit nickname.
     *
     * @param Request $request HTTP request
     * @param User    $user    User entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/nickname', name: 'nickname_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function nicknameEdit(Request $request, User $user): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('user_show', ['id' => $user->getId()]);
        }

        $form = $this->createForm(
            UserNicknameType::class,
            $user,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('nickname_edit', ['id' => $user->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->isGranted('ROLE_ADMIN')) {
                $currentPassword = $form->get('password')->getData();
                if (!$this->passwordHasher->isPasswordValid($user, $currentPassword)) {
                    $this->addFlash('danger', $this->translator->trans('message.error_password'));

                    return $this->redirectToRoute('nickname_edit', ['id' => $user->getId()]);
                }
            }

            $this->userService->save($user);

            $this->addFlash('success', $this->translator->trans('message.edited_successfully'));

            return $this->redirectToRoute('user_show', ['id' => $user->getId()]);
        }

        return $this->render('user/edit_nickname.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }

    /**
     * Edit email.
     *
     * @param Request $request HTTP Request
     * @param User    $user    User entity
     *
     * @return Response HTTP Response
     */
    #[Route('/{id}/email', name: 'email_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function emailEdit(Request $request, User $user): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('user_show', ['id' => $user->getId()]);
        }

        $form = $this->createForm(
            UserEmailType::class,
            $user,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('email_edit', ['id' => $user->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->isGranted('ROLE_ADMIN')) {
                $currentPassword = $form->get('password')->getData();
                if (!$this->passwordHasher->isPasswordValid($user, $currentPassword)) {
                    $this->addFlash('danger', $this->translator->trans('message.error_password'));

                    return $this->redirectToRoute('email_edit', ['id' => $user->getId()]);
                }
            }

            $this->userService->save($user);

            $this->addFlash('success', $this->translator->trans('message.edited_successfully'));

            return $this->redirectToRoute('user_show', ['id' => $user->getId()]);
        }

        return $this->render('user/edit_email.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }

    /**
     * Edit password.
     *
     * @param Request $request HTTP Request
     * @param User    $user    User entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/password', name: 'password_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function passwordEdit(Request $request, User $user): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('user_show', ['id' => $user->getId()]);
        }

        $form = $this->createForm(
            UserPasswordType::class,
            $user,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('password_edit', ['id' => $user->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->isGranted('ROLE_ADMIN')) {
                $currentPassword = $form->get('current_password')->getData();
                if (null === $currentPassword || !$this->passwordHasher->isPasswordValid($user, $currentPassword)) {
                    $this->addFlash('danger', $this->translator->trans('message.error_password'));

                    return $this->redirectToRoute('password_edit', ['id' => $user->getId()]);
                }
            }

            $newPassword = $form->get('password')->getData();
            $user->setPassword($this->passwordHasher->hashPassword($user, $newPassword));
            $this->userService->save($user);

            $this->addFlash('success', $this->translator->trans('message.edited_successfully'));

            return $this->redirectToRoute('user_show', ['id' => $user->getId()]);
        }

        return $this->render('user/edit_password.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
