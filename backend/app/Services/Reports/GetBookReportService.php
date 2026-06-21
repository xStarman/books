<?php

namespace App\Services\Reports;

use App\DTOs\Reports\BookReportFilterDTO;
use App\Exports\Reports\BooksExport;
use App\Repositories\Reports\BookReportRepository;
use Maatwebsite\Excel\Facades\Excel;

class GetBookReportService
{
    public function __construct(private BookReportRepository $repository) {}

    public function execute(BookReportFilterDTO $filters)
    {
        return Excel::download(new BooksExport($filters, $this->repository), 'relatorio_livros.xlsx');
    }
}
