<script setup lang="ts">
import { ref,computed,onMounted } from 'vue'
import { useAuthStore } from '../../../stores/authStore'
import { createUserRequest,getCampusesRequest } from '../services/userApi'

interface Campus {

  id: number

  name: string
}

defineProps({

  show: Boolean
})

const emit =
  defineEmits([
    'close',
    'created'
  ])

const authStore =
  useAuthStore()

const campuses =
  ref<Campus[]>([])

const name =
  ref('')

const email =
  ref('')

const password =
  ref('')

const role =
  ref('')

const campusId =
  ref('')

const errors =
  ref<any>({})

const firstError =
  ref('')

const successMessage =
  ref('')

const roles = computed(() => {

  if (
    authStore.user?.role ===
    'super_admin'
  ) {
    return [
      'campus_admin',
      'staff',
      'field_technician',
      'calendar_admin'
    ]
  }

  if (
    authStore.user?.role ===
    'campus_admin'
  ) {
    return [
      'staff',
      'field_technician',
      'calendar_admin'
    ]
  }

  return [
    'field_technician',
    'calendar_admin'
  ]
})

const fetchCampuses = async () => {

  if (
    authStore.user?.role !==
    'super_admin'
  ) {
    return
  }

  try {
    const response =
      await getCampusesRequest()

    campuses.value =
      response.data.data
  }

  catch (error) {
    console.log(error)
  }
}

const handleCreate = async () => {

  errors.value =
    {}

  firstError.value =
    ''

  successMessage.value =
    ''

  try {
    const payload: any = {

      name: name.value,

      email: email.value,

      password: password.value,

      role: role.value
    }

    if (
      authStore.user?.role ===
      'super_admin'
    ) {
      payload.campus_id =
        campusId.value
    }

    await createUserRequest(
      payload
    )

    successMessage.value =
      'User created successfully'

    emit(
      'created'
    )

    setTimeout(() => {

      emit(
        'close'
      )

    },1000)

    name.value =
      ''

    email.value =
      ''

    password.value =
      ''

    role.value =
      ''

    campusId.value =
      ''
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

onMounted(() => {
  fetchCampuses()
})
</script>

<template>

  <div v-if="show" class="fixed inset-0 bg-black/30 flex items-center justify-center">

    <div class="bg-white p-6 rounded-xl w-[500px]">

      <div class="flex justify-between mb-6">

        <h2 class="text-xl font-semibold">
          Create User
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
          placeholder="Password"
          :class="[
            'w-full px-3 py-2 rounded-md border',
            errors.password ? 'border-red-500 animate-shake' : 'border-gray-300'
          ]"
        >

        <select
          v-model="role"
          :class="[
            'w-full px-3 py-2 rounded-md border',
            errors.role ? 'border-red-500 animate-shake' : 'border-gray-300'
          ]"
        >

          <option value="">
            Select Role
          </option>

          <option
            v-for="item in roles"
            :key="item"
            :value="item"
          >
            {{ item }}
          </option>

        </select>

        <select
          v-if="authStore.user?.role === 'super_admin'"
          v-model="campusId"
          :class="[
            'w-full px-3 py-2 rounded-md border',
            errors.campus_id ? 'border-red-500 animate-shake' : 'border-gray-300'
          ]"
        >

          <option value="">
            Select Campus
          </option>

          <option
            v-for="campus in campuses"
            :key="campus.id"
            :value="campus.id"
          >
            {{ campus.name }}
          </option>

        </select>

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

        <button @click="handleCreate" class="w-full bg-emerald-700 text-white py-2 rounded-md">
          Create User
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