import { api, objectToUri } from "./api";
import { Paginated, PaginationCursor } from "./base-response-types";
import { Author } from "./entities/author.entity";

export type ListAuthorsRequest = PaginationCursor<Author>;
export type ListAuthorsResponse = Paginated<Author>;

export const getAuthorList = async (data?: ListAuthorsRequest): Promise<ListAuthorsResponse> => {
    const response = await api.get<ListAuthorsResponse>(`/api/authors?${objectToUri(data)}`);
    return response.data;
};
