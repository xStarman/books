import { AuthorForm } from "../../components/authors/author-form";
import Link from "next/link";
import { Title } from "../../components/base/title";

export default function NovoAutor() {
    return (
        <>
            <Title title="Cadastrar novo autor">
                <Link href="/autores" className="btn btn-primary d-flex align-items-center gap-2">
                    <i className="bi bi-arrow-left"></i> Voltar
                </Link>
            </Title>

            <div className="container-sm" style={{ maxWidth: "800px" }}>
                <AuthorForm />
            </div>
        </>
    );
}
