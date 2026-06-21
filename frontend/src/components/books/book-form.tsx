import { useForm, Controller } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { z } from "zod";
import { Input } from "../base/input";
import { MoneyInput } from "../base/money-input";
import { AuthorSelect } from "../authors/author-select";
import { SubjectSelect } from "../subjects/subject-select";
import { useMutation } from "@tanstack/react-query";
import { saveBook } from "../../lib/save-book";
import { toast } from "react-toastify";
import { useRouter } from "next/router";
import { Book } from "../../lib/entities/book.entity";

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
    autores: z.array(z.number()).min(1, "Selecione pelo menos um autor"),
    assuntos: z.array(z.number()).min(1, "Selecione pelo menos uma categoria"),
});

export type BookFormData = z.infer<typeof schema>;

type BookFormProps = {
    initialData?: Book;
};

export const BookForm = ({ initialData }: BookFormProps) => {
    const router = useRouter();

    const { register, handleSubmit, control, setError, formState: { errors, isSubmitting } } = useForm<BookFormData>({
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

    const mutation = useMutation({
        mutationFn: (data: BookFormData) => saveBook(data as any, initialData?.CodL),
        onSuccess: () => {
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
                } else {
                    toast.error("Conflito ao salvar o livro.");
                }
            } else if (error?.response?.status === 422) {
                const validationErrors = error.response.data.errors;
                Object.keys(validationErrors).forEach((key) => {
                    const fieldName = key.split('.')[0] as keyof BookFormData;
                    setError(fieldName, {
                        type: 'server',
                        message: validationErrors[key][0]
                    });
                });
                toast.warning("Verifique os campos com erro.");
            } else {
                toast.error("Ocorreu um erro inesperado ao salvar.");
            }
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
                        {...register("Titulo")}
                    />

                    <div className="mt-4">
                        <Input
                            label="Editora"
                            placeholder="Preencha o nome da editora"
                            error={errors.Editora?.message}
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

                    <div className="mt-4">
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
};
