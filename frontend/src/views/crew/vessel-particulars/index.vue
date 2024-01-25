<script setup>
import {onMounted, ref, watch, watchEffect, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useVesselParticular from "../../../composables/operations/useVesselParticular";
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import Store from './../../../store/index.js';
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import useDebouncedRef from "../../../composables/useDebouncedRef";
import useGlobalFilter from "../../../composables/useGlobalFilter";
import {useRouter} from "vue-router";

const icons = useHeroIcon();
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const router = useRouter();
const { vesselParticulars, getVesselParticulars, downloadGeneralParticular, downloadChartererParticular, isLoading, isTableLoading  } = useVesselParticular();
const { showFilter, swapFilter, setSortingState, clearFilter } = useGlobalFilter();
const { setTitle } = Title();
setTitle('Vessel Particulars');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;

function dlGeneralParticular(vesselName, vesselParticularId) {
  downloadGeneralParticular(vesselName, vesselParticularId)
}

function dlChartererParticular(vesselName, vesselParticularId) {
  downloadChartererParticular(vesselName, vesselParticularId)
}

let filterOptions = ref( {
"business_unit": businessUnit.value,
"items_per_page": 15,
"page": props.page,
"isFilter": false,
"filter_options": [
			{
			"relation_name": 'opsVessel',
			"field_name": "name",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},
      {
			"relation_name": null,
			"field_name": "imo",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},
      {
			"relation_name": null,
			"field_name": "class_no",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},
      {
			"relation_name": null,
			"field_name": "official_number",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},
      {
			"relation_name": null,
			"field_name": "call_sign",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},
      {
			"relation_name": null,
			"field_name": "overall_length",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},
      {
			"relation_name": null,
			"field_name": "loa",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},
      {
			"relation_name": null,
			"field_name": "depth",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},
      {
			"relation_name": null,
			"field_name": "grt",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},
      {
			"relation_name": null,
			"field_name": "nrt",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
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
      router.push({ name: 'crw.vesselParticulars.index', query: { page: filterOptions.value.page } });
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;
    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }

  getVesselParticulars(filterOptions.value)
    .then(() => {
      paginatedPage.value = filterOptions.value.page;
      const customDataTable = document.getElementById("customDataTable");

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
    <h2 class="text-2xl font-semibold text-gray-700">Vessel Particulars List</h2>
  </div>

  <div id="customDataTable" class="mb-6">
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
                  <span>Vessel Name</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(0,'asc',filterOptions)" :class="{ 'text-gray-800': filterOptions.filter_options[0].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[0].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(0,'desc',filterOptions)" :class="{'text-gray-800' : filterOptions.filter_options[0].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[0].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span>IMO</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(1,'asc',filterOptions)" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(1,'desc',filterOptions)" :class="{'text-gray-800' : filterOptions.filter_options[1].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[1].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span>Class No</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(2,'asc',filterOptions)" :class="{ 'text-gray-800': filterOptions.filter_options[2].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[2].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(2,'desc',filterOptions)" :class="{'text-gray-800' : filterOptions.filter_options[2].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[2].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span>Official No.</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(3,'asc',filterOptions)" :class="{ 'text-gray-800': filterOptions.filter_options[3].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[3].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(3,'desc',filterOptions)" :class="{'text-gray-800' : filterOptions.filter_options[3].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[3].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span>Call Sign</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(4,'asc',filterOptions)" :class="{ 'text-gray-800': filterOptions.filter_options[4].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[4].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(4,'desc',filterOptions)" :class="{'text-gray-800' : filterOptions.filter_options[4].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[4].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span>Length(LBP)</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(5,'asc',filterOptions)" :class="{ 'text-gray-800': filterOptions.filter_options[5].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[5].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(5,'desc',filterOptions)" :class="{'text-gray-800' : filterOptions.filter_options[5].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[5].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span>LOA</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(6,'asc',filterOptions)" :class="{ 'text-gray-800': filterOptions.filter_options[6].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[6].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(6,'desc',filterOptions)" :class="{'text-gray-800' : filterOptions.filter_options[6].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[6].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span>Breath</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(7,'asc',filterOptions)" :class="{ 'text-gray-800': filterOptions.filter_options[7].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[7].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(7,'desc',filterOptions)" :class="{'text-gray-800' : filterOptions.filter_options[7].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[7].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span>GRT</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(8,'asc',filterOptions)" :class="{ 'text-gray-800': filterOptions.filter_options[8].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[8].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(8,'desc',filterOptions)" :class="{'text-gray-800' : filterOptions.filter_options[8].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[8].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span>NRT</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(9,'asc',filterOptions)" :class="{ 'text-gray-800': filterOptions.filter_options[9].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[9].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(9,'desc',filterOptions)" :class="{'text-gray-800' : filterOptions.filter_options[9].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[9].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>Download</th>
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
              <th><input v-model="filterOptions.filter_options[0].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th><input v-model="filterOptions.filter_options[1].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th><input v-model="filterOptions.filter_options[2].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th><input v-model="filterOptions.filter_options[3].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th><input v-model="filterOptions.filter_options[4].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th><input v-model="filterOptions.filter_options[5].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th><input v-model="filterOptions.filter_options[6].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th><input v-model="filterOptions.filter_options[7].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th><input v-model="filterOptions.filter_options[8].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th><input v-model="filterOptions.filter_options[9].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th>
                <button title="Clear Filter" @click="clearFilter(filterOptions)" type="button" v-html="icons.NotFilterIcon"></button>
              </th>
            </tr>
          </thead>
          <tbody v-if="vesselParticulars?.data?.length"  class="relative">
              <tr v-for="(vesselParticular, index) in vesselParticulars.data" :key="vesselParticular?.id">
                  <td>{{ (paginatedPage - 1) * filterOptions.items_per_page + index + 1 }}</td>
                  <td>{{ vesselParticular?.opsVessel?.name }}</td>
                  <td>{{ vesselParticular?.imo }}</td>
                  <td>{{ vesselParticular?.class_no }}</td>
                  <td>{{ vesselParticular?.official_number }}</td>
                  <td>{{ vesselParticular?.call_sign }}</td>
                  <td>{{ vesselParticular?.overall_length }}</td>
                  <td>{{ vesselParticular?.loa }}</td>
                  <td>{{ vesselParticular?.depth }}</td>
                  <td>{{ vesselParticular?.grt }}</td>
                  <td>{{ vesselParticular?.nrt }}</td>
                  <td class="flex border-b-0 border-l-0 items-center justify-center space-x-2 text-gray-600 ">
                      <button title="General" @click="dlGeneralParticular(vesselParticular?.opsVessel?.name, vesselParticular.id)" class="flex bg-blue-500 hover:bg-blue-700 duration-150 text-white p-1 text-xs rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                      </button>
                      <button title="Charterer" v-if="vesselParticular.attachment" @click="dlChartererParticular(vesselParticular?.opsVessel?.name, vesselParticular.id)" class="flex bg-blue-500 hover:bg-blue-700 duration-150 text-white p-1 text-xs rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9 13.5 3 3m0 0 3-3m-3 3v-6m1.06-4.19-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                        </svg>
                      </button>
                      <button v-else title="Charterer" class="flex bg-red-600 duration-150 text-white p-1 text-xs rounded-md cursor-not-allowed opacity-70">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9 13.5 3 3m0 0 3-3m-3 3v-6m1.06-4.19-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                          </svg>
                      </button>
                </td>
                </tr>
                <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && vesselParticulars?.data?.length"></LoaderComponent>
          </tbody>
          <tfoot v-if="!vesselParticulars?.data?.length"  class="relative h-[250px]">
            <tr v-if="isLoading">
              <td colspan="14"></td>
            </tr>
            <tr v-else-if="isTableLoading">
              <td colspan="14">
                <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
              </td>
            </tr>
            <tr v-else-if="!vesselParticulars?.data?.length">
              <td colspan="14">No data found.</td>
            </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="vesselParticulars" to="crw.vesselParticulars.index" :page="page"></Paginate>
  </div>
</template>