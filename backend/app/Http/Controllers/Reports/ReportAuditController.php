<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;

use App\Services\Reports\GetAuditReportService;
use App\DTOs\Reports\AuditReportFilterDTO;
use App\Http\Requests\Reports\ReportAuditRequest;
use OpenApi\Attributes as OA;

class ReportAuditController extends Controller
{
    #[OA\Get(
        path: '/api/reports/audits',
        summary: 'Gerar relatório de auditoria (XLSX)',
        tags: ['Relatórios'],
        description: 'Gera uma planilha em Excel contendo os logs de auditoria dos livros baseados nos filtros informados.',
        parameters: [
            new OA\Parameter(name: 'Titulo', in: 'query', description: 'Título do livro para busca aproximada', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'acao', in: 'query', description: 'Ação realizada (Todos, UPDATE, DELETE)', required: false, schema: new OA\Schema(type: 'string', enum: ['Todos', 'UPDATE', 'DELETE'])),
            new OA\Parameter(name: 'dataInicial', in: 'query', description: 'Data inicial do filtro (YYYY-MM-DD)', required: false, schema: new OA\Schema(type: 'string', format: 'date')),
            new OA\Parameter(name: 'dataFinal', in: 'query', description: 'Data final do filtro (YYYY-MM-DD)', required: false, schema: new OA\Schema(type: 'string', format: 'date'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Planilha Excel'
            ),
            new OA\Response(
                response: 422,
                description: 'Erro de validação'
            )
        ]
    )]
    public function __invoke(ReportAuditRequest $request, GetAuditReportService $service)
    {
        $dto = AuditReportFilterDTO::fromArray($request->validated());
        return $service->execute($dto);
    }
}
