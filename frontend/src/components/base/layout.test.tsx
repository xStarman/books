import { render, screen } from '@testing-library/react'
import { Layout } from './layout'
import React from 'react'

jest.mock('./nav', () => ({
  Nav: () => <nav data-testid="mock-nav">Mock Nav</nav>,
}))

describe('Layout Component', () => {
  it('should render the Nav and children', () => {
    render(
      <Layout>
        <div data-testid="child-content">Child Content</div>
      </Layout>
    )

    expect(screen.getByTestId('mock-nav')).toBeInTheDocument()
    expect(screen.getByTestId('child-content')).toBeInTheDocument()
  })
})
