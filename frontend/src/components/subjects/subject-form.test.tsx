import { render, screen, waitFor } from '@testing-library/react'
import { faker } from '@faker-js/faker'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { SubjectForm } from './subject-form'
import { saveSubject } from '../../lib/save-subject'
import { toast } from 'react-toastify'
import { useRouter } from 'next/router'
import userEvent from '@testing-library/user-event'

jest.mock('../../lib/save-subject', () => ({
  saveSubject: jest.fn()
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

describe('SubjectForm', () => {
  const mockPush = jest.fn()

  beforeEach(() => {
    jest.clearAllMocks()
    queryClient.clear();
    (useRouter as jest.Mock).mockReturnValue({ push: mockPush })
  })

  it('should render empty form for new subject', () => {
    renderWithClient(<SubjectForm />)
    expect(screen.getByLabelText('Descrição')).toHaveValue('')
  })

  it('should render form with initial data for editing', () => {
    const initialDesc = faker.lorem.words(2)
    renderWithClient(<SubjectForm initialData={{ CodAs: 1, Descricao: initialDesc }} />)
    expect(screen.getByLabelText('Descrição')).toHaveValue(initialDesc)
  })

  it('should validate empty description', async () => {
    const user = userEvent.setup()
    renderWithClient(<SubjectForm />)

    await user.click(screen.getByRole('button', { name: 'Salvar' }))

    expect(await screen.findByText('A descrição é obrigatória')).toBeInTheDocument()
    expect(saveSubject).not.toHaveBeenCalled()
  })

  it('should submit form successfully and redirect', async () => {
    const user = userEvent.setup();
    (saveSubject as jest.Mock).mockResolvedValue({})
    renderWithClient(<SubjectForm />)

    const subjectDesc = faker.lorem.words(2)
    await user.type(screen.getByLabelText('Descrição'), subjectDesc)
    await user.click(screen.getByRole('button', { name: 'Salvar' }))

    await waitFor(() => {
      expect(saveSubject).toHaveBeenCalledWith({ Descricao: subjectDesc }, undefined)
      expect(toast.success).toHaveBeenCalledWith('Assunto cadastrado com sucesso!')
      expect(mockPush).toHaveBeenCalledWith('/assuntos')
    })
  })

  it('should submit form successfully for editing without redirect', async () => {
    const user = userEvent.setup();
    (saveSubject as jest.Mock).mockResolvedValue({})
    const initialDesc = faker.lorem.words(2)
    renderWithClient(<SubjectForm initialData={{ CodAs: 1, Descricao: initialDesc }} />)

    const newDesc = faker.lorem.words(2)
    await user.clear(screen.getByLabelText('Descrição'))
    await user.type(screen.getByLabelText('Descrição'), newDesc)
    await user.click(screen.getByRole('button', { name: 'Salvar' }))

    await waitFor(() => {
      expect(saveSubject).toHaveBeenCalledWith({ Descricao: newDesc }, 1)
      expect(toast.success).toHaveBeenCalledWith('Assunto atualizado com sucesso!')
      expect(mockPush).not.toHaveBeenCalled()
    })
  })

  it('should handle conflict error', async () => {
    const user = userEvent.setup();
    (saveSubject as jest.Mock).mockRejectedValue({
      response: { status: 409, data: { message: 'subject_already_exists' } }
    })

    renderWithClient(<SubjectForm />)

    await user.type(screen.getByLabelText('Descrição'), faker.lorem.words(2))
    await user.click(screen.getByRole('button', { name: 'Salvar' }))

    await waitFor(() => {
      expect(toast.error).toHaveBeenCalledWith('Já existe um assunto cadastrado com esta Descrição.')
    })
  })

  it('should handle validation errors from server', async () => {
    const user = userEvent.setup();
    (saveSubject as jest.Mock).mockRejectedValue({
      response: {
        status: 422,
        data: { errors: { Descricao: ['Descrição inválida no servidor'] } }
      }
    })

    renderWithClient(<SubjectForm />)

    await user.type(screen.getByLabelText('Descrição'), faker.lorem.words(2))
    await user.click(screen.getByRole('button', { name: 'Salvar' }))

    await waitFor(() => {
      expect(toast.warning).toHaveBeenCalledWith('Verifique os campos com erro.')
      expect(screen.getByText('Descrição inválida no servidor')).toBeInTheDocument()
    })
  })
})
