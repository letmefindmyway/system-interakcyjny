<?php
/**
 * Comment entity.
 */

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Comment.
 *
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ORM\Table(name: 'comments')]
class Comment
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Created at.
     *
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\Type(\DateTimeImmutable::class)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * email.
     */
    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * Nickname.
     */
    #[ORM\Column(length: 255)]
    private ?string $nickname = null;

    /**
     * content.
     */
    #[ORM\Column(length: 255)]
    private ?string $content = null;

    /**
     * Book to which the comment belongs.
     */
    #[ORM\ManyToOne(targetEntity: Book::class)]
    private ?Book $book = null;

    /**
     * Getter for Id.
     *
     * @return int|null int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for created at.
     *
     * @return \DateTimeImmutable|null Created at
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Setter for created at.
     *
     * @param \DateTimeImmutable $createdAt Created at
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Getter for Email.
     *
     * @return string|null string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Setter for Email.
     *
     * @param string $email email
     *
     * @return $this
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Getter for Nickname.
     *
     * @return string|null string
     */
    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    /**
     * Setter for Nickname.
     *
     * @param string $nickname Nickname
     *
     * @return $this
     */
    public function setNickname(string $nickname): static
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Getter for content.
     *
     * @return string|null string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Setter for content.
     *
     * @param string $content content
     *
     * @return $this
     */
    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Book|null book
     */
    public function getBook(): ?Book
    {
        return $this->book;
    }

    /**
     * @param Book|null $book book
     *
     * @return $this
     */
    public function setBook(?Book $book): static
    {
        $this->book = $book;

        return $this;
    }
}
