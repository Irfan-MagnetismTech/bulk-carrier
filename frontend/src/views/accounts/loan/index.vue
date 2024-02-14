<script setup>
import {onMounted, ref, watchEffect, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useLoan from "../../../composables/accounts/useLoan";
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import Store from "../../../store";
import useDebouncedRef from "../../../composables/useDebouncedRef";
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
import FilterComponent from "../../../components/utils/FilterComponent.vue";
const icons = useHeroIcon();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { loans, getLoans, deleteLoan, isLoading, isTableLoading} = useLoan();
const { setTitle } = Title();
setTitle('Loan');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);


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
      "field_name": "source_type",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Source Type",
      "filter_type": null,      
    },
    {
      "relation_name": 'bank',
      "field_name": "bank_name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Bank Name",
      "filter_type": "input"      
    },
    {
      "relation_name": null,
      "field_name": "loan_type",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Loan Type",
      "filter_type": "input"      
    },
    {
      "relation_name": null,
      "field_name": "loan_number",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Loan Number",
      "filter_type": "input"      
    },
    {
      "relation_name": null,
      "field_name": "loan_name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Loan Name",
      "filter_type": "input"      
    },
    {
      "relation_name": null,
      "field_name": "total_sanctioned",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Total Sanctioned",
      "filter_type": "input"      
    },
    {
      "relation_name": null,
      "field_name": "sanctioned_limit",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Sanctioned Limit",
      "filter_type": "input"      
    },
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
    text: "You want to change delete this item!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then((result) => {
    if (result.isConfirmed) {
      deleteLoan(id);
    }
  })
}

function clearFilter(){
  filterOptions.value.filter_options.forEach((option, index) => {
    filterOptions.value.filter_options[index].search_param = "";
    filterOptions.value.filter_options[index].order_by = null;
  });
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
    getLoans(filterOptions.value)
      .then(() => {
        paginatedPage.value = filterOptions.value.page;
        const customDataTable = document.getElementById("customDataTable");

        if (customDataTable) {
          tableScrollWidth.value = customDataTable.scrollWidth;
        }
      //  isTableLoader.value = true; 
      })
      .catch((error) => {
        console.error("Error fetching loan:", error);
      });
  });

  // filterOptions.value.filter_options.forEach((option, index) => {
  //   filterOptions.value.filter_options[index].search_param = useDebouncedRef('', 800);
  // });
});



</script>

<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Loan List</h2>
    <default-button :title="'Create Loan'" :to="{ name: 'acc.loans.create' }" :icon="icons.AddIcon"></default-button>
  </div>

  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      
      <table class="w-full whitespace-no-wrap" >
        <FilterComponent :filterOptions = "filterOptions"/>

          <!-- <thead>
            <tr class="w-full">
              <th class="w-16">
                <div class="w-full flex items-center justify-between">
                  # <button @click="swapFilter()" type="button" v-html="icons.FilterIcon"></button>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span>Source Type</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(0,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[0].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[0].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(0,'desc')" :class="{'text-gray-800' : filterOptions.filter_options[0].order_by === 'desc', 'text-gray-300' : filterOptions.filter_options[0].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span>Source Name</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(1,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(1,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span><nobr>Loan Type</nobr></span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(2,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[2].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[2].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(2,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[2].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[2].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span>Loan Number</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(3,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[3].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[3].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(3,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[3].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[3].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
               <th>
                <div class="flex justify-evenly items-center">
                  <span>Loan Name</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(4,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[4].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[4].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(4,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[4].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[4].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
               <th>
                <div class="flex justify-evenly items-center">
                  <span>Total Sanctioned</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(5,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[5].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[5].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(5,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[5].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[5].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
               <th>
                <div class="flex justify-evenly items-center">
                  <span>Sanctioned Limit</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(6,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[6].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[6].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(6,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[6].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[6].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>

             <th>
              <div class="flex justify-evenly item-center">
                <span><nobr>Business Unit</nobr></span>
              </div>
              </th>
              <th class=""> <no-br>Action</no-br></th>
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
                            
               <th>
                <filter-with-business-unit v-model="filterOptions.business_unit"></filter-with-business-unit>
              </th>
              <th>
                <button title="Clear Filter" @click="clearFilter()" type="button" v-html="icons.NotFilterIcon"></button>
              </th>
            </tr>
          </thead> -->

          <tbody class="relative">
                <tr v-for="(loan,index) in loans?.data" :key="index">
                  <td>{{ (paginatedPage  - 1) * filterOptions.items_per_page + index + 1 }}</td>
                  <td>{{ loan?.loanable_type }}</td>
                  <td class="text-left" >{{ loan?.bank?.bank_name }}</td>
                  <td class="text-left">{{ loan?.loan_type }}</td>
                  <td class="text-left">{{ loan?.loan_number }}</td>
                  <td class="text-left">{{ loan?.loan_name }}</td>
                  <td class="text-right">{{ loan?.total_sanctioned }}</td>
                  <td class="text-right">{{ loan?.sanctioned_limit }}</td>
                <td>
                  <span :class="loan?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">
                    {{ loan?.business_unit }}
                  </span>
                </td>
                <td>
                  <nobr>
                    <action-button :action="'show'" :to="{ name: 'acc.loans.show', params: { loanId: loan?.id } }"></action-button>
                    <action-button :action="'edit'" :to="{ name: 'acc.loans.edit', params: { loanId: loan?.id } }"></action-button>
                    <action-button @click="confirmDelete(loan?.id)" :action="'delete'"></action-button>
                  </nobr>
                </td>
              </tr>

            <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && loans?.data?.length"></LoaderComponent>
          </tbody>
          <tfoot v-if="!loans?.data?.length">
          <tr v-if="isLoading">
            <td colspan="13">Loading...</td>
          </tr>
          <tr v-else-if="isTableLoading">
              <td colspan="13">
                <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
              </td>
          </tr>
          <tr v-else-if="!loans?.data?.length">
            <td colspan="13">No data found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="loans" to="acc.loans.index" :page="page"></Paginate>
  </div>
</template>