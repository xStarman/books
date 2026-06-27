import { render, screen, fireEvent } from '@testing-library/react'
import { faker } from '@faker-js/faker'
import { ConfirmModal } from './confirm-modal'

describe('ConfirmModal', () => {
  const defaultProps = {
    isOpen: true,
    title: faker.lorem.words(2),
    message: faker.lorem.sentence(),
    onConfirm: jest.fn(),
    onCancel: jest.fn(),
  }

  beforeEach(() => {
    jest.clearAllMocks()
  })

  it('should render nothing when isOpen is false', () => {
    const { container } = render(<ConfirmModal {...defaultProps} isOpen={false} />)
    expect(container).toBeEmptyDOMElement()
  })

  it('should render the modal with title and message', () => {
    render(<ConfirmModal {...defaultProps} />)
    expect(screen.getByText(defaultProps.title)).toBeInTheDocument()
    expect(screen.getByText(defaultProps.message)).toBeInTheDocument()
  })

  it('should call onConfirm when confirm button is clicked', () => {
    render(<ConfirmModal {...defaultProps} />)
    fireEvent.click(screen.getByText('Excluir'))
    expect(defaultProps.onConfirm).toHaveBeenCalled()
  })

  it('should call onCancel when cancel button is clicked', () => {
    render(<ConfirmModal {...defaultProps} />)
    fireEvent.click(screen.getByText('Cancelar'))
    expect(defaultProps.onCancel).toHaveBeenCalled()
  })

  it('should call onCancel when close button is clicked', () => {
    render(<ConfirmModal {...defaultProps} />)
    fireEvent.click(screen.getByLabelText('Close'))
    expect(defaultProps.onCancel).toHaveBeenCalled()
  })

  it('should disable buttons and show confirming text when isConfirming is true', () => {
    render(<ConfirmModal {...defaultProps} isConfirming={true} />)
    expect(screen.getByText('Excluindo...')).toBeDisabled()
    expect(screen.getByText('Cancelar')).toBeDisabled()
    expect(screen.getByLabelText('Close')).toBeDisabled()
  })
})
