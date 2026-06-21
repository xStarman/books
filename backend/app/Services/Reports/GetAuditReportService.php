<?php

namespace App\Services\Reports;

use App\DTOs\Reports\AuditReportFilterDTO;
use App\Exports\Reports\AuditExport;
use App\Repositories\Reports\AuditReportRepository;
use Maatwebsite\Excel\Facades\Excel;

class GetAuditReportService
{
    public function __construct(private AuditReportRepository $repository) {}

    public function execute(AuditReportFilterDTO $filters)
    {
        return Excel::download(new AuditExport($filters, $this->repository), 'relatorio_auditoria.xlsx');
    }
}
