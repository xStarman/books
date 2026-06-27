import { render, screen, act } from '@testing-library/react'
import { faker } from '@faker-js/faker'
import userEvent from '@testing-library/user-event'
import { SubjectListFilters } from './subject-list-filters'

describe('SubjectListFilters', () => {
  beforeEach(() => {
    jest.useFakeTimers()
  })

  afterEach(() => {
    jest.useRealTimers()
  })

  it('should render input field', () => {
    const onFilterChange = jest.fn()
    render(<SubjectListFilters onFilterChange={onFilterChange} />)
    expect(screen.getByLabelText('Descrição')).toBeInTheDocument()
  })

  it('should call onFilterChange after typing with debounce', async () => {
    const onFilterChange = jest.fn()
    const user = userEvent.setup({ advanceTimers: jest.advanceTimersByTime })
    render(<SubjectListFilters onFilterChange={onFilterChange} />)

    const input = screen.getByLabelText('Descrição')
    const subjectDesc = faker.lorem.word()
    await user.type(input, subjectDesc)

    act(() => {
      jest.advanceTimersByTime(300)
    })

    expect(onFilterChange).toHaveBeenCalledWith(expect.objectContaining({ Descricao: subjectDesc }))
  })
})
