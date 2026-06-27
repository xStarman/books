import { render, screen, waitFor } from '@testing-library/react'
import { faker } from '@faker-js/faker'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { BookForm } from './book-form'
import { saveBook } from '../../lib/save-book'
import { toast } from 'react-toastify'
import { useRouter } from 'next/router'
import userEvent from '@testing-library/user-event'
import { getAllAuthors } from '../../lib/get-all-authors'
import { getAllSubjects } from '../../lib/get-all-subjects'

jest.mock('../../lib/save-book', () => ({
  saveBook: jest.fn()
}))

jest.mock('../../lib/get-all-authors', () => ({
  getAllAuthors: jest.fn()
}))

jest.mock('../../lib/get-all-subjects', () => ({
  getAllSubjects: jest.fn()
}))

jest.mock('react-toastify', () => ({
  toast: {
    success: jest.fn(),
    error: jest.fn(),
    warning: jest.fn(),
  }
}))

jest.mock('next/router', () => ({
  useRouter: jest.fn()
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

describe('BookForm', () => {
  const mockPush = jest.fn()

  beforeEach(() => {
    jest.clearAllMocks()
    queryClient.clear()
    const mockUseRouter = useRouter as jest.Mock;
    mockUseRouter.mockReturnValue({ push: mockPush });
    
    const mockGetAllAuthors = getAllAuthors as jest.Mock;
    mockGetAllAuthors.mockResolvedValue([{ CodAu: 1, Nome: faker.person.fullName() }]);
    
    const mockGetAllSubjects = getAllSubjects as jest.Mock;
    mockGetAllSubjects.mockResolvedValue([{ CodAs: 1, Descricao: faker.lorem.word() }]);
  })

  it('should render empty form for new book', () => {
    renderWithClient(<BookForm />)
    expect(screen.getByLabelText('Título')).toHaveValue('')
  })

  it('should validate required fields', async () => {
    const user = userEvent.setup()
    renderWithClient(<BookForm />)
    
    await user.click(screen.getByRole('button', { name: 'Salvar' }))
    
    expect(await screen.findByText('O título é obrigatório')).toBeInTheDocument()
    expect(screen.getByText('A editora é obrigatória')).toBeInTheDocument()
    expect(screen.getByText('A edição é obrigatória e deve ser maior que 0')).toBeInTheDocument()
    expect(saveBook).not.toHaveBeenCalled()
  })

  it('should submit form successfully and redirect', async () => {
    const user = userEvent.setup()
    const mockSaveBook = saveBook as jest.Mock;
    mockSaveBook.mockResolvedValue({});
    renderWithClient(<BookForm />)
    
    const bookTitle = faker.lorem.words(3)
    const publisher = faker.company.name()
    
    await user.type(screen.getByLabelText('Título'), bookTitle)
    await user.type(screen.getByLabelText('Editora'), publisher)
    await user.type(screen.getByLabelText('Edição'), '1')
    await user.clear(screen.getByLabelText('Ano de publicação'))
    await user.type(screen.getByLabelText('Ano de publicação'), '2023')
    await user.type(screen.getByLabelText('Preço'), '45,90')
    
    const authorSelect = await screen.findByLabelText('Autores')
    await user.selectOptions(authorSelect, '1')
    
    const subjectSelect = await screen.findByLabelText('Categorias')
    await user.selectOptions(subjectSelect, '1')
    
    // buttons to add multi select are icons without explicit labels, but they have class bi-plus
    // I can get them by clicking the button that contains .bi-plus
    const addBtns = screen.getAllByRole('button').filter(b => b.querySelector('.bi-plus'))
    await user.click(addBtns[0])
    await user.click(addBtns[1])

    await user.click(screen.getByRole('button', { name: 'Salvar' }))
    
    await waitFor(() => {
      expect(saveBook).toHaveBeenCalledWith(expect.objectContaining({ 
        Titulo: bookTitle,
        Editora: publisher,
        Edicao: 1,
        AnoPublicacao: 2023,
        Preco: 45.9,
        autores: [1],
        assuntos: [1]
      }), undefined)
      expect(toast.success).toHaveBeenCalledWith('Livro cadastrado com sucesso!')
      expect(mockPush).toHaveBeenCalledWith('/livros')
    })
  })

  it('should handle conflict error', async () => {
    const user = userEvent.setup()
    const mockSaveBook = saveBook as jest.Mock;
    mockSaveBook.mockRejectedValue({
      response: { status: 409, data: { message: 'book_already_exists' } }
    });
    
    renderWithClient(<BookForm />)
    
    await user.type(screen.getByLabelText('Título'), faker.lorem.words(3))
    await user.type(screen.getByLabelText('Editora'), faker.company.name())
    await user.type(screen.getByLabelText('Edição'), '1')
    await user.type(screen.getByLabelText('Preço'), '45,90')
    
    const authorSelect = await screen.findByLabelText('Autores')
    await user.selectOptions(authorSelect, '1')
    
    const subjectSelect = await screen.findByLabelText('Categorias')
    await user.selectOptions(subjectSelect, '1')
    
    const addBtns = screen.getAllByRole('button').filter(b => b.querySelector('.bi-plus'))
    await user.click(addBtns[0])
    await user.click(addBtns[1])

    await user.click(screen.getByRole('button', { name: 'Salvar' }))
    
    await waitFor(() => {
      expect(toast.error).toHaveBeenCalledWith('Já existe um livro cadastrado com este Título, Editora, Edição e Ano.')
    })
  })
})
