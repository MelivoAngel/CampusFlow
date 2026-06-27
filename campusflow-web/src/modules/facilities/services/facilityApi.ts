import api from '../../../services/api'

export const getFacilitiesRequest = () => {

  return api.get(
    '/v1/facilities'
  )
}

export const createFacilityRequest = (
  payload: any
) => {

  return api.post(

    '/v1/facilities',

    payload
  )
}

export const updateFacilityRequest = (
  id: number,
  payload: any
) => {

  return api.patch(

    `/v1/facilities/${id}`,

    payload
  )
}

export const deleteFacilityRequest = (
  id: number
) => {

  return api.delete(

    `/v1/facilities/${id}`
  )
}