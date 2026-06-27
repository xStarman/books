import { render, screen, waitFor } from '@testing-library/react'
import { faker } from '@faker-js/faker'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { BookReportForm } from './book-report-form'
import { downloadBookReport } from '../../lib/download-book-report'
import { toast } from 'react-toastify'
import userEvent from '@testing-library/user-event'
import { getAllAuthors } from '../../lib/get-all-authors'
import { getAllSubjects } from '../../lib/get-all-subjects'

jest.mock('../../lib/download-book-report', () => ({
  downloadBookReport: jest.fn()
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

describe('BookReportForm', () => {
  beforeEach(() => {
    jest.clearAllMocks()
    queryClient.clear()
    const mockGetAllAuthors = getAllAuthors as jest.Mock;
    mockGetAllAuthors.mockResolvedValue([{ CodAu: 1, Nome: faker.person.fullName() }]);
    const mockGetAllSubjects = getAllSubjects as jest.Mock;
    mockGetAllSubjects.mockResolvedValue([{ CodAs: 1, Descricao: faker.lorem.word() }]);
  })

  it('should validate range inputs format', async () => {
    const user = userEvent.setup()
    renderWithClient(<BookReportForm />)
    
    await user.type(screen.getByLabelText('Edição (ex: 1, 1-5, 1,3,5)'), '5-1')
    await user.click(screen.getByRole('button', { name: 'Gerar Relatório' }))
    
    expect(await screen.findByText('Formato inválido ou valor inicial maior que o final')).toBeInTheDocument()
    expect(downloadBookReport).not.toHaveBeenCalled()
  })

  it('should submit form and call download successfully', async () => {
    const user = userEvent.setup()
    const mockDownloadBookReport = downloadBookReport as jest.Mock;
    mockDownloadBookReport.mockResolvedValue({});
    renderWithClient(<BookReportForm />)
    
    const bookTitle = faker.lorem.words(3)
    await user.type(screen.getByLabelText('Título'), bookTitle)
    await user.type(screen.getByLabelText('Ano de publicação (ex: 2020-2023)'), '2020-2023')
    
    await user.click(screen.getByRole('button', { name: 'Gerar Relatório' }))
    
    await waitFor(() => {
      expect(downloadBookReport).toHaveBeenCalledWith({
        Titulo: bookTitle,
        AnoPublicacao: '2020-2023'
      })
      expect(toast.success).toHaveBeenCalledWith('Relatório gerado com sucesso!')
    })
  })
})
