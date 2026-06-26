import api from '../../../services/api'

import type {
  CreateUserPayload,
  UpdateUserPayload
} from '../../../types/requests'

export const getUsersRequest = () => {
  return api.get('/v1/users')
}

export const getCampusesRequest = () => {
  return api.get('/v1/campuses')
}

export const createUserRequest = (
  data: CreateUserPayload
) => {
  return api.post('/v1/users',data)
}

export const updateUserRequest = (
  id: number,
  data: UpdateUserPayload
) => {
  return api.patch(`/v1/users/${id}`,data)
}

export const deleteUserRequest = (
  id: number
) => {

  return api.delete(
    `/v1/users/${id}`
  )
}