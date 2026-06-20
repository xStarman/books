<?php

namespace App\DTOs;

class BookFiltersDTO
{
    public function __construct(
        public readonly ?string $Titulo = null,
        public readonly ?string $Editora = null,
        public readonly ?int $Edicao = null,
        public readonly ?int $AnoPublicacao = null,
        public readonly ?int $Autor = null,
        public readonly ?int $Assunto = null,
    ) {}

    public static function fromArray(array $filters): self
    {
        return new self(
            Titulo: $filters['Titulo'] ?? null,
            Editora: $filters['Editora'] ?? null,
            Edicao: isset($filters['Edicao']) ? (int) $filters['Edicao'] : null,
            AnoPublicacao: isset($filters['AnoPublicacao']) ? (int) $filters['AnoPublicacao'] : null,
            Autor: isset($filters['Autor']) ? (int) $filters['Autor'] : null,
            Assunto: isset($filters['Assunto']) ? (int) $filters['Assunto'] : null,
        );
    }
}
