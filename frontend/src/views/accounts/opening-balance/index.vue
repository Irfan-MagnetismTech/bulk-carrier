<script setup>
import {onMounted, ref, watch, watchEffect,watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useOpeningBalance from "../../../composables/accounts/useOpeningBalance";
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
const { openingBalances, getOpeningBalances, deleteOpeningBalance, isLoading, isTableLoading  } = useOpeningBalance();
const { setTitle } = Title();
setTitle('Opening Balance List');

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
      deleteOpeningBalance(id);
    }
  })
}

let showFilter = ref(false);
function swapFilter() {
  showFilter.value = !showFilter.value;
}

function clearFilter(){
  filterOptions.value.filter_options.forEach((option, index) => {
    filterOptions.value.filter_options[index].search_param = "";
    filterOptions.value.filter_options[index].order_by = null;
  });
}

let filterOptions = ref( {
  "business_unit": businessUnit.value,
  "items_per_page": 15,
  "page": props.page,
  "isFilter": false,
  "filter_options": [
    {
      "relation_name": "costCenter",
      "field_name": "name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    },
    {
      "relation_name": "account",
      "field_name": "account_name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    },
    {
      "relation_name": "",
      "field_name": "date",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    },
    {
      "relation_name": "",
      "field_name": "cr_amount",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    },
    {
      "relation_name": "",
      "field_name": "dr_amount",
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
  getOpeningBalances(filterOptions.value)
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
    <h2 class="text-2xl font-semibold text-gray-700">Opening Balance List</h2>
    <default-button :title="'Create Opening Balance'" :to="{ name: 'acc.opening-balances.create' }" :icon="icons.AddIcon"></default-button>
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
                <span>Cost Center</span>
                <div class="flex flex-col cursor-pointer">
                  <div v-html="icons.descIcon" @click="setSortingState(0,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[0].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[0].order_by !== 'asc' }" class=" font-semibold"></div>
                  <div v-html="icons.ascIcon" @click="setSortingState(0,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[0].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[0].order_by !== 'desc' }" class=" font-semibold"></div>
                </div>
              </div>
            </th>
            <th>
              <div class="flex justify-evenly items-center">
                <span><nobr>Account Name</nobr></span>
                <div class="flex flex-col cursor-pointer">
                  <div v-html="icons.descIcon" @click="setSortingState(1,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'asc' }" class=" font-semibold"></div>
                  <div v-html="icons.ascIcon" @click="setSortingState(1,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'desc' }" class=" font-semibold"></div>
                </div>
              </div>
            </th>
            <th>
              <div class="flex justify-evenly items-center">
                <span><nobr>Date</nobr></span>
                <div class="flex flex-col cursor-pointer">
                  <div v-html="icons.descIcon" @click="setSortingState(2,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[2].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[2].order_by !== 'asc' }" class=" font-semibold"></div>
                  <div v-html="icons.ascIcon" @click="setSortingState(2,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[2].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[2].order_by !== 'desc' }" class=" font-semibold"></div>
                </div>
              </div>
            </th>
            <th>
              <div class="flex justify-evenly items-center">
                <span><nobr>Debit Amount</nobr></span>
                <div class="flex flex-col cursor-pointer">
                  <div v-html="icons.descIcon" @click="setSortingState(3,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[3].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[3].order_by !== 'asc' }" class=" font-semibold"></div>
                  <div v-html="icons.ascIcon" @click="setSortingState(3,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[3].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[3].order_by !== 'desc' }" class=" font-semibold"></div>
                </div>
              </div>
            </th>
            <th>
              <div class="flex justify-evenly items-center">
                <span><nobr>Credit Amount</nobr></span>
                <div class="flex flex-col cursor-pointer">
                  <div v-html="icons.descIcon" @click="setSortingState(4,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[4].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[4].order_by !== 'asc' }" class=" font-semibold"></div>
                  <div v-html="icons.ascIcon" @click="setSortingState(4,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[4].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[4].order_by !== 'desc' }" class=" font-semibold"></div>
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
            <th><input v-model="filterOptions.filter_options[2].search_param" type="date" placeholder="" class="filter_input" autocomplete="off" /></th>
            <th><input v-model="filterOptions.filter_options[3].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
            <th><input v-model="filterOptions.filter_options[4].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
            <th>
              <filter-with-business-unit v-model="filterOptions.business_unit"></filter-with-business-unit>
            </th>
            <th><button title="Clear Filter" @click="clearFilter()" type="button" v-html="icons.NotFilterIcon"></button></th>
          </tr>
          </thead>
          <tbody class="relative">
          <tr v-for="(balanceData,index) in openingBalances?.data" :key="index">
            <!-- <td>{{ index + 1 }}</td> -->
            <td>{{ (paginatedPage  - 1) * filterOptions.items_per_page + index + 1 }}</td>
            <td>{{ balanceData?.costCenter?.name }}</td>
            <td>{{ balanceData?.account?.account_name }}</td>
            <td>{{ balanceData?.date }}</td>
            <td>{{ balanceData?.cr_amount }}</td>
            <td>{{ balanceData?.dr_amount }}</td>
            <td>
              <span :class="balanceData?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ balanceData?.business_unit }}</span>
            </td>
            <td>
              <action-button :action="'edit'" :to="{ name: 'acc.opening-balances.edit', params: { openingBalanceId: balanceData?.id } }"></action-button>
              <action-button @click="confirmDelete(balanceData?.id)" :action="'delete'"></action-button>
            </td>
          </tr>
          <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && openingBalances?.data?.length"></LoaderComponent>
          </tbody>
          <tfoot v-if="!openingBalances?.data?.length" class="relative h-[250px]">
          <tr v-if="isLoading">
            <td colspan="8">Loading...</td>
          </tr>
          <tr v-else-if="isTableLoading">
              <td colspan="8">
                <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
              </td>
            </tr>
          <tr v-else-if="!openingBalances?.data?.data?.length">
            <td colspan="8">No data found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="openingBalances" to="acc.opening-balances.index" :page="page"></Paginate>
  </div>
</template>