<script setup>
import {onMounted, ref, watch, watchEffect, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import useDoneWorkRequisition from "../../../composables/maintenance/useDoneWorkRequisition";
import Store from './../../../store/index.js';
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import {useRouter} from "vue-router/dist/vue-router";
import useDebouncedRef from "../../../composables/useDebouncedRef";
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import ErrorComponent from "../../../components/utils/ErrorComponent.vue";
import FilterComponent from "../../../components/utils/FilterComponent.vue";

const router = useRouter();
const debouncedValue = useDebouncedRef('', 800);
const icons = useHeroIcon();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { doneWorkRequisitions, getDoneWorkRequisitions, deleteDoneWorkRequisition, isLoading, isTableLoading, errors } = useDoneWorkRequisition();
const { setTitle } = Title();
setTitle('Done Work Requisition List');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
const defaultBusinessUnit = ref(Store.getters.getCurrentUser.business_unit);

function confirmDelete(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You want to delete this done work requisition!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then((result) => {
    if (result.isConfirmed) {
      deleteDoneWorkRequisition(id);
    }
  })
}


watch(
    () => businessUnit.value,
    (newBusinessUnit, oldBusinessUnit) => {
      if (newBusinessUnit !== oldBusinessUnit) {
        router.push({ name: "mnt.done-work-requisitions.index", query: { page: 1 } })
      }
    }
);
let filterOptions = ref( {
  "business_unit": businessUnit.value,
  "items_per_page": 15,
  "page": props.page,
  "isFilter": false,
  "filter_options": [
  {
      "rel_type": null,
      "relation_name": null,
      "field_name": "requisition_date",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Requisition Date",
      "filter_type": "date"

    },
    {
      "rel_type": null,
      "relation_name": null,
      "field_name": "reference_no",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Reference No",
      "filter_type": "input"
    },
    
    {
      "rel_type": null,
      "relation_name": "opsVessel",
      "field_name": "name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Vessel",
      "filter_type": "input"
    },
    
    {
      "rel_type": null,
      "relation_name": null,
      "field_name": "maintenance_type",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Maintenance Type",
      "filter_type": "input",
      // "filter_type": "dropdown",
      // "select_options": [
      //     { value: "", label: "Select" ,defaultSelected: true},
      //     { value: "Schedule", label: "Schedule" ,defaultSelected: false},
      //     { value: "Breakdown", label: "Breakdown",defaultSelected: false},
      //     { value: "Dry Dock", label: "Dry Dock",defaultSelected: false},
      //   ]
    },
    {
      "rel_type": null,
      "relation_name": null,
      "field_name": "status",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Status",
      "input_value": 'Done',
      
    },

  ]
});

let stringifiedFilterOptions = JSON.stringify(filterOptions.value);

const currentPage = ref(1);
const paginatedPage = ref(1);



onMounted(() => {
  watchPostEffect(() => {
    if(currentPage.value == props.page && currentPage.value != 1) {
      filterOptions.value.page = 1;
      router.push({ name: 'mnt.done-work-requisitions.index', query: { page: filterOptions.value.page } });
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;

    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }
  getDoneWorkRequisitions(filterOptions.value)
    .then(() => {
      paginatedPage.value = filterOptions.value.page;
      const customDataTable = document.getElementById("customDataTable");

      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
    })
    .catch((error) => {
      console.error("Error fetching Done work requisitions:", error);
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
    <h2 class="text-2xl font-semibold text-gray-700">Done Work Requisition List</h2>
    <!-- <default-button :title="'Create Work Requisition'" :to="{ name: 'mnt.work-requisitions.create' }" :icon="icons.AddIcon"></default-button> -->
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
          <!-- <thead v-once>
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
          </thead> -->
          <FilterComponent :filterOptions = "filterOptions"/>
          <tbody class="relative">
            
          <tr v-for="(doneWorkRequisition,index) in doneWorkRequisitions?.data" :key="index">
            <td>{{ ((paginatedPage-1) * filterOptions.items_per_page) + index + 1 }}</td>
            <td><nobr>{{ doneWorkRequisition?.requisition_date }}</nobr></td>
            <td>{{ doneWorkRequisition?.reference_no }}</td>
            <td>{{ doneWorkRequisition?.opsVessel?.name }}</td>
            <td>{{ doneWorkRequisition?.maintenance_type }}</td>
            <!-- <td>{{ workRequisition?.status }}</td> -->
            <td>
              <span :class="doneWorkRequisition?.status == 0 ? 'text-yellow-700 bg-yellow-100' : (doneWorkRequisition?.status == 1 ? 'text-blue-700 bg-blue-100' : 'text-green-700 bg-green-100') " class="px-2 py-1 font-semibold leading-tight rounded-full">{{ doneWorkRequisition?.status == 0 ? 'Pending' : (doneWorkRequisition?.status == 1 ? 'WIP' : 'Done') }}</span>
            </td>
            <td><span :class="doneWorkRequisition?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ doneWorkRequisition?.business_unit }}</span></td>
            
            <td>
                <action-button :action="'show'" :to="{ name: 'mnt.done-work-requisitions.show', params: { doneWorkRequisitionId: doneWorkRequisition?.id } }"></action-button>
                <!-- <action-button @click="confirmDelete(wipWorkRequisition?.id)" :action="'delete'"></action-button> -->
            </td>
          </tr>
          <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && doneWorkRequisitions?.data?.length"></LoaderComponent>
          </tbody>
          <tfoot v-if="!doneWorkRequisitions?.data?.length" class="relative h-[250px]">
            <tr v-if="isLoading">
              <td colspan="7">Loading...</td>
            </tr>
            <tr v-else-if="isTableLoading">
              <td colspan="7">
                <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
              </td>
            </tr>
            <tr v-else-if="!doneWorkRequisitions?.data?.length">
              <td colspan="7">No done work requisition found.</td>
            </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="doneWorkRequisitions" to="mnt.done-work-requisitions.index" :page="page"></Paginate>
  </div>
  <ErrorComponent :errors="errors"></ErrorComponent>
</template>