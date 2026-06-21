import { AuthorForm } from "../../components/authors/author-form";
import Link from "next/link";
import Head from "next/head";
import { useRouter } from "next/router";
import { useQuery } from "@tanstack/react-query";
import { getAuthorById } from "../../lib/get-author-by-id";

export default function EditarAutor() {
    const router = useRouter();
    const { id } = router.query;

    const numId = Number(id);
    const isValidId = id && !isNaN(numId);

    const { data: autor, isLoading, isError, error } = useQuery({
        queryKey: ['author', id],
        queryFn: () => getAuthorById(numId),
        enabled: !!isValidId
    });

    if (id && !isValidId) return <div className="alert alert-danger m-4">O autor solicitado não foi encontrado.</div>;
    if (isLoading) return <div className="p-5 text-center"><div className="spinner-border text-primary" /></div>;
    if (isError || !autor) {
        const message = (error as any)?.response?.data?.message || 'Erro ao carregar autor.';
        return <div className="alert alert-danger m-4">{message}</div>;
    }

    return (
        <>
            <Head>
                <title>Editar autor - {autor.Nome}</title>
            </Head>
            <div className="d-flex align-items-center justify-content-between mb-4">
                <h2>Editar autor</h2>
                <Link href="/autores" className="btn btn-primary d-flex align-items-center gap-2">
                    <i className="bi bi-arrow-left"></i> Voltar
                </Link>
            </div>

            <div className="container-sm" style={{ maxWidth: "800px" }}>
                <AuthorForm initialData={autor} />
            </div>
        </>
    );
}
