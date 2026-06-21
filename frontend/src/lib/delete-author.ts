import { api } from './api';

export const deleteAuthor = async (id: number): Promise<void> => {
    await api.delete(`/api/authors/${id}`);
};
