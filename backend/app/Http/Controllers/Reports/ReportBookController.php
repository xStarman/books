<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;

use App\Http\Requests\Reports\ReportBookRequest;
use App\Services\Reports\GetBookReportService;
use App\DTOs\Reports\BookReportFilterDTO;
use OpenApi\Attributes as OA;

class ReportBookController extends Controller
{
    #[OA\Get(
        path: '/api/reports/books',
        summary: 'Gera relatório de livros em Excel',
        tags: ['Relatórios'],
        parameters: [
            new OA\Parameter(name: 'Titulo', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'Editora', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'Edicao', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'AnoPublicacao', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'Preco', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'autores', in: 'query', required: false, schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'integer'))),
            new OA\Parameter(name: 'assuntos', in: 'query', required: false, schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'integer')))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Arquivo XLSX do relatório'
            ),
            new OA\Response(
                response: 422,
                description: 'Erro de validação nos filtros'
            )
        ]
    )]
    public function __invoke(ReportBookRequest $request, GetBookReportService $service)
    {
        $dto = BookReportFilterDTO::fromArray($request->validated());
        return $service->execute($dto);
    }
}
