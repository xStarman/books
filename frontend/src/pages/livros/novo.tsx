import { BookForm, BookFormHandle } from "../../components/books/book-form";
import Link from "next/link";
import { Title } from "../../components/base/title";
import { IsbnSearchPopover } from "../../components/books/isbn-search-popover";
import { useRef } from "react";

export default function NovoLivro() {
    const bookFormRef = useRef<BookFormHandle>(null);
    return (
        <>
            <Title title="Cadastrar novo livro">
                <IsbnSearchPopover onImport={(data) => bookFormRef.current?.importData(data)} />
                <Link href="/livros" className="btn btn-primary d-flex align-items-center gap-2">
                    <i className="bi bi-arrow-left"></i> Voltar
                </Link>
            </Title>

            <div className="container-sm" style={{ maxWidth: "800px" }}>
                <BookForm ref={bookFormRef} />
            </div>
        </>
    );
}
