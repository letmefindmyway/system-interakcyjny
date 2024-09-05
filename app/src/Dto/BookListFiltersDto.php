<?php
/**
 * Book list filters DTO.
 */

namespace App\Dto;

use App\Entity\Category;

/**
 * Class BookListFiltersDto.
 */
class BookListFiltersDto
{
    /**
     * Constructor.
     *
     * @param Category|null $category   Category entity
     */
    public function __construct(public readonly ?Category $category)
    {
    }
}