import { api } from "./api";
import { Subject } from "./entities/subject.entity";

export const getSubjectById = async (id: number): Promise<Subject> => {
    const response = await api.get<Subject>(`/api/subjects/${id}`);
    return response.data;
};
