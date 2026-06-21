<?php

namespace App\Services;

use App\DTOs\BookReportFilterDTO;
use App\Exports\BooksExport;
use App\Repositories\BookReportRepository;
use Maatwebsite\Excel\Facades\Excel;

class GetBookReportService
{
    public function __construct(private BookReportRepository $repository) {}

    public function execute(BookReportFilterDTO $filters)
    {
        return Excel::download(new BooksExport($filters, $this->repository), 'relatorio_livros.xlsx');
    }
}
