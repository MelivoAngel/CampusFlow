export interface Building {

  id: number

  name: string

  code: string

  description: string | null

  campus: {

    id: number

    name: string

  } | null

  meters: {

    id: number

    name: string

    meter_code: string

    resource_type: string

  }[]

  meters_count: number
}