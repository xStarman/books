import { Table, Column, SortOrder } from "../base/table";
import * as React from "react";
import { useState } from "react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { getAuthorList, ListAuthorsRequest } from "../../lib/get-author-list";
import { deleteAuthor } from "../../lib/delete-author";
import { Author } from "../../lib/entities/author.entity";
import { AuthorListFilters, AuthorFiltersData } from "./author-list-filters";
import Link from "next/link";
import { ConfirmModal } from "../base/confirm-modal";
import { toast } from "react-toastify";

export const AuthorList = () => {
    const [requestParams, setRequestParams] = useState<ListAuthorsRequest>({
        page: 1,
        page_size: 10,
    });
    const [authorToDelete, setAuthorToDelete] = useState<Author | null>(null);
    const queryClient = useQueryClient();

    const deleteMutation = useMutation({
        mutationFn: (id: number) => deleteAuthor(id),
        onSuccess: () => {
            toast.success("Autor excluído com sucesso!");
            queryClient.invalidateQueries({ queryKey: ['authors'] });
            setAuthorToDelete(null);
        },
        onError: (error: any) => {
            if (error?.response?.status === 409) {
                const msg = error.response.data?.message;
                if (msg === 'author_has_books') {
                    toast.error("Este autor possui livros vinculados e não pode ser excluído.");
                } else {
                    toast.error("Conflito de dados.");
                }
            } else {
                toast.error("Erro ao excluir o autor. Tente novamente.");
            }
            setAuthorToDelete(null);
        }
    });

    const { data, isLoading, isFetching, isError } = useQuery({
        queryKey: ["authors", requestParams],
        queryFn: () => getAuthorList(requestParams),
    });

    const columns: Column<Author>[] = [
        { key: "CodAu", label: "Código", sortable: true, width: '80px' },
        { key: "Nome", label: "Nome", sortable: true },
        {
            key: "actions",
            label: "Ações",
            render: (row) => (
                <div className="d-flex gap-2">
                    <Link href={`/autores/${row.CodAu}`} className="btn btn-sm btn-outline-primary" title="Editar">
                        <i className="bi bi-pencil"></i>
                    </Link>
                    <button 
                        className="btn btn-sm btn-outline-danger" 
                        title="Excluir"
                        onClick={() => setAuthorToDelete(row)}
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
        setRequestParams(prev => ({
            ...prev,
            order: { [column]: order } as any,
        }));
    };

    const handlePageChange = (page: number) => {
        setRequestParams(prev => ({ ...prev, page }));
    };

    const handleFilterChange = React.useCallback((filters: AuthorFiltersData) => {
        const cleanFilters = Object.fromEntries(
            Object.entries(filters).filter(([_, v]) => v !== "" && v != null)
        );

        setRequestParams(prev => ({
            ...prev,
            filters: Object.keys(cleanFilters).length > 0 ? cleanFilters : undefined,
            page: 1
        }));
    }, []);

    if (isError) return <div className="alert alert-danger m-3">Erro ao carregar autores.</div>;

    const sortColumn = requestParams.order ? Object.keys(requestParams.order)[0] : undefined;
    const sortOrder = requestParams.order ? Object.values(requestParams.order)[0] as SortOrder : undefined;

    return (
        <div className="flex-1 mb-5 d-flex flex-column">
            <AuthorListFilters onFilterChange={handleFilterChange} />
            <Table
                columns={columns}
                data={data?.data || []}
                sortColumn={sortColumn}
                sortOrder={sortOrder}
                onSort={handleSort}
                isLoading={isLoading || isFetching}
                pagination={{
                    currentPage: data?.current_page || 1,
                    totalPages: data?.last_page || 1,
                    onPageChange: handlePageChange
                }}
            />

            <ConfirmModal 
                isOpen={authorToDelete !== null}
                title="Excluir Autor"
                message={<>Tem certeza que deseja excluir o autor <strong>{authorToDelete?.Nome}</strong>?</>}
                onConfirm={() => authorToDelete && deleteMutation.mutate(authorToDelete.CodAu)}
                onCancel={() => setAuthorToDelete(null)}
                isConfirming={deleteMutation.isPending}
            />
        </div>
    );
};
