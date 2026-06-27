export interface Schedule {

  id: number

  organization: string

  event_name: string

  event_date: string

  start_time: string

  end_time: string

  description: string | null

  facility: {

    id: number

    name: string

    type: string

  } | null

  campus: {

    id: number

    name: string

  } | null
}