import Head from "next/head";
import { AuditReportForm } from "../../components/books/audit-report-form";

export default function RelatorioAuditoriaPage() {
    return (
        <>
            <Head>
                <title>Gerar Relatório de Auditoria</title>
            </Head>
            <div className="d-flex align-items-center justify-content-between mb-4">
                <h2>Gerar Relatório de Auditoria</h2>
            </div>

            <div className="container-sm" style={{ maxWidth: "800px" }}>
                <p className="text-muted small mb-4">
                    Preencha os filtros desejados para exportar o histórico de alterações dos livros (UPDATE e DELETE).
                </p>
                <AuditReportForm />
            </div>
        </>
    );
}
