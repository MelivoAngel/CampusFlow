import { ref,onMounted,onUnmounted } from 'vue'

export const useClock = () => {

  const currentTime =
    ref('')

  let interval:
    number

  const updateClock = () => {

    const now =
      new Date()

    const weekday =
      now.toLocaleDateString(

        'en-US',

        {
          weekday: 'long'
        }
      )

    const date =
      now.toLocaleDateString(

        'en-US',

        {
          month: 'short',

          day: 'numeric',

          year: 'numeric'
        }
      )

    const time =
      now.toLocaleTimeString(

        'en-US',

        {
          hour12: false
        }
      )

    currentTime.value =

      `${weekday}, ${date} ${time}`
  }

  onMounted(() => {

    updateClock()

    interval =

      window.setInterval(

        updateClock,

        1000
      )
  })

  onUnmounted(() => {

    clearInterval(
      interval
    )
  })

  return {

    currentTime
  }
}