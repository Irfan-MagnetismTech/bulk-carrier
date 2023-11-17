<script setup>
import {onMounted, ref, watchEffect, watchPostEffect} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useTransaction from "../../../composables/accounts/useTransaction";
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import Paginate from '../../../components/utils/paginate.vue';
import FilterWithBusinessUnit from "../../../components/searching/FilterWithBusinessUnit.vue";
import Swal from "sweetalert2";
import useHeroIcon from "../../../assets/heroIcon";
import Store from "../../../store";
import useDebouncedRef from "../../../composables/useDebouncedRef";
import LoaderComponent from "../../../components/utils/LoaderComponent.vue";
const icons = useHeroIcon();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { transactions, getTransactions, deleteTransaction, isLoading, isTableLoading} = useTransaction();
const { setTitle } = Title();
setTitle('Transactions');

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
      "relation_name": "costCenter",
      "field_name": "name",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    },
    {
      "relation_name": null,
      "field_name": "voucher_type",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    },
    {
      "relation_name": null,
      "field_name": "transaction_date",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    },
    {
      "relation_name": null,
      "field_name": "instrument_type",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    },
    {
      "relation_name": null,
      "field_name": "instrument_no",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    },
    {
      "relation_name": null,
      "field_name": "instrument_date",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    },
    {
      "relation_name": null,
      "field_name": "bill_no",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    },
    //this need to be changed later for Account name search
    {
      "relation_name": null,
      "field_name": "bill_no",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    },
    //this need to be changed later for Account name search
    {
      "relation_name": "ledgerEntries",
      "field_name": "dr_amount",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null
    },
    {
      "relation_name": "ledgerEntries",
      "field_name": "cr_amount",
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
    text: "You want to change delete this item!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then((result) => {
    if (result.isConfirmed) {
      deleteTransaction(id);
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
    getTransactions(filterOptions.value)
      .then(() => {
        paginatedPage.value = filterOptions.value.page;
        const customDataTable = document.getElementById("customDataTable");

        if (customDataTable) {
          tableScrollWidth.value = customDataTable.scrollWidth;
        }
      //  isTableLoader.value = true; 
      })
      .catch((error) => {
        console.error("Error fetching transection:", error);
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
    <h2 class="text-2xl font-semibold text-gray-700">Voucher List</h2>
    <default-button :title="'Create Voucher'" :to="{ name: 'acc.transactions.create' }" :icon="icons.AddIcon"></default-button>
  </div>

  <div id="customDataTable">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      
      <table class="w-full whitespace-no-wrap" >
          <!-- <thead v-once>
          <tr class="w-full">
            <th>#</th>
            <th> Cost Center </th>
            <th> Voucher Type </th>
            <th> Applied Date </th>
            <th> Payment Type </th>
            <th> Cheque Number </th>
            <th> Cheque Date </th>
            <th> Bill No </th>
            <th> Account Name </th>
            <th> Debit Amount </th>
            <th> Credit Amount </th>
            <th> Business Unit </th>
            <th> Action </th>
          </tr>
          </thead> -->
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
                  <span>Voucher Type</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(1,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(1,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[1].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[1].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span><nobr>Applied Date</nobr></span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(2,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[2].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[2].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(2,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[2].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[2].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
              <th>
                <div class="flex justify-evenly items-center">
                  <span>Payment Type</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(3,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[3].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[3].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(3,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[3].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[3].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
               <th>
                <div class="flex justify-evenly items-center">
                  <span>Cheque Number</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(4,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[4].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[4].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(4,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[4].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[4].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
               <th>
                <div class="flex justify-evenly items-center">
                  <span>Cheque Date</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(5,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[5].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[5].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(5,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[5].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[5].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
               <th>
                <div class="flex justify-evenly items-center">
                  <span>Bill No</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(6,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[6].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[6].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(6,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[6].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[6].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
               <!-- <th>
                <div class="flex justify-evenly items-center">
                  <span>Account Name</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(7,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[7].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[7].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(7,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[7].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[7].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th> -->
               <th>
                <div class="flex justify-evenly items-center">
                  <span>Account Name</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" :class="{ 'text-gray-800': filterOptions.filter_options[8].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[8].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" :class="{ 'text-gray-800': filterOptions.filter_options[8].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[8].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
               <th>
                <div class="flex justify-evenly items-center">
                  <span>Debit Amount</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(8,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[8].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[8].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(8,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[8].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[8].order_by !== 'desc' }" class=" font-semibold"></div>
                  </div>
                </div>
              </th>
               <th>
                <div class="flex justify-evenly items-center">
                  <span>Credit Amount</span>
                  <div class="flex flex-col cursor-pointer">
                    <div v-html="icons.descIcon" @click="setSortingState(9,'asc')" :class="{ 'text-gray-800': filterOptions.filter_options[9].order_by === 'asc', 'text-gray-300': filterOptions.filter_options[9].order_by !== 'asc' }" class=" font-semibold"></div>
                    <div v-html="icons.ascIcon" @click="setSortingState(9,'desc')" :class="{ 'text-gray-800': filterOptions.filter_options[9].order_by === 'desc', 'text-gray-300': filterOptions.filter_options[9].order_by !== 'desc' }" class=" font-semibold"></div>
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
              <!-- <th><input v-model="filterOptions.filter_options[7].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th> -->
              <th><input type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th><input v-model="filterOptions.filter_options[8].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
              <th><input v-model="filterOptions.filter_options[9].search_param" type="text" placeholder="" class="filter_input" autocomplete="off" /></th>
               <th>
                <filter-with-business-unit v-model="filterOptions.business_unit"></filter-with-business-unit>
              </th>
            </tr>
          </thead>
          <tbody class="relative">
            <template v-for="(transactionData,index) in transactions?.data" :key="index">
              <tr v-for="(ledger, ledgerIndex) in transactionData?.ledgerEntries" :key="ledgerIndex">
                <td v-if="ledgerIndex == 0" :rowspan="Object.keys(transactionData?.ledgerEntries).length"> {{ (paginatedPage - 1) * filterOptions.items_per_page + index + 1 }} </td>
                <td v-if="ledgerIndex == 0" :rowspan="Object.keys(transactionData?.ledgerEntries).length"> {{ transactionData?.costCenter?.name }} </td>
                <td v-if="ledgerIndex == 0" :rowspan="Object.keys(transactionData?.ledgerEntries).length"> {{ transactionData?.voucher_type }} </td>
                <td v-if="ledgerIndex == 0" :rowspan="Object.keys(transactionData?.ledgerEntries).length"> <nobr>{{ transactionData?.transaction_date }}</nobr> </td>
                <td v-if="ledgerIndex == 0" :rowspan="Object.keys(transactionData?.ledgerEntries).length"> {{ transactionData?.instrument_type }} </td>
                <td v-if="ledgerIndex == 0" :rowspan="Object.keys(transactionData?.ledgerEntries).length"> {{ transactionData?.instrument_no }} </td>
                <td v-if="ledgerIndex == 0" :rowspan="Object.keys(transactionData?.ledgerEntries).length"> <nobr>{{ transactionData?.instrument_date }}</nobr> </td>
                <td v-if="ledgerIndex == 0" :rowspan="Object.keys(transactionData?.ledgerEntries).length"> {{ transactionData?.bill_no }} </td>

                <td class="text-left"> <no-br>{{ ledger?.account.account_name }} </no-br></td>
                <td class="text-right"> {{ ledger.dr_amount }} </td>
                <td class="text-right"> {{ ledger.cr_amount }} </td>

                <td v-if="ledgerIndex == 0" :rowspan="Object.keys(transactionData?.ledgerEntries).length">
                  <span :class="transactionData?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ transactionData?.business_unit }}</span>
                </td>
                <td v-if="ledgerIndex == 0" :rowspan="Object.keys(transactionData?.ledgerEntries).length">
                  <nobr>
                    <action-button :action="'edit'" :to="{ name: 'acc.transactions.edit', params: { transactionId: transactionData?.id } }"></action-button>
                    <action-button @click="confirmDelete(transactionData?.id)" :action="'delete'"></action-button>
                  </nobr>
                </td>
              </tr>
            </template>
            <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && transactions?.data?.length"></LoaderComponent>
          </tbody>
          <tfoot v-if="!transactions?.data?.length">
          <tr v-if="isLoading">
            <td colspan="13">Loading...</td>
          </tr>
          <tr v-else-if="isTableLoading">
              <td colspan="13">
                <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>                
              </td>
          </tr>
          <tr v-else-if="!transactions?.data?.length">
            <td colspan="13">No data found.</td>
          </tr>
          </tfoot>
      </table>
    </div>
    <Paginate :data="transactions" to="acc.transactions.index" :page="page"></Paginate>
  </div>
</template>