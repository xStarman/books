import { api, objectToUri } from "./api";
import { Paginated, PaginationCursor } from "./base-response-types";
import { Subject } from "./entities/subject.entity";

export type ListSubjectsRequest = PaginationCursor<Subject>;
export type ListSubjectsResponse = Paginated<Subject>;

export const getSubjectList = async (data?: ListSubjectsRequest): Promise<ListSubjectsResponse> => {
    const response = await api.get<ListSubjectsResponse>(`/api/subjects?${objectToUri(data)}`);
    return response.data;
};
