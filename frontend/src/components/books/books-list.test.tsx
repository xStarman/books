import { render, screen, fireEvent, waitFor } from '@testing-library/react'
import { faker } from '@faker-js/faker'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { BookList } from './books-list'
import { getBookList } from '../../lib/get-book-list'
import { deleteBook } from '../../lib/delete-book'
import { toast } from 'react-toastify'

jest.mock('../../lib/get-book-list', () => ({
  getBookList: jest.fn()
}))

jest.mock('../../lib/delete-book', () => ({
  deleteBook: jest.fn()
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

describe('BookList Component', () => {
  beforeEach(() => {
    jest.clearAllMocks()
    queryClient.clear()
  })

  it('should render books in the table and handle multiple authors expansion', async () => {
    const bookTitle = faker.lorem.words(3);
    const author1 = faker.person.fullName();
    const author2 = faker.person.fullName();

    (getBookList as jest.Mock).mockResolvedValue({
      data: [{
        CodL: 1,
        Titulo: bookTitle,
        Preco: 15.5,
        autores: [{ CodAu: 1, Nome: author1 }, { CodAu: 2, Nome: author2 }],
        assuntos: []
      }],
      current_page: 1,
      last_page: 1,
    })

    renderWithClient(<BookList />)

    expect(await screen.findByText(bookTitle)).toBeInTheDocument()

    expect(screen.getByText(new RegExp(author1))).toBeInTheDocument()
    const expandBtn = screen.getByRole('button', { name: /\+1/ })
    fireEvent.click(expandBtn)

    expect(screen.getByText(new RegExp(author2))).toBeInTheDocument()
    const hideBtn = screen.getByRole('button', { name: '(ocultar)' })
    fireEvent.click(hideBtn)
  })

  it('should open delete confirm modal and call deleteBook on confirm', async () => {
    const bookTitle = faker.lorem.words(3);
    (getBookList as jest.Mock).mockResolvedValue({
      data: [{ CodL: 1, Titulo: bookTitle, Preco: 10, autores: [], assuntos: [] }],
      current_page: 1,
      last_page: 1,
    });
    (deleteBook as jest.Mock).mockResolvedValue({})

    renderWithClient(<BookList />)

    await screen.findByText(bookTitle)

    const deleteBtn = document.querySelector('.btn-outline-danger')
    fireEvent.click(deleteBtn!)

    expect(screen.getByText('Excluir Livro')).toBeInTheDocument()

    const confirmBtn = screen.getByRole('button', { name: 'Excluir' })
    fireEvent.click(confirmBtn)

    await waitFor(() => {
      expect(deleteBook).toHaveBeenCalledWith(1)
      expect(toast.success).toHaveBeenCalledWith('Livro excluído com sucesso!')
    })
  })
})
