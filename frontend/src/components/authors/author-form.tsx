import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { z } from "zod";
import { Input } from "../base/input";
import { useMutation, useQueryClient } from "@tanstack/react-query";
import { saveAuthor } from "../../lib/save-author";
import { toast } from "react-toastify";
import { useRouter } from "next/router";
import { Author } from "../../lib/entities/author.entity";

const schema = z.object({
    Nome: z.string().min(1, "O nome é obrigatório").max(40, "Máximo de 40 caracteres"),
});

export type AuthorFormData = z.infer<typeof schema>;

type AuthorFormProps = {
    initialData?: Author;
};

export const AuthorForm = ({ initialData }: AuthorFormProps) => {
    const router = useRouter();
    const queryClient = useQueryClient();

    const { register, handleSubmit, setError, formState: { errors, isSubmitting } } = useForm<AuthorFormData>({
        resolver: zodResolver(schema as any),
        defaultValues: {
            Nome: initialData?.Nome || "",
        }
    });

    const mutation = useMutation({
        mutationFn: (data: AuthorFormData) => saveAuthor(data, initialData?.CodAu),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['authors'] });
            toast.success(`Autor ${initialData ? 'atualizado' : 'cadastrado'} com sucesso!`);
            if (!initialData) {
                router.push('/autores');
            }
        },
        onError: (error: any) => {
            if (error?.response?.status === 409) {
                const msg = error.response.data?.message;
                if (msg === 'author_already_exists') {
                    toast.error("Já existe um autor cadastrado com este Nome.");
                } else {
                    toast.error("Conflito ao salvar o autor.");
                }
            } else if (error?.response?.status === 422) {
                const validationErrors = error.response.data.errors;
                Object.keys(validationErrors).forEach((key) => {
                    const fieldName = key as keyof AuthorFormData;
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

    const onSubmit = (data: AuthorFormData) => {
        mutation.mutate(data);
    };

    return (
        <form onSubmit={handleSubmit(onSubmit as any)}>
            <div className="row g-4">
                <div className="col-12">
                    <Input
                        label="Nome do Autor"
                        placeholder="Preencha com o nome do autor"
                        error={errors.Nome?.message}
                        {...register("Nome")}
                    />
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
