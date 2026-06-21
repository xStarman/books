<?php

namespace App\Exports;

use App\Repositories\BookReportRepository;
use App\DTOs\BookReportFilterDTO;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Illuminate\Support\Collection;

class BooksExport implements FromCollection, WithHeadings, WithMapping, WithCustomStartCell, WithEvents, ShouldAutoSize
{
    private Collection $collection;
    private int $maxAutores = 1;
    private int $maxAssuntos = 1;

    public function __construct(
        private BookReportFilterDTO $filters,
        private BookReportRepository $repository
    ) {
        $this->collection = $this->repository->getQuery($this->filters)->get();
        
        $this->maxAutores = $this->collection->max(function($b) {
            return count(array_filter(array_map('trim', explode(',', (string) $b->Autores))));
        }) ?: 1;

        $this->maxAssuntos = $this->collection->max(function($b) {
            return count(array_filter(array_map('trim', explode(',', (string) $b->Assuntos))));
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

    public function map($livro): array
    {
        $row = [
            $livro->CodL,
            $livro->Titulo,
            $livro->Editora,
            $livro->Edicao,
            $livro->AnoPublicacao,
            number_format((float) $livro->Preco, 2, ',', '.'),
        ];

        $autores = array_values(array_filter(array_map('trim', explode(',', (string) $livro->Autores))));
        for ($i = 0; $i < $this->maxAutores; $i++) {
            $row[] = $autores[$i] ?? '';
        }

        $assuntos = array_values(array_filter(array_map('trim', explode(',', (string) $livro->Assuntos))));
        for ($i = 0; $i < $this->maxAssuntos; $i++) {
            $row[] = $assuntos[$i] ?? '';
        }

        return $row;
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function(BeforeSheet $event) {
                $sheet = $event->sheet;
                
                $colIndex = 6 + $this->maxAutores + $this->maxAssuntos;
                $lastColumnLetter = Coordinate::stringFromColumnIndex($colIndex);
                
                $sheet->mergeCells("A1:{$lastColumnLetter}1");
                $sheet->setCellValue('A1', 'Relatório de Livros');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                $sheet->mergeCells("A2:{$lastColumnLetter}2");
                $sheet->setCellValue('A2', 'Data de Geração: ' . now()->format('d/m/Y H:i:s'));

                $sheet->setCellValue('A4', 'Filtros aplicados:');
                $sheet->getStyle('A4')->getFont()->setBold(true);

                $filtersText = [];
                if ($this->filters->titulo) $filtersText[] = "Título: {$this->filters->titulo}";
                if ($this->filters->editora) $filtersText[] = "Editora: {$this->filters->editora}";
                if ($this->filters->edicao) $filtersText[] = "Edição: {$this->filters->edicao}";
                if ($this->filters->anoPublicacao) $filtersText[] = "Ano: {$this->filters->anoPublicacao}";
                if ($this->filters->preco) $filtersText[] = "Preço: {$this->filters->preco}";
                if ($this->filters->autores) $filtersText[] = "Autores (IDs): " . implode(', ', $this->filters->autores);
                if ($this->filters->assuntos) $filtersText[] = "Assuntos (IDs): " . implode(', ', $this->filters->assuntos);

                $sheet->mergeCells("B4:{$lastColumnLetter}4");
                $sheet->setCellValue('B4', empty($filtersText) ? 'Nenhum' : implode(' | ', $filtersText));
                
                $sheet->getStyle("A6:{$lastColumnLetter}6")->getFont()->setBold(true);
                $sheet->getStyle("A6:{$lastColumnLetter}6")->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            },
        ];
    }
}
