<?php
/**
 * User service.
 */

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class UserService.
 */
class UserService implements UserServiceInterface
{
    /**
     * @param UserRepository        $userRepository User repository
     * @param TokenStorageInterface $tokenStorage   Token
     * @param UserProviderInterface $userProvider   User provider
     */
    public function __construct(private readonly UserRepository $userRepository, private readonly TokenStorageInterface $tokenStorage, private readonly UserProviderInterface $userProvider)
    {
    }

    /**
     * @param User $user User
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(User $user): void
    {
        $this->userRepository->save($user);
    }

    /**
     * @param User $user User
     */
    public function refreshUserToken(User $user): void
    {
        $this->userProvider->refreshUser($user);
        $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
        $this->tokenStorage->setToken($token);
    }
}
