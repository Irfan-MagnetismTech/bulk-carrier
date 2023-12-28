<script setup>
import {onMounted, ref, watchEffect, watch, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useSurvey from "../../../composables/maintenance/useSurvey";
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import Store from './../../../store/index.js';
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import {useRouter} from "vue-router/dist/vue-router";
import useDebouncedRef from "../../../composables/useDebouncedRef";
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import ErrorComponent from "../../../components/utils/ErrorComponent.vue";
import FilterComponent from "../../../components/utils/FilterComponent.vue";
import moment from "moment";
const router = useRouter();
const debouncedValue = useDebouncedRef('', 800);

const icons = useHeroIcon();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { surveys, getSurveys, deleteSurvey, isLoading, isTableLoading, errors } = useSurvey();
const { setTitle } = Title();
setTitle('Survey List');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
const defaultBusinessUnit = ref(Store.getters.getCurrentUser.business_unit);

function confirmDelete(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You want to delete this survey!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then((result) => {
    if (result.isConfirmed) {
      deleteSurvey(id);
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
      "relation_name": "mntSurveyItem",
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
      "relation_name": null,
      "field_name": "survey_name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Survey Name",
      "filter_type": "input"
    },
    
    {
      "rel_type": null,
      "relation_name": null,
      "field_name": "range_date_from",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Range Date (From)",
      "filter_type": "date"
    },

    
    {
      "rel_type": null,
      "relation_name": null,
      "field_name": "range_date_to",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Range Date (To)",
      "filter_type": "date"
    },

    
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
      router.push({ name: 'mnt.surveys.index', query: { page: filterOptions.value.page } });
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;
    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }
  getSurveys(filterOptions.value)
    .then(() => {
      paginatedPage.value = filterOptions.value.page;
      const customDataTable = document.getElementById("customDataTable");

      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
    })
    .catch((error) => {
      console.error("Error fetching surveys:", error);
    });
});

});

</script>

<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Survey List</h2>
    <default-button :title="'Create Survey'" :to="{ name: 'mnt.surveys.create' }" :icon="icons.AddIcon"></default-button>
  </div>
  
  <div id="customDataTable">

    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      
      <table class="w-full whitespace-no-wrap" >
          <!-- <thead v-once>
          <tr class="w-full">
            <th class="w-1/12">#</th>
            <th class="w-2/12">Vessel</th>
            <th class="w-2/12">Cri. Function</th>
            <th class="w-2/12">Cri. Category</th>
            <th class="w-2/12">Cri. Item</th>
            <th class="w-2/12">Business Unit</th>
            <th class="w-1/12">Action</th>
          </tr>
          </thead> -->
          <FilterComponent :filterOptions = "filterOptions"/>
          <tbody class="relative">
          <tr v-for="(survey,index) in surveys?.data" :key="index">
            <td>{{ ((paginatedPage-1) * filterOptions.items_per_page) + index + 1 }}</td>
            <td>{{ survey?.opsVessel?.name }}</td>
            <td>{{ survey?.mntSurveyItem?.item_name }}</td>
            <!-- <td>{{ survey?.mntSurveyType?.survey_type_name }}</td> -->
            <!-- <td>{{ survey?.short_code }}</td> -->
            <td>{{ survey?.survey_name }}</td>
            <!-- <td><nobr>{{ survey?.range_date_from }}</nobr></td> -->
            <td><nobr>{{  moment(survey?.range_date_from).format('DD/MM/YYYY') }}</nobr></td>
            <!-- <td><nobr>{{ survey?.range_date_to }}</nobr></td> -->
            <td><nobr>{{  moment(survey?.range_date_to).format('DD/MM/YYYY') }}</nobr></td>
            <!-- <td><nobr>{{ survey?.assigned_date }}</nobr></td> -->
            <td><nobr>{{  moment(survey?.assigned_date).format('DD/MM/YYYY') }}</nobr></td>
            <!-- <td><nobr>{{ survey?.due_date }}</nobr></td> -->
            <td><nobr>{{  moment(survey?.due_date).format('DD/MM/YYYY') }}</nobr></td>
  
            <td><span :class="survey?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ survey?.business_unit }}</span></td>
            <td>
              <nobr>
                <action-button :action="'edit'" :to="{ name: 'mnt.surveys.edit', params: { surveyId: survey?.id } }"></action-button>
                <action-button @click="confirmDelete(survey?.id)" :action="'delete'"></action-button>
              </nobr>
            </td>
          </tr>
          <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && surveys?.data?.length"></LoaderComponent>
          </tbody>
          <tfoot v-if="!surveys?.data?.length" class="relative h-[250px]">
          <tr v-if="isLoading">
            <td colspan="7">Loading...</td>
          </tr>
          <tr v-else-if="isTableLoading">
              <td colspan="7">
                <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
              </td>
            </tr>
          <tr v-else-if="!surveys?.data?.length">
            <td colspan="7">No survey found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="surveys" to="mnt.surveys.index" :page="page"></Paginate>
  </div>
  <ErrorComponent :errors="errors"></ErrorComponent>
</template>