import { api } from './api';

export const deleteSubject = async (id: number): Promise<void> => {
    await api.delete(`/api/subjects/${id}`);
};
