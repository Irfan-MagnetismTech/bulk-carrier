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
setTitle('Lighter Noon Report List');

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
			"date_from": null
			},
			{
			"relation_name": 'opsVessel',
			"field_name": "name",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},
			{
			"relation_name": 'opsVoyage',
			"field_name": "voyage_sequence",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},
      {
			"relation_name": null,
			"field_name": "next_port",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},      

      {
			"relation_name": null,
			"field_name": "lat_long",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},
      {
			"relation_name": null,
			"field_name": "ship_master",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
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
    <h2 class="text-2xl font-semibold text-gray-700">Lighter Noon Report List</h2>
    <default-button :title="'Create Lighter Noon Report'" :to="{ name: 'ops.lighter-noon-reports.create' }" :icon="icons.AddIcon"></default-button>
  </div>

  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      
      <table class="w-full whitespace-no-wrap" >

          <thead>
            <tr class="w-full">
              <th class="w-16">
                  <div class="w-full flex items-center justify-between">
                    # <button @click="swapFilter()" type="button" v-html="icons.FilterIcon"></button>
                  </div>
                </th>
              <th>
                <div class="flex justify-evenly items-center">
                    <nobr>Date and Time</nobr>
                    <div class="flex flex-col cursor-pointer">
                      <div v-html="icons.descIcon" @click="setSortingState(0,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[0].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[0].order_by !== 'asc' }" class=" font-semibold"></div>
                      <div v-html="icons.ascIcon" @click="setSortingState(0,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[0].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[0].order_by !== 'desc' }" class=" font-semibold"></div>
                    </div>
                  </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                    <nobr>Vessel</nobr>
                    <div class="flex flex-col cursor-pointer">
                      <div v-html="icons.descIcon" @click="setSortingState(1,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'asc' }" class=" font-semibold"></div>
                      <div v-html="icons.ascIcon" @click="setSortingState(1,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[1].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[1].order_by !== 'desc' }" class=" font-semibold"></div>
                    </div>
                  </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                    <nobr>Voyage</nobr>
                    <div class="flex flex-col cursor-pointer">
                      <div v-html="icons.descIcon" @click="setSortingState(2,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[2].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[2].order_by !== 'asc' }" class=" font-semibold"></div>
                      <div v-html="icons.ascIcon" @click="setSortingState(2,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[2].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[2].order_by !== 'desc' }" class=" font-semibold"></div>
                    </div>
                  </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                    <nobr>Next Port</nobr>
                    <div class="flex flex-col cursor-pointer">
                      <div v-html="icons.descIcon" @click="setSortingState(3,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[3].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[3].order_by !== 'asc' }" class=" font-semibold"></div>
                      <div v-html="icons.ascIcon" @click="setSortingState(3,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[3].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[3].order_by !== 'desc' }" class=" font-semibold"></div>
                    </div>
                  </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                    <nobr>Lat/Long</nobr>
                    <div class="flex flex-col cursor-pointer">
                      <div v-html="icons.descIcon" @click="setSortingState(4,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[4].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[4].order_by !== 'asc' }" class=" font-semibold"></div>
                      <div v-html="icons.ascIcon" @click="setSortingState(4,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[4].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[4].order_by !== 'desc' }" class=" font-semibold"></div>
                    </div>
                  </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                    <nobr>Master</nobr>
                    <div class="flex flex-col cursor-pointer">
                      <div v-html="icons.descIcon" @click="setSortingState(5,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[5].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[5].order_by !== 'asc' }" class=" font-semibold"></div>
                      <div v-html="icons.ascIcon" @click="setSortingState(5,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[5].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[5].order_by !== 'desc' }" class=" font-semibold"></div>
                    </div>
                  </div>
              </th>
             
             
              <th>
                Action
              </th>
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
              <th><input v-model.trim="filterOptions.filter_options[0].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th><input v-model.trim="filterOptions.filter_options[1].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th><input v-model.trim="filterOptions.filter_options[2].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th><input v-model.trim="filterOptions.filter_options[3].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th><input v-model.trim="filterOptions.filter_options[4].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th><input v-model.trim="filterOptions.filter_options[5].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              
              

              <th>
                <button title="Clear Filter" @click="clearFilter(filterOptions)" type="button" v-html="icons.NotFilterIcon"></button>
              </th>
            </tr>
          </thead>
          <tbody v-if="lighterNoonReports?.data?.length" class="relative">
              <tr v-for="(lighterNoonReport, index) in lighterNoonReports.data" :key="lighterNoonReport?.id">
                  <td>{{ ((paginatedPage-1) * filterOptions.items_per_page) + index + 1 }}</td>
                  <td>
                    <nobr>{{ lighterNoonReport?.date ? moment(lighterNoonReport?.date).format('MM-DD-YYYY hh:mm A') : null }}</nobr>
                  </td>
                  <td>{{ lighterNoonReport?.opsVessel?.name }}</td>
                  <td>{{ lighterNoonReport?.opsVoyage?.voyage_sequence }}</td>
                  <td><nobr>{{ lighterNoonReport?.next_port }}</nobr></td>
                  <td><nobr>{{ lighterNoonReport?.lat_long }}</nobr></td>
                  <td><nobr>{{ lighterNoonReport?.ship_master }}</nobr></td>
                  <!-- <td>
                    <span :class="lighterNoonReport?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ lighterNoonReport?.business_unit }}</span>
                  </td> -->
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
            <td colspan="8">Loading...</td>
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
