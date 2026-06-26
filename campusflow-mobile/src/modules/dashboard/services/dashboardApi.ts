import api from '../../../services/api'

export const getPendingReadingsRequest = () => {

  return api.get(

    '/v1/my-pending-readings'
  )
}