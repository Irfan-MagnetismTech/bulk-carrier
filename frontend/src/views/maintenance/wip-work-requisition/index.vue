<script setup>
import {onMounted, ref, watchEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import useWipWorkRequisition from "../../../composables/maintenance/useWipWorkRequisition";
const icons = useHeroIcon();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { wipWorkRequisitions, getWipWorkRequisitions, deleteWipWorkRequisition, isLoading } = useWipWorkRequisition();
const { setTitle } = Title();
setTitle('Wip Work Requisition List');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
const defaultBusinessUnit = ref(Store.getters.getCurrentUser.business_unit);

function confirmDelete(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You want to change delete this wip work requisition!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then((result) => {
    if (result.isConfirmed) {
      deleteWipWorkRequisition(id);
    }
  })
}

function setBusinessUnit($el){
  businessUnit.value = $el.target.value;
}

onMounted(() => {
  watchEffect(() => {
  getWipWorkRequisitions(props.page, businessUnit.value)
    .then(() => {
      const customDataTable = document.getElementById("customDataTable");

      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
    })
    .catch((error) => {
      console.error("Error fetching wip work requisitions:", error);
    });
});

});

</script>

<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">WIP Work Requisition List</h2>
    <!-- <default-button :title="'Create Work Requisition'" :to="{ name: 'mnt.work-requisitions.create' }" :icon="icons.AddIcon"></default-button> -->
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
            <th class="w-1/12">#</th>
            <th class="w-2/12">Reference No</th>
            <th class="w-2/12">Vessel</th>
            <th class="w-2/12">Maintenance Type</th>
            <th class="w-2/12">Requisition Date</th>
            <th class="w-1/12">Status</th>
            <th class="w-1/12">Business Unit</th>
            <th class="w-1/12">Action</th>
          </tr>
          </thead>
          <tbody>
            
          <tr v-for="(wipWorkRequisition,index) in wipWorkRequisitions?.data" :key="index">
            <td>{{ index + 1 }}</td>
            <td>{{ wipWorkRequisition?.reference_no }}</td>
            <td>{{ wipWorkRequisition?.opsVessel?.name }}</td>
            <td>{{ wipWorkRequisition?.maintenance_type }}</td>
            <td>{{ wipWorkRequisition?.requisition_date }}</td>
            <!-- <td>{{ workRequisition?.status }}</td> -->
            <td>
              <span :class="wipWorkRequisition?.status === 0 ? 'text-yellow-700 bg-yellow-100' : (wipWorkRequisition?.status === 1 ? 'text-blue-700 bg-blue-100' : 'text-green-700 bg-green-100') " class="px-2 py-1 font-semibold leading-tight rounded-full">{{ wipWorkRequisition?.status === 0 ? 'Pending' : (wipWorkRequisition?.status === 1 ? 'WIP' : 'Done') }}</span>
            </td>
            <td><span :class="wipWorkRequisition?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ wipWorkRequisition?.business_unit }}</span></td>
            
            <td>
                <action-button :action="'edit'" :to="{ name: 'mnt.wip-work-requisitions.edit', params: { wipWorkRequisitionId: wipWorkRequisition?.id } }"></action-button>
                <!-- <action-button @click="confirmDelete(wipWorkRequisition?.id)" :action="'delete'"></action-button> -->
            </td>
          </tr>
          </tbody>
          <tfoot v-if="!wipWorkRequisitions?.data?.length">
            <tr v-if="isLoading">
              <td colspan="7">Loading...</td>
            </tr>
            <tr v-else-if="!wipWorkRequisitions?.data?.length">
              <td colspan="7">No work requisition found.</td>
            </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="wipWorkRequisitions" to="mnt.wip-work-requisitions.index" :page="page"></Paginate>
  </div>
</template>