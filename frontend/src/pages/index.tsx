import Link from "next/link";
import { Title } from "../components/base/title";
import { BookList } from "../components/books/books-list";

export default function Home() {
  return <>
    <Title title="Livros">
      <Link href="/livros/cadastrar" className="btn btn-primary">
        <i className="bi bi-plus-lg me-1"></i>
        Cadastrar
      </Link>
    </Title>
    <BookList />
  </>;
}
