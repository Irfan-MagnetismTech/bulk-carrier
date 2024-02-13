<script setup>
import {onMounted, ref, watch, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import useLighterNoonReport from '../../../composables/operations/useLighterNoonReport';
import Store from "../../../store";
import moment from 'moment';
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import useDebouncedRef from "../../../composables/useDebouncedRef";
import useGlobalFilter from "../../../composables/useGlobalFilter";
import { formatDateTime } from "../../../utils/helper";
import FilterComponent from "../../../components/utils/FilterComponent.vue";

const { lighterNoonReports, getLighterNoonReports, deleteLighterNoonReport, isLoading, isTableLoading} = useLighterNoonReport();
const { showFilter,  swapFilter, setSortingState, clearFilter } = useGlobalFilter()
const icons = useHeroIcon();
const debouncedValue = useDebouncedRef('', 800);

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { setTitle } = Title();
setTitle('Lighterage Noon Report List');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

let filterOptions = ref( {
"business_unit": businessUnit.value,
"items_per_page": 15,
"page": props.page,
"isFilter": false,
"filter_options": [
			{
			"relation_name": null,
			"field_name": "date",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Date",
      "filter_type": "dateTime"
			},
			{
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
			"relation_name": 'opsVoyage',
			"field_name": "voyage_sequence",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Voyage No.",
      "filter_type": "input"
			},
      {
			"relation_name": null,
			"field_name": "next_port",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Next Port Code",
      "filter_type": "input"
			},      

      {
			"relation_name": null,
			"field_name": "lat_long",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Lat/Long",
      "filter_type": "input"
			},
      {
			"relation_name": null,
			"field_name": "ship_master",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Ship Master",
      "filter_type": "input"
			}
	]
});

let stringifiedFilterOptions = JSON.stringify(filterOptions.value);


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
        deleteLighterNoonReport(id);
    }
  })
}


const currentPage = ref(1);
const paginatedPage = ref(1);

onMounted(() => {
  watchPostEffect(() => {
  
    if(currentPage.value == props.page && currentPage.value != 1) {
      filterOptions.value.page = 1;
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;

    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }

    getLighterNoonReports(filterOptions.value)
    .then(() => {      
      paginatedPage.value = filterOptions.value.page;
      const customDataTable = document.getElementById("customDataTable");

      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
    })
    .catch((error) => {
      console.error("Error fetching data.", error);
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
    <h2 class="text-2xl font-semibold text-gray-700">Lighterage Noon Report List</h2>
    <default-button :title="'Create Lighter Noon Report'" :to="{ name: 'ops.lighter-noon-reports.create' }" :icon="icons.AddIcon"></default-button>
  </div>

  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      
      <table class="w-full whitespace-no-wrap" >

          <FilterComponent :filterOptions = "filterOptions"/>
          <tbody v-if="lighterNoonReports?.data?.length" class="relative">
              <tr v-for="(lighterNoonReport, index) in lighterNoonReports.data" :key="lighterNoonReport?.id">
                  <td>{{ ((paginatedPage-1) * filterOptions.items_per_page) + index + 1 }}</td>
                  <td>
                    <nobr>{{ formatDateTime(lighterNoonReport?.date) }}</nobr>
                  </td>
                  <td>{{ lighterNoonReport?.opsVessel?.name }}</td>
                  <td>{{ lighterNoonReport?.opsVoyage?.voyage_sequence }}</td>
                  <td><nobr>{{ lighterNoonReport?.next_port }}</nobr></td>
                  <td><nobr>{{ lighterNoonReport?.lat_long }}</nobr></td>
                  <td><nobr>{{ lighterNoonReport?.ship_master }}{{ lighterNoonReport?.opsBunkers[0].fuel_con_24h }}</nobr></td>
                  <td>
                    <span :class="lighterNoonReport?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ lighterNoonReport?.business_unit }}</span>
                  </td>
                  <td class="items-center justify-center space-x-1 text-gray-600">
                      <nobr>
                        <action-button :action="'copy'" :to="{ name: 'ops.lighter-noon-reports.copy', params: { lighterNoonReportId: lighterNoonReport.id } }"></action-button>
                        <action-button :action="'show'" :to="{ name: 'ops.lighter-noon-reports.show', params: { lighterNoonReportId: lighterNoonReport.id } }"></action-button>
                        <action-button :action="'edit'" :to="{ name: 'ops.lighter-noon-reports.edit', params: { lighterNoonReportId: lighterNoonReport.id } }"></action-button>
                        <action-button @click="confirmDelete(lighterNoonReport.id)" :action="'delete'"></action-button>
                      </nobr>
                    <!-- <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: port.subject_type,subject_id: port.id } }"></action-button> -->
                  </td>
              </tr>
              <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && lighterNoonReports?.data?.length"></LoaderComponent>
          </tbody>
          
          <tfoot v-if="!lighterNoonReports?.data?.length" class="relative h-[250px]">
          <tr v-if="isLoading">
          </tr>
          <tr v-else-if="isTableLoading">
              <td colspan="8">
                <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
              </td>
          </tr>
          <tr v-else-if="!lighterNoonReports?.data?.length">
            <td colspan="8">No data found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="lighterNoonReports" to="ops.lighter-noon-reports.index" :page="page"></Paginate>
  </div>
</template>
