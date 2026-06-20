import React from 'react'

export type PaginationProps = {
    currentPage: number
    totalPages: number
    onPageChange: (page: number) => void

}

export const Pagination: React.FC<PaginationProps> = ({ currentPage, totalPages, onPageChange }) => {
    if (totalPages <= 1) return null

    const getVisiblePages = (current: number, total: number) => {
        const windowSize = 1;
        const maxElements = 7;

        if (total <= maxElements) {
            return Array.from({ length: total }, (_, i) => i + 1)
        }

        const visiblePages: (number | string)[] = []

        const showLeftEllipsis = current > windowSize + 3;
        const showRightEllipsis = current < total - (windowSize + 2);

        if (!showLeftEllipsis && showRightEllipsis) {
            for (let i = 1; i <= maxElements - 2; i++) {
                visiblePages.push(i);
            }
            visiblePages.push('...');
            visiblePages.push(total);
        } else if (showLeftEllipsis && !showRightEllipsis) {
            visiblePages.push(1);
            visiblePages.push('...');
            for (let i = total - (maxElements - 3); i <= total; i++) {
                visiblePages.push(i);
            }
        } else {
            visiblePages.push(1);
            visiblePages.push('...');
            for (let i = current - windowSize; i <= current + windowSize; i++) {
                visiblePages.push(i);
            }
            visiblePages.push('...');
            visiblePages.push(total);
        }

        return visiblePages
    }

    const pages = getVisiblePages(currentPage, totalPages)

    return (
        <nav aria-label="Navegação de páginas">
            <ul className="pagination mb-0 pagination-">
                <li className={`page-item ${currentPage === 1 ? 'disabled' : ''}`}>
                    <button
                        className="page-link"
                        onClick={() => onPageChange(currentPage - 1)}
                        disabled={currentPage === 1}
                    >
                        Anterior
                    </button>
                </li>

                {pages.map((page, index) => (
                    <li key={index} className={`page-item ${page === '...' ? 'disabled' : ''} ${currentPage === page ? 'active' : ''}`}>
                        {page === '...' ? (
                            <span className="page-link">...</span>
                        ) : (
                            <button className="page-link" onClick={() => onPageChange(page as number)}>
                                {page}
                            </button>
                        )}
                    </li>
                ))}

                <li className={`page-item ${currentPage === totalPages ? 'disabled' : ''}`}>
                    <button
                        className="page-link"
                        onClick={() => onPageChange(currentPage + 1)}
                        disabled={currentPage === totalPages}
                    >
                        Próximo
                    </button>
                </li>
            </ul>
        </nav>
    )
}