import api from '../../../services/api'

import type {
  CreateMeterPayload,
  UpdateMeterPayload
} from '../../../types/requests'

export const getMetersRequest = () => {
  return api.get('/v1/meters')
}

export const createMeterRequest = (
  data: CreateMeterPayload
) => {
  return api.post('/v1/meters',data)
}

export const updateMeterRequest = (
  id: number,
  data: UpdateMeterPayload
) => {
  return api.patch(`/v1/meters/${id}`,data)
}