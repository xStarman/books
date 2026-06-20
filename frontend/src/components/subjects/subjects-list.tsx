import { Table, Column, SortOrder } from "../base/table";
import { useState } from "react";
import { useQuery } from "@tanstack/react-query";
import { getSubjectList, ListSubjectsRequest } from "../../lib/get-subject-list";
import { Subject } from "../../lib/entities/subject.entity";

export const SubjectList = () => {
    const [requestParams, setRequestParams] = useState<ListSubjectsRequest>({
        page: 1,
        page_size: 10,
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
                    <button className="btn btn-sm btn-outline-primary" title="Editar">
                        <i className="bi bi-pencil"></i>
                    </button>
                    <button className="btn btn-sm btn-outline-danger" title="Excluir">
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


    if (isError) return <div className="alert alert-danger m-3">Erro ao carregar assuntos.</div>;

    const sortColumn = requestParams.order ? Object.keys(requestParams.order)[0] : undefined;
    const sortOrder = requestParams.order ? Object.values(requestParams.order)[0] as SortOrder : undefined;

    return (
        <div className="flex-1 mb-5 d-flex flex-column">
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
        </div>
    );
};
