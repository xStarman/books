<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "BookSchema",
    title: "Livro",
    description: "Modelo de Livro"
)]
class BookSchema
{
    #[OA\Property(example: 1)]
    public int $CodL;

    #[OA\Property(example: "O Senhor dos Anéis")]
    public string $Titulo;

    #[OA\Property(example: "Martins Fontes")]
    public string $Editora;

    #[OA\Property(example: 1)]
    public int $Edicao;

    #[OA\Property(example: 1954)]
    public int $AnoPublicacao;

    #[OA\Property(example: 49.90)]
    public float $Preco;
}
