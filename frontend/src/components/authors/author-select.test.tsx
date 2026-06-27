import { render, screen } from '@testing-library/react'
import { faker } from '@faker-js/faker'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { AuthorSelect } from './author-select'
import { getAllAuthors } from '../../lib/get-all-authors'

jest.mock('../../lib/get-all-authors', () => ({
  getAllAuthors: jest.fn()
}))

const queryClient = new QueryClient({
    defaultOptions: {
        queries: {
            retry: false,
        },
    },
})

const renderWithClient = (ui: React.ReactElement) => {
  return render(
    <QueryClientProvider client={queryClient}>
      {ui}
    </QueryClientProvider>
  )
}

describe('AuthorSelect', () => {
  beforeEach(() => {
    jest.clearAllMocks()
    queryClient.clear()
  })

  it('should render loading state initially', () => {
    (getAllAuthors as jest.Mock).mockReturnValue(new Promise(() => {}))
    renderWithClient(<AuthorSelect />)
    expect(screen.getByText('Carregando autores...')).toBeInTheDocument()
  })

  it('should render authors when data is loaded', async () => {
    const authorName = faker.person.lastName();
    (getAllAuthors as jest.Mock).mockResolvedValue([{ CodAu: 1, Nome: authorName }])
    renderWithClient(<AuthorSelect />)
    
    const option = await screen.findByText(authorName)
    expect(option).toBeInTheDocument()
  })

  it('should render multi select when isMulti is true', async () => {
    const authorName = faker.person.lastName();
    (getAllAuthors as jest.Mock).mockResolvedValue([{ CodAu: 1, Nome: authorName }])
    renderWithClient(<AuthorSelect isMulti value={[]} onChange={() => {}} />)
    
    const option = await screen.findByText(authorName)
    expect(option).toBeInTheDocument()
  })

  it('should render error state if query fails', async () => {
    (getAllAuthors as jest.Mock).mockRejectedValue(new Error('Network error'))
    renderWithClient(<AuthorSelect />)
    
    expect(await screen.findByText('Erro ao carregar')).toBeInTheDocument()
    expect(screen.getByText('Não foi possível carregar a lista de autores.')).toBeInTheDocument()
  })
})
