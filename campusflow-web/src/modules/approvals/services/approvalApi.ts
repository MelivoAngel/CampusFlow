import api from '../../../services/api'

export const getApprovalsRequest = () => {
  return api.get(
    '/v1/meter-readings'
  )
}

export const approveRequest = (
  id: number
) => {
  return api.patch(
    `/v1/meter-readings/${id}/approve`
  )
}

export const correctRequest = (
  id: number,
  currentReading: number
) => {
  return api.patch(
    `/v1/meter-readings/${id}/correct`,
    { current_reading: currentReading }
  )
}