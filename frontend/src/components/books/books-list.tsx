import { Table, Column, SortOrder } from "../base/table";
import { useState } from "react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { getBookList } from "../../lib/get-book-list";
import { deleteBook } from "../../lib/delete-book";
import { Book } from "../../lib/entities/book.entity";
import { Author } from "../../lib/entities/author.entity";
import { moneyFormat } from "../../utils/money-format";
import { BookListFilters, BookFiltersData } from "./book-list-filters";
import Link from "next/link";
import { ConfirmModal } from "../base/confirm-modal";
import { toast } from "react-toastify";

const AuthorCell = ({ autores }: { autores?: Author[] }) => {
    const [expanded, setExpanded] = useState(false);

    if (!autores || autores.length === 0) return <span className="text-muted">-</span>;

    if (autores.length <= 1) {
        return (
            <div className="d-flex align-items-center gap-1">
                {autores.map((a, i) => <span key={a.CodAu}>{a.Nome}{i < autores.length - 1 ? ',' : ''}</span>)}
            </div>
        );
    }

    if (expanded) {
        return (
            <div style={{ whiteSpace: 'normal', maxWidth: '300px' }}>
                {autores.map(a => a.Nome).join(', ')}
                <button
                    className="btn btn-link p-0 text-decoration-none ms-1"
                    onClick={() => setExpanded(false)}
                >
                    (ocultar)
                </button>
            </div>
        );
    }

    return (
        <div className="d-flex align-items-center gap-1">
            <span>{autores.slice(0, 1).map(a => a.Nome).join(', ')}</span>
            <button
                className="btn btn-sm btn-light py-0 px-1 ms-1 border"
                onClick={() => setExpanded(true)}
                title={autores.slice(1).map(a => a.Nome).join(', ')}
            >
                +{autores.length - 1}
            </button>
        </div>
    );
};

export const BookList: React.FC = () => {
    const queryClient = useQueryClient();
    const [page, setPage] = useState(1);
    const [sortCol, setSortCol] = useState<string>("Titulo");
    const [sortOrder, setSortOrder] = useState<SortOrder>("asc");
    const [bookToDelete, setBookToDelete] = useState<Book | null>(null);

    const [filters, setFilters] = useState<BookFiltersData>({});

    const cleanFilters = Object.fromEntries(
        Object.entries(filters).filter(([_, v]) => v !== "" && v != null)
    );

    const { data, isLoading, isFetching, error } = useQuery({
        queryKey: ['books', page, sortCol, sortOrder, cleanFilters],
        queryFn: async () => getBookList({
            page,
            page_size: 25,
            order: { [sortCol]: sortOrder },
            filters: cleanFilters as any
        }),
    });

    const deleteMutation = useMutation({
        mutationFn: (id: number) => deleteBook(id),
        onSuccess: () => {
            toast.success("Livro excluído com sucesso!");
            queryClient.invalidateQueries({ queryKey: ['books'] });
            setBookToDelete(null);
        },
        onError: () => {
            toast.error("Erro ao excluir o livro. Tente novamente.");
            setBookToDelete(null);
        }
    });

    const columns: Column<Book>[] = [
        { key: "CodL", label: "Código", sortable: true, width: '80px' },
        { key: "Titulo", label: "Título", sortable: true },
        { key: "Editora", label: "Editora", sortable: true },
        { key: "Edicao", label: "Edição", sortable: true },
        { key: "AnoPublicacao", label: "Ano", sortable: true },
        {
            key: "autores",
            label: "Autores",
            render: (row) => <AuthorCell autores={row.autores} />
        },
        {
            key: "assuntos",
            label: "Assuntos",
            render: (row) => (
                <div className="d-flex gap-1">
                    {row.assuntos?.map(a => (
                        <span key={a.CodAs} className="badge bg-secondary">
                            {a.Descricao}
                        </span>
                    ))}
                    {(!row.assuntos || row.assuntos.length === 0) && <span className="text-muted">-</span>}
                </div>
            )
        },
        {
            key: "Preco",
            label: "Preço",
            sortable: true,
            render: (row) => moneyFormat(Number(row.Preco))
        },
        {
            key: "actions",
            label: "Ações",
            render: (row) => (
                <div className="d-flex gap-2">
                    <Link href={`/livros/${row.CodL}`} className="btn btn-sm btn-outline-primary">
                        <i className="bi bi-pencil"></i>
                    </Link>
                    <button 
                        className="btn btn-sm btn-outline-danger"
                        onClick={() => setBookToDelete(row)}
                    >
                        <i className="bi bi-trash"></i>
                    </button>
                </div>
            ),
            width: '100px',
            sticky: 'right'
        }
    ];

    const handleSort = (column: string, order: SortOrder) => {
        setSortCol(column);
        setSortOrder(order);
    };

    const handleFilterChange = (newFilters: BookFiltersData) => {
        setFilters(newFilters);
        setPage(1);
    };

    if (error) {
        return <div className="alert alert-danger">Erro ao carregar livros.</div>;
    }

    return (
        <div className="flex-1 mb-5 d-flex flex-column">
            <BookListFilters onFilterChange={handleFilterChange} />
            <Table<Book>
                columns={columns}
                data={data?.data || []}
                sortColumn={sortCol}
                sortOrder={sortOrder}
                onSort={handleSort}
                isLoading={isLoading || isFetching}
                pagination={{
                    currentPage: data?.current_page || 1,
                    totalPages: data?.last_page || 1,
                    onPageChange: (p) => setPage(p)
                }}
            />

            <ConfirmModal 
                isOpen={bookToDelete !== null}
                title="Excluir Livro"
                message={<>Tem certeza que deseja excluir o livro <strong>{bookToDelete?.Titulo}</strong>?</>}
                onConfirm={() => bookToDelete && deleteMutation.mutate(bookToDelete.CodL)}
                onCancel={() => setBookToDelete(null)}
                isConfirming={deleteMutation.isPending}
            />
        </div>
    );
}
