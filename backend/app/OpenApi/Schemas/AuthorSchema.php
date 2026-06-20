<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "AuthorSchema",
    title: "Autor",
    description: "Modelo de Autor"
)]
class AuthorSchema
{
    #[OA\Property(example: 1)]
    public int $CodAu;

    #[OA\Property(example: "Carl Sagan")]
    public string $Nome;
}
