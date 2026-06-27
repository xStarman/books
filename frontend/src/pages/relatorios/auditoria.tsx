import { Title } from "../../components/base/title";
import { AuditReportForm } from "../../components/books/audit-report-form";

export default function RelatorioAuditoriaPage() {
    return (
        <>
            <Title title="Gerar Relatório de Auditoria" />

            <div className="container-sm" style={{ maxWidth: "800px" }}>
                <p className="text-muted small mb-4">
                    Preencha os filtros desejados para exportar o histórico de alterações dos livros (UPDATE e DELETE).
                </p>
                <AuditReportForm />
            </div>
        </>
    );
}
