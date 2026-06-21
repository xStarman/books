<?php

namespace App\DTOs\Reports;

class AuditReportFilterDTO
{
    public function __construct(
        public readonly ?string $titulo = null,
        public readonly ?string $acao = null,
        public readonly ?string $dataInicial = null,
        public readonly ?string $dataFinal = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            titulo: $data['Titulo'] ?? null,
            acao: $data['acao'] ?? null,
            dataInicial: $data['dataInicial'] ?? null,
            dataFinal: $data['dataFinal'] ?? null,
        );
    }
}
