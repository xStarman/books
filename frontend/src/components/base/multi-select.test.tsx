import { render, screen, fireEvent } from '@testing-library/react'
import { MultiSelect } from './multi-select'
import { faker } from '@faker-js/faker'

describe('MultiSelect', () => {
  const defaultOptions = [
    { label: 'Option 1', value: '1' },
    { label: 'Option 2', value: 2 },
  ]
  const mockOnChange = jest.fn()

  beforeEach(() => {
    jest.clearAllMocks()
  })

  it('should render label and placeholder', () => {
    const label = faker.lorem.words(2)
    const placeholder = faker.lorem.words(2)
    render(
      <MultiSelect
        label={label}
        options={defaultOptions}
        value={[]}
        onChange={mockOnChange}
        placeholder={placeholder}
      />
    )
    expect(screen.getByText(label)).toBeInTheDocument()
    expect(screen.getByText(placeholder)).toBeInTheDocument()
  })

  it('should show no items selected message when empty', () => {
    render(<MultiSelect options={defaultOptions} value={[]} onChange={mockOnChange} />)
    expect(screen.getByText('Nenhum item selecionado')).toBeInTheDocument()
  })

  it('should render selected items and remove from select list', () => {
    render(<MultiSelect options={defaultOptions} value={['1']} onChange={mockOnChange} />)
    expect(screen.getByText('Option 1')).toBeInTheDocument()
    
    const select = screen.getByRole('combobox')
    expect(select.querySelector('option[value="1"]')).not.toBeInTheDocument()
    expect(select.querySelector('option[value="2"]')).toBeInTheDocument()
  })

  it('should add an item when selected and button is clicked', () => {
    render(<MultiSelect options={defaultOptions} value={[]} onChange={mockOnChange} />)
    const select = screen.getByRole('combobox')
    const btns = screen.getAllByRole('button')
    const addBtn = btns.find(b => b.querySelector('.bi-plus'))
    
    fireEvent.change(select, { target: { value: '2' } })
    fireEvent.click(addBtn!)
    
    expect(mockOnChange).toHaveBeenCalledWith([2])
  })

  it('should remove an item when the remove button is clicked', () => {
    render(<MultiSelect options={defaultOptions} value={['1', 2]} onChange={mockOnChange} />)
    const removeBtns = screen.getAllByRole('button').filter(b => b.querySelector('.bi-dash-square'))
    fireEvent.click(removeBtns[0])
    expect(mockOnChange).toHaveBeenCalledWith([2])
  })

  it('should render dynamic "novo:" items with badge', () => {
    const newName = faker.person.fullName()
    render(
      <MultiSelect
        options={defaultOptions}
        value={[`novo:${newName}`]}
        onChange={mockOnChange}
      />
    )
    expect(screen.getByText(newName)).toBeInTheDocument()
    expect(screen.getByText('novo')).toBeInTheDocument()
    expect(screen.getByText('novo')).toHaveClass('badge', 'bg-success')
  })

  it('should render mixed existing and dynamic items', () => {
    const newWord = faker.lorem.word()
    render(
      <MultiSelect
        options={defaultOptions}
        value={['1', `novo:${newWord}`]}
        onChange={mockOnChange}
      />
    )
    expect(screen.getByText('Option 1')).toBeInTheDocument()
    expect(screen.getByText(newWord)).toBeInTheDocument()
    expect(screen.getByText('novo')).toBeInTheDocument()
  })

  it('should be able to remove dynamic items', () => {
    const newEntity = faker.company.name()
    render(
      <MultiSelect
        options={defaultOptions}
        value={[`novo:${newEntity}`]}
        onChange={mockOnChange}
      />
    )
    const removeBtns = screen.getAllByRole('button').filter(b => b.querySelector('.bi-dash-square'))
    fireEvent.click(removeBtns[0])
    expect(mockOnChange).toHaveBeenCalledWith([])
  })
})
