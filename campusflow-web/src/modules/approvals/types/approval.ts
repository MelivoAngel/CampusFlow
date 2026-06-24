export interface Approval {

  id: number

  current_reading: number

  consumption: number

  recorded_date: string

  is_approved: boolean

  was_corrected: boolean

  meter: {

    id: number

    name: string
  }

  technician: {

    id: number

    name: string
  }

  anomaly: {

    id: number

    type: string

    severity: string

    message: string

    is_resolved: boolean
  } | null
}