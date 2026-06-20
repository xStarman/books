<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "SubjectSchema",
    title: "Assunto",
    description: "Modelo de Assunto"
)]
class SubjectSchema
{
    #[OA\Property(example: 1)]
    public int $CodAs;

    #[OA\Property(example: "Ficção Científica")]
    public string $Descricao;
}
