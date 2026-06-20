import { api, objectToUri } from './api';
import { Paginated, PaginationCursor } from './base-response-types';
import { Book } from './entities/book.entity';

export interface ListBooksRequest extends PaginationCursor<Book> { }

export interface ListBooksResponse extends Paginated<Book> { }

export const getBookList = async (data?: ListBooksRequest): Promise<ListBooksResponse> => {
    const response = await api.get<ListBooksResponse>(`/api/books?${objectToUri(data)}`);
    return response.data;
};
