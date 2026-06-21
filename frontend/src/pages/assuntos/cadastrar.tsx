import { SubjectForm } from "../../components/subjects/subject-form";
import Link from "next/link";
import Head from "next/head";

export default function NovoAssunto() {
    return (
        <>
            <Head>
                <title>Cadastrar novo assunto</title>
            </Head>
            <div className="d-flex align-items-center justify-content-between mb-4">
                <h2>Cadastrar novo assunto</h2>
                <Link href="/assuntos" className="btn btn-primary d-flex align-items-center gap-2">
                    <i className="bi bi-arrow-left"></i> Voltar
                </Link>
            </div>

            <div className="container-sm" style={{ maxWidth: "800px" }}>
                <SubjectForm />
            </div>
        </>
    );
}
