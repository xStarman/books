import Link from "next/link";
import { Title } from "../components/base/title";
import { SubjectList } from "../components/subjects/subjects-list";

export default function AssuntosPage() {
    return (
        <>
            <Title title="Assuntos">
                <Link href="/assuntos/cadastrar" className="btn btn-primary">
                    <i className="bi bi-plus-lg me-1"></i>
                    Cadastrar
                </Link>
            </Title>
            <SubjectList />
        </>
    );
}
