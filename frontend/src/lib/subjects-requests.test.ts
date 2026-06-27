import { api } from './api'
import { faker } from '@faker-js/faker'
import { deleteSubject } from './delete-subject'
import { getAllSubjects } from './get-all-subjects'
import { getSubjectById } from './get-subject-by-id'
import { getSubjectList } from './get-subject-list'
import { saveSubject } from './save-subject'

jest.mock('./api', () => ({
  api: {
    get: jest.fn(),
    post: jest.fn(),
    put: jest.fn(),
    delete: jest.fn(),
  },
  objectToUri: jest.requireActual('./api').objectToUri
}))

describe('Subject Requests', () => {
  beforeEach(() => {
    jest.clearAllMocks()
  })

  it('should call delete endpoint for deleteSubject', async () => {
    (api.delete as jest.Mock).mockResolvedValue({ data: {} })
    await deleteSubject(1)
    expect(api.delete).toHaveBeenCalledWith('/api/subjects/1')
  })

  it('should call get all endpoint for getAllSubjects', async () => {
    (api.get as jest.Mock).mockResolvedValue({ data: [] })
    await getAllSubjects()
    expect(api.get).toHaveBeenCalledWith('/api/subjects/all')
  })

  it('should call get by id endpoint for getSubjectById', async () => {
    (api.get as jest.Mock).mockResolvedValue({ data: { id: 1 } })
    await getSubjectById(1)
    expect(api.get).toHaveBeenCalledWith('/api/subjects/1')
  })

  it('should call list endpoint for getSubjectList with correct params', async () => {
    (api.get as jest.Mock).mockResolvedValue({ data: { data: [] } })
    await getSubjectList({ page: 1, page_size: 10 })
    expect(api.get).toHaveBeenCalledWith('/api/subjects?page=1&page_size=10')
  })

  it('should call post endpoint when saving new subject', async () => {
    const subjectDesc = faker.lorem.words(2);
    (api.post as jest.Mock).mockResolvedValue({ data: { id: 1 } })
    await saveSubject({ Descricao: subjectDesc })
    expect(api.post).toHaveBeenCalledWith('/api/subjects', { Descricao: subjectDesc })
  })

  it('should call put endpoint when updating subject', async () => {
    const subjectDesc = faker.lorem.words(2);
    (api.put as jest.Mock).mockResolvedValue({ data: { id: 1 } })
    await saveSubject({ Descricao: subjectDesc }, 1)
    expect(api.put).toHaveBeenCalledWith('/api/subjects/1', { Descricao: subjectDesc })
  })
})
