import { api } from "./api";
import { Subject } from "./entities/subject.entity";

export const getAllSubjects = async (): Promise<Subject[]> => {
    const response = await api.get<Subject[]>('/api/subjects/all');
    return response.data;
};
