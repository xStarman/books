import Link from "next/link";
import { Table } from "../base/table";
import { useState } from "react";
import { useQuery } from "@tanstack/react-query";
import { getBookList, ListBooksResponse } from "../../lib/get-book-list";

export const BookList: React.FC = () => {

    const { data, isLoading, isFetching, error } = useQuery<ListBooksResponse>({
        queryKey: ['all-features'],
        queryFn: async () => getBookList(),
    });

    const onPageChange = (page: number) => {
        console.log(page)
    }

    return <>
        <Table
            columns={[
                {
                    key: "title",
                    label: "Título",
                    sortable: true,
                },
                {
                    key: "author",
                    label: "Autor",
                    sortable: true,
                },
                {
                    key: "year",
                    label: "Ano",
                    sortable: true,
                },
            ]}
            data={[
                {
                    title: "Livro 1",
                    author: "Autor 1",
                    year: 2022,
                },
                {
                    title: "Livro 2",
                    author: "Autor 2",
                    year: 2023,
                },
            ]}
        />
    </>;
}
