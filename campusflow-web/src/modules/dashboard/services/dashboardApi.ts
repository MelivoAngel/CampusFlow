import api from '../../../services/api'

export const getCalendarDashboardRequest = () => {

  return api.get(

    '/v1/calendar-dashboard'
  )
}