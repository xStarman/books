import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { z } from "zod";
import { Input } from "../base/input";
import { useMutation } from "@tanstack/react-query";
import { downloadAuditReport } from "../../lib/download-audit-report";
import { toast } from "react-toastify";
import { useId } from "react";

const schema = z.object({
    Titulo: z.string().optional(),
    acao: z.enum(["Todos", "UPDATE", "DELETE"]).optional(),
    dataInicial: z.string().optional(),
    dataFinal: z.string().optional(),
}).refine(data => {
    if (data.dataInicial && data.dataFinal) {
        return new Date(data.dataInicial) <= new Date(data.dataFinal);
    }
    return true;
}, {
    message: "A data inicial não pode ser maior que a data final",
    path: ["dataInicial"],
});

export type AuditReportFormData = z.infer<typeof schema>;

export const AuditReportForm = () => {
    const acaoId = useId();

    const { register, handleSubmit, formState: { errors } } = useForm<AuditReportFormData>({
        resolver: zodResolver(schema as any),
        defaultValues: {
            Titulo: "",
            acao: "Todos",
            dataInicial: "",
            dataFinal: "",
        }
    });

    const mutation = useMutation({
        mutationFn: (data: AuditReportFormData) => {
            const cleanData = Object.fromEntries(
                Object.entries(data).filter(([_, v]) => v !== "" && v != null)
            );
            return downloadAuditReport(cleanData);
        },
        onSuccess: () => {
            toast.success("Relatório gerado com sucesso!");
        },
        onError: () => {
            toast.error("Erro ao gerar o relatório. Tente novamente.");
        }
    });

    const onSubmit = (data: AuditReportFormData) => {
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
                        <label htmlFor={acaoId} className="form-label mb-1 fw-medium small text-muted">Ação</label>
                        <select
                            id={acaoId}
                            className={`form-select ${errors.acao ? 'is-invalid' : ''}`}
                            {...register("acao")}
                        >
                            <option value="Todos">Todos</option>
                            <option value="UPDATE">UPDATE</option>
                            <option value="DELETE">DELETE</option>
                        </select>
                        {errors.acao && <div className="invalid-feedback d-block mt-0 mb-2">{errors.acao.message}</div>}
                    </div>
                </div>

                <div className="col-12 col-md-6">
                    <Input
                        label="Data Inicial"
                        type="date"
                        error={errors.dataInicial?.message}
                        {...register("dataInicial")}
                    />

                    <div className="mt-4">
                        <Input
                            label="Data Final"
                            type="date"
                            error={errors.dataFinal?.message}
                            {...register("dataFinal")}
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
