import { api } from './api';

export const deleteBook = async (id: number): Promise<void> => {
    await api.delete(`/api/books/${id}`);
};
