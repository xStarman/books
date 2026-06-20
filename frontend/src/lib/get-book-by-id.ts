import { api } from "./api";
import { Book } from "./entities/book.entity";

export const getBookById = async (id: number): Promise<Book> => {
    const response = await api.get<Book>(`/api/books/${id}`);
    return response.data;
};
