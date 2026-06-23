export interface Meter {

  id: number

  name: string

  meter_code: string

  resource_type: string

  description: string | null

  is_active: boolean

  campus: {

    id: number

    name: string

  } | null
}