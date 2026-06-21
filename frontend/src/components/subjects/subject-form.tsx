import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { z } from "zod";
import { Input } from "../base/input";
import { useMutation } from "@tanstack/react-query";
import { saveSubject } from "../../lib/save-subject";
import { toast } from "react-toastify";
import { useRouter } from "next/router";
import { Subject } from "../../lib/entities/subject.entity";

const schema = z.object({
    Descricao: z.string().min(1, "A descrição é obrigatória").max(20, "Máximo de 20 caracteres"),
});

export type SubjectFormData = z.infer<typeof schema>;

type SubjectFormProps = {
    initialData?: Subject;
};

export const SubjectForm = ({ initialData }: SubjectFormProps) => {
    const router = useRouter();

    const { register, handleSubmit, setError, formState: { errors, isSubmitting } } = useForm<SubjectFormData>({
        resolver: zodResolver(schema as any),
        defaultValues: {
            Descricao: initialData?.Descricao || "",
        }
    });

    const mutation = useMutation({
        mutationFn: (data: SubjectFormData) => saveSubject(data, initialData?.CodAs),
        onSuccess: () => {
            toast.success(`Assunto ${initialData ? 'atualizado' : 'cadastrado'} com sucesso!`);
            if (!initialData) {
                router.push('/assuntos');
            }
        },
        onError: (error: any) => {
            if (error?.response?.status === 409) {
                const msg = error.response.data?.message;
                if (msg === 'subject_already_exists') {
                    toast.error("Já existe um assunto cadastrado com esta Descrição.");
                } else {
                    toast.error("Conflito ao salvar o assunto.");
                }
            } else if (error?.response?.status === 422) {
                const validationErrors = error.response.data.errors;
                Object.keys(validationErrors).forEach((key) => {
                    const fieldName = key as keyof SubjectFormData;
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

    const onSubmit = (data: SubjectFormData) => {
        mutation.mutate(data);
    };

    return (
        <form onSubmit={handleSubmit(onSubmit as any)}>
            <div className="row g-4">
                <div className="col-12">
                    <Input
                        label="Descrição"
                        placeholder="Preencha com a descrição do assunto"
                        error={errors.Descricao?.message}
                        {...register("Descricao")}
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
