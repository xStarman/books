import { render, screen, waitFor } from '@testing-library/react'
import { faker } from '@faker-js/faker'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { AuthorForm } from './author-form'
import { saveAuthor } from '../../lib/save-author'
import { toast } from 'react-toastify'
import { useRouter } from 'next/router'
import userEvent from '@testing-library/user-event'

jest.mock('../../lib/save-author', () => ({
  saveAuthor: jest.fn()
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

describe('AuthorForm', () => {
  const mockPush = jest.fn()

  beforeEach(() => {
    jest.clearAllMocks()
    queryClient.clear()
    const mockUseRouter = useRouter as jest.Mock;
    mockUseRouter.mockReturnValue({ push: mockPush });
  })

  it('should render empty form for new author', () => {
    renderWithClient(<AuthorForm />)
    expect(screen.getByLabelText('Nome do Autor')).toHaveValue('')
  })

  it('should render form with initial data for editing', () => {
    const initialName = faker.person.fullName()
    renderWithClient(<AuthorForm initialData={{ CodAu: 1, Nome: initialName }} />)
    expect(screen.getByLabelText('Nome do Autor')).toHaveValue(initialName)
  })

  it('should validate empty name', async () => {
    const user = userEvent.setup()
    renderWithClient(<AuthorForm />)
    
    await user.click(screen.getByRole('button', { name: 'Salvar' }))
    
    expect(await screen.findByText('O nome é obrigatório')).toBeInTheDocument()
    expect(saveAuthor).not.toHaveBeenCalled()
  })

  it('should submit form successfully and redirect', async () => {
    const user = userEvent.setup()
    const mockSaveAuthor = saveAuthor as jest.Mock;
    mockSaveAuthor.mockResolvedValue({});
    renderWithClient(<AuthorForm />)
    
    const authorName = faker.person.fullName()
    await user.type(screen.getByLabelText('Nome do Autor'), authorName)
    await user.click(screen.getByRole('button', { name: 'Salvar' }))
    
    await waitFor(() => {
      expect(saveAuthor).toHaveBeenCalledWith({ Nome: authorName }, undefined)
      expect(toast.success).toHaveBeenCalledWith('Autor cadastrado com sucesso!')
      expect(mockPush).toHaveBeenCalledWith('/autores')
    })
  })

  it('should submit form successfully for editing without redirect', async () => {
    const user = userEvent.setup()
    const mockSaveAuthor = saveAuthor as jest.Mock;
    mockSaveAuthor.mockResolvedValue({});
    const initialName = faker.person.fullName()
    renderWithClient(<AuthorForm initialData={{ CodAu: 1, Nome: initialName }} />)
    
    const newName = faker.person.fullName()
    await user.clear(screen.getByLabelText('Nome do Autor'))
    await user.type(screen.getByLabelText('Nome do Autor'), newName)
    await user.click(screen.getByRole('button', { name: 'Salvar' }))
    
    await waitFor(() => {
      expect(saveAuthor).toHaveBeenCalledWith({ Nome: newName }, 1)
      expect(toast.success).toHaveBeenCalledWith('Autor atualizado com sucesso!')
      expect(mockPush).not.toHaveBeenCalled()
    })
  })

  it('should handle conflict error', async () => {
    const user = userEvent.setup()
    const mockSaveAuthor = saveAuthor as jest.Mock;
    mockSaveAuthor.mockRejectedValue({
      response: { status: 409, data: { message: 'author_already_exists' } }
    });
    
    renderWithClient(<AuthorForm />)
    
    await user.type(screen.getByLabelText('Nome do Autor'), faker.person.fullName())
    await user.click(screen.getByRole('button', { name: 'Salvar' }))
    
    await waitFor(() => {
      expect(toast.error).toHaveBeenCalledWith('Já existe um autor cadastrado com este Nome.')
    })
  })

  it('should handle validation errors from server', async () => {
    const user = userEvent.setup()
    const mockSaveAuthor = saveAuthor as jest.Mock;
    mockSaveAuthor.mockRejectedValue({
      response: { 
        status: 422, 
        data: { errors: { Nome: ['Nome inválido no servidor'] } } 
      }
    });
    
    renderWithClient(<AuthorForm />)
    
    await user.type(screen.getByLabelText('Nome do Autor'), faker.person.fullName())
    await user.click(screen.getByRole('button', { name: 'Salvar' }))
    
    await waitFor(() => {
      expect(toast.warning).toHaveBeenCalledWith('Verifique os campos com erro.')
      expect(screen.getByText('Nome inválido no servidor')).toBeInTheDocument()
    })
  })
})
