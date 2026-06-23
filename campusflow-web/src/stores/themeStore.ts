import { defineStore } from 'pinia'

export const useThemeStore =
  defineStore(

    'theme',

    {

      state: () => ({

        darkMode:

          localStorage.getItem(
            'theme'
          ) === 'dark'
      }),

      actions: {

        toggleTheme() {

          this.darkMode =
            !this.darkMode

          localStorage.setItem(

            'theme',

            this.darkMode

              ? 'dark'

              : 'light'
          )
        }
      }
    }
  )