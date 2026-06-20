import { api } from "./api";
import { Author } from "./entities/author.entity";

export const getAllAuthors = async (): Promise<Author[]> => {
    const response = await api.get<Author[]>('/api/authors/all');
    return response.data;
};
