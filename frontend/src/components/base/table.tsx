import React, { useState, useEffect, useRef, useCallback } from 'react'
import { Pagination, PaginationProps } from './pagination'

export type Column<T> = {
    key: Extract<keyof T, string> | string
    label: string
    sortable?: boolean
    render?: (row: T) => React.ReactNode
    sticky?: 'left' | 'right'
    width?: string
}

export type SortOrder = 'asc' | 'desc'

export type TableProps<T> = {
    columns: Column<T>[]
    data: T[]
    sortColumn?: string
    sortOrder?: SortOrder
    onSort?: (column: string, order: SortOrder) => void
    pagination?: PaginationProps
    isLoading?: boolean
}

export const Table = <T extends Record<string, any>,>({
    columns,
    data,
    sortColumn,
    sortOrder,
    onSort,
    pagination,
    isLoading,
}: TableProps<T>) => {

    const handleSort = (columnKey: string) => {
        if (!onSort) return

        if (sortColumn === columnKey) {
            onSort(columnKey, sortOrder === 'asc' ? 'desc' : 'asc')
        } else {
            onSort(columnKey, 'asc')
        }
    }

    const tableContainerRef = useRef<HTMLDivElement>(null)
    const [scrollState, setScrollState] = useState({ left: false, right: false })

    const checkScroll = useCallback(() => {
        if (!tableContainerRef.current) return
        const { scrollLeft, scrollWidth, clientWidth } = tableContainerRef.current
        setScrollState({
            left: scrollLeft > 0,
            right: Math.ceil(scrollLeft + clientWidth) < scrollWidth
        })
    }, [])

    useEffect(() => {
        checkScroll()
        window.addEventListener('resize', checkScroll)
        return () => window.removeEventListener('resize', checkScroll)
    }, [checkScroll, data])

    return (
        <div className="position-relative d-flex flex-column flex-1">
            {isLoading && (
                <div className="position-absolute w-100 h-100 d-flex justify-content-center align-items-center bg-light bg-opacity-75" style={{ zIndex: 10 }}>
                    <div className="spinner-border text-primary" role="status">
                        <span className="visually-hidden">Carregando...</span>
                    </div>
                </div>
            )}
            <div className="table-responsive flex-1 overflow-y-auto" ref={tableContainerRef} onScroll={checkScroll}>
                <table className="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            {columns.map(col => {
                                let className = col.sticky ? "sticky-column" : "";
                                if (col.sticky === 'left' && scrollState.left) className += " sticky-left-shadow";
                                if (col.sticky === 'right' && scrollState.right) className += " sticky-right-shadow";

                                return (
                                    <th
                                        key={col.key}
                                        scope="col"
                                        className={className}
                                        style={{
                                            cursor: col.sortable ? 'pointer' : 'default',
                                            userSelect: 'none',
                                            position: 'sticky',
                                            top: 0,
                                            zIndex: col.sticky ? 2 : 1,
                                            left: col.sticky === 'left' ? '0' : undefined,
                                            right: col.sticky === 'right' ? '0' : undefined,
                                            width: col.width ? col.width : undefined,
                                        }}
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
                                );
                            })}
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
                                    {columns.map(col => {
                                        let className = col.sticky ? "sticky-column" : "";
                                        if (col.sticky === 'left' && scrollState.left) className += " sticky-left-shadow";
                                        if (col.sticky === 'right' && scrollState.right) className += " sticky-right-shadow";

                                        return (
                                            <td key={col.key} className={className} style={{
                                                whiteSpace: 'nowrap',
                                                left: col.sticky === 'left' ? '0' : undefined,
                                                right: col.sticky === 'right' ? '0' : undefined,
                                                position: col.sticky ? 'sticky' : undefined,
                                                zIndex: col.sticky ? 1 : undefined,
                                                width: col.width ? col.width : undefined,
                                            }}>
                                                {col.render ? col.render(row) : row[col.key]}
                                            </td>
                                        );
                                    })}
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
        </div>
    )
}