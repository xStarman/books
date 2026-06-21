import Link from "next/link";
import Head from "next/head";
import { BookReportForm } from "../../components/books/book-report-form";

export default function RelatorioLivrosPage() {
    return (
        <>
            <Head>
                <title>Gerar Relatório de Livros</title>
            </Head>
            <div className="d-flex align-items-center justify-content-between mb-4">
                <h2>Gerar Relatório de Livros</h2>
                <Link href="/livros" className="btn btn-primary d-flex align-items-center gap-2">
                    <i className="bi bi-arrow-left"></i> Voltar
                </Link>
            </div>

            <div className="container-sm" style={{ maxWidth: "800px" }}>
                <p className="text-muted small mb-4">
                    Preencha os filtros desejados. Você pode usar valores isolados separados por vírgula (ex: 1,3,5) ou intervalos separados por traço (ex: 1-5). Todos os campos são opcionais.
                </p>
                <BookReportForm />
            </div>
        </>
    );
}
