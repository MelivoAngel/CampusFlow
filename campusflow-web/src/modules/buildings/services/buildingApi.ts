import api from '../../../services/api'

export const getBuildingsRequest = () => {
  return api.get('/v1/buildings')
}

export const createBuildingRequest = (payload: any) => {
  return api.post('/v1/buildings',payload)
}

export const updateBuildingRequest = (id: number,payload: any) => {
  return api.patch(`/v1/buildings/${id}`,payload)
}

export const getAvailableMetersRequest = (id: number) => {
  return api.get(`/v1/buildings/${id}/available-meters`)
}

export const assignBuildingMetersRequest = (id: number,meterIds: number[]) => {
  return api.patch(`/v1/buildings/${id}/assign-meters`,{meter_ids: meterIds})
}

export const deleteBuildingRequest = (id: number,force = false) => {
  return api.delete(`/v1/buildings/${id}`,{params: {force}})
}