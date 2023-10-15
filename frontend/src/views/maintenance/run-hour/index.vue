<script setup>
import {onMounted, ref, watchEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useItemGroup from "../../../composables/maintenance/useItemGroup";
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useRunHour from "../../../composables/maintenance/useRunHour";
import useHeroIcon from "../../../assets/heroIcon";
const icons = useHeroIcon();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { runHours, getRunHours, deleteRunHour, isLoading } = useRunHour();
const { setTitle } = Title();
setTitle('Run Hour List');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;


function confirmDelete(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You want to change delete this run hour!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then((result) => {
    if (result.isConfirmed) {
      deleteRunHour(id);
    }
  })
}

onMounted(() => {
  watchEffect(() => {
  getRunHours(props.page)
    .then(() => {
      const customDataTable = document.getElementById("customDataTable");

      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
    })
    .catch((error) => {
      console.error("Error fetching run hours:", error);
    });
});

});

</script>

<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Run Hour List</h2>
    <!-- <default-button :title="'Create'" :to="{ name: 'maintenance.run-hours.create' }"></default-button> -->
    <default-button :title="'Create'" :to="{ name: 'maintenance.run-hours.create' }" :icon="icons.AddIcon"></default-button>
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
            <th class="w-1/6">#</th>
            <th class="w-1/6 ">Vessel</th>
            <th class="w-1/6 ">Item Group</th>
            <th class="w-1/6 ">Item Name</th>
            <th class="w-1/6 ">Present Run Hour</th>
            <th class="w-1/6">Action</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(runHour,index) in runHours?.data" :key="index">
            <td>{{ index + 1 }}</td>
            <td>{{ runHour?.opsVessel?.name }}</td>
            <td>{{ runHour?.mntItem?.mntItemGroup?.name }}</td>
            <td>{{ runHour?.mntItem?.name }}</td>
            <td>{{ runHour?.mntItem?.presentRunHour }}</td>
            
            <td class="">
              <div class="flex">
                <action-button :action="'edit'" :to="{ name: 'maintenance.run-hours.edit', params: { runHourId: runHour?.id } }"></action-button>
                <action-button @click="confirmDelete(runHour?.id)" :action="'delete'"></action-button>
              </div>
            </td>
          </tr>
          </tbody>
          <tfoot v-if="!runHours?.data?.length">
          <tr v-if="isLoading">
            <td colspan="6">Loading...</td>
          </tr>
          <tr v-else-if="!runHours?.data?.length">
            <td colspan="6">No run hour found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="runHours" to="maintenance.run-hours.index" :page="page"></Paginate>
  </div>
</template>