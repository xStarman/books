<?php

namespace App\DTOs;

class AuthorFiltersDTO
{
    public function __construct(
        public readonly ?string $Nome = null,
    ) {}

    public static function fromArray(array $filters): self
    {
        return new self(
            Nome: $filters['Nome'] ?? null,
        );
    }
}
