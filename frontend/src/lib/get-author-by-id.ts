import { api } from "./api";
import { Author } from "./entities/author.entity";

export const getAuthorById = async (id: number): Promise<Author> => {
    const response = await api.get<Author>(`/api/authors/${id}`);
    return response.data;
};
