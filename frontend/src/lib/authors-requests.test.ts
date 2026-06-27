import { api } from './api'
import { faker } from '@faker-js/faker'
import { deleteAuthor } from './delete-author'
import { getAllAuthors } from './get-all-authors'
import { getAuthorById } from './get-author-by-id'
import { getAuthorList } from './get-author-list'
import { saveAuthor } from './save-author'

jest.mock('./api', () => ({
  api: {
    get: jest.fn(),
    post: jest.fn(),
    put: jest.fn(),
    delete: jest.fn(),
  },
  objectToUri: jest.requireActual('./api').objectToUri
}))

describe('Author Requests', () => {
  beforeEach(() => {
    jest.clearAllMocks()
  })

  it('should call delete endpoint for deleteAuthor', async () => {
    (api.delete as jest.Mock).mockResolvedValue({ data: {} })
    await deleteAuthor(1)
    expect(api.delete).toHaveBeenCalledWith('/api/authors/1')
  })

  it('should call get all endpoint for getAllAuthors', async () => {
    (api.get as jest.Mock).mockResolvedValue({ data: [] })
    await getAllAuthors()
    expect(api.get).toHaveBeenCalledWith('/api/authors/all')
  })

  it('should call get by id endpoint for getAuthorById', async () => {
    (api.get as jest.Mock).mockResolvedValue({ data: { id: 1 } })
    await getAuthorById(1)
    expect(api.get).toHaveBeenCalledWith('/api/authors/1')
  })

  it('should call list endpoint for getAuthorList with correct params', async () => {
    const filterName = faker.person.lastName();
    (api.get as jest.Mock).mockResolvedValue({ data: { data: [] } })
    await getAuthorList({ page: 1, page_size: 10, filters: { Nome: filterName } })
    expect(api.get).toHaveBeenCalledWith(`/api/authors?page=1&page_size=10&filters[Nome]=${filterName}`)
  })

  it('should call post endpoint when saving new author', async () => {
    const authorName = faker.person.fullName();
    (api.post as jest.Mock).mockResolvedValue({ data: { id: 1 } })
    await saveAuthor({ Nome: authorName })
    expect(api.post).toHaveBeenCalledWith('/api/authors', { Nome: authorName })
  })

  it('should call put endpoint when updating author', async () => {
    const authorName = faker.person.fullName();
    (api.put as jest.Mock).mockResolvedValue({ data: { id: 1 } })
    await saveAuthor({ Nome: authorName }, 1)
    expect(api.put).toHaveBeenCalledWith('/api/authors/1', { Nome: authorName })
  })
})
