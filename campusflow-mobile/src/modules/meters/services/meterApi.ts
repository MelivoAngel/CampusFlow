import api from '../../../services/api'

export const getMetersRequest = () => {

  return api.get(

    '/v1/mobile/meters'
  )
}