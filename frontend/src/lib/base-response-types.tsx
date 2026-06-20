export type Paginated<T> = {
    data: T[]
    current_page: 1
    first_page_url: string
    from: number
    last_page: number
    last_page_url: string
    next_page_url?: string
    path: string
    per_page: number
    prev_page_url?: string
    to: number
    total: number
}

export type PaginationCursor<T = any> = {
    order?: {
        [P in keyof T]?: 'asc' | 'desc';
    };
    filters?: { [P in keyof T]?: T[P] | T[P][] | string[] | number[] };
    page?: number;
    page_size?: number;
};
