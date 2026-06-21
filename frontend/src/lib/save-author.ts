import { api } from "./api";
import { Author } from "./entities/author.entity";

export type SaveAuthorData = {
    Nome: string;
}

export const saveAuthor = async (data: SaveAuthorData, id?: number): Promise<Author> => {
    if (id) {
        const response = await api.put<Author>(`/api/authors/${id}`, data);
        return response.data;
    } else {
        const response = await api.post<Author>('/api/authors', data);
        return response.data;
    }
};
