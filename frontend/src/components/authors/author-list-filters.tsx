import { useForm } from "react-hook-form";
import { Input } from "../base/input";
import { useEffect } from "react";

export type AuthorFiltersData = {
    Nome?: string;
}

export type AuthorListFiltersProps = {
    onFilterChange: (filters: AuthorFiltersData) => void;
}

export const AuthorListFilters = ({ onFilterChange }: AuthorListFiltersProps) => {
    const { register, watch } = useForm<AuthorFiltersData>({
        defaultValues: {
            Nome: ""
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
                    {...register("Nome")}
                    label="Nome"
                    placeholder="Buscar pelo nome do autor"
                />
            </div>
        </form>
    );
};
