import { useForm } from "react-hook-form";
import { Input } from "../base/input";
import { AuthorSelect } from "../authors/author-select";
import { SubjectSelect } from "../subjects/subject-select";
import { useEffect } from "react";

export type BookFiltersData = {
    Titulo?: string;
    Edicao?: string;
    Autor?: string;
    Assunto?: string;
}

export type BookListFiltersProps = {
    onFilterChange: (filters: BookFiltersData) => void;
}

export const BookListFilters = ({ onFilterChange }: BookListFiltersProps) => {
    const { register, watch } = useForm<BookFiltersData>({
        defaultValues: {
            Titulo: "",
            Edicao: "",
            Autor: "",
            Assunto: ""
        }
    });

    const formValues = watch();
    const serializedFormValues = JSON.stringify(formValues);

    useEffect(() => {
        const timer = setTimeout(() => {
            onFilterChange(JSON.parse(serializedFormValues));
        }, 300);

        return () => clearTimeout(timer);
    }, [serializedFormValues, onFilterChange]);

    return (
        <form className="row g-3 mb-4" onSubmit={(e) => e.preventDefault()}>
            <div className="col-12 col-md-3">
                <Input
                    {...register("Titulo")}
                    label="Título"
                    placeholder="Buscar pelo título"
                />
            </div>
            
            <div className="col-12 col-md-3">
                <Input
                    {...register("Edicao")}
                    label="Edição"
                    placeholder="Buscar pela edição"
                    type="number"
                    min="1"
                />
            </div>
            
            <div className="col-12 col-md-3">
                <AuthorSelect
                    {...register("Autor")}
                    label="Autor"
                />
            </div>
            
            <div className="col-12 col-md-3">
                <SubjectSelect
                    {...register("Assunto")}
                    label="Categoria"
                />
            </div>
        </form>
    );
};
