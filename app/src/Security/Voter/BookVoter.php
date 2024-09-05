<?php
/**
 * Book voter.
 */

namespace App\Security\Voter;

use App\Entity\Book;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class BookVoter.
 */
class BookVoter extends Voter
{
    public const CREATE = 'CREATE';

    /**
     * Edit permission.
     *
     * @const string
     */
    public const EDIT = 'EDIT';

    /**
     * View permission.
     *
     * @const string
     */
    public const VIEW = 'VIEW';

    private const DELETE = 'DELETE';

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute Attribute to check
     * @param mixed  $subject   The subject to check against
     *
     * @return bool Result
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::CREATE, self::EDIT, self::VIEW, self::DELETE])
            && $subject instanceof Book;
    }

    /**
     * Determines if the given attribute is granted for the specified subject and user.
     *
     * @param string         $attribute The attribute to be checked
     * @param Book           $subject   The subject to check
     * @param TokenInterface $token     Security token
     *
     * @return bool Result
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$subject instanceof Book) {
            return false;
        }

        if (!$user instanceof UserInterface) {
            return self::VIEW === $attribute;
        }

        return match ($attribute) {
            self::VIEW => $this->canView(),
            self::CREATE => $this->canCreate($subject, $user),
            self::EDIT => $this->canEdit($subject, $user),
            self::DELETE => $this->canDelete($subject, $user),
            // self::COMMENT => $this->canComment($subject, $user),
            default => false,
        };
    }

    /**
     * Checks if user can view book.
     *
     * @return bool Result
     */
    private function canView(): bool
    {
        return true;
    }

    /**
     * Determines if User can create a new Book.
     *
     * @param Book          $book book
     * @param UserInterface $user user
     *
     * @return bool bool
     */
    private function canCreate(Book $book, UserInterface $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles());
    }

    /**
     * Checks if user can edit book.
     *
     * @param Book          $book Book entity
     * @param UserInterface $user User
     *
     * @return bool Result
     */
    private function canEdit(Book $book, UserInterface $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles());
    }

    /**
     * Checks if user can delete book.
     *
     * @param Book          $book Book entity
     * @param UserInterface $user User
     *
     * @return bool Result
     */
    private function canDelete(Book $book, UserInterface $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles());
    }

    // private function canComment(mixed $subject, UserInterface $user)
    // {
    // }
}
