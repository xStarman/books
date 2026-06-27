import { render, screen, fireEvent } from '@testing-library/react'
import { Pagination } from './pagination'

describe('Pagination', () => {
  const mockOnPageChange = jest.fn()

  beforeEach(() => {
    jest.clearAllMocks()
  })

  it('should return null if totalPages <= 1', () => {
    const { container } = render(<Pagination currentPage={1} totalPages={1} onPageChange={mockOnPageChange} />)
    expect(container).toBeEmptyDOMElement()
  })

  it('should render correctly for small amount of pages', () => {
    render(<Pagination currentPage={1} totalPages={3} onPageChange={mockOnPageChange} />)
    expect(screen.getByText('1')).toBeInTheDocument()
    expect(screen.getByText('2')).toBeInTheDocument()
    expect(screen.getByText('3')).toBeInTheDocument()
  })

  it('should call onPageChange with correct page', () => {
    render(<Pagination currentPage={2} totalPages={5} onPageChange={mockOnPageChange} />)
    fireEvent.click(screen.getByText('3'))
    expect(mockOnPageChange).toHaveBeenCalledWith(3)
    fireEvent.click(screen.getByText('Próximo'))
    expect(mockOnPageChange).toHaveBeenCalledWith(3)
    fireEvent.click(screen.getByText('Anterior'))
    expect(mockOnPageChange).toHaveBeenCalledWith(1)
  })

  it('should disable Anterior on page 1', () => {
    render(<Pagination currentPage={1} totalPages={5} onPageChange={mockOnPageChange} />)
    expect(screen.getByText('Anterior')).toBeDisabled()
  })

  it('should disable Próximo on last page', () => {
    render(<Pagination currentPage={5} totalPages={5} onPageChange={mockOnPageChange} />)
    expect(screen.getByText('Próximo')).toBeDisabled()
  })

  it('should show left ellipsis', () => {
    render(<Pagination currentPage={7} totalPages={10} onPageChange={mockOnPageChange} />)
    const ellipses = screen.getAllByText('...')
    expect(ellipses.length).toBeGreaterThan(0)
  })
  
  it('should show right ellipsis', () => {
    render(<Pagination currentPage={2} totalPages={10} onPageChange={mockOnPageChange} />)
    const ellipses = screen.getAllByText('...')
    expect(ellipses.length).toBeGreaterThan(0)
  })
  
  it('should show both ellipses', () => {
    render(<Pagination currentPage={5} totalPages={10} onPageChange={mockOnPageChange} />)
    const ellipses = screen.getAllByText('...')
    expect(ellipses).toHaveLength(2)
  })
})
