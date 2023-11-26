<script setup>
import {onMounted, ref, watch, watchEffect,watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useBankReconciliation from "../../../composables/accounts/useBankReconciliation";
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
const { bankReconciliations, getBankReconciliations, deleteBankReconciliation, isLoading, isTableLoading  } = useBankReconciliation();
const { setTitle } = Title();
setTitle('Bank Reconciliation');

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
      deleteBankReconciliation(id);
    }
  })
}

let showFilter = ref(false);
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
      "field_name": "short_name",
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
    }
  ]
});

let stringifiedFilterOptions = JSON.stringify(filterOptions.value);

function setSortingState(index, order) {
  filterOptions.value.filter_options.forEach(function (t) {
    t.order_by = null;
  });
  filterOptions.value.filter_options[index].order_by = order;
}

function swapFilter() {
  showFilter.value = !showFilter.value;
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
    // filterOptions.value.page = props.page;
    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }
  getBankReconciliations(filterOptions.value)
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
    <h2 class="text-2xl font-semibold text-gray-700">Bank Reconciliation List</h2>
    <!-- <default-button :title="'Create Cost Center'" :to="{ name: 'acc.cost-centers.create' }" :icon="icons.AddIcon"></default-button> -->
  </div>
  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      
      <table class="w-full whitespace-no-wrap" >
          <thead>
          <tr class="w-full">
            <th class="w-16">
              <div class="w-full flex items-center justify-between">
                # <button title="Filter Data" @click="swapFilter()" type="button" v-html="icons.FilterIcon"></button>
              </div>
            </th>
            <th>
              <div class="flex justify-evenly items-center">
                <span><nobr>Voucher Date </nobr></span>
                <div class="flex flex-col cursor-pointer">
                  <div v-html="icons.descIcon" @click="setSortingState(0,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[0].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[0].order_by !== 'asc' }" class=" font-semibold"></div>
                  <div v-html="icons.ascIcon" @click="setSortingState(0,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[0].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[0].order_by !== 'desc' }" class=" font-semibold"></div>
                </div>
              </div>
            </th>
            <th>
              <div class="flex justify-evenly items-center">
                <span><nobr>Voucher Type</nobr></span>
                <div class="flex flex-col cursor-pointer">
                  <div v-html="icons.descIcon" @click="setSortingState(1,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'asc' }" class=" font-semibold"></div>
                  <div v-html="icons.ascIcon" @click="setSortingState(1,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'desc' }" class=" font-semibold"></div>
                </div>
              </div>
            </th>
            <th>
              <div class="flex justify-evenly items-center">
                <span><nobr> Instrument Type </nobr></span>
                <div class="flex flex-col cursor-pointer">
                  <div v-html="icons.descIcon" @click="setSortingState(1,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'asc' }" class=" font-semibold"></div>
                  <div v-html="icons.ascIcon" @click="setSortingState(1,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'desc' }" class=" font-semibold"></div>
                </div>
              </div>
            </th>
            <th>
              <div class="flex justify-evenly items-center">
                <span><nobr> Instrument No </nobr></span>
                <div class="flex flex-col cursor-pointer">
                  <div v-html="icons.descIcon" @click="setSortingState(1,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'asc' }" class=" font-semibold"></div>
                  <div v-html="icons.ascIcon" @click="setSortingState(1,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'desc' }" class=" font-semibold"></div>
                </div>
              </div>
            </th>
            <th>
              <div class="flex justify-evenly items-center">
                <span><nobr> Instrument Date </nobr></span>
                <div class="flex flex-col cursor-pointer">
                  <div v-html="icons.descIcon" @click="setSortingState(1,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'asc' }" class=" font-semibold"></div>
                  <div v-html="icons.ascIcon" @click="setSortingState(1,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'desc' }" class=" font-semibold"></div>
                </div>
              </div>
            </th>
            <th>
              <div class="flex justify-evenly items-center">
                <span><nobr> Account Name </nobr></span>
                <div class="flex flex-col cursor-pointer">
                  <div v-html="icons.descIcon" @click="setSortingState(1,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'asc' }" class=" font-semibold"></div>
                  <div v-html="icons.ascIcon" @click="setSortingState(1,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'desc' }" class=" font-semibold"></div>
                </div>
              </div>
            </th>
            <th>
              <div class="flex justify-evenly items-center">
                <span><nobr> Debit Amount </nobr></span>
                <div class="flex flex-col cursor-pointer">
                  <div v-html="icons.descIcon" @click="setSortingState(1,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'asc' }" class=" font-semibold"></div>
                  <div v-html="icons.ascIcon" @click="setSortingState(1,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'desc' }" class=" font-semibold"></div>
                </div>
              </div>
            </th>
            <th>
              <div class="flex justify-evenly items-center">
                <span><nobr> Credit Amount </nobr></span>
                <div class="flex flex-col cursor-pointer">
                  <div v-html="icons.descIcon" @click="setSortingState(1,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'asc' }" class=" font-semibold"></div>
                  <div v-html="icons.ascIcon" @click="setSortingState(1,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'desc' }" class=" font-semibold"></div>
                </div>
              </div>
            </th>
            <th>
              <div class="flex justify-evenly items-center">
                <span><nobr> Transaction Date </nobr></span>
                <div class="flex flex-col cursor-pointer">
                  <div v-html="icons.descIcon" @click="setSortingState(1,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'asc' }" class=" font-semibold"></div>
                  <div v-html="icons.ascIcon" @click="setSortingState(1,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'desc' }" class=" font-semibold"></div>
                </div>
              </div>
            </th>

            <th>
              <div class="flex justify-evenly items-center">
                <span><nobr>Business Unit</nobr></span>
              </div>
            </th>
            <th class="w-20">Action</th>
          </tr>
          <tr class="w-full" v-if="showFilter">
            <th>
              <select v-model.trim="filterOptions.items_per_page" class="filter_input">
                <option value="15">15</option>
                <option value="30">30</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>
            </th>
            <th><input v-model.trim="filterOptions.filter_options[0].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
            <th><input v-model.trim="filterOptions.filter_options[1].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
            <th><input v-model.trim="filterOptions.filter_options[1].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
            <th><input v-model.trim="filterOptions.filter_options[1].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
            <th><input v-model.trim="filterOptions.filter_options[1].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
            <th><input v-model.trim="filterOptions.filter_options[1].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
            <th><input v-model.trim="filterOptions.filter_options[1].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
            <th>
              <filter-with-business-unit v-model.trim="filterOptions.business_unit"></filter-with-business-unit>
            </th>
            <th>
              <button title="Clear Filter" @click="clearFilter()" type="button" v-html="icons.NotFilterIcon"></button>
            </th>
          </tr>
          </thead>
          <tbody class="relative">

            <template v-for="(bankReconciliation,index) in bankReconciliations?.data" :key="index">
              <tr v-for="(ledger, ledgerIndex) in bankReconciliation?.ledgerEntries" :key="ledgerIndex">
                <td v-if="ledgerIndex == 0" :rowspan="Object.keys(bankReconciliation?.ledgerEntries).length"> {{ (paginatedPage - 1) * filterOptions.items_per_page + index + 1 }} </td>
                <!-- <td v-if="ledgerIndex == 0" :rowspan="Object.keys(bankReconciliation?.ledgerEntries).length"> {{ bankReconciliation?.costCenter?.name }} </td> -->
                <td v-if="ledgerIndex == 0" :rowspan="Object.keys(bankReconciliation?.ledgerEntries).length"> <nobr>{{ bankReconciliation?.transaction_date }}</nobr> </td>
                <td v-if="ledgerIndex == 0" :rowspan="Object.keys(bankReconciliation?.ledgerEntries).length"> {{ bankReconciliation?.voucher_type }} </td>
                <td v-if="ledgerIndex == 0" :rowspan="Object.keys(bankReconciliation?.ledgerEntries).length"> {{ bankReconciliation?.instrument_type }} </td>
                <td v-if="ledgerIndex == 0" :rowspan="Object.keys(bankReconciliation?.ledgerEntries).length"> {{ bankReconciliation?.instrument_no }} </td>
                <td v-if="ledgerIndex == 0" :rowspan="Object.keys(bankReconciliation?.ledgerEntries).length"> <nobr>{{ bankReconciliation?.instrument_date }}</nobr> </td>

                <!-- <td v-if="ledgerIndex == 0" :rowspan="Object.keys(bankReconciliation?.ledgerEntries).length"> {{ bankReconciliation?.bill_no }} </td> -->

                <td class="text-left"> <no-br>{{ ledger?.account.account_name }} </no-br></td>
                <td class="text-right"> {{ ledger.dr_amount }} </td>
                <td class="text-right"> {{ ledger.cr_amount }} </td>

                <td v-if="ledgerIndex == 0" :rowspan="Object.keys(bankReconciliation?.ledgerEntries).length"> 
                  
                  <button class="btn"> - - - </button>
                
                </td>


                <td v-if="ledgerIndex == 0" :rowspan="Object.keys(bankReconciliation?.ledgerEntries).length">
                  <span :class="bankReconciliation?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ bankReconciliation?.business_unit }}</span>
                </td>
                <td v-if="ledgerIndex == 0" :rowspan="Object.keys(bankReconciliation?.ledgerEntries).length">
                  <nobr>
                    <!-- <action-button :action="'edit'" :to="{ name: 'acc.transactions.edit', params: { transactionId: bankReconciliation?.id } }"></action-button> -->
                  </nobr>
                </td>
              </tr>
            </template>
            <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && bankReconciliations?.data?.length"></LoaderComponent>
          </tbody>


          <tfoot v-if="!bankReconciliations?.data?.length" class="relative h-[250px]">
          <tr v-if="isLoading">
            <td colspan="10">Loading...</td>
          </tr>
          <tr v-else-if="isTableLoading">
            <td colspan="10">
              <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
            </td>
          </tr>

          <tr v-else-if="!bankReconciliations?.data?.length">
            <td colspan="10">No data found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="bankReconciliations" to="acc.bank-reconciliation.index" :page="page"></Paginate>
  </div>
</template>