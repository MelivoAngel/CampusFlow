export interface Anomaly {
  id: number
  type: string
  severity: string
  message: string
  is_resolved: boolean

  meter: {
    id: number
    name: string
  }

  reading: {
    id: number
    current_reading: number
    consumption: number
  }
}