import { api, objectToUri } from "./api";

export const downloadAuditReport = async (filters: any) => {
    const response = await api.get(`/api/reports/audits?${objectToUri(filters)}`, {
        responseType: 'blob'
    });

    const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', 'relatorio_auditoria.xlsx');
    document.body.appendChild(link);
    link.click();
    link.remove();
    window.URL.revokeObjectURL(url);
};
