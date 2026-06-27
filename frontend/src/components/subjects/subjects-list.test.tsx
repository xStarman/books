import { render, screen, fireEvent, waitFor } from '@testing-library/react'
import { faker } from '@faker-js/faker'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { SubjectList } from './subjects-list'
import { getSubjectList } from '../../lib/get-subject-list'
import { deleteSubject } from '../../lib/delete-subject'
import { toast } from 'react-toastify'

jest.mock('../../lib/get-subject-list', () => ({
  getSubjectList: jest.fn()
}))

jest.mock('../../lib/delete-subject', () => ({
  deleteSubject: jest.fn()
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

describe('SubjectList Component', () => {
  beforeEach(() => {
    jest.clearAllMocks()
    queryClient.clear()
  })

  it('should render loading state initially', () => {
    (getSubjectList as jest.Mock).mockReturnValue(new Promise(() => { }))
    renderWithClient(<SubjectList />)
    expect(screen.getByText('Carregando...')).toBeInTheDocument()
  })

  it('should render error state on query fail', async () => {
    (getSubjectList as jest.Mock).mockRejectedValue(new Error('error'))
    renderWithClient(<SubjectList />)
    expect(await screen.findByText('Erro ao carregar assuntos.')).toBeInTheDocument()
  })

  it('should render subjects in the table', async () => {
    const subjectDesc = faker.lorem.words(2);
    (getSubjectList as jest.Mock).mockResolvedValue({
      data: [{ CodAs: 1, Descricao: subjectDesc }],
      current_page: 1,
      last_page: 1,
      total: 1
    })

    renderWithClient(<SubjectList />)

    expect(await screen.findByText(subjectDesc)).toBeInTheDocument()
  })

  it('should open delete confirm modal and call deleteSubject on confirm', async () => {
    const subjectDesc = faker.lorem.words(2);
    (getSubjectList as jest.Mock).mockResolvedValue({
      data: [{ CodAs: 1, Descricao: subjectDesc }],
      current_page: 1,
      last_page: 1,
    });
    (deleteSubject as jest.Mock).mockResolvedValue({})

    renderWithClient(<SubjectList />)

    await screen.findByText(subjectDesc)

    const deleteBtn = screen.getByTitle('Excluir')
    fireEvent.click(deleteBtn)

    expect(screen.getByText('Excluir Assunto')).toBeInTheDocument()

    const confirmBtn = screen.getAllByRole('button', { name: 'Excluir' })[1]
    fireEvent.click(confirmBtn)

    await waitFor(() => {
      expect(deleteSubject).toHaveBeenCalledWith(1)
      expect(toast.success).toHaveBeenCalledWith('Assunto excluído com sucesso!')
    })
  })

  it('should show specific error toast when deleting subject with books', async () => {
    const subjectDesc = faker.lorem.words(2);
    (getSubjectList as jest.Mock).mockResolvedValue({
      data: [{ CodAs: 1, Descricao: subjectDesc }],
      current_page: 1,
      last_page: 1,
    })

    const errorResponse = {
      response: {
        status: 409,
        data: { message: 'subject_has_books' }
      }
    };
    (deleteSubject as jest.Mock).mockRejectedValue(errorResponse)

    renderWithClient(<SubjectList />)

    await screen.findByText(subjectDesc)
    fireEvent.click(screen.getByTitle('Excluir'))
    fireEvent.click(screen.getAllByRole('button', { name: 'Excluir' })[1])

    await waitFor(() => {
      expect(toast.error).toHaveBeenCalledWith('Este assunto possui livros vinculados e não pode ser excluído.')
    })
  })
})
