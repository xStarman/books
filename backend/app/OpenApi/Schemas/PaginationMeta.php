<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "PaginationMeta",
    title: "PaginationMeta",
    description: "Metadados de paginação padrão do Laravel"
)]
class PaginationMeta
{
    #[OA\Property(example: 1)]
    public int $current_page;

    #[OA\Property(example: "http://localhost/api/books?page=1")]
    public string $first_page_url;

    #[OA\Property(example: 1)]
    public int $from;

    #[OA\Property(example: 5)]
    public int $last_page;

    #[OA\Property(example: "http://localhost/api/books?page=5")]
    public string $last_page_url;

    #[OA\Property(example: "http://localhost/api/books?page=2", nullable: true)]
    public ?string $next_page_url;

    #[OA\Property(example: "http://localhost/api/books")]
    public string $path;

    #[OA\Property(example: 25)]
    public int $per_page;

    #[OA\Property(example: null, nullable: true)]
    public ?string $prev_page_url;

    #[OA\Property(example: 25)]
    public int $to;

    #[OA\Property(example: 125)]
    public int $total;
}
