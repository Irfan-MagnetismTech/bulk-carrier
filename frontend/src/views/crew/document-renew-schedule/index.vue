<script setup>
import {onMounted, ref, watch, watchEffect, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useUser from "../../../composables/administration/useUser";
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import FilterComponent from "../../../components/utils/FilterComponent.vue";
const icons = useHeroIcon();
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import useDebouncedRef from "../../../composables/useDebouncedRef";
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import useCrewDocument from "../../../composables/crew/useCrewDocument";
import useCrewCommonApiRequest from "../../../composables/crew/useCrewCommonApiRequest";
import env from '../../../config/env';

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { crewDocumentRenewSchedules, currentCrewDocRenewData, isCrewDocumentRenewScheduleModalOpen, getCrewDocumentRenewSchedules, storeCrewRenewDocument, deleteCrewRenewDocument, updateCrewRenewDocument, isLoading, isTableLoading } = useCrewDocument();
const { crewDocumentRenewals, getCrewDocumentRenewals, isCrewDocumentRenewModalOpen } = useCrewCommonApiRequest();

const { setTitle } = Title();
setTitle('Renew Schedule');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
let showFilter = ref(false);
// let isTableLoader = ref(false);


function swapFilter() {
  showFilter.value = !showFilter.value;
}

let renewFormData = ref({
  crw_crew_document_id: '',
  issue_date: '',
  expire_date: '',
  reference_no: '',
  attachment: null,
});

let filterOptions = ref( {
  "business_unit": businessUnit.value,
  "items_per_page": 15,
  "page": props.page,
  "isFilter": false,
  "filter_options": [
    {
      "relation_name": null,
      "field_name": "document_name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Document Name",
      "filter_type": null, 
    },
    {
      "relation_name": "crwCrewDocumentRenewal",
      "field_name": "expire_date",
      "search_param": "30",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Expire Date",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "left_days",
      "search_param": "30",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Left Days",
      "filter_type": "input"
    },
    {
      "relation_name": "crwCrewProfile",
      "field_name": "full_name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Crew Name",
      "filter_type": "input"
    },
    {
      "relation_name": "crwCrewProfile",
      "field_name": "pre_mobile_no",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Contact",
      "filter_type": "input"
    },
  ]
});

function setSortingState(index, order) {
  filterOptions.value.filter_options.forEach(function (t) {
    t.order_by = null;
  });
  filterOptions.value.filter_options[index].order_by = order;
}

const currentPage = ref(1);
const paginatedPage = ref(1);
let stringifiedFilterOptions = JSON.stringify(filterOptions.value);

function showCrewDocumentRenewModal(crwDocumentId){
  isCrewDocumentRenewScheduleModalOpen.value = 1;
  renewFormData.value.crw_crew_document_id = crwDocumentId;
}

function closeCrewDocumentRenewModal(){
  isCrewDocumentRenewModalOpen.value = 0;
}

const selectedRenewFile = (event) => {
  renewFormData.value.attachment = event.target.files[0];
};

function clearFilter() {
  filterOptions.value.business_unit = businessUnit.value;
  filterOptions.value.filter_options = filterOptions.value.filter_options.map((option) => ({
     ...option,
    search_param: null,
    order_by: null,
   }));
}

onMounted(() => {
  watchPostEffect(() => {
    if(currentPage.value == props.page && currentPage.value != 1) {
      filterOptions.value.page = 1;
      router.push({ name: 'crw.agencyBills.index', query: { page: filterOptions.value.page } });
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;
    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }
    getCrewDocumentRenewSchedules(filterOptions.value)
    .then(() => {
      paginatedPage.value = filterOptions.value.page;
      const customDataTable = document.getElementById("customDataTable");

      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
    })
    .catch((error) => {
      console.error("Error fetching users:", error);
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
    <h2 class="text-2xl font-semibold text-gray-700">Renew Schedule</h2>
<!--    <default-button :title="'Create User'" :to="{ name: 'administration.users.create' }" :icon="icons.AddIcon"></default-button>-->
  </div>

  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      <table class="w-full whitespace-no-wrap" >
        <!-- <FilterComponent :filterOptions = "filterOptions"/> -->
        <thead>
          <tr>
            <th> # </th>
            <th> Document Name </th>
            <th> Expire Date </th>
            <th> Left Days</th>
            <th> Crew Name </th>
            <th> Contact </th>
            <th> Business Unit</th>
            <th> Action </th>
          </tr>
        </thead>
          <tbody class="relative">
          <tr v-for="(crwDocument,index) in crewDocumentRenewSchedules" :key="index">
            <td>{{ index + 1 }}</td>
            <td>{{ crwDocument?.document_name }}</td>
            <td>{{ crwDocument?.crwCrewDocumentRenewal?.expire_date }}</td>
            <td>{{ crwDocument?.crwCrewDocumentRenewal?.left_days }}</td>
            <td>{{ crwDocument?.crwCrewProfile?.full_name }}</td>
            <td>{{ crwDocument?.crwCrewProfile?.pre_mobile_no }}</td>
            <td>
              <span :class="crwDocument?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ crwDocument?.business_unit }}</span>
            </td>
            <td>
              <nobr>
                <button @click="showCrewDocumentRenewModal(crwDocument?.id)" title="Renew Schedule" class="px-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                  Renew
                </button>
              </nobr>
            </td>
          </tr>
          <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && crewDocumentRenewSchedules?.data?.length"></LoaderComponent>
          </tbody>
          <tfoot v-if="!crewDocumentRenewSchedules?.data?.length" class="relative h-[250px]">
          <tr v-if="isLoading">
             <td colspan="8"></td>
          </tr>
          <tr v-else-if="isTableLoading">
              <td colspan="8">
                <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>
              </td>
          </tr>
          <tr v-else-if="!crewDocumentRenewSchedules">
            <td colspan="8">No data found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="crewDocumentRenewSchedules" to="crw.renew-schedules.index" :page="page"></Paginate>
  </div>
  <div v-show="isCrewDocumentRenewScheduleModalOpen" class="fixed inset-0 z-30 flex items-end overflow-y-auto bg-black bg-opacity-50 sm:items-center sm:justify-center">
    <!-- Modal -->
    <form @submit.prevent="" style="position: absolute;top: 0;">
      <div class="w-full px-6 py-4 overflow-y-auto bg-white rounded-t-lg dark-disabled:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl" role="dialog" id="modal">
        <!-- Remove header if you don't want a close icon. Use modal body to place modal tile. -->
        <header class="flex justify-end">
          <button type="button"
                  class="inline-flex items-center justify-center w-6 h-6 mb-2 text-gray-400 transition-colors duration-150 rounded dark-disabled:hover:text-gray-200 hover: hover:text-gray-700"
                  aria-label="close" @click="closeCrewDocumentRenewModal">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" role="img" aria-hidden="true">
              <path
                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                  clip-rule="evenodd" fill-rule="evenodd"></path>
            </svg>
          </button>
        </header>
        <!-- Modal body -->
        <table class="w-full mb-2 whitespace-no-wrap border-collapse contract-assign-table table2">
          <thead v-once>
          <tr style="background-color: #04AA6D;color: white"
              class="text-xs font-semibold tracking-wide text-gray-500 border-b dark-disabled:border-gray-700 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
            <th colspan="5">Renew Crew Document</th>
          </tr>
          </thead>
        </table>
        <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
          <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Add Renew Data</legend>
          <form @submit.prevent="storeCrewRenewDocument(renewFormData,crewDocumentRenewals)">
            <table class="w-full whitespace-no-wrap" id="table">
              <thead>
              <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
                <th class="px-4 py-3 align-bottom">Issue Date <span class="text-red-500">*</span></th>
                <th class="px-4 py-3 align-bottom">Expire Date <span class="text-red-500">*</span></th>
                <th class="px-4 py-3 align-bottom">Reference No</th>
                <th class="px-4 py-3 align-bottom">Attachment</th>
                <th class="px-4 py-3 text-center align-bottom">Action</th>
              </tr>
              </thead>
              <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
              <tr class="text-gray-700 dark-disabled:text-gray-400">
                <td class="px-1 py-1">
                  <input type="date" v-model.trim="renewFormData.issue_date" class="form-input" autocomplete="off" required/>
                </td>
                <td class="px-1 py-1">
                  <input type="date" v-model.trim="renewFormData.expire_date" class="form-input" autocomplete="off" required/>
                </td>
                <td class="px-1 py-1">
                  <input type="text" v-model.trim="renewFormData.reference_no" placeholder="Reference" class="form-input" autocomplete="off" />
                </td>
                <td class="px-1 py-1">
                  <input type="file" @change="selectedRenewFile" class="form-input" autocomplete="off" />
                </td>
                <td class="px-1 py-1 text-center">
                  <button type="submit" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Save
                  </button>
                </td>
              </tr>
              </tbody>
            </table>
          </form>
        </fieldset>
        <footer class="flex flex-col items-center justify-end px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark-disabled:bg-gray-800">
          <button type="button" @click="closeCrewDocumentRenewModal" style="color: #1b1e21"
                  class="w-full px-5 py-3 text-sm font-medium leading-5 text-white text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark-disabled:text-gray-400 sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray">
            Cancel
          </button>
        </footer>
      </div>
    </form>
  </div>
</template>
<style lang="postcss" scoped>
#modal {
  max-width: 60rem;
}
</style>