import { render, screen, act } from '@testing-library/react'
import { faker } from '@faker-js/faker'
import userEvent from '@testing-library/user-event'
import { AuthorListFilters } from './author-list-filters'

describe('AuthorListFilters', () => {
  beforeEach(() => {
    jest.useFakeTimers()
  })

  afterEach(() => {
    jest.useRealTimers()
  })

  it('should render input field', () => {
    const onFilterChange = jest.fn()
    render(<AuthorListFilters onFilterChange={onFilterChange} />)
    expect(screen.getByLabelText('Nome')).toBeInTheDocument()
  })

  it('should call onFilterChange after typing with debounce', async () => {
    const onFilterChange = jest.fn()
    const user = userEvent.setup({ advanceTimers: jest.advanceTimersByTime })
    render(<AuthorListFilters onFilterChange={onFilterChange} />)
    
    const input = screen.getByLabelText('Nome')
    const authorName = faker.person.lastName()
    await user.type(input, authorName)
    
    act(() => {
      jest.advanceTimersByTime(300)
    })
    
    expect(onFilterChange).toHaveBeenCalledWith(expect.objectContaining({ Nome: authorName }))
  })
})
