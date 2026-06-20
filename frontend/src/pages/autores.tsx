import Link from "next/link";
import { Title } from "../components/base/title";
import { AuthorList } from "../components/authors/authors-list";

export default function AutoresPage() {
    return (
        <>
            <Title title="Autores">
                <Link href="/autores/cadastrar" className="btn btn-primary">
                    <i className="bi bi-plus-lg me-1"></i>
                    Cadastrar
                </Link>
            </Title>
            <AuthorList />
        </>
    );
}
