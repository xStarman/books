import { Table, Column, SortOrder } from "../base/table";
import { useState } from "react";
import { useQuery } from "@tanstack/react-query";
import { getAuthorList, ListAuthorsRequest } from "../../lib/get-author-list";
import { Author } from "../../lib/entities/author.entity";

export const AuthorList = () => {
    const [requestParams, setRequestParams] = useState<ListAuthorsRequest>({
        page: 1,
        page_size: 10,
    });

    const { data, isLoading, isError } = useQuery({
        queryKey: ["authors", requestParams],
        queryFn: () => getAuthorList(requestParams),
    });

    const columns: Column<Author>[] = [
        { key: "CodAu", label: "Código", sortable: true },
        { key: "Nome", label: "Nome", sortable: true },
        {
            key: "actions",
            label: "Ações",
            render: (row) => (
                <div className="d-flex gap-2">
                    <button className="btn btn-sm btn-outline-primary" title="Editar">
                        <i className="bi bi-pencil"></i>
                    </button>
                    <button className="btn btn-sm btn-outline-danger" title="Excluir">
                        <i className="bi bi-trash"></i>
                    </button>
                </div>
            )
        }
    ];

    const handleSort = (column: string, order: SortOrder) => {
        setRequestParams(prev => ({
            ...prev,
            order: { [column]: order } as any,
        }));
    };

    const handlePageChange = (page: number) => {
        setRequestParams(prev => ({ ...prev, page }));
    };

    if (isLoading) return <div className="text-center py-5"><div className="spinner-border text-primary" role="status"></div></div>;
    if (isError) return <div className="alert alert-danger m-3">Erro ao carregar autores.</div>;

    const sortColumn = requestParams.order ? Object.keys(requestParams.order)[0] : undefined;
    const sortOrder = requestParams.order ? Object.values(requestParams.order)[0] as SortOrder : undefined;

    return (
        <Table
            columns={columns}
            data={data?.data || []}
            sortColumn={sortColumn}
            sortOrder={sortOrder}
            onSort={handleSort}
            pagination={{
                currentPage: data?.current_page || 1,
                totalPages: data?.last_page || 1,
                onPageChange: handlePageChange
            }}
        />
    );
};
