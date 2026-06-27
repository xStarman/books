import { render, screen, waitFor } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { faker } from '@faker-js/faker'
import { IsbnSearchPopover } from './isbn-search-popover'
import { getBookByIsbn } from '../../lib/get-book-by-isbn'
import { toast } from 'react-toastify'

jest.mock('../../lib/get-book-by-isbn', () => ({
  getBookByIsbn: jest.fn()
}))

jest.mock('react-toastify', () => ({
  toast: {
    error: jest.fn(),
  }
}))

const mockGetBookByIsbn = getBookByIsbn as jest.Mock

const createQueryClient = () => new QueryClient({
  defaultOptions: {
    queries: { retry: false },
    mutations: { retry: false },
  },
})

const renderWithClient = (ui: React.ReactElement) => {
  const queryClient = createQueryClient()
  return render(
    <QueryClientProvider client={queryClient}>
      {ui}
    </QueryClientProvider>
  )
}

describe('IsbnSearchPopover', () => {
  const mockOnImport = jest.fn()

  beforeEach(() => {
    jest.clearAllMocks()
  })

  it('should render the trigger button', () => {
    renderWithClient(<IsbnSearchPopover onImport={mockOnImport} />)
    expect(screen.getByText('Buscar por ISBN')).toBeInTheDocument()
  })

  it('should render isbn input inside dropdown menu', () => {
    renderWithClient(<IsbnSearchPopover onImport={mockOnImport} />)
    expect(screen.getByPlaceholderText('Ex: 9788575228074')).toBeInTheDocument()
  })

  it('should disable search button when input is empty', () => {
    renderWithClient(<IsbnSearchPopover onImport={mockOnImport} />)
    const buttons = screen.getAllByRole('button')
    const searchBtn = buttons.find(b => b.getAttribute('type') === 'submit')
    expect(searchBtn).toBeDisabled()
  })

  it('should call getBookByIsbn on form submit', async () => {
    const user = userEvent.setup();
    mockGetBookByIsbn.mockResolvedValue({
      Titulo: faker.lorem.words(3),
      Editora: faker.company.name(),
      AnoPublicacao: '2020',
      autores: [],
      assuntos: [],
    })

    renderWithClient(<IsbnSearchPopover onImport={mockOnImport} />)

    const isbnCode = '978' + faker.string.numeric(10)
    const input = screen.getByPlaceholderText('Ex: 9788575228074')
    await user.type(input, isbnCode)

    const form = input.closest('form')!
    const searchBtn = form.querySelector('button[type="submit"]')!
    await user.click(searchBtn)

    await waitFor(() => {
      expect(mockGetBookByIsbn).toHaveBeenCalledWith(isbnCode)
    })
  })

  it('should display book data after successful search', async () => {
    const user = userEvent.setup();
    const bookTitle = faker.lorem.words(3)
    mockGetBookByIsbn.mockResolvedValue({
      Titulo: bookTitle,
      Editora: faker.company.name(),
      AnoPublicacao: '2019',
      autores: [faker.person.fullName()],
      assuntos: [faker.lorem.word()],
    })

    renderWithClient(<IsbnSearchPopover onImport={mockOnImport} />)

    const input = screen.getByPlaceholderText('Ex: 9788575228074')
    await user.type(input, '978' + faker.string.numeric(10))

    const form = input.closest('form')!
    const searchBtn = form.querySelector('button[type="submit"]')!
    await user.click(searchBtn)

    expect(await screen.findByText(bookTitle)).toBeInTheDocument()
    expect(screen.getByText('Importar dados')).toBeInTheDocument()
  })

  it('should call onImport and reset state when import button is clicked', async () => {
    const user = userEvent.setup()
    const bookData = {
      Titulo: faker.lorem.words(3),
      Editora: faker.company.name(),
      AnoPublicacao: '2024',
      autores: [faker.person.fullName()],
      assuntos: [faker.lorem.word()],
    };
    mockGetBookByIsbn.mockResolvedValue(bookData)

    renderWithClient(<IsbnSearchPopover onImport={mockOnImport} />)

    const input = screen.getByPlaceholderText('Ex: 9788575228074')
    await user.type(input, '1234567890')

    const form = input.closest('form')!
    const searchBtn = form.querySelector('button[type="submit"]')!
    await user.click(searchBtn)

    const importBtn = await screen.findByText('Importar dados')
    await user.click(importBtn)

    expect(mockOnImport).toHaveBeenCalledWith(bookData)
  })

  it('should show error message on failed search', async () => {
    const user = userEvent.setup();
    const errorMessage = faker.lorem.sentence()
    mockGetBookByIsbn.mockRejectedValue({
      response: { data: { message: errorMessage } }
    })

    renderWithClient(<IsbnSearchPopover onImport={mockOnImport} />)

    const input = screen.getByPlaceholderText('Ex: 9788575228074')
    await user.type(input, '0000000000')

    const form = input.closest('form')!
    const searchBtn = form.querySelector('button[type="submit"]')!
    await user.click(searchBtn)

    await waitFor(() => {
      expect(toast.error).toHaveBeenCalledWith(errorMessage)
    })
  })
})

