import { useState } from "react";
import { toast } from "react-toastify";
import { useMutation } from "@tanstack/react-query";
import { getBookByIsbn, IsbnBookData } from "../../lib/get-book-by-isbn";

type IsbnSearchPopoverProps = {
    onImport: (data: IsbnBookData) => void;
};

export const IsbnSearchPopover = ({ onImport }: IsbnSearchPopoverProps) => {
    const [isbn, setIsbn] = useState("");
    const [error, setError] = useState("");

    const mutation = useMutation({
        mutationFn: (isbnString: string) => getBookByIsbn(isbnString),
        onError: (err: any) => {
            const msg = err.response?.data?.message || "Erro ao buscar ISBN";
            setError(msg);
            toast.error(msg);
        },
        onSuccess: () => {
            setError("");
        }
    });

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        if (!isbn.trim()) return;
        mutation.mutate(isbn.trim());
    };

    const handleImport = () => {
        if (mutation.data) {
            onImport(mutation.data);
            const btn = document.getElementById('btn-isbn-search');
            if (btn && btn.classList.contains('show')) {
                btn.click();
            }
            setIsbn("");
            setError("");
            mutation.reset();
        }
    };

    const isLoading = mutation.isPending;
    const bookData = mutation.data;

    return (
        <div className="dropdown d-inline-block">
            <button
                id="btn-isbn-search"
                className="btn btn-primary d-flex align-items-center gap-2"
                type="button"
                data-bs-toggle="dropdown"
                data-bs-auto-close="outside"
                aria-expanded="false"
            >
                <i className="bi bi-search"></i> Buscar por ISBN
            </button>

            <div className="dropdown-menu p-3 shadow" style={{ minWidth: "320px" }}>
                <form onSubmit={handleSearch} className="mb-3">
                    <label className="form-label small fw-medium">Código ISBN</label>
                    <div className="input-group">
                        <input
                            type="text"
                            className="form-control"
                            placeholder="Ex: 9788575228074"
                            value={isbn}
                            onChange={(e) => setIsbn(e.target.value)}
                        />
                        <button className="btn btn-primary" type="submit" disabled={isLoading || !isbn.trim()}>
                            {isLoading ? <span className="spinner-border spinner-border-sm"></span> : <i className="bi bi-search"></i>}
                        </button>
                    </div>
                    {error && <div className="text-danger small mt-1">{error}</div>}
                </form>

                {bookData && (
                    <div className="border-top pt-3">
                        <h6 className="mb-1 text-truncate" title={bookData.Titulo}>{bookData.Titulo}</h6>
                        <div className="small text-muted mb-3">
                            {bookData.Editora} &bull; {bookData.AnoPublicacao}
                        </div>

                        <button className="btn btn-success btn-sm w-100 fw-medium" type="button" onClick={handleImport}>
                            <i className="bi bi-download me-1"></i> Importar dados
                        </button>
                    </div>
                )}
            </div>
        </div>
    );
};
