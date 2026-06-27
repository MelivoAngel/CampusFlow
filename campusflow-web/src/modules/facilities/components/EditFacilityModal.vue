<script setup lang="ts">
import {
  ref,
  watch
} from 'vue'

import {
  updateFacilityRequest
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

    'updated'
  ])

const loading =
  ref(false)

const error =
  ref('')

const name =
  ref('')

const description =
  ref('')

const isActive =
  ref(true)

watch(

  () => props.facility,

  facility => {

    if (!facility) {
      return
    }

    name.value =
      facility.name

    description.value =
      facility.description || ''

    isActive.value =
      facility.is_active
  },

  {
    immediate: true
  }
)

const handleSubmit = async () => {

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

    await updateFacilityRequest(

      props.facility.id,

      {
        name:
          name.value,

        description:
          description.value,

        is_active:
          isActive.value
      }
    )

    emit('updated')

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
      class="bg-white rounded-xl p-6 w-[500px]"
    >

      <div class="mb-4">

        <h2 class="text-xl font-semibold">
          Edit Facility
        </h2>

      </div>

      <div class="space-y-4">

        <input
          v-model="name"
          placeholder="Facility Name"
          class="w-full border border-gray-300 rounded-md px-3 py-2"
        >

        <textarea
          v-model="description"
          placeholder="Description"
          class="w-full border border-gray-300 rounded-md px-3 py-2"
        />

        <label
          class="flex items-center gap-2"
        >

          <input
            v-model="isActive"
            type="checkbox"
          >

          Active

        </label>

      </div>

      <p
        v-if="error"
        class="text-sm text-red-600 mt-4"
      >
        {{ error }}
      </p>

      <div
        class="flex justify-end gap-3 mt-6"
      >

        <button
          @click="emit('close')"
          class="text-gray-600"
        >
          Cancel
        </button>

        <button
          @click="handleSubmit"
          class="bg-blue-600 text-white px-4 py-2 rounded-md"
        >
          {{

            loading

              ? 'Updating...'

              : 'Update Facility'
          }}
        </button>

      </div>

    </div>

  </div>

</template>