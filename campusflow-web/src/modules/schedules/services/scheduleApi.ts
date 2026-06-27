import api from '../../../services/api'

export const getSchedulesRequest = () => {

  return api.get(
    '/v1/schedules'
  )
}

export const createScheduleRequest = (
  payload: any
) => {

  return api.post(

    '/v1/schedules',

    payload
  )
}

export const updateScheduleRequest = (
  id: number,
  payload: any
) => {

  return api.patch(

    `/v1/schedules/${id}`,

    payload
  )
}

export const deleteScheduleRequest = (
  id: number
) => {

  return api.delete(

    `/v1/schedules/${id}`
  )
}