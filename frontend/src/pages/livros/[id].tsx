import { BookForm } from "../../components/books/book-form";
import Link from "next/link";
import Head from "next/head";
import { useRouter } from "next/router";
import { useQuery } from "@tanstack/react-query";
import { getBookById } from "../../lib/get-book-by-id";

export default function EditarLivro() {
    const router = useRouter();
    const { id } = router.query;

    const numId = Number(id);
    const isValidId = id && !isNaN(numId);

    const { data: book, isLoading, isError, error } = useQuery({
        queryKey: ['book', id],
        queryFn: () => getBookById(numId),
        enabled: !!isValidId
    });

    if (id && !isValidId) return <div className="alert alert-danger m-4">O livro solicitado não foi encontrado.</div>;
    if (isLoading) return <div className="p-5 text-center"><div className="spinner-border text-primary" /></div>;
    if (isError || !book) {
        const message = (error as any)?.response?.data?.message || 'Erro ao carregar livro.';
        return <div className="alert alert-danger m-4">{message}</div>;
    }

    return (
        <>
            <Head>
                <title>Editar livro - {book.Titulo}</title>
            </Head>
            <div className="d-flex align-items-center justify-content-between mb-4">
                <h2>Editar livro</h2>
                <Link href="/livros" className="btn btn-primary d-flex align-items-center gap-2">
                    <i className="bi bi-arrow-left"></i> Voltar
                </Link>
            </div>

            <div className="container-sm" style={{ maxWidth: "800px" }}>
                <BookForm initialData={book} />
            </div>
        </>
    );
}
