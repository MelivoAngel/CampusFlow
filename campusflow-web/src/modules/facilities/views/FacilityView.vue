<script setup lang="ts">
import { ref,onMounted,computed } from 'vue'
import { useAuthStore } from '../../../stores/authStore'

import AppLayout from '../../../shared/layouts/AppLayout.vue'
import FacilityTable from '../components/FacilityTable.vue'
import CreateFacilityModal from '../components/CreateFacilityModal.vue'
import EditFacilityModal from '../components/EditFacilityModal.vue'
import DeleteFacilityModal from '../components/DeleteFacilityModal.vue'

import {
  getFacilitiesRequest
} from '../services/facilityApi'

import {
  getCampusesRequest
} from '../../users/services/userApi'

import type {
  Facility
} from '../../../types/facility'

import type {
  Campus
} from '../../../types/campus'

const authStore =
  useAuthStore()

const facilities =
  ref<Facility[]>([])

const campuses =
  ref<Campus[]>([])

const loading =
  ref(false)

const search =
  ref('')

const campusFilter =
  ref('')

const showCreateModal =
  ref(false)

const showEditModal =
  ref(false)

const showDeleteModal =
  ref(false)

const selectedFacility =
  ref<Facility | null>(null)

const isSuperAdmin = computed(() => {

  return (
    authStore.user?.role ===
    'super_admin'
  )
})

const filteredFacilities = computed(() => {

  return facilities.value.filter(
    facility => {

      const matchesSearch =

        facility.name
          .toLowerCase()
          .includes(
            search.value
              .toLowerCase()
          )

      const matchesCampus =

        !campusFilter.value ||

        facility.campus?.id ===
        Number(
          campusFilter.value
        )

      return (

        matchesSearch &&

        matchesCampus
      )
    }
  )
})

const fetchFacilities = async () => {

  loading.value =
    true

  try {

    const response =
      await getFacilitiesRequest()

    facilities.value =
      response.data.data
  }

  catch (error) {
    console.log(error)
  }

  loading.value =
    false
}

const fetchCampuses = async () => {

  if (
    !isSuperAdmin.value
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

const handleEdit = (
  facility: Facility
) => {

  selectedFacility.value =
    facility

  showEditModal.value =
    true
}

const handleDelete = (
  facility: Facility
) => {

  selectedFacility.value =
    facility

  showDeleteModal.value =
    true
}

onMounted(() => {

  fetchFacilities()

  fetchCampuses()
})
</script>

<template>

  <AppLayout>

    <div class="bg-white p-6 rounded-xl shadow">

      <div class="flex justify-between mb-6">

        <div>

          <h1 class="text-2xl font-semibold">
            Facilities
          </h1>

          <p class="text-sm text-gray-500">
            {{ filteredFacilities.length }}
            registered facilities
          </p>

        </div>

        <button
          @click="showCreateModal = true"
          class="bg-emerald-700 text-white px-4 py-2 rounded-md"
        >
          Add Facility
        </button>

      </div>

      <div class="flex gap-3 mb-6">

        <input
          v-model="search"
          placeholder="Search facility..."
          class="border border-gray-300 rounded-md px-3 py-2 flex-1"
        >

        <select
          v-if="isSuperAdmin"
          v-model="campusFilter"
          class="border border-gray-300 rounded-md px-3 py-2"
        >

          <option value="">
            All Campuses
          </option>

          <option
            v-for="campus in campuses"
            :key="campus.id"
            :value="campus.id"
          >
            {{ campus.name }}
          </option>

        </select>

      </div>

      <p
        v-if="loading"
        class="text-gray-500"
      >
        Loading facilities...
      </p>

      <p
        v-else-if="!filteredFacilities.length"
        class="text-gray-500"
      >
        No facilities found
      </p>

      <FacilityTable
        v-else
        :facilities="filteredFacilities"
        @edit="handleEdit"
        @delete="handleDelete"
      />

      <CreateFacilityModal
        :show="showCreateModal"
        @close="showCreateModal = false"
        @created="fetchFacilities"
      />

      <EditFacilityModal
        :show="showEditModal"
        :facility="selectedFacility"
        @close="showEditModal = false"
        @updated="fetchFacilities"
      />

      <DeleteFacilityModal
        :show="showDeleteModal"
        :facility="selectedFacility"
        @close="showDeleteModal = false"
        @deleted="fetchFacilities"
      />

    </div>

  </AppLayout>

</template>