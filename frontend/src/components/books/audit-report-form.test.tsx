import { render, screen, waitFor } from '@testing-library/react'
import { faker } from '@faker-js/faker'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { AuditReportForm } from './audit-report-form'
import { downloadAuditReport } from '../../lib/download-audit-report'
import { toast } from 'react-toastify'
import userEvent from '@testing-library/user-event'

jest.mock('../../lib/download-audit-report', () => ({
  downloadAuditReport: jest.fn()
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

describe('AuditReportForm', () => {
  beforeEach(() => {
    jest.clearAllMocks()
    queryClient.clear()
  })

  it('should validate date range', async () => {
    const user = userEvent.setup()
    renderWithClient(<AuditReportForm />)
    
    await user.type(screen.getByLabelText('Data Inicial'), '2023-12-31')
    await user.type(screen.getByLabelText('Data Final'), '2023-01-01')
    await user.click(screen.getByRole('button', { name: 'Gerar Relatório' }))
    
    expect(await screen.findByText('A data inicial não pode ser maior que a data final')).toBeInTheDocument()
    expect(downloadAuditReport).not.toHaveBeenCalled()
  })

  it('should submit form and call download successfully', async () => {
    const user = userEvent.setup()
    const mockDownloadAuditReport = downloadAuditReport as jest.Mock;
    mockDownloadAuditReport.mockResolvedValue({});
    renderWithClient(<AuditReportForm />)
    
    const title = faker.lorem.words(3)
    await user.type(screen.getByLabelText('Título'), title)
    await user.selectOptions(screen.getByLabelText('Ação'), 'UPDATE')
    await user.type(screen.getByLabelText('Data Inicial'), '2023-01-01')
    await user.type(screen.getByLabelText('Data Final'), '2023-12-31')
    
    await user.click(screen.getByRole('button', { name: 'Gerar Relatório' }))
    
    await waitFor(() => {
      expect(downloadAuditReport).toHaveBeenCalledWith({
        Titulo: title,
        acao: 'UPDATE',
        dataInicial: '2023-01-01',
        dataFinal: '2023-12-31'
      })
      expect(toast.success).toHaveBeenCalledWith('Relatório gerado com sucesso!')
    })
  })
})
