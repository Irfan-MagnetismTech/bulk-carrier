<script setup>
import {onMounted, ref, watchEffect, watch, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useCrewAssign from "../../../composables/crew/useCrewAssign";
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import Store from "../../../store";
import {useRouter} from "vue-router/dist/vue-router";
import useDebouncedRef from "../../../composables/useDebouncedRef";
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import FilterComponent from "../../../components/utils/FilterComponent.vue";
import RemarksComponent from "../../../components/utils/RemarksComponent.vue";

const icons = useHeroIcon();
const router = useRouter();
import env from '../../../config/env';

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

let statusFormData = ref({});

const { crewAssigns, getCrewAssigns, deleteCrewAssign, updateCrewAssign, updateCrewAssignStatus, isCrewUpdateStatusModalOpen, isLoading, isTableLoading } = useCrewAssign();

const debouncedValue = useDebouncedRef('', 800);

const { setTitle } = Title();

setTitle('Crew Assigns');

const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

let filterOptions = ref( {
  "business_unit": businessUnit.value,
  "items_per_page": 15,
  "page": props.page,
  "isFilter": false,
  "filter_options": [
    {
      "relation_name": null,
      "field_name": "assignment_code",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Assignment Code",
      "filter_type": "input",
      "nobr_tag": true
    },
    {
      "relation_name": "opsVessel",
      "field_name": "name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Vessel Name",
      "filter_type": "input"
    },
    {
      "relation_name": "crwCrew",
      "field_name": "full_name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Crew Name",
      "filter_type": "input"
    },
    {
      "relation_name": "crwCrew",
      "field_name": "pre_mobile_no",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Crew Contact",
      "filter_type": "input",
      "nobr_tag": true
    },
    {
      "relation_name": null,
      "field_name": "position_onboard",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Position Onboard",
      "filter_type": "input",
      "nobr_tag": true
    },
    {
      "relation_name": null,
      "field_name": "joining_date",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Joining Date",
      "filter_type": "input",
      "nobr_tag": true
    },
    {
      "relation_name": 'opsPort',
      "field_name": "joining_port_code",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Joining Port Code",
      "filter_type": "input",
      "nobr_tag": true
    },
    {
      "relation_name": null,
      "field_name": "duration",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Duration (Months)",
      "filter_type": "input",
      "nobr_tag": true
    },
    {
      "relation_name": null,
      "field_name": "status",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Status",
      "filter_type": "input"
    }
  ]
});

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;

const currentPage = ref(1);
const paginatedPage = ref(1);
let stringifiedFilterOptions = JSON.stringify(filterOptions.value);

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
      deleteCrewAssign(id);
    }
  })
}

function updateAssignCrewStatus(assignData){
  statusFormData.value.id = assignData?.id;
  statusFormData.value.completion_date = '';
  statusFormData.value.completion_remarks = '';

  if(assignData?.status === "Onboard"){
    statusFormData.value.status = "Complete";
  } else {
    statusFormData.value.status = "Onboard";
  }

  isCrewUpdateStatusModalOpen.value = 1;

  // let statusFormData = ref({
  //   id: '',
  //   status: '',
  //   completion_date: '',
  //   completion_remarks: '',
  // });
}

function closeCrewUpdateStatusModal(){
  isCrewUpdateStatusModalOpen.value = 0;
}

onMounted(() => {
  watchPostEffect(() => {
    if(currentPage.value == props.page && currentPage.value != 1) {
      filterOptions.value.page = 1;
      router.push({ name: 'crw.crewAssigns.index', query: { page: filterOptions.value.page } });
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;
    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }

    getCrewAssigns(filterOptions.value)
        .then(() => {
          const customDataTable = document.getElementById("customDataTable");
          paginatedPage.value = filterOptions.value.page;
          if (customDataTable) {
            tableScrollWidth.value = customDataTable.scrollWidth;
          }
        })
        .catch((error) => {
          console.error("Error fetching ranks:", error);
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
    <h2 class="text-2xl font-semibold text-gray-700">Crew Assign List</h2>
    <default-button :title="'Create Item'" :to="{ name: 'crw.crewAssigns.create' }" :icon="icons.AddIcon"></default-button>
  </div>

  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">

      <table class="w-full whitespace-no-wrap" >
        <FilterComponent :filterOptions = "filterOptions"/>
          <tbody>
            <tr v-for="(crwAssign,index) in crewAssigns?.data" :key="index">
              <td> {{ index + 1 }} </td>
              <td> <nobr> {{ crwAssign?.assignment_code }} </nobr> </td>
              <td> <nobr> {{ crwAssign?.opsVessel?.name }} </nobr> </td>
              <td class="text-left"> <nobr> {{ crwAssign?.crwCrew?.full_name }} </nobr> </td>
              <td> <nobr> {{ crwAssign?.crwCrew?.pre_mobile_no }} </nobr> </td>
              <td> <nobr> {{ crwAssign?.position_onboard }} </nobr> </td>
              <td> <nobr> {{ crwAssign?.joining_date }} </nobr> </td>
              <td> <nobr> {{ crwAssign?.joining_port_code }} </nobr> </td>
              <td> {{ crwAssign?.duration }}  </td>
              <td>
                <span :class="crwAssign?.status === 'Onboard' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full"> {{ crwAssign?.status }}
                </span>
              </td>
              <td>
                <span :class="crwAssign?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ crwAssign?.business_unit }}</span>
              </td>
              <td>
                <nobr>
                  <div class="tooltip cursor-pointer" :class="{ 'custom_opacity': crwAssign?.status === 'Complete' }">
                    <svg @click="updateAssignCrewStatus(crwAssign)" :class="{ 'text-green-900': crwAssign?.status === 'Onboard' }" xmlns="http://www.w3.org/2000/svg" class="icn dark:text-gray-600 dark:hover:text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="tooltiptext">{{ crwAssign?.status === 'Onboard' ? "Mark as Complete" : "Already Completed" }}</span>
                  </div>
                  <action-button :action="'show'" :to="{ name: 'crw.crewAssigns.show', params: { crewAssignId: crwAssign?.id } }"></action-button>
                  <action-button :action="'edit'" :to="{ name: 'crw.crewAssigns.edit', params: { crewAssignId: crwAssign?.id } }"></action-button>
                  <action-button @click="confirmDelete(crwAssign?.id)" :action="'delete'"></action-button>
                </nobr>
              </td>
            </tr>
            <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && crewAssigns?.data?.length"></LoaderComponent>
          </tbody>
          <tfoot v-if="!crewAssigns?.data?.length" class="relative h-[250px]">
            <tr v-if="isLoading">
              <td colspan="11"></td>
            </tr>
            <tr v-else-if="isTableLoading">
              <td colspan="11">
                <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>
              </td>
            </tr>
            <tr v-else-if="!crewAssigns?.data?.data?.length">
              <td colspan="11">No data found.</td>
            </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="crewAssigns" to="crw.crewAssigns.index" :page="page"></Paginate>
  </div>
  <div v-show="isCrewUpdateStatusModalOpen" class="fixed inset-0 z-30 flex items-end overflow-y-auto bg-black bg-opacity-50 sm:items-center sm:justify-center">
    <!-- Modal -->
    <form @submit.prevent="updateCrewAssignStatus(statusFormData,crewAssigns?.data)" style="position: absolute;top: 0;">
      <div class="w-full px-6 py-4 overflow-y-auto bg-white rounded-t-lg dark-disabled:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl" role="dialog" id="modal">
        <!-- Remove header if you don't want a close icon. Use modal body to place modal tile. -->
        <header class="flex justify-end">
          <button type="button"
                  class="inline-flex items-center justify-center w-6 h-6 mb-2 text-gray-400 transition-colors duration-150 rounded dark-disabled:hover:text-gray-200 hover: hover:text-gray-700"
                  aria-label="close" @click="closeCrewUpdateStatusModal">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" role="img" aria-hidden="true">
              <path
                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                  clip-rule="evenodd" fill-rule="evenodd"></path>
            </svg>
          </button>
        </header>
        <!-- Modal body -->
        <table class="w-full mb-2 whitespace-no-wrap border-collapse contract-assign-table table2">
          <thead>
          <tr style="background-color: #04AA6D;color: white" class="text-xs font-semibold tracking-wide text-gray-500 border-b dark-disabled:border-gray-700 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
            <th colspan="2">Update Assigned Crew Status</th>
          </tr>
          </thead>
        </table>
        <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
          <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Completion Info</legend>
          <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
            <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Status <span class="text-red-500">*</span></span>
              <input type="text" v-model.trim="statusFormData.status" placeholder="Status" class="form-input vms-readonly-input" autocomplete="off" readonly required />
            </label>
            <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Completion Date <span class="text-red-500">*</span></span>
              <input type="date" v-model.trim="statusFormData.completion_date" placeholder="Completion Date" class="form-input" autocomplete="off" required />
            </label>
          </div>
          <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
            <RemarksComponent v-model.trim="statusFormData.completion_remarks" :maxlength="500" :fieldLabel="'Completion Remarks'"></RemarksComponent>
          </div>
        </fieldset>
        <footer class="flex flex-col items-center justify-end px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark-disabled:bg-gray-800">
          <button type="button" @click="closeCrewUpdateStatusModal" style="color: #1b1e21"
                  class="w-full px-5 py-3 text-sm font-medium leading-5 text-white text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark-disabled:text-gray-400 sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray">
            Cancel
          </button>
          <button
              class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            Submit
          </button>
        </footer>
      </div>
    </form>
  </div>
</template>