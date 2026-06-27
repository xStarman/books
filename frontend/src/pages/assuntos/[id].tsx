import { SubjectForm } from "../../components/subjects/subject-form";
import Link from "next/link";
import { Title } from "../../components/base/title";
import { useRouter } from "next/router";
import { useQuery } from "@tanstack/react-query";
import { getSubjectById } from "../../lib/get-subject-by-id";

export default function EditarAssunto() {
    const router = useRouter();
    const { id } = router.query;

    const numId = Number(id);
    const isValidId = id && !isNaN(numId);

    const { data: assunto, isLoading, isError, error } = useQuery({
        queryKey: ['subject', id],
        queryFn: () => getSubjectById(numId),
        enabled: !!isValidId
    });

    if (id && !isValidId) return <div className="alert alert-danger m-4">O assunto solicitado não foi encontrado.</div>;
    if (isLoading) return <div className="p-5 text-center"><div className="spinner-border text-primary" /></div>;
    if (isError || !assunto) {
        const message = (error as any)?.response?.data?.message || 'Erro ao carregar assunto.';
        return <div className="alert alert-danger m-4">{message}</div>;
    }

    return (
        <>
            <Title title={`Editar assunto - ${assunto.Descricao}`}>
                <Link href="/assuntos" className="btn btn-primary d-flex align-items-center gap-2">
                    <i className="bi bi-arrow-left"></i> Voltar
                </Link>
            </Title>

            <div className="container-sm" style={{ maxWidth: "800px" }}>
                <SubjectForm initialData={assunto} />
            </div>
        </>
    );
}
