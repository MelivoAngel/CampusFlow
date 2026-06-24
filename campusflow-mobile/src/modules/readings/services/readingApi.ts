import api from '../../../services/api'

export const submitReadingRequest = (
  formData: FormData
) => {

  return api.post(

    '/v1/meter-readings',

    formData,

    {
      headers: {
        'Content-Type':
          'multipart/form-data'
      }
    }
  )
}