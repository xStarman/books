import React from 'react'
import { Nav } from './nav'

export type LayoutProps = {
    children: React.ReactNode
}

export const Layout: React.FC<LayoutProps> = ({ children }) => {
    return (
        <>
            <Nav />
            <div className="container mt-4">
                {children}
            </div>
        </>
    )
}