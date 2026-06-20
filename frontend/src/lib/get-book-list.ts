import { api, objectToUri } from './api';
import { Paginated, PaginationCursor } from './base-response-types';
import { Book } from './entities/book.entity';

export interface ListBooksRequest extends Omit<PaginationCursor<Book>, 'filters'> {
    filters?: PaginationCursor<Book>['filters'] & {
        Autor?: number;
        Assunto?: number;
    };
}

export interface ListBooksResponse extends Paginated<Book> { }

export const getBookList = async (data?: ListBooksRequest): Promise<ListBooksResponse> => {
    const response = await api.get<ListBooksResponse>(`/api/books?${objectToUri(data)}`);
    return response.data;
};
