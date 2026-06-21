import { forwardRef, SelectHTMLAttributes, useId } from "react";
import { useQuery } from "@tanstack/react-query";
import { getAllSubjects } from "../../lib/get-all-subjects";
import { MultiSelect } from "../base/multi-select";

export interface SubjectSelectProps extends Omit<SelectHTMLAttributes<HTMLSelectElement>, 'children' | 'value' | 'onChange'> {
    label?: string;
    error?: string;
    isMulti?: boolean;
    helpText?: string;
    value?: any;
    onChange?: any;
    placeholder?: string;
}

export const SubjectSelect = forwardRef<HTMLSelectElement, SubjectSelectProps>(
    ({ label, error, className = "", id, isMulti, helpText, value, onChange, placeholder, ...props }, ref) => {
        const generatedId = useId();
        const selectId = id || generatedId;

        const { data: subjects, isLoading, isError } = useQuery({
            queryKey: ['subjects', 'all'],
            queryFn: getAllSubjects,
            staleTime: 1000 * 60 * 5,
        });

        if (isMulti) {
            const options = (subjects || []).map(s => ({ label: s.Descricao, value: s.CodAs }));
            return (
                <MultiSelect
                    label={label}
                    options={options}
                    value={value || []}
                    onChange={onChange}
                    error={error || (isError ? "Erro ao carregar categorias" : undefined)}
                    helpText={helpText}
                    placeholder={isLoading ? "Carregando..." : placeholder}
                />
            );
        }

        return (
            <div className="d-flex flex-column gap-1">
                {label && <label htmlFor={selectId} className="form-label mb-0 fw-medium small text-muted">{label}</label>}
                
                <select
                    id={selectId}
                    ref={ref}
                    className={`form-select ${error || isError ? "is-invalid" : ""} ${className}`}
                    disabled={isLoading || isError || props.disabled}
                    value={value}
                    onChange={onChange}
                    {...props}
                >
                    <option value="">{isLoading ? "Carregando categorias..." : isError ? "Erro ao carregar" : (placeholder || "Selecione uma categoria")}</option>
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
