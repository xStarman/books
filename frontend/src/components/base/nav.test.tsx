import { render, screen } from '@testing-library/react'
import { Nav } from './nav'

jest.mock('next/navigation', () => ({
  usePathname: () => '/',
}))

describe('Nav Component', () => {
  it('should render the branding logo', () => {
    render(<Nav />)
    expect(screen.getByText('Books')).toBeInTheDocument()
  })

  it('should render standard links', () => {
    render(<Nav />)
    expect(screen.getByText('Livros')).toBeInTheDocument()
    expect(screen.getByText('Autores')).toBeInTheDocument()
    expect(screen.getByText('Assuntos')).toBeInTheDocument()
  })
})
