<script setup>
import {onMounted, ref, watch, watchEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import useWorkRequisition from "../../../composables/maintenance/useWorkRequisition";
import Store from './../../../store/index.js';
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import {useRouter} from "vue-router/dist/vue-router";
import useDebouncedRef from "../../../composables/useDebouncedRef";
const router = useRouter();
const debouncedValue = useDebouncedRef('', 800);
const icons = useHeroIcon();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { workRequisitions, getWorkRequisitions, deleteWorkRequisition, isLoading } = useWorkRequisition();
const { setTitle } = Title();
setTitle('Work Requisition List');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
const defaultBusinessUnit = ref(Store.getters.getCurrentUser.business_unit);
let showFilter = ref(false);
function swapFilter() {
  showFilter.value = !showFilter.value;
}

function confirmDelete(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You want to change delete this work requisition!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then((result) => {
    if (result.isConfirmed) {
      deleteWorkRequisition(id);
    }
  })
}

watch(
    () => businessUnit.value,
    (newBusinessUnit, oldBusinessUnit) => {
      if (newBusinessUnit !== oldBusinessUnit) {
        router.push({ name: "mnt.ship-departments.index", query: { page: 1 } })
      }
    }
);
let filterOptions = ref( {
  "business_unit": businessUnit.value,
  "items_per_page": 15,
  "page": props.page,
  "filter_options": [
    {
      "rel_type": null,
      "relation_name": null,
      "field_name": "reference_no",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    },
    
    {
      "rel_type": null,
      "relation_name": "opsVessel",
      "field_name": "name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    },
    
    {
      "rel_type": null,
      "relation_name": null,
      "field_name": "maintenance_type",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    },
    {
      "rel_type": null,
      "relation_name": null,
      "field_name": "requisition_date",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    },
    {
      "rel_type": null,
      "relation_name": null,
      "field_name": "status",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    },

  ]
});
function setSortingState(index,order){
  filterOptions.value.filter_options[index].order_by = order;
}

onMounted(() => {
  watchEffect(() => {
  getWorkRequisitions(filterOptions.value)
    .then(() => {
      const customDataTable = document.getElementById("customDataTable");

      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
    })
    .catch((error) => {
      console.error("Error fetching work requisitions:", error);
    });
});

filterOptions.value.filter_options.forEach((option, index) => {
    filterOptions.value.filter_options[index].search_param = useDebouncedRef('', 800);
  });

});

</script>

<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Work Requisition List</h2>
    <default-button :title="'Create Work Requisition'" :to="{ name: 'mnt.work-requisitions.create' }" :icon="icons.AddIcon"></default-button>
  </div>
  <!-- <div class="flex items-center justify-between mb-2 select-none">
    <div class="relative w-full">
      <select @change="setBusinessUnit($event)" class="form-control business_filter_input border-transparent focus:ring-0"
      :disabled="defaultBusinessUnit === 'TSLL' || defaultBusinessUnit === 'PSML'"
      >
        <option value="ALL" :selected="businessUnit === 'ALL'">ALL</option>
        <option value="PSML" :selected="businessUnit === 'PSML'">PSML</option>
        <option value="TSLL" :selected="businessUnit === 'TSLL'">TSLL</option>
      </select>
    </div>

    <div class="relative w-full">
      <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-0 w-5 h-5 mr-2 text-gray-500 bottom-2" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
      </svg>
      <input type="text" placeholder="Search..." class="search" />
    </div>
  </div> -->
  

  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      
      <table class="w-full whitespace-no-wrap" >
          <thead>
          <tr class="w-full">
            <th class="w-1/12">
              <div class="w-full flex items-center justify-between">
                  # <button @click="swapFilter()" type="button" v-html="icons.FilterIcon"></button>
                </div>
            </th>
            <th class="w-2/12">
              <div class="flex justify-evenly items-center">
                  <span>Reference No</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(0,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[0].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[0].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(0,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[0].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[0].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                  
                </div>
              </th>
            <th class="w-2/12">
              <div class="flex justify-evenly items-center">
                  <span>Vessel</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(1,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(1,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[1].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[1].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                  
                </div>
              </th>
            <th class="w-2/12">
              <div class="flex justify-evenly items-center">
                  <span>Maintenance Type</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(2,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[2].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[2].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(2,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[2].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[2].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                  
                </div>
            </th>
            <th class="w-2/12">
              <div class="flex justify-evenly items-center">
                  <span>Requisition Date</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(3,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[3].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[3].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(3,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[3].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[3].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                  
                </div>
              </th>
            <th class="w-1/12">
              <div class="flex justify-evenly items-center">
                  <span>Status</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(4,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[4].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[4].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(4,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[4].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[4].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                  
                </div>
            </th>
            <th class="w-1/12">
              <div class="flex justify-evenly items-center">
                  <span>Business Unit</span>
                </div>

            </th>
            <th class="w-1/12">Action</th>
          </tr>
          <tr class="w-full" v-if="showFilter">
              <th>
                <select v-model="filterOptions.items_per_page" class="filter_input">
                  <option value="15">15</option>
                  <option value="30">30</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
                </select>
              </th>
              <th><input v-model="filterOptions.filter_options[0].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th><input v-model="filterOptions.filter_options[1].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th><input v-model="filterOptions.filter_options[2].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th><input v-model="filterOptions.filter_options[3].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th>
                
              </th>
              <th>
                <filter-with-business-unit v-model="filterOptions.business_unit"></filter-with-business-unit>
              </th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            
          <tr v-for="(workRequisition,index) in workRequisitions?.data" :key="index">
            <td>{{ index + 1 }}</td>
            <td>{{ workRequisition?.reference_no }}</td>
            <td>{{ workRequisition?.opsVessel?.name }}</td>
            <td>{{ workRequisition?.maintenance_type }}</td>
            <td>{{ workRequisition?.requisition_date }}</td>
            <!-- <td>{{ workRequisition?.status }}</td> -->
            <td>
              <span :class="workRequisition?.status === 0 ? 'text-yellow-700 bg-yellow-100' : (workRequisition?.status === 1 ? 'text-blue-700 bg-blue-100' : 'text-green-700 bg-green-100') " class="px-2 py-1 font-semibold leading-tight rounded-full">{{ workRequisition?.status === 0 ? 'Pending' : (workRequisition?.status === 1 ? 'WIP' : 'Done') }}</span>
            </td>
            <td><span :class="workRequisition?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ workRequisition?.business_unit }}</span></td>
            
            <td>
                <action-button :action="'edit'" :to="{ name: 'mnt.work-requisitions.edit', params: { workRequisitionId: workRequisition?.id } }"></action-button>
                <action-button @click="confirmDelete(workRequisition?.id)" :action="'delete'"></action-button>
            </td>
          </tr>
          </tbody>
          <tfoot v-if="!workRequisitions?.data?.length">
            <tr v-if="isLoading">
              <td colspan="7">Loading...</td>
            </tr>
            <tr v-else-if="!workRequisitions?.data?.length">
              <td colspan="7">No work requisition found.</td>
            </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="workRequisitions" to="mnt.work-requisitions.index" :page="page"></Paginate>
  </div>
</template>