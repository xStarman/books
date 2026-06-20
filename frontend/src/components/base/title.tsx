import Head from 'next/head'
import React from 'react'

export type TitleProps = {
    title: string
    children?: React.ReactNode
}

export const Title: React.FC<TitleProps> = ({ title, children }) => {
    return (
        <>
            <Head>
                <title>{title}</title>
            </Head>
            <div className="d-flex justify-content-between align-items-center mb-4 pb-5">
                <h2 className="mb-0">{title}</h2>
                {children && (
                    <div className="d-flex gap-2">
                        {children}
                    </div>
                )}
            </div>
        </>
    )
}