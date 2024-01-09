<script setup>
import {onMounted, ref, watchEffect, watch, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useSurveyEntry from "../../../composables/maintenance/useSurveyEntry";
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import Store from './../../../store/index.js';
import {useRouter} from "vue-router/dist/vue-router";
import useDebouncedRef from "../../../composables/useDebouncedRef";
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import ErrorComponent from "../../../components/utils/ErrorComponent.vue";
import FilterComponent from "../../../components/utils/FilterComponent.vue";
import moment from "moment";
import { formatDate } from "../../../utils/helper";
const router = useRouter();
const debouncedValue = useDebouncedRef('', 800);

const icons = useHeroIcon();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { surveyEntries, getSurveyEntries, deleteSurveyEntry, isLoading, isTableLoading, errors } = useSurveyEntry();
const { setTitle } = Title();
setTitle('Survey Entry List');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
const defaultBusinessUnit = ref(Store.getters.getCurrentUser.business_unit);

function confirmDelete(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You want to delete this survey entry!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then((result) => {
    if (result.isConfirmed) {
      deleteSurveyEntry(id);
    }
  })
}

let filterOptions = ref( {
  "business_unit": businessUnit.value,
  "items_per_page": 15,
  "page": props.page,
  "isFilter": false,
  "filter_options": [
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
      "relation_name": "mntSurvey.mntSurveyItem",
      "field_name": "item_name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Survey Item",
      "filter_type": "input"
    },  

    // {
    //   "rel_type": null,
    //   "relation_name": null,
    //   "field_name": "short_code",
    //   "search_param": "",
    //   "action": null,
    //   "order_by": null,
    //   "date_from": null,
    //   "label": "Short Code",
    //   "filter_type": "input"
    // },
    
    {
      "rel_type": null,
      "relation_name": "mntSurvey",
      "field_name": "survey_name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Survey Name",
      "filter_type": "input"
    },
    
    // {
    //   "rel_type": null,
    //   "relation_name": null,
    //   "field_name": "range_date_from",
    //   "search_param": "",
    //   "action": null,
    //   "order_by": null,
    //   "date_from": null,
    //   "label": "Range Date (From)",
    //   "filter_type": "date"
    // },

    
    // {
    //   "rel_type": null,
    //   "relation_name": null,
    //   "field_name": "range_date_to",
    //   "search_param": "",
    //   "action": null,
    //   "order_by": null,
    //   "date_from": null,
    //   "label": "Range Date (To)",
    //   "filter_type": "date"
    // },

    
    {
      "rel_type": null,
      "relation_name": null,
      "field_name": "assigned_date",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Assigned Date",
      "filter_type": "date"
    },

    
    {
      "rel_type": null,
      "relation_name": null,
      "field_name": "due_date",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Due Date",
      "filter_type": "date"
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
      router.push({ name: 'mnt.survey-entries.index', query: { page: filterOptions.value.page } });
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;
    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }
    getSurveyEntries(filterOptions.value)
    .then(() => {
      paginatedPage.value = filterOptions.value.page;
      const customDataTable = document.getElementById("customDataTable");

      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
    })
    .catch((error) => {
      console.error("Error fetching survey entries:", error);
    });
});

});

</script>

<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Survey Entry List</h2>
    <default-button :title="'Create Survey'" :to="{ name: 'mnt.survey-entries.create' }" :icon="icons.AddIcon"></default-button>
  </div>
  
  <div id="customDataTable">

    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      
      <table class="w-full whitespace-no-wrap" >
          
          <FilterComponent :filterOptions = "filterOptions"/>
          <tbody class="relative">
          <tr v-for="(surveyEntry,index) in surveyEntries?.data" :key="index">
            <td>{{ ((paginatedPage-1) * filterOptions.items_per_page) + index + 1 }}</td>
            <td>{{ surveyEntry?.opsVessel?.name }}</td>
            <td>{{ surveyEntry?.mntSurvey?.mntSurveyItem?.item_name }}</td>
            <td>{{ surveyEntry?.mntSurvey?.survey_name }}</td>
            <!-- <td><nobr>{{  formatDate(surveyEntry?.range_date_from) }}</nobr></td>
            <td><nobr>{{  formatDate(surveyEntry?.range_date_to) }}</nobr></td> -->
            <td><nobr>{{  formatDate(surveyEntry?.assigned_date) }}</nobr></td>
            <td><nobr>{{  formatDate(surveyEntry?.due_date) }}</nobr></td>
  
            <td><span :class="surveyEntry?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ surveyEntry?.business_unit }}</span></td>
            <td>
              <nobr>
                <action-button :action="'show'" :to="{ name: 'mnt.survey-entries.show', params: { surveyEntryId: surveyEntry?.id } }"></action-button>
                <action-button v-show="surveyEntry?.entry_status == 'latest'" :action="'edit'" :to="{ name: 'mnt.survey-entries.edit', params: { surveyEntryId: surveyEntry?.id } }"></action-button>
                <action-button @click="confirmDelete(surveyEntry?.id)" :action="'delete'"></action-button>
              </nobr>
            </td>
          </tr>
          <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && surveyEntries?.data?.length"></LoaderComponent>
          </tbody>
          <tfoot v-if="!surveyEntries?.data?.length" class="relative h-[250px]">
          <tr v-if="isLoading">
            <td colspan="10">Loading...</td>
          </tr>
          <tr v-else-if="isTableLoading">
              <td colspan="10">
                <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
              </td>
            </tr>
          <tr v-else-if="!surveyEntries?.data?.length">
            <td colspan="10">No survey entry found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="surveyEntries" to="mnt.survey-entries.index" :page="page"></Paginate>
  </div>
  <ErrorComponent :errors="errors"></ErrorComponent>
</template>