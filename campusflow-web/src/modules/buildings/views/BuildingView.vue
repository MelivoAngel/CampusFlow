<script setup lang="ts">
import { ref,onMounted,computed } from 'vue'
import { useAuthStore } from '../../../stores/authStore'

import AppLayout from '../../../shared/layouts/AppLayout.vue'
import BuildingTable from '../components/BuildingTable.vue'
import CreateBuildingModal from '../components/CreateBuildingModal.vue'
import EditBuildingModal from '../components/EditBuildingModal.vue'
import AssignBuildingMeterModal from '../components/AssignBuildingMeterModal.vue'
import DeleteBuildingModal from '../components/DeleteBuildingModal.vue'

import {
  getBuildingsRequest
} from '../services/buildingApi'

import {
  getCampusesRequest
} from '../../users/services/userApi'

import type { Building } from '../../../types/building'
import type { Campus } from '../../../types/campus'

const authStore =
  useAuthStore()

const buildings =
  ref<Building[]>([])

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

const showAssignModal =
  ref(false)

const showDeleteModal =
  ref(false)

const selectedBuilding =
  ref<Building | null>(null)

const isSuperAdmin = computed(() => {

  return (
    authStore.user?.role ===
    'super_admin'
  )
})

const filteredBuildings = computed(() => {

  return buildings.value.filter(
    building => {

      const matchesSearch =

        building.name
          .toLowerCase()
          .includes(
            search.value
              .toLowerCase()
          ) ||

        building.code
          .toLowerCase()
          .includes(
            search.value
              .toLowerCase()
          )

      const matchesCampus =

        !campusFilter.value ||

        building.campus?.id ===
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

const fetchBuildings = async () => {

  loading.value =
    true

  try {

    const response =
      await getBuildingsRequest()

    buildings.value =
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
  building: Building
) => {

  selectedBuilding.value =
    building

  showEditModal.value =
    true
}

const handleAssign = (
  building: Building
) => {

  selectedBuilding.value =
    building

  showAssignModal.value =
    true
}

const handleDelete = (
  building: Building
) => {

  selectedBuilding.value =
    building

  showDeleteModal.value =
    true
}

onMounted(() => {

  fetchBuildings()

  fetchCampuses()
})
</script>

<template>

  <AppLayout>

    <div class="bg-white p-6 rounded-xl shadow">

      <div class="flex justify-between mb-6">

        <div>

          <h1 class="text-2xl font-semibold">
            Buildings
          </h1>

          <p class="text-sm text-gray-500">
            {{ filteredBuildings.length }}
            registered buildings
          </p>

        </div>

        <button
          @click="showCreateModal = true"
          class="bg-emerald-700 text-white px-4 py-2 rounded-md"
        >
          Add Building
        </button>

      </div>

      <div class="flex gap-3 mb-6">

        <input
          v-model="search"
          placeholder="Search building..."
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
        Loading buildings...
      </p>

      <p
        v-else-if="!filteredBuildings.length"
        class="text-gray-500"
      >
        No buildings found
      </p>

      <BuildingTable
        v-else
        :buildings="filteredBuildings"
        @edit="handleEdit"
        @assign="handleAssign"
        @delete="handleDelete"
      />

      <CreateBuildingModal
        :show="showCreateModal"
        @close="showCreateModal = false"
        @created="fetchBuildings"
      />

      <EditBuildingModal
        :show="showEditModal"
        :building="selectedBuilding"
        @close="showEditModal = false"
        @updated="fetchBuildings"
      />

      <AssignBuildingMeterModal
        :show="showAssignModal"
        :building="selectedBuilding"
        @close="showAssignModal = false"
        @updated="fetchBuildings"
      />

      <DeleteBuildingModal
        :show="showDeleteModal"
        :building="selectedBuilding"
        @close="showDeleteModal = false"
        @deleted="fetchBuildings"
      />

    </div>

  </AppLayout>

</template>