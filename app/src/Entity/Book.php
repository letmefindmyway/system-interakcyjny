<?php
/**
 * Book entity.
 */

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;
use Monolog\DateTimeImmutable;

/**
 * Class Book.
 *
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ORM\Table(name: 'books')]
class Book
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    /**
     * releaseYear.
     *
     * @var \DateTimeImmutable|null /DateTimeImmutable|null
     *
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[ORM\Column]
    private ?\DateTimeImmutable $releaseYear = null;

    /**
     * author.
     */
    #[ORM\Column(length: 64)]
    private ?string $author = null;

    /**
     * title.
     */
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /**
     * description.
     */
    #[ORM\Column(length: 255)]
    private ?string $description = null;

    /**
     * Created at.
     *
     * @var \DateTimeImmutable|null /DateTimeImmutable|null
     *
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * Updated at.
     *
     * @var \DateTimeImmutable|null /DateTimeImmutable|null
     *
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * Category.
     */
    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    /**
     * Getter for Id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for releaseYear.
     *
     * @return \DateTimeImmutable|null /DateTimeImmutable|null Release year
     */
    public function getReleaseYear(): ?\DateTimeImmutable
    {
        return $this->releaseYear;
    }

    /**
     * Setter for releaseYear.
     *
     * @param \DateTimeImmutable $releaseYear Release year
     *
     * @return Book
     */
    public function setReleaseYear(\DateTimeImmutable $releaseYear): static
    {
        $this->releaseYear = $releaseYear;

        return $this;
    }

    /**
     * Getter for author.
     *
     * @return string|null author
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * Setter for author.
     *
     * @param string $author Author
     *
     * @return Book
     */
    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Getter for title.
     *
     * @return string|null Title
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Setter for title.
     *
     * @param string $title Title
     *
     * @return Book
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Getter for description.
     *
     * @return string|null Description
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Setter for description.
     *
     * @param string $description description
     *
     * @return Book return
     */
    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Getter for created at.
     *
     * @return DateTimeImmutable|null Created at
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Setter for created at.
     *
     * @param \DateTimeImmutable $createdAt Created at
     *
     * @return Book return
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Getter for updated at.
     *
     * @return DateTimeImmutable|null Updated at
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Setter for updated at.
     *
     * @param \DateTimeImmutable $updatedAt Updated at
     *
     * @return Book return
     */
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Category|null Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category Category
     *
     * @return $this return
     */
    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }
}
