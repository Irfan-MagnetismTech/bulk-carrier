<script setup>
import {onMounted, ref, watchEffect, watch, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useIncidentRecord from "../../../composables/crew/useIncidentRecord";
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
import { formatDate,formatMonthYear,formatMonthYearWithTime } from "../../../utils/helper.js";
import FileExportButton from "../../../components/buttons/FileExportButton.vue";


const rightAlign = [];
const leftAlign = [1];
const router = useRouter();
const icons = useHeroIcon();
import env from '../../../config/env';

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { incidentRecords, getIncidentRecords, deleteIncidentRecord, isLoading, isTableLoading } = useIncidentRecord();
const debouncedValue = useDebouncedRef('', 800);
const { setTitle } = Title();
setTitle('Incident Record');
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

let filterOptions = ref( {
  "business_unit": businessUnit.value,
  "items_per_page": 15,
  "page": props.page,
  "isFilter": false,
  "filter_options": [
    {
      "relation_name": null,
      "field_name": "date_time",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Incident Date & Time",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "type",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Incident Type",
      "filter_type": "input"
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
      "relation_name": null,
      "field_name": "location",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Location",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "reported_by",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Reported Person",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "attachment",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Attachment",
      "filter_type": null
    },
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
      deleteIncidentRecord(id);
    }
  })
}

onMounted(() => {
  watchPostEffect(() => {
    if(currentPage.value == props.page && currentPage.value != 1) {
      filterOptions.value.page = 1;
      router.push({ name: 'crw.incidentRecords.index', query: { page: filterOptions.value.page } });
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;
    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }
    getIncidentRecords(filterOptions.value)
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
  <div class="flex items-center justify-between w-full my-3">
    <h2 class="text-2xl font-semibold text-gray-700">Incident Record List</h2>
    <div class="flex gap-2">
      <default-button :title="'Create Item'" :to="{ name: 'crw.incidentRecords.create' }" :icon="icons.AddIcon"></default-button>
      <file-export-button
          :businessUnit="businessUnit"
          :pageOrientation="'l'"
          :fileName="'Crew Incident List'"
          :tableId="'crew-incident-list'"
          :leftAlign="leftAlign"
          :rightAlign="rightAlign"
      ></file-export-button>
    </div>
  </div>
  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      <table class="w-full whitespace-no-wrap" id="crew-incident-list">
        <FilterComponent :filterOptions = "filterOptions"/>
          <tbody class="relative">
          <tr v-for="(crwIncidentRecord,index) in incidentRecords?.data" :key="index">
            <td>{{ index + 1 }}</td>
            <td>{{ formatMonthYearWithTime(crwIncidentRecord?.date_time) }}</td>
            <td class="!text-left">{{ crwIncidentRecord?.type }}</td>
            <td class="text-left">{{ crwIncidentRecord?.opsVessel?.name }}</td>
            <td class="!text-left">{{ crwIncidentRecord?.location }}</td>
            <td class="!text-left">{{ crwIncidentRecord?.reported_by }}</td>
            <td>
              <a v-html="icons.Attachment" type="button" v-if="typeof crwIncidentRecord?.attachment === 'string'" class="text-green-800" target="_blank" :href="env.BASE_API_URL+'/'+crwIncidentRecord?.attachment"></a>
              <a v-else>---</a>
            </td>
            <td>
              <span :class="crwIncidentRecord?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ crwIncidentRecord?.business_unit }}</span>
            </td>
            <td>
              <nobr>
                <action-button :action="'show'" :to="{ name: 'crw.incidentRecords.show', params: { incidentRecordId: crwIncidentRecord?.id } }"></action-button>
                <action-button :action="'edit'" :to="{ name: 'crw.incidentRecords.edit', params: { incidentRecordId: crwIncidentRecord?.id } }"></action-button>
                <action-button @click="confirmDelete(crwIncidentRecord?.id)" :action="'delete'"></action-button>
              </nobr>
            </td>
          </tr>
          <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && incidentRecords?.data?.length"></LoaderComponent>
          </tbody>
        <tfoot v-if="!incidentRecords?.data?.length" class="relative h-[250px]">
        <tr v-if="isLoading">
          <td colspan="9"></td>
        </tr>
        <tr v-else-if="isTableLoading">
          <td colspan="9">
            <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>
          </td>
        </tr>
        <tr v-else-if="!incidentRecords?.data?.length">
          <td colspan="9">No data found.</td>
        </tr>
        </tfoot>
      </table>
    </div>
    <Paginate :data="incidentRecords" to="crw.incidentRecords.index" :page="page"></Paginate>
  </div>
</template>