<script setup>
import {onMounted, ref, watchEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useCrewRequisition from "../../../composables/crew/useCrewRequisition";
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import Store from "../../../store";
const icons = useHeroIcon();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { crewRequisitions, getCrewRequisitions, deleteCrewRequisition, isLoading } = useCrewRequisition();
const { setTitle } = Title();
setTitle('Crew Requisition');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);


function confirmDelete(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You want to change delete this item!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then((result) => {
    if (result.isConfirmed) {
      deleteCrewRequisition(id);
    }
  })
}

onMounted(() => {
  watchEffect(() => {
  getCrewRequisitions(props.page,businessUnit.value)
    .then(() => {
      const customDataTable = document.getElementById("customDataTable");

      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
    })
    .catch((error) => {
      console.error("Error fetching ranks:", error);
    });
});

});

</script>

<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Crew Requisition List</h2>
    <default-button :title="'Create Item'" :to="{ name: 'crw.crewRequisitions.create' }" :icon="icons.AddIcon"></default-button>
  </div>
  <div class="flex items-center justify-between mb-2 select-none">
    <!-- Search -->
    <filter-with-business-unit v-model="businessUnit"></filter-with-business-unit>
    <div class="relative w-full">
      <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-0 w-5 h-5 mr-2 text-gray-500 bottom-2" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
      </svg>
      <input type="text" placeholder="Search..." class="search" />
    </div>
  </div>

  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      
      <table class="w-full whitespace-no-wrap" >
          <thead v-once>
          <tr class="w-full">
            <th>#</th>
            <th>Requisition Date</th>
            <th>Vessel</th>
            <th>Required Qty.</th>
            <th>Business Unit</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(requiredCrew,index) in crewRequisitions?.data" :key="index">
            <td>{{ index + 1 }}</td>
            <td>{{ requiredCrew?.applied_date }}</td>
            <td>{{ requiredCrew?.opsVessel?.name }}</td>
            <td>{{ requiredCrew?.total_required_manpower }}</td>
            <td>
              <span :class="requiredCrew?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ requiredCrew?.business_unit }}</span>
            </td>
            <td>Waiting</td>
            <td>
              <action-button :action="'edit'" :to="{ name: 'crw.crewRequisitions.edit', params: { crewRequisitionId: requiredCrew?.id } }"></action-button>
              <action-button @click="confirmDelete(requiredCrew?.id)" :action="'delete'"></action-button>
            </td>
          </tr>
          </tbody>
          <tfoot v-if="!crewRequisitions?.data?.length">
          <tr v-if="isLoading">
            <td colspan="7">Loading...</td>
          </tr>
          <tr v-else-if="!crewRequisitions?.data?.length">
            <td colspan="7">No data found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="crewRequisitions" to="crw.crewRequisitions.index" :page="page"></Paginate>
  </div>
</template>