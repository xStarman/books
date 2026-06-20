import React from 'react'
import { Pagination, PaginationProps } from './pagination'

export type Column<T> = {
    key: Extract<keyof T, string> | string
    label: string
    sortable?: boolean
    render?: (row: T) => React.ReactNode
}

export type SortOrder = 'asc' | 'desc'

export type TableProps<T> = {
    columns: Column<T>[]
    data: T[]
    sortColumn?: string
    sortOrder?: SortOrder
    onSort?: (column: string, order: SortOrder) => void
    pagination?: PaginationProps
}

export const Table = <T extends Record<string, any>>({
    columns,
    data,
    sortColumn,
    sortOrder,
    onSort,
    pagination
}: TableProps<T>) => {

    const handleSort = (columnKey: string) => {
        if (!onSort) return

        if (sortColumn === columnKey) {
            onSort(columnKey, sortOrder === 'asc' ? 'desc' : 'asc')
        } else {
            onSort(columnKey, 'asc')
        }
    }

    return (
        <>
            <div className="table-responsive">
                <table className="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            {columns.map(col => (
                                <th
                                    key={col.key}
                                    scope="col"
                                    style={{ cursor: col.sortable ? 'pointer' : 'default', userSelect: 'none' }}
                                    onClick={() => col.sortable && handleSort(col.key)}
                                >
                                    <div className="d-flex align-items-center gap-1">
                                        {col.label}
                                        {col.sortable && (
                                            <span className="text-muted">
                                                {sortColumn === col.key ? (
                                                    sortOrder === 'asc'
                                                        ? <i className="bi bi-caret-up-fill text-primary"></i>
                                                        : <i className="bi bi-caret-down-fill text-primary"></i>
                                                ) : (
                                                    <i className="bi bi-arrow-down-up opacity-25"></i>
                                                )}
                                            </span>
                                        )}
                                    </div>
                                </th>
                            ))}
                        </tr>
                    </thead>
                    <tbody className="table-group-divider">
                        {data.length === 0 ? (
                            <tr>
                                <td colSpan={columns.length} className="text-center py-4 text-muted">
                                    Nenhum registro encontrado.
                                </td>
                            </tr>
                        ) : (
                            data.map((row, rowIndex) => (
                                <tr key={rowIndex}>
                                    {columns.map(col => (
                                        <td key={col.key}>
                                            {col.render ? col.render(row) : row[col.key]}
                                        </td>
                                    ))}
                                </tr>
                            ))
                        )}
                    </tbody>
                </table>
            </div>

            {pagination && (
                <div className="d-flex justify-content-end mt-3">
                    <Pagination {...pagination} />
                </div>
            )}
        </>
    )
}