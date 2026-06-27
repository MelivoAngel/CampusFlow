<script setup lang="ts">
import { ref } from 'vue'

import {
  deleteFacilityRequest
} from '../services/facilityApi'

import type {
  Facility
} from '../../../types/facility'

const props =
  defineProps<{

    show: boolean

    facility: Facility | null
}>()

const emit =
  defineEmits([

    'close',

    'deleted'
  ])

const loading =
  ref(false)

const error =
  ref('')

const handleDelete = async () => {

  if (
    !props.facility
  ) {
    return
  }

  loading.value =
    true

  error.value =
    ''

  try {

    await deleteFacilityRequest(

      props.facility.id
    )

    emit('deleted')

    emit('close')
  }

  catch (error: any) {

    error.value =

      error.response?.data?.message ||

      'Something went wrong'
  }

  loading.value =
    false
}
</script>

<template>

  <div
    v-if="show"
    class="fixed inset-0 bg-black/40 flex items-center justify-center"
  >

    <div
      class="bg-white rounded-xl p-6 w-[450px]"
    >

      <div class="mb-4">

        <h2 class="text-xl font-semibold">
          Delete Facility
        </h2>

      </div>

      <div class="mb-6">

        <p class="text-gray-700">

          Are you sure you want to delete

          <span class="font-semibold">
            {{ props.facility?.name }}
          </span>

          ?

        </p>

        <p
          class="text-sm text-red-500 mt-2"
        >
          This action cannot be undone.
        </p>

      </div>

      <p
        v-if="error"
        class="text-sm text-red-600 mb-4"
      >
        {{ error }}
      </p>

      <div
        class="flex justify-end gap-3"
      >

        <button
          @click="emit('close')"
          class="text-gray-600"
        >
          Cancel
        </button>

        <button
          @click="handleDelete"
          class="bg-red-600 text-white px-4 py-2 rounded-md"
        >
          {{

            loading

              ? 'Deleting...'

              : 'Delete Facility'
          }}
        </button>

      </div>

    </div>

  </div>

</template>