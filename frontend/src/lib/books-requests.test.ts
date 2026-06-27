import { api } from './api'
import { faker } from '@faker-js/faker'
import { deleteBook } from './delete-book'
import { getBookById } from './get-book-by-id'
import { getBookList } from './get-book-list'
import { saveBook } from './save-book'
import { downloadAuditReport } from './download-audit-report'
import { downloadBookReport } from './download-book-report'

jest.mock('./api', () => ({
  api: {
    get: jest.fn(),
    post: jest.fn(),
    put: jest.fn(),
    delete: jest.fn(),
  },
  objectToUri: jest.requireActual('./api').objectToUri
}))

describe('Book Requests', () => {
  beforeEach(() => {
    jest.clearAllMocks()
  })

  it('should call delete endpoint for deleteBook', async () => {
    (api.delete as jest.Mock).mockResolvedValue({ data: {} })
    await deleteBook(1)
    expect(api.delete).toHaveBeenCalledWith('/api/books/1')
  })

  it('should call get by id endpoint for getBookById', async () => {
    (api.get as jest.Mock).mockResolvedValue({ data: { id: 1 } })
    await getBookById(1)
    expect(api.get).toHaveBeenCalledWith('/api/books/1')
  })

  it('should call list endpoint for getBookList with correct params', async () => {
    (api.get as jest.Mock).mockResolvedValue({ data: { data: [] } })
    await getBookList({ page: 1, page_size: 10 })
    expect(api.get).toHaveBeenCalledWith('/api/books?page=1&page_size=10')
  })

  it('should call post endpoint when saving new book', async () => {
    const bookTitle = faker.lorem.words(3);
    (api.post as jest.Mock).mockResolvedValue({ data: { id: 1 } })
    await saveBook({ Titulo: bookTitle } as any)
    expect(api.post).toHaveBeenCalledWith('/api/books', { Titulo: bookTitle })
  })

  it('should call put endpoint when updating book', async () => {
    const bookTitle = faker.lorem.words(3)
      ; (api.put as jest.Mock).mockResolvedValue({ data: { id: 1 } })
    await saveBook({ Titulo: bookTitle } as any, 1)
    expect(api.put).toHaveBeenCalledWith('/api/books/1', { Titulo: bookTitle })
  })

  it('should request audit report download', async () => {
    (api.get as jest.Mock).mockResolvedValue({ data: new Blob() })
    await downloadAuditReport({})
    expect(api.get).toHaveBeenCalledWith('/api/reports/audits?', { responseType: 'blob' })
  })

  it('should request book report download', async () => {
    (api.get as jest.Mock).mockResolvedValue({ data: new Blob() })
    await downloadBookReport({})
    expect(api.get).toHaveBeenCalledWith('/api/reports/books?', { responseType: 'blob' })
  })
})
