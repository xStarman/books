import { SubjectForm } from "../../components/subjects/subject-form";
import Link from "next/link";
import { Title } from "../../components/base/title";

export default function NovoAssunto() {
    return (
        <>
            <Title title="Cadastrar novo assunto">
                <Link href="/assuntos" className="btn btn-primary d-flex align-items-center gap-2">
                    <i className="bi bi-arrow-left"></i> Voltar
                </Link>
            </Title>

            <div className="container-sm" style={{ maxWidth: "800px" }}>
                <SubjectForm />
            </div>
        </>
    );
}
