export interface User {

  id: number

  name: string

  email: string

  role: string

  campus: {

    id: number

    name: string

  } | null
}