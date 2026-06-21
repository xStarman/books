<?php

namespace App\DTOs;

class BookReportFilterDTO
{
    public function __construct(
        public readonly ?string $titulo = null,
        public readonly ?string $editora = null,
        public readonly ?string $edicao = null,
        public readonly ?string $anoPublicacao = null,
        public readonly ?string $preco = null,
        public readonly ?array $autores = null,
        public readonly ?array $assuntos = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            titulo: $data['Titulo'] ?? null,
            editora: $data['Editora'] ?? null,
            edicao: $data['Edicao'] ?? null,
            anoPublicacao: $data['AnoPublicacao'] ?? null,
            preco: $data['Preco'] ?? null,
            autores: $data['autores'] ?? null,
            assuntos: $data['assuntos'] ?? null,
        );
    }
}
