import { forwardRef, SelectHTMLAttributes, useId } from "react";
import { useQuery } from "@tanstack/react-query";
import { getAllSubjects } from "../../lib/get-all-subjects";

export interface SubjectSelectProps extends Omit<SelectHTMLAttributes<HTMLSelectElement>, 'children'> {
    label?: string;
    error?: string;
}

export const SubjectSelect = forwardRef<HTMLSelectElement, SubjectSelectProps>(
    ({ label, error, className = "", id, ...props }, ref) => {
        const generatedId = useId();
        const selectId = id || generatedId;

        const { data: subjects, isLoading, isError } = useQuery({
            queryKey: ['subjects-all'],
            queryFn: getAllSubjects,
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
                    <option value="">{isLoading ? "Carregando categorias..." : isError ? "Erro ao carregar" : "Selecione uma categoria"}</option>
                    {subjects?.map(subject => (
                        <option key={subject.CodAs} value={subject.CodAs}>
                            {subject.Descricao}
                        </option>
                    ))}
                </select>
                
                {(error || isError) && (
                    <div className="invalid-feedback d-block">
                        {error || "Não foi possível carregar a lista de categorias."}
                    </div>
                )}
            </div>
        );
    }
);

SubjectSelect.displayName = "SubjectSelect";
