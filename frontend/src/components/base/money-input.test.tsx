import { render, screen, fireEvent } from '@testing-library/react'
import { MoneyInput } from './money-input'

describe('MoneyInput', () => {
  it('should render correctly with label', () => {
    render(<MoneyInput label="Price" />)
    expect(screen.getByText('Price')).toBeInTheDocument()
    expect(screen.getByText('R$')).toBeInTheDocument()
  })

  it('should format initial value correctly', () => {
    render(<MoneyInput value={10.5} onChange={() => {}} />)
    const input = screen.getByRole('textbox')
    expect(input).toHaveValue('10,50')
  })
  
  it('should format initial string value correctly', () => {
    render(<MoneyInput value="15,25" onChange={() => {}} />)
    const input = screen.getByRole('textbox')
    expect(input).toHaveValue('15,25')
  })

  it('should handle empty value', () => {
    render(<MoneyInput value="" onChange={() => {}} />)
    const input = screen.getByRole('textbox')
    expect(input).toHaveValue('')
  })

  it('should call onChange with parsed value when user types', () => {
    const handleChange = jest.fn()
    render(<MoneyInput name="price" onChange={handleChange} />)
    const input = screen.getByRole('textbox')
    
    fireEvent.change(input, { target: { value: '1500' } })
    
    expect(handleChange).toHaveBeenCalled()
    const event = handleChange.mock.calls[0][0]
    expect(event.target.name).toBe('price')
    expect(event.target.value).toBe('15.00')
  })
})
