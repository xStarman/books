import { useForm, Controller } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { z } from "zod";
import { Input } from "../base/input";
import { MoneyInput } from "../base/money-input";
import { AuthorSelect } from "../authors/author-select";
import { SubjectSelect } from "../subjects/subject-select";
import { useMutation, useQueryClient } from "@tanstack/react-query";
import { saveBook } from "../../lib/save-book";
import { toast } from "react-toastify";
import { useRouter } from "next/router";
import { Book } from "../../lib/entities/book.entity";
import { forwardRef, useImperativeHandle, useState } from "react";

const schema = z.object({
    Titulo: z.string().min(1, "O título é obrigatório").max(40, "Máximo de 40 caracteres"),
    Editora: z.string().min(1, "A editora é obrigatória").max(40, "Máximo de 40 caracteres"),
    Edicao: z.preprocess((val) => {
        const num = Number(val);
        return isNaN(num) || val === '' || val === null ? -1 : num;
    }, z.number().int().min(1, "A edição é obrigatória e deve ser maior que 0")),
    AnoPublicacao: z.preprocess((val) => {
        const num = Number(val);
        return isNaN(num) || val === '' || val === null ? -1 : num;
    }, z.number().int().min(1000, "Ano inválido").max(9999, "Ano inválido")),
    Preco: z.preprocess((val) => {
        if (typeof val === 'string') {
            const num = Number(val.replace(',', '.'));
            return isNaN(num) || val === '' ? -1 : num;
        }
        return typeof val === 'number' && !isNaN(val) ? val : -1;
    }, z.number().min(0, "O preço é obrigatório e não pode ser negativo")),
    autores: z.array(z.union([z.number(), z.string()])).min(1, "Selecione pelo menos um autor"),
    assuntos: z.array(z.union([z.number(), z.string()])).min(1, "Selecione pelo menos uma categoria"),
});

export type BookFormData = z.infer<typeof schema>;

type BookFormProps = {
    initialData?: Book;
};

export type BookFormHandle = {
    importData: (data: any) => void;
};

export const BookForm = forwardRef<BookFormHandle, BookFormProps>(({ initialData }, ref) => {
    const router = useRouter();
    const queryClient = useQueryClient();
    const [highlightedFields, setHighlightedFields] = useState<Record<string, boolean>>({});

    const { register, handleSubmit, control, setError, setValue, formState: { errors, isSubmitting } } = useForm<BookFormData>({
        resolver: zodResolver(schema as any),
        defaultValues: {
            Titulo: initialData?.Titulo || "",
            Editora: initialData?.Editora || "",
            Edicao: initialData?.Edicao || ("" as any),
            AnoPublicacao: initialData?.AnoPublicacao || new Date().getFullYear(),
            Preco: initialData?.Preco ? Number(initialData.Preco) : ("" as any),
            autores: initialData?.autores?.map(a => a.CodAu) || [],
            assuntos: initialData?.assuntos?.map(a => a.CodAs) || [],
        }
    });

    useImperativeHandle(ref, () => ({
        importData: (bookData: any) => {
            const newHighlights: Record<string, boolean> = {};

            if (bookData.Titulo) {
                setValue('Titulo', bookData.Titulo, { shouldValidate: true });
                newHighlights.Titulo = true;
            }
            if (bookData.Editora) {
                setValue('Editora', bookData.Editora, { shouldValidate: true });
                newHighlights.Editora = true;
            }
            if (bookData.AnoPublicacao) {
                setValue('AnoPublicacao', Number(bookData.AnoPublicacao), { shouldValidate: true });
                newHighlights.AnoPublicacao = true;
            }

            const allAuthors = queryClient.getQueryData<any[]>(['authors', 'all']) || [];
            const allSubjects = queryClient.getQueryData<any[]>(['subjects', 'all']) || [];

            const newAuthors = (bookData.autores || []).map((a: string) => {
                const existing = allAuthors.find(ea => ea.Nome.toLowerCase() === a.toLowerCase());
                return existing ? existing.CodAu : `novo:${a}`;
            });
            if (newAuthors.length > 0) {
                setValue('autores', newAuthors, { shouldValidate: true });
                newHighlights.autores = true;
            }

            const newSubjects = (bookData.assuntos || []).map((s: string) => {
                const existing = allSubjects.find(es => es.Descricao.toLowerCase() === s.toLowerCase());
                return existing ? existing.CodAs : `novo:${s}`;
            });
            if (newSubjects.length > 0) {
                setValue('assuntos', newSubjects, { shouldValidate: true });
                newHighlights.assuntos = true;
            }

            setHighlightedFields(newHighlights);
            setTimeout(() => {
                setHighlightedFields({});
            }, 1500);
        }
    }));

    const mutation = useMutation({
        mutationFn: (data: BookFormData) => saveBook(data as any, initialData?.CodL),
        onSuccess: (data, variables) => {
            queryClient.invalidateQueries({ queryKey: ['books'] });
            
            if (variables.autores?.some(a => typeof a === 'string' && a.startsWith('novo:'))) {
                queryClient.invalidateQueries({ queryKey: ['authors'] });
            }
            if (variables.assuntos?.some(s => typeof s === 'string' && s.startsWith('novo:'))) {
                queryClient.invalidateQueries({ queryKey: ['subjects'] });
            }

            toast.success(`Livro ${initialData ? 'atualizado' : 'cadastrado'} com sucesso!`);
            if (!initialData) {
                router.push('/livros');
            }
        },
        onError: (error: any) => {
            if (error?.response?.status === 409) {
                const msg = error.response.data?.message;
                if (msg === 'book_already_exists') {
                    toast.error("Já existe um livro cadastrado com este Título, Editora, Edição e Ano.");
                    return;
                } 
                toast.error("Conflito ao salvar o livro.");
                return;
            } 
            
            if (error?.response?.status === 422) {
                const validationErrors = error.response.data.errors;
                Object.keys(validationErrors).forEach((key) => {
                    const fieldName = key.split('.')[0] as keyof BookFormData;
                    setError(fieldName, {
                        type: 'server',
                        message: validationErrors[key][0]
                    });
                });
                toast.warning("Verifique os campos com erro.");
                return;
            } 
            
            toast.error("Ocorreu um erro inesperado ao salvar.");
        }
    });

    const onSubmit = (data: BookFormData) => {
        mutation.mutate(data);
    };

    return (
        <form onSubmit={handleSubmit(onSubmit as any)}>
            <div className="row g-4">
                <div className="col-12 col-md-6">
                    <Input
                        label="Título"
                        placeholder="Preencha com o título do livro"
                        error={errors.Titulo?.message}
                        className={highlightedFields.Titulo ? "flash-green" : ""}
                        {...register("Titulo")}
                    />

                    <div className="mt-4">
                        <Input
                            label="Editora"
                            placeholder="Preencha o nome da editora"
                            error={errors.Editora?.message}
                            className={highlightedFields.Editora ? "flash-green" : ""}
                            {...register("Editora")}
                        />
                    </div>

                    <div className="mt-4">
                        <Input
                            label="Edição"
                            placeholder="Volume 1"
                            type="number"
                            min="1"
                            error={errors.Edicao?.message}
                            {...register("Edicao")}
                        />
                    </div>

                    <div className="mt-4">
                        <Input
                            label="Ano de publicação"
                            placeholder="Preencha o ano de publicação"
                            type="number"
                            min="1000"
                            max="9999"
                            error={errors.AnoPublicacao?.message}
                            className={highlightedFields.AnoPublicacao ? "flash-green" : ""}
                            {...register("AnoPublicacao")}
                        />
                    </div>

                    <div className="mt-4">
                        <Controller
                            control={control}
                            name="Preco"
                            render={({ field }) => (
                                <div>
                                    <MoneyInput
                                        label="Preço"
                                        placeholder="0,00"
                                        {...field}
                                    />
                                    {errors.Preco && <div className="invalid-feedback d-block mt-0 mb-2">{errors.Preco.message}</div>}
                                </div>
                            )}
                        />
                    </div>
                </div>

                <div className="col-12 col-md-6">
                    <div className={highlightedFields.autores ? "flash-green-container" : ""}>
                        <Controller
                            control={control}
                            name="autores"
                        render={({ field }) => (
                            <AuthorSelect
                                isMulti
                                label="Autores"
                                placeholder="Selecione um autor"
                                helpText="Clique no + para adicionar múltiplos autores"
                                value={field.value}
                                onChange={field.onChange}
                                error={errors.autores?.message}
                            />
                        )}
                    />
                    </div>

                    <div className={`mt-4 ${highlightedFields.assuntos ? "flash-green-container" : ""}`}>
                        <Controller
                            control={control}
                            name="assuntos"
                            render={({ field }) => (
                                <SubjectSelect
                                    isMulti
                                    label="Categorias"
                                    placeholder="Selecione uma categoria"
                                    helpText="Clique no + para adicionar múltiplas categorias"
                                    value={field.value}
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
                    disabled={isSubmitting || mutation.isPending}
                >
                    {isSubmitting || mutation.isPending ? 'Salvando...' : 'Salvar'}
                </button>
            </div>
        </form>
    );
});

BookForm.displayName = 'BookForm';
