import { useForm } from "react-hook-form";
import { Input } from "../base/input";
import { useEffect } from "react";

export type SubjectFiltersData = {
    Descricao?: string;
}

export type SubjectListFiltersProps = {
    onFilterChange: (filters: SubjectFiltersData) => void;
}

export const SubjectListFilters = ({ onFilterChange }: SubjectListFiltersProps) => {
    const { register, watch } = useForm<SubjectFiltersData>({
        defaultValues: {
            Descricao: ""
        }
    });

    const formValues = watch();

    useEffect(() => {
        const timer = setTimeout(() => {
            onFilterChange(formValues);
        }, 300);

        return () => clearTimeout(timer);
    }, [formValues, onFilterChange]);

    return (
        <form className="row g-3 mb-4" onSubmit={(e) => e.preventDefault()}>
            <div className="col-12 col-md-4">
                <Input
                    {...register("Descricao")}
                    label="Descrição"
                    placeholder="Buscar pela descrição do assunto"
                />
            </div>
        </form>
    );
};
