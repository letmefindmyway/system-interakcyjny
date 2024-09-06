<?php
/**
 * Book list input filters DTO.
 */

namespace App\Dto;

/**
 * Class BookListInputFiltersDto.
 */
class BookListInputFiltersDto
{
    /**
     * Constructor.
     *
     * @param int|null $categoryId Category identifier
     */
    public function __construct(public readonly ?int $categoryId = null)
    {
    }
}
