<script setup lang="ts">
import { ref,watch } from 'vue'
import { updateUserRequest } from '../services/userApi'

interface User {

  id: number

  name: string

  email: string
}

const props =
  defineProps<{

    show: boolean

    user: User | null
}>()

const emit =
  defineEmits([
    'close',
    'updated'
  ])

const name =
  ref('')

const email =
  ref('')

const password =
  ref('')

const errors =
  ref<any>({})

const firstError =
  ref('')

const successMessage =
  ref('')

watch(

  () => props.user,

  (user) => {

    if (!user) {
      return
    }

    name.value =
      user.name

    email.value =
      user.email

    password.value =
      ''
  },

  {
    immediate: true
  }
)

const handleUpdate = async () => {

  errors.value =
    {}

  firstError.value =
    ''

  successMessage.value =
    ''

  try {
    await updateUserRequest(

      props.user!.id,

      {
        name: name.value,

        email: email.value,

        password: password.value
      }
    )

    successMessage.value =
      'User updated successfully'

    emit(
      'updated'
    )

    setTimeout(() => {

      emit(
        'close'
      )

    },1000)
  }

  catch (error: any) {

    const backendErrors:
        Record<string,string[]> =

        error.response?.data?.errors

    if (backendErrors) {

      errors.value =
        backendErrors

      firstError.value =

        Object.values(
          backendErrors
        )[0][0] as string
    }

    else {

      firstError.value =

        error.response?.data?.message ||

        'Something went wrong'
    }
  }
}
</script>

<template>

  <div v-if="show" class="fixed inset-0 bg-black/30 flex items-center justify-center">

    <div class="bg-white p-6 rounded-xl w-[500px]">

      <div class="flex justify-between mb-6">

        <h2 class="text-xl font-semibold">
          Edit User
        </h2>

        <button @click="emit('close')">
          X
        </button>

      </div>

      <div class="space-y-4">

        <input
          v-model="name"
          placeholder="Name"
          :class="[
            'w-full px-3 py-2 rounded-md border',
            errors.name ? 'border-red-500 animate-shake' : 'border-gray-300'
          ]"
        >

        <input
          v-model="email"
          placeholder="Email"
          :class="[
            'w-full px-3 py-2 rounded-md border',
            errors.email ? 'border-red-500 animate-shake' : 'border-gray-300'
          ]"
        >

        <input
          v-model="password"
          type="password"
          placeholder="New Password (Optional)"
          :class="[
            'w-full px-3 py-2 rounded-md border',
            errors.password ? 'border-red-500 animate-shake' : 'border-gray-300'
          ]"
        >

        <p
          v-if="successMessage"
          class="text-green-600 text-sm"
        >
          {{ successMessage }}
        </p>

        <p
          v-if="firstError"
          class="text-red-600 text-sm"
        >
          {{ firstError }}
        </p>

        <button @click="handleUpdate" class="w-full bg-blue-600 text-white py-2 rounded-md">
          Save Changes
        </button>

      </div>

    </div>

  </div>

</template>

<style scoped>

@keyframes shake {

  0% {
    transform: translateX(0);
  }

  25% {
    transform: translateX(-4px);
  }

  50% {
    transform: translateX(4px);
  }

  75% {
    transform: translateX(-4px);
  }

  100% {
    transform: translateX(0);
  }
}

.animate-shake {

  animation: shake 0.3s;
}

</style>