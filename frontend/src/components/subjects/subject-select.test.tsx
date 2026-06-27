import { render, screen } from '@testing-library/react'
import { faker } from '@faker-js/faker'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { SubjectSelect } from './subject-select'
import { getAllSubjects } from '../../lib/get-all-subjects'

jest.mock('../../lib/get-all-subjects', () => ({
  getAllSubjects: jest.fn()
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

describe('SubjectSelect', () => {
  beforeEach(() => {
    jest.clearAllMocks()
    queryClient.clear()
  })

  it('should render loading state initially', () => {
    (getAllSubjects as jest.Mock).mockReturnValue(new Promise(() => {}))
    renderWithClient(<SubjectSelect />)
    expect(screen.getByText('Carregando categorias...')).toBeInTheDocument()
  })

  it('should render subjects when data is loaded', async () => {
    const subjectDesc = faker.lorem.word();
    (getAllSubjects as jest.Mock).mockResolvedValue([{ CodAs: 1, Descricao: subjectDesc }])
    renderWithClient(<SubjectSelect />)
    
    const option = await screen.findByText(subjectDesc)
    expect(option).toBeInTheDocument()
  })

  it('should render multi select when isMulti is true', async () => {
    const subjectDesc = faker.lorem.word();
    (getAllSubjects as jest.Mock).mockResolvedValue([{ CodAs: 1, Descricao: subjectDesc }])
    renderWithClient(<SubjectSelect isMulti value={[]} onChange={() => {}} />)
    
    const option = await screen.findByText(subjectDesc)
    expect(option).toBeInTheDocument()
  })

  it('should render error state if query fails', async () => {
    (getAllSubjects as jest.Mock).mockRejectedValue(new Error('Network error'))
    renderWithClient(<SubjectSelect />)
    
    expect(await screen.findByText('Erro ao carregar')).toBeInTheDocument()
    expect(screen.getByText('Não foi possível carregar a lista de categorias.')).toBeInTheDocument()
  })
})
