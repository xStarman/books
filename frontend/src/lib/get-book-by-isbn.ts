import { api } from "./api";

export type IsbnBookData = {
    Titulo: string;
    Editora: string;
    AnoPublicacao: string | null;
    autores: string[];
    assuntos: string[];
};

export const getBookByIsbn = async (isbn: string): Promise<IsbnBookData> => {
    const response = await api.get<IsbnBookData>(`/api/isbn/${isbn}`);
    return response.data;
};
