import { api } from './api'
import { getBookByIsbn } from './get-book-by-isbn'
import { faker } from '@faker-js/faker'

jest.mock('./api', () => ({
  api: {
    get: jest.fn(),
  },
}))

const mockApiGet = api.get as jest.Mock

describe('getBookByIsbn', () => {
  beforeEach(() => {
    jest.clearAllMocks()
  })

  it('should call the isbn endpoint with the correct isbn', async () => {
    const mockData = {
      Titulo: faker.lorem.words(3),
      Editora: faker.company.name(),
      AnoPublicacao: '2020',
      autores: [faker.person.fullName()],
      assuntos: [faker.lorem.word()],
    };
    mockApiGet.mockResolvedValue({ data: mockData })

    const isbnCode = '978' + faker.string.numeric(10)
    const result = await getBookByIsbn(isbnCode)

    expect(mockApiGet).toHaveBeenCalledWith(`/api/isbn/${isbnCode}`)
    expect(result).toEqual(mockData)
  })

  it('should propagate errors from the api', async () => {
    const error = { response: { status: 404, data: { message: faker.lorem.sentence() } } };
    mockApiGet.mockRejectedValue(error)

    await expect(getBookByIsbn('0000000000')).rejects.toEqual(error)
  })
})
