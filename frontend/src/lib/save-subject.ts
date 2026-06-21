import { api } from "./api";
import { Subject } from "./entities/subject.entity";

export type SaveSubjectData = {
    Descricao: string;
}

export const saveSubject = async (data: SaveSubjectData, id?: number): Promise<Subject> => {
    if (id) {
        const response = await api.put<Subject>(`/api/subjects/${id}`, data);
        return response.data;
    } else {
        const response = await api.post<Subject>('/api/subjects', data);
        return response.data;
    }
};
