import Link from 'next/link'
import { usePathname } from 'next/navigation'
import React, { useCallback } from 'react'
import { API_URL } from '../../lib/env'

export type NavProps = {
}

export type NavLink = {
    label: string
    href?: string
    items?: NavLink[]
}

export const Nav: React.FC<NavProps> = () => {

    const pathname = usePathname()

    const links: NavLink[] = [
        { label: 'Livros', href: '/livros' },
        { label: 'Autores', href: '/autores' },
        { label: 'Assuntos', href: '/assuntos' },
        {
            label: 'Relatórios', items: [
                { label: 'Relatório de livros', href: '/relatorios/livros' },
                { label: 'Relatório de auditoria', href: '/relatorios/auditoria' },
            ]
        },
    ]

    const getActive = useCallback((link: NavLink) => {
        if (!pathname) return 'link-dark'
        if (link.href) {
            return pathname.startsWith(link.href) ? 'link-primary' : 'link-dark'
        }
        return link.items?.some(item => pathname.startsWith(item.href || '')) ? 'link-primary' : 'link-dark'
    }, [pathname])

    return (
        <nav className="py-2 bg-light border-bottom">
            <div className="container d-flex flex-wrap gap-5">
                <Link href="/" className='nav-link link-dark'>
                    <span className="fs-4">Books</span>
                </Link>
                <div className="flex-1 d-flex gap-5 justify-content-between">
                    <ul className="nav d-flex gap-2">
                        {links.map(link => (
                            <li className="nav-item" key={link.label}>
                                {link.href ? (
                                    <Link href={link.href} className={`nav-link px-2 ${getActive(link)}`}>
                                        {link.label}
                                    </Link>
                                ) : (
                                    <NavDropdown link={link} activeClass={getActive(link)} />
                                )}
                            </li>
                        ))}
                    </ul>
                    <ul className="nav d-flex">
                        <li className="nav-item" >
                            <Link target='_blank' href={`${API_URL}/api/documentation`} className={`nav-link px-2`}>
                                Documentação Swagger
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    )
}

const NavDropdown: React.FC<{ link: NavLink; activeClass: string }> = ({ link, activeClass }) => {
    return (
        <div>
            <ul className="navbar-nav">
                <li className="nav-item dropdown">
                    <a className={`nav-link px-2 ${activeClass} dropdown-toggle`} href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {link.label}
                    </a>
                    <ul className="dropdown-menu" aria-labelledby="navbarDarkDropdownMenuLink">
                        {link.items?.map(item => (
                            <li key={item.label}>
                                <Link className='dropdown-item' href={item.href || '#'}>
                                    {item.label}
                                </Link>
                            </li>
                        ))}
                    </ul>
                </li>
            </ul>
        </div>
    )
}