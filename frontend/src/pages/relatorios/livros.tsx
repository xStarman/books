import Link from "next/link";
import { Title } from "../../components/base/title";
import { BookReportForm } from "../../components/books/book-report-form";

export default function RelatorioLivrosPage() {
    return (
        <>
            <Title title="Gerar Relatório de Livros">
                <Link href="/livros" className="btn btn-primary d-flex align-items-center gap-2">
                    <i className="bi bi-arrow-left"></i> Voltar
                </Link>
            </Title>

            <div className="container-sm" style={{ maxWidth: "800px" }}>
                <p className="text-muted small mb-4">
                    Preencha os filtros desejados. Você pode usar valores isolados separados por vírgula (ex: 1,3,5) ou intervalos separados por traço (ex: 1-5). Todos os campos são opcionais.
                </p>
                <BookReportForm />
            </div>
        </>
    );
}
