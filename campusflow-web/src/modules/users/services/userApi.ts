import api from '../../../services/api'

export const getUsersRequest = () => {
  return api.get(
    '/v1/users'
  )
}

export const createUserRequest = (
  data: object
) => {
  return api.post(
    '/v1/users',
    data
  )
}

export const getCampusesRequest = () => {
  return api.get(
    '/v1/campuses'
  )
}

export const updateUserRequest = (
  id: number,
  data: object
) => {
  return api.patch(
    `/v1/users/${id}`,
    data
  )
}