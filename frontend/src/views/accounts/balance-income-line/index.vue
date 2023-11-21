<script setup>
import {onMounted, ref, watch, watchEffect, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useBalanceIncomeLine from "../../../composables/accounts/useBalanceIncomeLine";
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
const router = useRouter();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});
const icons = useHeroIcon();
const { balanceIncomeLines, getBalanceIncomeLines, deleteBalanceIncomeLine, isLoading, isTableLoading   } = useBalanceIncomeLine();
const { setTitle } = Title();
setTitle('Balance Income List');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

function confirmDelete(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You want to delete this item!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then((result) => {
    if (result.isConfirmed) {
      deleteBalanceIncomeLine(id);
    }
  })
}

let showFilter = ref(false);
function swapFilter() {
  showFilter.value = !showFilter.value;
}
let filterOptions = ref( {
  "business_unit": businessUnit.value,
  "items_per_page": 15,
  "page": props.page,
  "isFilter": false,
  "filter_options": [
    {
      "relation_name": "parentLine",
      "field_name": "line_text",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    },
    {
      "relation_name": null,
      "field_name": "line_text",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    },
    {
      "relation_name": null,
      "field_name": "value_type",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    },
    {
      "relation_name": null,
      "field_name": "line_type",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    },
  ]
});

let stringifiedFilterOptions = JSON.stringify(filterOptions.value);

function setSortingState(index, order) {
  filterOptions.value.filter_options.forEach(function (t) {
    t.order_by = null;
  });
  filterOptions.value.filter_options[index].order_by = order;
}

function clearFilter(){
  filterOptions.value.filter_options.forEach((option, index) => {
    filterOptions.value.filter_options[index].search_param = "";
    filterOptions.value.filter_options[index].order_by = null;
  });
}

const currentPage = ref(1);
const paginatedPage = ref(1);
onMounted(() => {
  watchPostEffect(() => {
  // filterOptions.value.page = props.page;
  if(currentPage.value == props.page && currentPage.value != 1) {
      filterOptions.value.page = 1;
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;
    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }
  getBalanceIncomeLines(filterOptions.value)
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
  <div class="flex items-center justify-between w-full my-3">
    <h2 class="text-2xl font-semibold text-gray-700">Balance Income List</h2>
    <default-button :title="'Create Balance Income Line'" :to="{ name: 'acc.balance-income-lines.create' }" :icon="icons.AddIcon"></default-button>
  </div>
<!--  <div class="flex items-center justify-between mb-2 select-none">-->
<!--    <filter-with-business-unit v-model="businessUnit"></filter-with-business-unit>-->
<!--    &lt;!&ndash; Search &ndash;&gt;-->
<!--    <div class="relative w-full">-->
<!--      <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-0 w-5 h-5 mr-2 text-gray-500 bottom-2" viewBox="0 0 20 20" fill="currentColor">-->
<!--        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />-->
<!--      </svg>-->
<!--      <input type="text" placeholder="Search..." class="search" />-->
<!--    </div>-->
<!--  </div>-->

  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      
      <table class="w-full whitespace-no-wrap">
          <thead>
          <tr class="w-full">
            <th class="w-16">
              <div class="w-full flex items-center justify-between">
                # <button @click="swapFilter()" type="button" v-html="icons.FilterIcon"></button>
              </div>
            </th>
            <th>
              <div class="flex justify-evenly items-center">
                <span>Parent Line</span>
                <div class="flex flex-col cursor-pointer">
                  <div v-html="icons.descIcon" @click="setSortingState(0,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[0].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[0].order_by !== 'asc' }" class=" font-semibold"></div>
                  <div v-html="icons.ascIcon" @click="setSortingState(0,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[0].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[0].order_by !== 'desc' }" class=" font-semibold"></div>
                </div>
              </div>
            </th>            
            <th>
              <div class="flex justify-evenly items-center">
                <span>Line Name</span>
                <div class="flex flex-col cursor-pointer">
                  <div v-html="icons.descIcon" @click="setSortingState(1,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'asc' }" class=" font-semibold"></div>
                  <div v-html="icons.ascIcon" @click="setSortingState(1,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[1].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[1].order_by !== 'desc' }" class=" font-semibold"></div>
                </div>
              </div>
            </th>
            <th>
              <div class="flex justify-evenly items-center">
              <span><nobr>Value Type</nobr></span>
              <div class="flex flex-col cursor-pointer">
                <div v-html="icons.descIcon" @click="setSortingState(2,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[2].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[2].order_by !== 'asc' }" class=" font-semibold"></div>
                <div v-html="icons.ascIcon" @click="setSortingState(2,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[2].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[2].order_by !== 'desc' }" class=" font-semibold"></div>
              </div>
              </div>
            </th>
            <th>
              <div class="flex justify-evenly items-center">
              <span>Line Type</span>
              <div class="flex flex-col cursor-pointer">
                <div v-html="icons.descIcon" @click="setSortingState(3,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[3].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[3].order_by !== 'asc' }" class=" font-semibold"></div>
                <div v-html="icons.ascIcon" @click="setSortingState(3,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[3].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[3].order_by !== 'desc' }" class=" font-semibold"></div>
              </div>
              </div>
            </th>
            <th>
              <div class="flex justify-evenly items-center">
                <span><nobr>Business Unit</nobr></span>
              </div>
            </th>
            <th class="w-20 min-w-full">Action</th>
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
              <select class="filter_input" v-model="filterOptions.filter_options[2].search_param" autocomplete="off">
                <option value="" disabled selected>Select</option>
                <option value="D">Debit</option>
                <option value="C">Credit</option>
              </select>
            </th>
            <th><input v-model="filterOptions.filter_options[3].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
            <th>
              <filter-with-business-unit v-model="filterOptions.business_unit"></filter-with-business-unit>
            </th>
            <th>
              <button title="Clear Filter" @click="clearFilter()" type="button" v-html="icons.NotFilterIcon"></button>
            </th>
          </tr>
          </thead>
          <tbody class="relative">
          <tr v-for="(incomeLine,index) in balanceIncomeLines?.data" :key="index">
            <td>{{ (paginatedPage  - 1) * filterOptions.items_per_page + index + 1 }}</td>
            <td class="text-left">{{ incomeLine?.parentLine?.line_text ?? '---' }}</td>
            <td class="text-left">{{ incomeLine?.line_text }}</td>
            <td>{{ incomeLine?.value_type === 'D' ? 'Debit' : 'Credit' }}</td>
            <td>{{ incomeLine?.line_type }}</td>
            <td>
              <span :class="incomeLine?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ incomeLine?.business_unit }}</span>
            </td>
            <td>
              <action-button :action="'edit'" :to="{ name: 'acc.balance-income-lines.edit', params: { balanceIncomeLineId: incomeLine?.id } }"></action-button>
              <action-button @click="confirmDelete(incomeLine?.id)" :action="'delete'"></action-button>
            </td>
          </tr>
          <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && balanceIncomeLines?.data?.length"></LoaderComponent>
          </tbody>
          <tfoot v-if="!balanceIncomeLines?.data?.length" class="relative h-[250px]">
          <tr v-if="isLoading">
            <td colspan="7">Loading...</td>
          </tr>
          <tr v-else-if="isTableLoading">
              <td colspan="7">
                <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
              </td>
            </tr>
          <tr v-else-if="!balanceIncomeLines?.data?.data?.length">
            <td colspan="7">No data found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="balanceIncomeLines" to="acc.balance-income-lines.index" :page="page"></Paginate>
  </div>
</template>