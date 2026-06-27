import { render, screen, fireEvent, waitFor } from '@testing-library/react'
import { faker } from '@faker-js/faker'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { AuthorList } from './authors-list'
import { getAuthorList } from '../../lib/get-author-list'
import { deleteAuthor } from '../../lib/delete-author'
import { toast } from 'react-toastify'

jest.mock('../../lib/get-author-list', () => ({
  getAuthorList: jest.fn()
}))

jest.mock('../../lib/delete-author', () => ({
  deleteAuthor: jest.fn()
}))

jest.mock('react-toastify', () => ({
  toast: {
    success: jest.fn(),
    error: jest.fn(),
  }
}))

const queryClient = new QueryClient({
  defaultOptions: {
    queries: { retry: false },
    mutations: { retry: false },
  },
})

const renderWithClient = (ui: React.ReactElement) => {
  return render(
    <QueryClientProvider client={queryClient}>
      {ui}
    </QueryClientProvider>
  )
}

describe('AuthorList Component', () => {
  beforeEach(() => {
    jest.clearAllMocks()
    queryClient.clear()
  })

  it('should render loading state initially', () => {
    (getAuthorList as jest.Mock).mockReturnValue(new Promise(() => {}))
    renderWithClient(<AuthorList />)
    expect(screen.getByText('Carregando...')).toBeInTheDocument()
  })

  it('should render error state on query fail', async () => {
    (getAuthorList as jest.Mock).mockRejectedValue(new Error('error'))
    renderWithClient(<AuthorList />)
    expect(await screen.findByText('Erro ao carregar autores.')).toBeInTheDocument()
  })

  it('should render authors in the table', async () => {
    const authorName = faker.person.fullName();
    (getAuthorList as jest.Mock).mockResolvedValue({
      data: [{ CodAu: 1, Nome: authorName }],
      current_page: 1,
      last_page: 1,
      total: 1
    })

    renderWithClient(<AuthorList />)
    
    expect(await screen.findByText(authorName)).toBeInTheDocument()
  })

  it('should open delete confirm modal and call deleteAuthor on confirm', async () => {
    const authorName = faker.person.fullName();
    (getAuthorList as jest.Mock).mockResolvedValue({
      data: [{ CodAu: 1, Nome: authorName }],
      current_page: 1,
      last_page: 1,
    })
    const mockDeleteAuthor = deleteAuthor as jest.Mock;
    mockDeleteAuthor.mockResolvedValue({});

    renderWithClient(<AuthorList />)
    
    await screen.findByText(authorName)
    
    const deleteBtn = screen.getByTitle('Excluir')
    fireEvent.click(deleteBtn)
    
    expect(screen.getByText('Excluir Autor')).toBeInTheDocument()
    
    const confirmBtn = screen.getAllByRole('button', { name: 'Excluir' })[1]
    fireEvent.click(confirmBtn)
    
    await waitFor(() => {
      expect(deleteAuthor).toHaveBeenCalledWith(1)
      expect(toast.success).toHaveBeenCalledWith('Autor excluído com sucesso!')
    })
  })

  it('should show specific error toast when deleting author with books', async () => {
    const authorName = faker.person.fullName();
    (getAuthorList as jest.Mock).mockResolvedValue({
      data: [{ CodAu: 1, Nome: authorName }],
      current_page: 1,
      last_page: 1,
    })
    
    const errorResponse = {
      response: {
        status: 409,
        data: { message: 'author_has_books' }
      }
    }
    const mockDeleteAuthor = deleteAuthor as jest.Mock;
    mockDeleteAuthor.mockRejectedValue(errorResponse);

    renderWithClient(<AuthorList />)
    
    await screen.findByText(authorName)
    fireEvent.click(screen.getByTitle('Excluir'))
    fireEvent.click(screen.getAllByRole('button', { name: 'Excluir' })[1])
    
    await waitFor(() => {
      expect(toast.error).toHaveBeenCalledWith('Este autor possui livros vinculados e não pode ser excluído.')
    })
  })
})
