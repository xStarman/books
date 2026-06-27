import { api } from "./api";
import { Book } from "./entities/book.entity";

export type SaveBookData = {
    Titulo: string;
    Editora: string;
    Edicao: number;
    AnoPublicacao: number;
    Preco: number;
    autores: number[];
    assuntos: number[];
}

export const saveBook = async (data: SaveBookData, id?: number): Promise<Book> => {
    if (id) {
        const response = await api.put<Book>(`/api/books/${id}`, data);
        return response.data;
    } 
    
    const response = await api.post<Book>('/api/books', data);
    return response.data;
};
