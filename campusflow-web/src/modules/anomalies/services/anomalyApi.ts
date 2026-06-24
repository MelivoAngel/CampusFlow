import api from '../../../services/api'

export const getAnomaliesRequest = () => {
  return api.get(
    '/v1/meter-anomalies'
  )
}

export const resolveAnomalyRequest = (
  id: number,
  note: string
) => {
  return api.patch(
    `/v1/meter-anomalies/${id}/resolve`,
    { resolution_note: note }
  )
}