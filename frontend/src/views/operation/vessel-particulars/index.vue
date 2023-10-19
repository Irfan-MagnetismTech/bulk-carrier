<script setup>
import {onMounted, ref, watchEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
const icons = useHeroIcon();
import useVesselParticular from '../../../composables/operations/useVesselParticular';
const { vesselParticulars, getVesselParticulars, deleteVesselParticular, isLoading } = useVesselParticular();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { setTitle } = Title();
setTitle('Vessel Particular List');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;


function confirmDelete(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You want to delete this data!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then((result) => {
    if (result.isConfirmed) {
        deleteVesselParticular(id);
    }
  })
}

onMounted(() => {
  watchEffect(() => {
    getVesselParticulars(props.page)
    .then(() => {
      const customDataTable = document.getElementById("customDataTable");

      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
    })
    .catch((error) => {
      console.error("Error fetching data.", error);
    });
});

});

</script>

<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Vessel Particular List</h2>
    <default-button :title="'Create Vessel Particular'" :to="{ name: 'ops.vessel-particulars.create' }" :icon="icons.AddIcon"></default-button>
  </div>
  <div class="flex items-center justify-between mb-2 select-none">
    <!-- Search -->
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
            <th>Tariff Name</th>
            <th>Vessel</th>
            <th>Loading Point</th>
            <th>Unloading Point</th>
            <th>Cargo Type</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
          </thead>
          <tbody v-if="vesselParticulars?.data?.length">
              <tr v-for="(vesselParticular, index) in vesselParticulars.data" :key="vesselParticular?.id">
                  <td>{{ vesselParticulars.from + index }}</td>
                  <td>{{ vesselParticular?.tariff_name }}</td>
                  <td>{{ vesselParticular?.opsVessel?.name }}</td>
                  <td>{{ vesselParticular?.loading_point }}</td>
                  <td>{{ vesselParticular?.unloading_point }}</td>
                  <td>{{ vesselParticular?.opsCargoType?.cargo_type }}</td>
                  <td>{{ vesselParticular?.status }}</td>
                  <td class="items-center justify-center space-x-2 text-gray-600">
                      <action-button :action="'edit'" :to="{ name: 'ops.vessel-particulars.edit', params: { vesselParticularId: vesselParticular.id } }"></action-button>
                      <action-button :action="'show'" :to="{ name: 'ops.vessel-particulars.show', params: { vesselParticularId: vesselParticular.id } }"></action-button>
                      <action-button @click="confirmDelete(vesselParticular.id)" :action="'delete'"></action-button>
                    <!-- <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: port.subject_type,subject_id: port.id } }"></action-button> -->
                  </td>
              </tr>
          </tbody>
          
          <tfoot v-if="!vesselParticulars?.length">
          <tr v-if="isLoading">
            <td colspan="6">Loading...</td>
          </tr>
          <tr v-else-if="!vesselParticulars?.data?.length">
            <td colspan="6">No data found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="vesselParticulars" to="ops.vessel-particulars.index" :page="page"></Paginate>
  </div>
</template>