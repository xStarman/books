import { forwardRef, SelectHTMLAttributes, useId } from "react";
import { useQuery } from "@tanstack/react-query";
import { getAllAuthors } from "../../lib/get-all-authors";

export interface AuthorSelectProps extends Omit<SelectHTMLAttributes<HTMLSelectElement>, 'children'> {
    label?: string;
    error?: string;
}

export const AuthorSelect = forwardRef<HTMLSelectElement, AuthorSelectProps>(
    ({ label, error, className = "", id, ...props }, ref) => {
        const generatedId = useId();
        const selectId = id || generatedId;

        const { data: authors, isLoading, isError } = useQuery({
            queryKey: ['authors-all'],
            queryFn: getAllAuthors,
            staleTime: 1000 * 60 * 5,
        });

        return (
            <div className="d-flex flex-column gap-1">
                {label && <label htmlFor={selectId} className="form-label mb-0 fw-medium small text-muted">{label}</label>}
                
                <select
                    id={selectId}
                    ref={ref}
                    className={`form-select ${error || isError ? "is-invalid" : ""} ${className}`}
                    disabled={isLoading || isError || props.disabled}
                    {...props}
                >
                    <option value="">{isLoading ? "Carregando autores..." : isError ? "Erro ao carregar" : "Selecione um autor"}</option>
                    {authors?.map(author => (
                        <option key={author.CodAu} value={author.CodAu}>
                            {author.Nome}
                        </option>
                    ))}
                </select>
                
                {(error || isError) && (
                    <div className="invalid-feedback d-block">
                        {error || "Não foi possível carregar a lista de autores."}
                    </div>
                )}
            </div>
        );
    }
);

AuthorSelect.displayName = "AuthorSelect";
