import { objectToUri } from './api'
import { faker } from '@faker-js/faker'

describe('api - objectToUri', () => {
  it('should convert object to query string', () => {
    const name = faker.person.firstName()
    const result = objectToUri({ name, age: 30 })
    expect(result).toBe(`name=${name}&age=30`)
  })

  it('should handle undefined values correctly', () => {
    const name = faker.person.firstName()
    const result = objectToUri({ name, age: undefined })
    expect(result).toBe(`name=${name}`)
  })
})
