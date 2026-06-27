import { render, screen } from '@testing-library/react'
import { Input } from './input'
import React from 'react'

describe('Input Component', () => {
  it('should render correctly with default props', () => {
    render(<Input placeholder="Test input" />)
    expect(screen.getByPlaceholderText('Test input')).toBeInTheDocument()
  })

  it('should render a label if provided', () => {
    render(<Input label="My Label" />)
    expect(screen.getByText('My Label')).toBeInTheDocument()
  })

  it('should link label and input with id', () => {
    render(<Input label="My Label" id="custom-id" />)
    expect(screen.getByLabelText('My Label')).toHaveAttribute('id', 'custom-id')
  })

  it('should render error message and add is-invalid class when error is provided', () => {
    render(<Input error="This is an error" placeholder="Test input" />)
    expect(screen.getByText('This is an error')).toBeInTheDocument()
    expect(screen.getByPlaceholderText('Test input')).toHaveClass('is-invalid')
  })

  it('should pass other HTML input attributes', () => {
    render(<Input type="password" disabled data-testid="pass-input" />)
    const input = screen.getByTestId('pass-input')
    expect(input).toHaveAttribute('type', 'password')
    expect(input).toBeDisabled()
  })

  it('should forward ref to the input element', () => {
    const ref = React.createRef<HTMLInputElement>()
    render(<Input ref={ref} />)
    expect(ref.current).toBeInstanceOf(HTMLInputElement)
  })
})
