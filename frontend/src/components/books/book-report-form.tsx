import { useForm, Controller } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { z } from "zod";
import { Input } from "../base/input";
import { AuthorSelect } from "../authors/author-select";
import { SubjectSelect } from "../subjects/subject-select";
import { useMutation } from "@tanstack/react-query";
import { downloadBookReport } from "../../lib/download-book-report";
import { toast } from "react-toastify";

const rangeValidator = (val: string | undefined, isFloat = false) => {
    if (!val) return true;
    if (val.includes('-')) {
        const parts = val.split('-');
        if (parts.length !== 2) return false;
        
        let min, max;
        if (isFloat) {
            min = parseFloat(parts[0].trim().replace(',', '.'));
            max = parseFloat(parts[1].trim().replace(',', '.'));
        } else {
            min = parseInt(parts[0].trim(), 10);
            max = parseInt(parts[1].trim(), 10);
        }
        
        if (isNaN(min) || isNaN(max)) return false;
        if (min > max) return false;
    }
    return true;
};

const schema = z.object({
    Titulo: z.string().optional(),
    Editora: z.string().optional(),
    Edicao: z.string()
        .refine(v => rangeValidator(v, false), "Formato inválido ou valor inicial maior que o final")
        .optional(),
    AnoPublicacao: z.string()
        .refine(v => rangeValidator(v, false), "Formato inválido ou valor inicial maior que o final")
        .optional(),
    Preco: z.string()
        .refine(v => rangeValidator(v, true), "Formato inválido ou valor inicial maior que o final")
        .optional(),
    autores: z.array(z.number()).optional(),
    assuntos: z.array(z.number()).optional(),
});

export type BookReportFormData = z.infer<typeof schema>;

export const BookReportForm = () => {
    const { register, handleSubmit, control, formState: { errors } } = useForm<BookReportFormData>({
        resolver: zodResolver(schema as any),
        defaultValues: {
            Titulo: "",
            Editora: "",
            Edicao: "",
            AnoPublicacao: "",
            Preco: "",
            autores: [],
            assuntos: [],
        }
    });

    const mutation = useMutation({
        mutationFn: (data: BookReportFormData) => {
            const cleanData = Object.fromEntries(
                Object.entries(data).filter(([_, v]) => v !== "" && v != null && (Array.isArray(v) ? v.length > 0 : true))
            );
            return downloadBookReport(cleanData);
        },
        onSuccess: () => {
            toast.success("Relatório gerado com sucesso!");
        },
        onError: () => {
            toast.error("Erro ao gerar o relatório. Tente novamente.");
        }
    });

    const onSubmit = (data: BookReportFormData) => {
        mutation.mutate(data);
    };

    return (
        <form onSubmit={handleSubmit(onSubmit as any)}>
            <div className="row g-4">
                <div className="col-12 col-md-6">
                    <Input
                        label="Título"
                        placeholder="Busca aproximada"
                        error={errors.Titulo?.message}
                        {...register("Titulo")}
                    />

                    <div className="mt-4">
                        <Input
                            label="Editora"
                            placeholder="Busca aproximada"
                            error={errors.Editora?.message}
                            {...register("Editora")}
                        />
                    </div>

                    <div className="mt-4">
                        <Input
                            label="Edição (ex: 1, 1-5, 1,3,5)"
                            placeholder="Valores fixos ou range"
                            error={errors.Edicao?.message}
                            {...register("Edicao")}
                        />
                    </div>

                    <div className="mt-4">
                        <Input
                            label="Ano de publicação (ex: 2020-2023)"
                            placeholder="Valores fixos ou range"
                            error={errors.AnoPublicacao?.message}
                            {...register("AnoPublicacao")}
                        />
                    </div>

                    <div className="mt-4">
                        <Input
                            label="Preço (ex: 10.50, 10.50-50.00)"
                            placeholder="Valores fixos ou range (use ponto para decimais)"
                            error={errors.Preco?.message}
                            {...register("Preco")}
                        />
                    </div>
                </div>

                <div className="col-12 col-md-6">
                    <Controller
                        control={control}
                        name="autores"
                        render={({ field }) => (
                            <AuthorSelect
                                isMulti
                                label="Autores"
                                placeholder="Selecione os autores"
                                helpText="Retorna livros que possuam pelo menos um dos autores"
                                value={field.value || []}
                                onChange={field.onChange}
                                error={errors.autores?.message}
                            />
                        )}
                    />

                    <div className="mt-4">
                        <Controller
                            control={control}
                            name="assuntos"
                            render={({ field }) => (
                                <SubjectSelect
                                    isMulti
                                    label="Categorias"
                                    placeholder="Selecione as categorias"
                                    helpText="Retorna livros que possuam pelo menos uma das categorias"
                                    value={field.value || []}
                                    onChange={field.onChange}
                                    error={errors.assuntos?.message}
                                />
                            )}
                        />
                    </div>
                </div>
            </div>

            <div className="d-flex justify-content-end mt-4 pt-3">
                <button
                    type="submit"
                    className="btn btn-primary px-4"
                    disabled={mutation.isPending}
                >
                    {mutation.isPending ? 'Gerando...' : 'Gerar Relatório'}
                </button>
            </div>
        </form>
    );
};
