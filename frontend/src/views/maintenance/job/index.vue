<script setup>
import {onMounted, ref, watchEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useJob from "../../../composables/maintenance/useJob";
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
const icons = useHeroIcon();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { jobs, getJobs, deleteJob, isLoading } = useJob();
const { setTitle } = Title();
setTitle('Job List');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
const defaultBusinessUnit = ref(Store.getters.getCurrentUser.business_unit);


function confirmDelete(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You want to change delete this Job!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then((result) => {
    if (result.isConfirmed) {
      deleteJob(id);
    }
  })
}

function setBusinessUnit($el){
  businessUnit.value = $el.target.value;
}

onMounted(() => {
  watchEffect(() => {
  getJobs(props.page, businessUnit.value)
    .then(() => {
      const customDataTable = document.getElementById("customDataTable");

      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
    })
    .catch((error) => {
      console.error("Error fetching jobs:", error);
    });
});

});

</script>

<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Job List</h2>
    <!-- <default-button :title="'Add Job'" :to="{ name: 'mnt.jobs.create' }"></default-button> -->
    <default-button :title="'Add Job'" :to="{ name: 'mnt.jobs.create' }" :icon="icons.AddIcon"></default-button>
    
  </div>
  <div class="flex items-center justify-between mb-2 select-none">
    <div class="relative w-full">
      <select @change="setBusinessUnit($event)" class="form-control business_filter_input border-transparent focus:ring-0"
      :disabled="defaultBusinessUnit === 'TSLL' || defaultBusinessUnit === 'PSML'"
      >
        <option value="ALL" :selected="businessUnit === 'ALL'">ALL</option>
        <option value="PSML" :selected="businessUnit === 'PSML'">PSML</option>
        <option value="TSLL" :selected="businessUnit === 'TSLL'">TSLL</option>
      </select>
    </div>
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
            <th class="w-1/5">#</th>
            <th class="w-1/5">Vessel Name</th>
            <th class="w-1/5">Ship Department</th>
            <th class="w-1/5">Item</th>
            
            <th class="w-1/5">Action</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(job,index) in jobs?.data" :key="index">
            <td>{{ index + 1 }}</td>
            <td>{{ job?.opsVessel?.name }}</td>
            <td>{{ job?.mntShipDepartment?.name }}</td>
            <td>{{ job?.mntItem?.name }}</td>
            
            <td class="">
              <div class="flex justify-center">
                <action-button :action="'edit'" :to="{ name: 'mnt.jobs.edit', params: { jobId: job?.id } }"></action-button>
                <action-button @click="confirmDelete(job?.id)" :action="'delete'"></action-button>
              </div>
            </td>
          </tr>
          </tbody>
          <tfoot v-if="!jobs?.data?.length">
          <tr v-if="isLoading">
            <td colspan="5">Loading...</td>
          </tr>
          <tr v-else-if="!jobs?.data?.length">
            <td colspan="5">No job found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="jobs" to="mnt.jobs.index" :page="page"></Paginate>
  </div>
</template>