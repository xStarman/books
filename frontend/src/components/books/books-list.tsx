import { Table, Column, SortOrder } from "../base/table";
import { useState } from "react";
import { useQuery } from "@tanstack/react-query";
import { getBookList } from "../../lib/get-book-list";
import { Book } from "../../lib/entities/book.entity";
import { moneyFormat } from "../../utils/money-format";

export const BookList: React.FC = () => {
    const [page, setPage] = useState(1);
    const [sortCol, setSortCol] = useState<string>("Titulo");
    const [sortOrder, setSortOrder] = useState<SortOrder>("asc");

    const { data, isLoading, isFetching, error } = useQuery({
        queryKey: ['books', page, sortCol, sortOrder],
        queryFn: async () => getBookList({
            page,
            page_size: 25,
            order: { [sortCol]: sortOrder }
        }),
    });

    const columns: Column<Book>[] = [
        { key: "CodL", label: "Código", sortable: true, width: '80px' },
        { key: "Titulo", label: "Título", sortable: true },
        { key: "Editora", label: "Editora", sortable: true },
        { key: "Edicao", label: "Edição", sortable: true },
        { key: "AnoPublicacao", label: "Ano", sortable: true },
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
                    <button className="btn btn-sm btn-outline-primary"><i className="bi bi-pencil"></i></button>
                    <button className="btn btn-sm btn-outline-danger"><i className="bi bi-trash"></i></button>
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

    if (error) {
        return <div className="alert alert-danger">Erro ao carregar livros.</div>;
    }

    return (
        <div className="flex-1 mb-5 d-flex flex-column">
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
        </div>
    );
}
