<script setup>
import {onMounted, ref, watchEffect, watch, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useRestHourRecord from "../../../composables/crew/useRestHourRecord";
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
import { formatDate } from "../../../utils/helper.js";
const router = useRouter();
const debouncedValue = useDebouncedRef('', 800);

const icons = useHeroIcon();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { restHourRecords, getRestHourRecords, deleteRestHourRecord, isLoading, isTableLoading, errors } = useRestHourRecord();
const { setTitle } = Title();
setTitle('Rest Hour Record List');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
const defaultBusinessUnit = ref(Store.getters.getCurrentUser.business_unit);

function confirmDelete(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You want to delete this appraisal record!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then((result) => {
    if (result.isConfirmed) {
      deleteRestHourRecord(id);
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
      "relation_name": null,
      "field_name": "record_date",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Record Date",
      "filter_type": "input"
    },
    {
      "rel_type": null,
      "relation_name": 'opsVessel',
      "field_name": "name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Vessel Name",
      "filter_type": "input"
    },
    {
      "rel_type": null,
      "relation_name": null,
      "field_name": null,
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Total Crew",
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
      router.push({ name: 'crw.rest-hour-records.index', query: { page: filterOptions.value.page } });
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;
    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }
  getRestHourRecords(filterOptions.value)
    .then(() => {
      paginatedPage.value = filterOptions.value.page;
      const customDataTable = document.getElementById("customDataTable");

      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
    })
    .catch((error) => {
      console.error("Error fetching appraisal records:", error);
    });
  });
});

</script>

<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Rest Hour Record List</h2>
    <default-button :title="'Create Rest Hour Record'" :to="{ name: 'crw.rest-hour-records.create' }" :icon="icons.AddIcon"></default-button>
  </div>
  <div class="flex items-center justify-between mb-2 select-none"></div>
  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      
      <table class="w-full whitespace-no-wrap" >
          
          <FilterComponent :filterOptions = "filterOptions"/>
          <tbody class="relative">

          <tr v-for="(restHourRecordData,index) in restHourRecords?.data" :key="index">
            <td>{{ ((paginatedPage-1) * filterOptions.items_per_page) + index + 1 }}</td>
            <td>{{ formatDate(restHourRecordData?.record_date) }}</td>
            <td class="text-left">{{ restHourRecordData?.opsVessel?.name }}</td>
            <td>{{ restHourRecordData?.crwRestHourEntryLines?.length }}</td>
            <td><span :class="restHourRecordData?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ restHourRecordData?.business_unit }}</span></td>
            <td>
              <nobr>
                <action-button :action="'show'" :to="{ name: 'crw.rest-hour-records.show', params: { restHourRecordId: restHourRecordData?.id } }"></action-button>
                <action-button :action="'edit'" :to="{ name: 'crw.rest-hour-records.edit', params: { restHourRecordId: restHourRecordData?.id } }"></action-button>
                <action-button @click="confirmDelete(restHourRecordData?.id)" :action="'delete'"></action-button>
              </nobr>
            </td>
          </tr>
          <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && restHourRecords?.data?.length"></LoaderComponent>
          </tbody>
          <tfoot v-if="!restHourRecords?.data?.length" class="relative h-[250px]">
          <tr v-if="isLoading">
            <td colspan="6">Loading...</td>
          </tr>
          <tr v-else-if="isTableLoading">
              <td colspan="6">
                <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
              </td>
            </tr>
          <tr v-else-if="!restHourRecords?.data?.length">
            <td colspan="6">No data found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="restHourRecords" to="crw.rest-hour-records.index" :page="page"></Paginate>
  </div>
  <ErrorComponent :errors="errors"></ErrorComponent>
</template>