import api from '../../../services/api'

export const loginRequest = (

  email: string,

  password: string

) => {

  return api.post(

    '/v1/auth/login',

    {

      email,

      password
    }
  )
}