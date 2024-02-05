<script setup>
import {onMounted, watchEffect,watch,ref, watchPostEffect} from 'vue';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import useLcRecord from "../../../composables/supply-chain/useLcRecord";
import useHelper from "../../../composables/useHelper.js";
import Title from "../../../services/title";
import useDebouncedRef from '../../../composables/useDebouncedRef';
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import FilterComponent from "../../../components/utils/FilterComponent.vue";
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import ErrorComponent from "../../../components/utils/ErrorComponent.vue";
import { formatDate, showErrorAlert } from '../../../config/setting';

const { getLcRecords, lcRecords, deleteLcRecord, isLoading,isTableLoading , storeLcRecordStatus, showLcRecordStatuses, lcRecordStatuses, lcRecordStatus } = useLcRecord();
const { numberFormat } = useHelper();
const { setTitle } = Title();
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

// Modal start
const closingRemarks = ref(null);
const isModalOpen = ref(0);
const details = ref([{type: ''}]);
const currentIndex = ref(null);


function showModal(id) {
  isModalOpen.value = 1
  currentIndex.value = id;
}

function closeModel() {
  isModalOpen.value = 0
  closingRemarks.value = null;
  currentIndex.value = null;
}

function getStatus(id){
  showLcRecordStatuses(id).then(() => {
      // addStatus();
      showModal(id)
  }).catch((error) => {
    console.error("Error closing WR:", error);
  });
}
function storeStatus() {

  const messages = ref([]);
  if(!lcRecordStatus.value?.status){
    messages.value.push('Status Required');
  }
  
  lcRecordStatus.value.scm_lc_record_id = currentIndex.value;
  if(!lcRecordStatus.value?.date){
    messages.value.push('Date Required');
  }

  if(messages.value?.length){
    showErrorAlert(messages);
    return;
  }


  storeLcRecordStatus(lcRecordStatus.value).then(() => {
    lcRecordStatus.value.status = '';
    lcRecordStatus.value.date = '';
      closeModel()
  }).catch((error) => {
    console.error("Error closing WR:", error);
  });
}
// Modal end

function addStatus() {
  lcRecordStatuses.value.push({ status: '', date: '' });
}





const critical = ['No','Yes'];
const icons = useHeroIcon();
const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;

setTitle('Lc Records');


let filterOptions = ref({
  "business_unit": businessUnit.value,
  "items_per_page": 15,
  "page": props.page,
  "isFilter": false,
  "filter_options": [
    {
      "relation_name": null,
      "field_name": "lc_no",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "LC No",
      "filter_type": "input" 
    },
    {
      "relation_name": null,
      "field_name": "lc_date",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "LC Date",
      "filter_type": "input" 
    },
    {
      "relation_name": null,
      "field_name": "expire_date",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Expire Date",
      "filter_type": "input" 
    },
    {
      "relation_name": null,
      "field_name": "weight",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Weight",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "invoice_value",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Invoice Value",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "assessment_value",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Assessment Value",
      "filter_type": "input"
    },
    
    {
      "relation_name": null,
      "field_name": "",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Status",
      "filter_type": ""
    },

  ]
});


const currentPage = ref(1);
const paginatedPage = ref(1);

let stringifiedFilterOptions = JSON.stringify(filterOptions.value);




onMounted(() => {
  watchPostEffect(() => {
    if(currentPage.value == props.page && currentPage.value != 1) {
      filterOptions.value.page = 1;
      router.push({ name: 'scm.lc-records.index', query: { page: filterOptions.value.page } });
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;
    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }
    getLcRecords(filterOptions.value)
      .then(() => {
      paginatedPage.value = filterOptions.value.page;
      const customDataTable = document.getElementById("customDataTable");
      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
    })
    .catch((error) => {
      console.error("Error fetching PR:", error);
    });
});
filterOptions.value.filter_options.forEach((option, index) => {
    filterOptions.value.filter_options[index].search_param = useDebouncedRef('', 800);
  });
});
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
            deleteLcRecord(id);
          }
        })
      }
</script>

<template>
  <!-- Heading -->
 
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">LC Records List</h2>
    <default-button :title="'Create LC Records'" :to="{ name: 'scm.lc-records.create' }" :icon="icons.AddIcon"></default-button>
  </div>
  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      <table class="w-full whitespace-no-wrap" >
          <FilterComponent :filterOptions = "filterOptions"/>
          <tbody class="relative">
            <tr v-for="(lcRecord,index) in (lcRecords?.data ? lcRecords?.data : lcRecords)" :key="index">
              <td>{{ (paginatedPage - 1) * filterOptions.items_per_page + index + 1 }}</td>
              <td>{{ lcRecord?.lc_no }}</td>
              <td>{{ lcRecord?.lc_date }}</td>
              <td>{{ lcRecord?.expire_date }}</td>
              <td>{{ lcRecord?.weight }}</td>
              <td>{{ lcRecord?.invoice_value }}</td>
              <td>{{ lcRecord?.assessment_value }}</td>
              <td><button @click="getStatus(lcRecord?.id)" class="px-2 py-1 font-semibold leading-tight rounded-full text-white bg-purple-600 hover:bg-purple-700"><nobr>Status</nobr></button></td>
              <td>
                <span :class="lcRecord?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ lcRecord?.business_unit }}</span>
              </td>
              <td>
                <nobr>
                  <action-button :action="'show'" :to="{ name: 'scm.lc-records.show', params: { lcRecordId: lcRecord.id } }"></action-button>
                  <action-button :action="'edit'" :to="{ name: 'scm.lc-records.edit', params: { lcRecordId: lcRecord.id } }"></action-button>
                  <action-button @click="confirmDelete(lcRecord.id)" :action="'delete'"></action-button>
                </nobr>
              </td>
            </tr>
            <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && lcRecords?.data?.length"></LoaderComponent>
          </tbody>
          <tfoot v-if="!lcRecords?.data?.length" class="bg-white dark-disabled:bg-gray-800">
            <tr v-if="isLoading">
            </tr>
            <tr v-else-if="isTableLoading">
                <td colspan="7">
                  <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
                </td>
            </tr>
            <tr v-else-if="!lcRecords?.data?.length">
              <td colspan="7">No Data found.</td>
            </tr>
        </tfoot>
      </table>
    </div>
    <Paginate :data="lcRecords" to="scm.lc-records.index" :page="page"></Paginate>
  </div>
  <!-- Heading -->

  <div v-show="isModalOpen" class="fixed inset-0 z-30 flex items-end overflow-y-auto bg-black bg-opacity-50 sm:items-center sm:justify-center">
    <!-- Modal -->
    <form @submit.prevent="" style="position: absolute;top: 0;">
      <div class="w-full px-6 py-4  overflow-y-auto bg-white rounded-t-lg dark-disabled:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl" role="dialog" id="modal">
        <!-- Remove header if you don't want a close icon. Use modal body to place modal tile. -->
        <header class="flex justify-end">
          <button type="button"
                  class="inline-flex items-center justify-center w-6 h-6 mb-2 text-gray-400 transition-colors duration-150 rounded dark-disabled:hover:text-gray-200 hover: hover:text-gray-700"
                  aria-label="close" @click="closeModel">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" role="img" aria-hidden="true">
              <path
                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                  clip-rule="evenodd" fill-rule="evenodd"></path>
            </svg>
          </button>
        </header>
        <!-- Modal body -->
        <div class="grid grid-cols-1 md:grid-cols-2 md:gap-2 ">
          <label class="label-group">
            <span class="label-item-title">Status <span class="text-red-500">*</span></span>
              <input type="text" v-model="lcRecordStatus.status" required class="form-input" name="scm_warehouse_id" :id="'lc_no'" />
          </label>
          
          <label class="label-group">
            <span class="label-item-title">Date <span class="text-red-500">*</span></span>
            <VueDatePicker  v-model="lcRecordStatus.date" class="form-input" required auto-apply :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :auto-position="false" :min-date="lcRecordStatuses?.last_date"></VueDatePicker>
          </label>

        </div>
        <div class="mt-3 min-h-[250px]">
          <span class="label-item-title"> Previous Status </span>
          <table class="w-full mb-5 whitespace-no-wrap border-collapse contract-assign-table table2">
          <thead v-once>
          <tr style="background-color: #04AA6D;color: white"
              class="text-xs font-semibold tracking-wide text-gray-500  border-b dark-disabled:border-gray-700 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
            <th>Status</th>
            <th>Date</th>
          </tr>
          </thead>
          <tbody >
              <tr v-for="(lcRecordSt,lcRecordStatusIndex) in lcRecordStatuses?.scmLcRecordStatus" :key="lcRecordStatusIndex" v-if="lcRecordStatuses?.scmLcRecordStatus?.length">
                <td class="p-1">{{ lcRecordSt.status }}
                  <!-- <input :readonly="lcRecordStatusIndex != lcRecordStatuses?.length - 1" type="text" v-model="lcRecordStatus.status" required class="form-input" name="scm_warehouse_id" :id="'lc_no'" /> -->
                </td>
                <td>
                  <!-- <VueDatePicker :readonly="lcRecordStatusIndex != lcRecordStatuses?.length - 1" v-model="lcRecordStatus.date" class="form-input" required auto-apply :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :auto-position="false" ></VueDatePicker> -->
                  {{ formatDate(lcRecordSt.date) }}
                
                </td>
                
              </tr>
              <tr v-else>
                <td colspan="2" class="h-[120px]">No data found</td>
              </tr>
           </tbody>
        </table>
        </div>
        
        <!-- <div class="dt-responsive table-responsive">
          <table id="dataTable" class="w-full table table-striped table-bordered">
            
          </table>
        </div> -->
        <footer class="flex flex-col items-center justify-between px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark-disabled:bg-gray-800">
          <button type="button" @click="closeModel" style="color: #1b1e21"
                  class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 border border-gray-300 rounded-lg dark-disabled:text-gray-400 sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray">
            CLOSE
          </button>
          <button type="button" @click="storeStatus" style="color: #1b1e21"
                  class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 border border-gray-300 rounded-lg dark-disabled:text-gray-400 sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray">
            CONFIRM
          </button>
        </footer>
      </div>
    </form>
    </div>
  
  

  <ErrorComponent :errors="errors"></ErrorComponent>  
</template>