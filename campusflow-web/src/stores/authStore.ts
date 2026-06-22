import { defineStore } from 'pinia'

interface User {
  id: number
  name: string
  email: string
  role: string
  campus_id: number | null
}

export const useAuthStore = defineStore(

  'auth',

  {

    state: () => ({

      token: localStorage.getItem('token') || null,

      user: null as User | null
    }),

    actions: {

      setAuth(
        token: string,
        user: User
      ) {
        this.token = token

        this.user = user

        localStorage.setItem(
          'token',
          token
        )
      },

      logout() {
        this.token = null

        this.user = null

        localStorage.removeItem(
          'token'
        )
      }
    }
  }
)