<?php

namespace App\Exports\Reports;

use App\Repositories\Reports\AuditReportRepository;
use App\DTOs\Reports\AuditReportFilterDTO;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Illuminate\Support\Collection;

class AuditExport implements FromCollection, WithHeadings, WithMapping, WithCustomStartCell, WithEvents, ShouldAutoSize
{
    private Collection $collection;
    private int $maxAutores = 1;
    private int $maxAssuntos = 1;

    public function __construct(
        private AuditReportFilterDTO $filters,
        private AuditReportRepository $repository
    ) {
        $this->collection = $this->repository->getQuery($this->filters)->get();
        
        $this->maxAutores = $this->collection->max(function($audit) {
            $autores = json_decode($audit->Autores ?? '[]', true) ?: [];
            return count($autores);
        }) ?: 1;

        $this->maxAssuntos = $this->collection->max(function($audit) {
            $assuntos = json_decode($audit->Assuntos ?? '[]', true) ?: [];
            return count($assuntos);
        }) ?: 1;
    }

    public function collection()
    {
        return $this->collection;
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function headings(): array
    {
        $headings = [
            'Data Alteração',
            'Ação',
            'Código',
            'Título',
            'Editora',
            'Edição',
            'Ano de Publicação',
            'Preço',
        ];

        for ($i = 1; $i <= $this->maxAutores; $i++) {
            $headings[] = "Autor $i";
        }

        for ($i = 1; $i <= $this->maxAssuntos; $i++) {
            $headings[] = "Assunto $i";
        }

        return $headings;
    }

    public function map($audit): array
    {
        $row = [
            $audit->data_alteracao,
            $audit->acao,
            $audit->CodL,
            $audit->Titulo,
            $audit->Editora,
            $audit->Edicao,
            $audit->AnoPublicacao,
            number_format((float) $audit->Preco, 2, ',', '.'),
        ];

        $autores = json_decode($audit->Autores ?? '[]', true) ?: [];
        for ($i = 0; $i < $this->maxAutores; $i++) {
            $row[] = isset($autores[$i]) ? $autores[$i]['Nome'] : '';
        }

        $assuntos = json_decode($audit->Assuntos ?? '[]', true) ?: [];
        for ($i = 0; $i < $this->maxAssuntos; $i++) {
            $row[] = isset($assuntos[$i]) ? $assuntos[$i]['Descricao'] : '';
        }

        return $row;
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function(BeforeSheet $event) {
                $sheet = $event->sheet;
                
                $colIndex = 8 + $this->maxAutores + $this->maxAssuntos;
                $lastColumnLetter = Coordinate::stringFromColumnIndex($colIndex);
                
                $sheet->mergeCells("A1:{$lastColumnLetter}1");
                $sheet->setCellValue('A1', 'Relatório de Auditoria de Livros');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                $sheet->mergeCells("A2:{$lastColumnLetter}2");
                $sheet->setCellValue('A2', 'Data de Geração: ' . now()->format('d/m/Y H:i:s'));

                $sheet->setCellValue('A4', 'Filtros aplicados:');
                $sheet->getStyle('A4')->getFont()->setBold(true);

                $filtersText = [];
                if ($this->filters->titulo) $filtersText[] = "Título: {$this->filters->titulo}";
                if ($this->filters->acao && $this->filters->acao !== 'Todos') $filtersText[] = "Ação: {$this->filters->acao}";
                if ($this->filters->dataInicial) $filtersText[] = "Data Inicial: {$this->filters->dataInicial}";
                if ($this->filters->dataFinal) $filtersText[] = "Data Final: {$this->filters->dataFinal}";

                $sheet->mergeCells("B4:{$lastColumnLetter}4");
                $sheet->setCellValue('B4', empty($filtersText) ? 'Nenhum' : implode(' | ', $filtersText));
                
                $sheet->getStyle("A6:{$lastColumnLetter}6")->getFont()->setBold(true);
                $sheet->getStyle("A6:{$lastColumnLetter}6")->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            },
        ];
    }
}
