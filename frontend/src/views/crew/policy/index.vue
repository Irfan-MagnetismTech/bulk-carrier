<script setup>
import {onMounted, ref, watch, watchEffect, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import usePolicy from "../../../composables/crew/usePolicy";
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import Store from "../../../store";
const icons = useHeroIcon();
import env from '../../../config/env';
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import useDebouncedRef from "../../../composables/useDebouncedRef";
import useGlobalFilter from "../../../composables/useGlobalFilter";
import {useRouter} from "vue-router";

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const router = useRouter();
const { policies, getPolicies, deletePolicy, isLoading, isTableLoading  } = usePolicy();
const { showFilter, swapFilter, setSortingState, clearFilter } = useGlobalFilter();
const { setTitle } = Title();
setTitle('Policy List');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);


function confirmDelete(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You want to change delete this policy!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then((result) => {
    if (result.isConfirmed) {
      deletePolicy(id);
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
			"relation_name": null,
			"field_name": "name",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},
      {
			"relation_name": null,
			"field_name": "type",
			"search_param": "",
			"action": null,
			"order_by": null,
			"date_from": null
			},
      {
			"relation_name": null,
			"field_name": "business_unit",
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
      router.push({ name: 'crw.policies.index', query: { page: filterOptions.value.page } });
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;

  if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }
  getPolicies(filterOptions.value)
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
    <h2 class="text-2xl font-semibold text-gray-700">Policy List</h2>
    <default-button :title="'Create Policy'" :to="{ name: 'crw.policies.create' }" :icon="icons.AddIcon"></default-button>
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
                  <span>Policy Name</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(0,'asc',filterOptions)" :class="{ 'text-gray-800': filterOptions.filter_options[0].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[0].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(0,'desc',filterOptions)" :class="{'text-gray-800' : filterOptions.filter_options[0].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[0].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span>Policy Type</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(1,'asc',filterOptions)" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(1,'desc',filterOptions)" :class="{'text-gray-800' : filterOptions.filter_options[1].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[1].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span>Business Unit</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(2,'asc',filterOptions)" :class="{ 'text-gray-800': filterOptions.filter_options[2].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[2].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(2,'desc',filterOptions)" :class="{'text-gray-800' : filterOptions.filter_options[2].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[2].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>Action</th>
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
              <th>
                <filter-with-business-unit v-model="filterOptions.business_unit"></filter-with-business-unit>
              </th>
              <th>
                <button title="Clear Filter" @click="clearFilter(filterOptions)" type="button" v-html="icons.NotFilterIcon"></button>
              </th>
              </tr>
          </thead>
          <tbody  class="relative">
          <tr v-for="(crwPolicy,index) in policies?.data" :key="index">
            <td>{{ (paginatedPage - 1) * filterOptions.items_per_page + index + 1 }}</td>
            <td class="text-left">{{ crwPolicy?.name }}</td>
            <td class="text-left">{{ crwPolicy?.type }}</td>
            <td>
              <span :class="crwPolicy?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ crwPolicy?.business_unit }}</span>
            </td>
            <td>
              <nobr>
                <a :href="env.BASE_API_URL+'/'+crwPolicy?.attachment" target="_blank" :class="{'custom_disabled' : !crwPolicy?.attachment}">
                  <div class="tooltip">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icn w-6 h-6 text-green-800 dark-disabled:text-green-800 dark-disabled:hover:text-green-800" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    <span class="tooltiptext">Download Attachment</span>
                  </div>
                </a>
                <action-button :action="'edit'" :to="{ name: 'crw.policies.edit', params: { policyId: crwPolicy?.id } }"></action-button>
                <action-button @click="confirmDelete(crwPolicy?.id)" :action="'delete'"></action-button>
              </nobr>
            </td>
          </tr>
          <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && policies?.data?.length"></LoaderComponent>
          </tbody>
        <tfoot v-if="!policies?.data?.length" class="relative h-[250px]">
        <tr v-if="isLoading">
          <td colspan="5"></td>
        </tr>
        <tr v-else-if="isTableLoading">
              <td colspan="5">
                <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
              </td>
            </tr>
        <tr v-else-if="!policies?.data?.data?.length">
          <td colspan="5">No data found.</td>
        </tr>
        </tfoot>
      </table>
    </div>
    <Paginate :data="policies" to="crw.policies.index" :page="page"></Paginate>
  </div>
</template>