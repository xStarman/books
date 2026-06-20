import { BookForm } from "../../components/books/book-form";
import Link from "next/link";
import Head from "next/head";

export default function NovoLivro() {
    return (
        <>
            <Head>
                <title>Cadastrar novo livro</title>
            </Head>
            <div className="d-flex align-items-center justify-content-between mb-4">
                <h2>Cadastrar novo livro</h2>
                <Link href="/livros" className="btn btn-primary d-flex align-items-center gap-2">
                    <i className="bi bi-arrow-left"></i> Voltar
                </Link>
            </div>

            <div className="container-sm" style={{ maxWidth: "800px" }}>
                <BookForm />
            </div>
        </>
    );
}
