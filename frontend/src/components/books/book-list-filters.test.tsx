import { render, screen, act } from '@testing-library/react'
import { faker } from '@faker-js/faker'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { BookListFilters } from './book-list-filters'

const queryClient = new QueryClient({
  defaultOptions: {
    queries: { retry: false },
  },
})

const renderWithClient = (ui: React.ReactElement) => {
  return render(
    <QueryClientProvider client={queryClient}>
      {ui}
    </QueryClientProvider>
  )
}

describe('BookListFilters', () => {
  beforeEach(() => {
    jest.useFakeTimers()
  })

  afterEach(() => {
    jest.useRealTimers()
  })

  it('should render input fields', () => {
    const onFilterChange = jest.fn()
    renderWithClient(<BookListFilters onFilterChange={onFilterChange} />)
    expect(screen.getByLabelText('Título')).toBeInTheDocument()
    expect(screen.getByLabelText('Edição')).toBeInTheDocument()
  })

  it('should call onFilterChange after typing with debounce', async () => {
    const onFilterChange = jest.fn()
    const user = userEvent.setup({ advanceTimers: jest.advanceTimersByTime })
    renderWithClient(<BookListFilters onFilterChange={onFilterChange} />)
    
    const input = screen.getByLabelText('Título')
    const bookTitle = faker.lorem.words(3)
    await user.type(input, bookTitle)
    
    act(() => {
      jest.advanceTimersByTime(300)
    })
    
    expect(onFilterChange).toHaveBeenCalledWith(expect.objectContaining({ Titulo: bookTitle }))
  })
})
