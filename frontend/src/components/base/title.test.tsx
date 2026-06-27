import { render, screen } from '@testing-library/react'
import { Title } from './title'

jest.mock('next/head', () => {
  return {
    __esModule: true,
    default: ({ children }: { children: React.ReactNode }) => <div data-testid="next-head">{children}</div>,
  }
})

describe('Title Component', () => {
  it('should render title text', () => {
    render(<Title title="My Page" />)
    expect(screen.getByRole('heading', { level: 2, name: 'My Page' })).toBeInTheDocument()
    expect(screen.getByTestId('next-head')).toHaveTextContent('My Page')
  })

  it('should render children if provided', () => {
    render(
      <Title title="My Page">
        <button>Add</button>
      </Title>
    )
    expect(screen.getByRole('button', { name: 'Add' })).toBeInTheDocument()
  })
})
