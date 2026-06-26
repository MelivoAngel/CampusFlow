export interface CreateUserPayload {

  name: string
  email: string
  password: string
  role: string
  campus_id?: string
}

export interface UpdateUserPayload {

  name: string
  email: string
  password?: string
}

export interface CreateMeterPayload {
  name: string
  resource_type: string
  description?: string
  campus_id?: string
}

export interface UpdateMeterPayload {

  name: string
  description?: string
  is_active: boolean
}