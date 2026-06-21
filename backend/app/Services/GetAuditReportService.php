<?php

namespace App\Services;

use App\DTOs\AuditReportFilterDTO;
use App\Exports\AuditExport;
use App\Repositories\AuditReportRepository;
use Maatwebsite\Excel\Facades\Excel;

class GetAuditReportService
{
    public function __construct(private AuditReportRepository $repository) {}

    public function execute(AuditReportFilterDTO $filters)
    {
        return Excel::download(new AuditExport($filters, $this->repository), 'relatorio_auditoria.xlsx');
    }
}
