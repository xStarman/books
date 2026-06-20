import React from 'react'

export type PaginationProps = {
    currentPage: number
    totalPages: number
    onPageChange: (page: number) => void
}

export const Pagination: React.FC<PaginationProps> = ({ currentPage, totalPages, onPageChange }) => {
    if (totalPages <= 1) return null

    const pages = []
    for (let i = 1; i <= totalPages; i++) {
        pages.push(i)
    }

    return (
        <nav aria-label="Navegação de páginas">
            <ul className="pagination mb-0">
                <li className={`page-item ${currentPage === 1 ? 'disabled' : ''}`}>
                    <button 
                        className="page-link" 
                        onClick={() => onPageChange(currentPage - 1)} 
                        disabled={currentPage === 1}
                    >
                        Anterior
                    </button>
                </li>
                
                {pages.map(page => (
                    <li key={page} className={`page-item ${currentPage === page ? 'active' : ''}`}>
                        <button className="page-link" onClick={() => onPageChange(page)}>
                            {page}
                        </button>
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