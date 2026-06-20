import React from 'react'
import { Nav } from './nav'

export type LayoutProps = {
    children: React.ReactNode
}

export const Layout: React.FC<LayoutProps> = ({ children }) => {
    return (
        <div className='h-100 d-flex flex-column overflow-hidden' style={{ maxHeight: '100dvh' }}>
            <Nav />
            <div className="container mt-4 flex-1 d-flex flex-column overflow-hidden">
                {children}
            </div>
        </div>
    )
}