import { Table, Column, SortOrder } from "../base/table";
import * as React from "react";
import { useState } from "react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { getSubjectList, ListSubjectsRequest } from "../../lib/get-subject-list";
import { deleteSubject } from "../../lib/delete-subject";
import { Subject } from "../../lib/entities/subject.entity";
import { SubjectListFilters, SubjectFiltersData } from "./subject-list-filters";
import Link from "next/link";
import { ConfirmModal } from "../base/confirm-modal";
import { toast } from "react-toastify";

export const SubjectList = () => {
    const [requestParams, setRequestParams] = useState<ListSubjectsRequest>({
        page: 1,
        page_size: 10,
        order: { CodAs: "desc" },
    });
    const [subjectToDelete, setSubjectToDelete] = useState<Subject | null>(null);
    const queryClient = useQueryClient();

    const deleteMutation = useMutation({
        mutationFn: (id: number) => deleteSubject(id),
        onSuccess: () => {
            toast.success("Assunto excluído com sucesso!");
            queryClient.invalidateQueries({ queryKey: ['subjects'] });
            setSubjectToDelete(null);
        },
        onError: (error: any) => {
            if (error?.response?.status === 409) {
                const msg = error.response.data?.message;
                if (msg === 'subject_has_books') {
                    toast.error("Este assunto possui livros vinculados e não pode ser excluído.");
                } else {
                    toast.error("Conflito de dados.");
                }
            } else {
                toast.error("Erro ao excluir o assunto. Tente novamente.");
            }
            setSubjectToDelete(null);
        }
    });

    const { data, isLoading, isFetching, isError } = useQuery({
        queryKey: ["subjects", requestParams],
        queryFn: () => getSubjectList(requestParams),
    });

    const columns: Column<Subject>[] = [
        { key: "CodAs", label: "Código", sortable: true, width: '80px' },
        { key: "Descricao", label: "Descrição", sortable: true },
        {
            key: "actions",
            label: "Ações",
            render: (row) => (
                <div className="d-flex gap-2">
                    <Link href={`/assuntos/${row.CodAs}`} className="btn btn-sm btn-outline-primary" title="Editar">
                        <i className="bi bi-pencil"></i>
                    </Link>
                    <button 
                        className="btn btn-sm btn-outline-danger" 
                        title="Excluir"
                        onClick={() => setSubjectToDelete(row)}
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

    const handleFilterChange = React.useCallback((filters: SubjectFiltersData) => {
        const cleanFilters = Object.fromEntries(
            Object.entries(filters).filter(([_, v]) => v !== "" && v != null)
        );

        setRequestParams(prev => ({
            ...prev,
            filters: Object.keys(cleanFilters).length > 0 ? cleanFilters : undefined,
            page: 1
        }));
    }, []);

    if (isError) return <div className="alert alert-danger m-3">Erro ao carregar assuntos.</div>;

    const sortColumn = requestParams.order ? Object.keys(requestParams.order)[0] : undefined;
    const sortOrder = requestParams.order ? Object.values(requestParams.order)[0] as SortOrder : undefined;

    return (
        <div className="flex-1 mb-5 d-flex flex-column">
            <SubjectListFilters onFilterChange={handleFilterChange} />
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
                isOpen={subjectToDelete !== null}
                title="Excluir Assunto"
                message={<>Tem certeza que deseja excluir o assunto <strong>{subjectToDelete?.Descricao}</strong>?</>}
                onConfirm={() => subjectToDelete && deleteMutation.mutate(subjectToDelete.CodAs)}
                onCancel={() => setSubjectToDelete(null)}
                isConfirming={deleteMutation.isPending}
            />
        </div>
    );
};
