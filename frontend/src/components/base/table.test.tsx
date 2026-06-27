import { render, screen, fireEvent } from '@testing-library/react'
import { faker } from '@faker-js/faker'
import { Table, Column } from './table'

describe('Table Component', () => {
  const columns: Column<any>[] = [
    { key: 'id', label: 'ID', sortable: true },
    { key: 'name', label: 'Name', sortable: false },
    { key: 'action', label: 'Action', render: (row: any) => <button>{row.id}</button>, sticky: 'right' },
  ]
  const data = [
    { id: 1, name: faker.person.firstName() },
    { id: 2, name: faker.person.firstName() },
  ]

  it('should render columns and data', () => {
    render(<Table columns={columns} data={data} />)
    expect(screen.getByText('ID')).toBeInTheDocument()
    expect(screen.getByText('Name')).toBeInTheDocument()
    expect(screen.getByText(data[0].name)).toBeInTheDocument()
    expect(screen.getByText(data[1].name)).toBeInTheDocument()
  })

  it('should render custom render function', () => {
    render(<Table columns={columns} data={data} />)
    expect(screen.getByRole('button', { name: '1' })).toBeInTheDocument()
  })

  it('should handle sorting', () => {
    const handleSort = jest.fn()
    render(<Table columns={columns} data={data} onSort={handleSort} sortColumn="id" sortOrder="asc" />)
    
    fireEvent.click(screen.getByText('ID'))
    expect(handleSort).toHaveBeenCalledWith('id', 'desc')
    
    fireEvent.click(screen.getByText('Name'))
    expect(handleSort).toHaveBeenCalledTimes(1)
  })
  
  it('should handle sorting when changing column', () => {
    const handleSort = jest.fn()
    const cols = [...columns, { key: 'age', label: 'Age', sortable: true }]
    render(<Table columns={cols} data={data} onSort={handleSort} sortColumn="id" sortOrder="asc" />)
    
    fireEvent.click(screen.getByText('Age'))
    expect(handleSort).toHaveBeenCalledWith('age', 'asc')
  })

  it('should display loading state', () => {
    render(<Table columns={columns} data={[]} isLoading={true} />)
    expect(screen.getByText('Carregando...')).toBeInTheDocument()
  })

  it('should display empty state', () => {
    render(<Table columns={columns} data={[]} />)
    expect(screen.getByText('Nenhum registro encontrado.')).toBeInTheDocument()
  })
  
  it('should render pagination when provided', () => {
      const paginationProps = {
          currentPage: 1,
          totalPages: 5,
          onPageChange: jest.fn()
      }
      render(<Table columns={columns} data={data} pagination={paginationProps} />)
      expect(screen.getByText('Próximo')).toBeInTheDocument()
  })
})
