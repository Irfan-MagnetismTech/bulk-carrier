<script setup>
import {onMounted, ref, watchEffect, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useWarehouse from "../../../composables/supply-chain/useWarehouse";
import Title from "../../../services/title";
import { useFuse } from "@vueuse/integrations/useFuse";
import useHeroIcon from "../../../assets/heroIcon";
import Store from './../../../store/index.js';
import Swal from "sweetalert2";
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import useDebouncedRef from "../../../composables/useDebouncedRef";
import Paginate from '../../../components/utils/paginate.vue';
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const icons = useHeroIcon();
const { warehouses, getWarehouses, deleteWarehouse, isLoading, isTableLoading} = useWarehouse();

const { setTitle } = Title();
setTitle('Warehouses');


const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);


const debouncedValue = useDebouncedRef('', 800);

let showFilter = ref(false);
// let isTableLoader = ref(false);


function swapFilter() {
  showFilter.value = !showFilter.value;
}

let filterOptions = ref({
  "business_unit": businessUnit.value,
  "items_per_page": 15,
  "isFilter": false,
  "page": props.page,
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
      "relation_name": "scmWarehouseContactPerson",
      "field_name": "name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    },
    {
      "relation_name": "scmWarehouseContactPerson",
      "field_name": "phone",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    }
  ]
});

function setSortingState(index, order) {
  filterOptions.value.filter_options.forEach(function (t) {
    t.order_by = null;
  });
  filterOptions.value.filter_options[index].order_by = order;
}
const currentPage = ref(1);
const paginatedPage = ref(1);
let stringifiedFilterOptions = JSON.stringify(filterOptions.value);

function confirmDelete(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You want to change delete this warehouse!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then((result) => {
    if (result.isConfirmed) {
      deleteWarehouse(id);
    }
  })
}

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
    getWarehouses(filterOptions.value)
      .then(() => {
        paginatedPage.value = filterOptions.value.page;
        const customDataTable = document.getElementById("customDataTable");

        if (customDataTable) {
          tableScrollWidth.value = customDataTable.scrollWidth;
        }
      //  isTableLoader.value = true; 
      })
      .catch((error) => {
        console.error("Error fetching warehouses:", error);
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
    <h2 class="text-2xl font-semibold text-gray-700">Warehouse List</h2>
    <default-button :title="'Create Warehouse'" :to="{ name: 'scm.warehouse.create' }" :icon="icons.AddIcon"></default-button>
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
                  <span>Name</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(0,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[0].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[0].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(0,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[0].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[0].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span><nobr>Contact Person</nobr></span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(1,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(1,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span><nobr>Contact No</nobr></span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(2,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[2].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[2].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(2,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[2].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[2].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
             <th>
              <div class="flex justify-evenly item-center">
                <span><nobr>Business Unit</nobr></span>
              </div>
              </th>
              <th class="">Action</th>
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
               <th>
                <filter-with-business-unit v-model="filterOptions.business_unit"></filter-with-business-unit>
              </th>
            </tr>
          </thead>
          <tbody class="relative">
          <tr v-for="(warehouse, index) in warehouses?.data" :key="warehouse.id">  
              <td>{{ (paginatedPage - 1) * filterOptions.items_per_page + index + 1 }}</td>
              <td class="px-4 py-3 text-sm">{{ warehouse.name }}</td>
              <td class="px-4 py-3 text-sm">{{ warehouse.scmWarehouseContactPerson.name }}</td>
              <td class="px-4 py-3 text-sm">{{ warehouse.scmWarehouseContactPerson.phone }}</td>
            <td>
              <span :class="warehouse?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ warehouse?.business_unit }}</span>
            </td>
            <td>
              <action-button :action="'edit'" :to="{ name: 'scm.warehouse.edit', params: { warehouseId: warehouse?.id } }"></action-button>
              <action-button @click="confirmDelete(warehouse?.id)" :action="'delete'"></action-button>
            </td>
          </tr>
          <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && warehouses?.data?.length"></LoaderComponent>
          </tbody>
          <tfoot v-if="!warehouses?.data?.length">
          <tr v-if="isLoading">
            <td colspan="4">Loading...</td>
          </tr>
          <tr v-else-if="isTableLoading">
            <td colspan="7">
              <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
            </td>
        </tr>
          <tr v-else-if="!warehouses?.data?.length">
            <td colspan="4">No Datas found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="warehouses" to="scm.warehouse.index" :page="page"></Paginate>
  </div>
</template>
