import api from '../../../services/api'

export const getCalendarDashboardRequest = () => {

  return api.get(

    '/v1/calendar-dashboard'
  )
}

export const getAdminDashboardRequest = () => {

  return api.get(

    '/v1/admin-dashboard'
  )
}