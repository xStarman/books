<?php

namespace App\DTOs;

class SubjectFiltersDTO
{
    public function __construct(
        public readonly ?string $Descricao = null,
    ) {}

    public static function fromArray(array $filters): self
    {
        return new self(
            Descricao: $filters['Descricao'] ?? null,
        );
    }
}
