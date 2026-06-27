export interface Facility {

  id: number

  name: string

  description: string | null

  is_active: boolean

  campus: {

    id: number

    name: string

  } | null
}