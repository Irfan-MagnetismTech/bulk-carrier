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
import FilterComponent from "../../../components/utils/FilterComponent.vue";
const router = useRouter();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});
const icons = useHeroIcon();
const { bankReconciliations, getBankReconciliations, updateBankReconciliation, deleteBankReconciliation, isOpenReconciliationDateModal, isLoading, isTableLoading  } = useBankReconciliation();
const { setTitle } = Title();
setTitle('Bank Reconciliation');
const debouncedValue = useDebouncedRef('', 800);

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
      "field_name": "transaction_date",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Voucher Date",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "voucher_type",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Voucher Type",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "instrument_type",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Instrument Type",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "instrument_no",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Instrument No",
      "filter_type": "input"
    },
    {
      "relation_name": null,
      "field_name": "instrument_date",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Instrument Date",
      "filter_type": "input"
    },
    {
      "relation_name": 'bankReconciliation',
      "field_name": "reconciliation_date",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Reconciliation Date",
      "filter_type": "input"
    },
    {
      "relation_name": 'bankReconciliation',
      "field_name": "status",
      "search_param": "",
      "action": null,
      "order_by": null,
      "date_from": null,
      "label": "Status",
      "filter_type": "input"
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

const formParams = ref( {
  reconciliation_date: null,
  acc_transaction_id: null,
  business_unit: null,
});

function updateBankReconciliationDate(event,transactionId,businessUnit){
  isOpenReconciliationDateModal.value = 1;
  formParams.value.acc_transaction_id = transactionId;
  formParams.value.business_unit = businessUnit;
}

function closeReconciliationDateModal(){
  isOpenReconciliationDateModal.value = 0;
  formParams.value.reconciliation_date = null;
}

onMounted(() => {
  watchPostEffect(() => {
    if(currentPage.value == props.page && currentPage.value != 1) {
      filterOptions.value.page = 1;
      router.push({ name: 'acc.bank-reconciliation.index', query: { page: filterOptions.value.page } });
    } else {
      filterOptions.value.page = props.page;
    }
    currentPage.value = props.page;
    if (JSON.stringify(filterOptions.value) !== stringifiedFilterOptions) {
      filterOptions.value.isFilter = true;
    }
    getBankReconciliations(filterOptions.value)
        .then(() => {
          const customDataTable = document.getElementById("customDataTable");
          paginatedPage.value = filterOptions.value.page;
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
      
      <table class="w-full whitespace-no-wrap">
        <FilterComponent :filterOptions = "filterOptions"/>
          <tbody class="relative">
            <template v-for="(bankReconciliation,index) in bankReconciliations?.data" :key="index">
              <tr v-for="(ledger, ledgerIndex) in bankReconciliation?.ledgerEntries" :key="ledgerIndex">
                <td v-if="ledgerIndex == 0" :rowspan="Object.keys(bankReconciliation?.ledgerEntries).length"> {{ (paginatedPage - 1) * filterOptions.items_per_page + index + 1 }} </td>
                <td v-if="ledgerIndex == 0" :rowspan="Object.keys(bankReconciliation?.ledgerEntries).length"> <nobr>{{ bankReconciliation?.transaction_date }}</nobr> </td>
                <td v-if="ledgerIndex == 0" :rowspan="Object.keys(bankReconciliation?.ledgerEntries).length"> {{ bankReconciliation?.voucher_type }} </td>
                <td v-if="ledgerIndex == 0" :rowspan="Object.keys(bankReconciliation?.ledgerEntries).length"> {{ bankReconciliation?.instrument_type }} </td>
                <td v-if="ledgerIndex == 0" :rowspan="Object.keys(bankReconciliation?.ledgerEntries).length"> {{ bankReconciliation?.instrument_no }} </td>
                <td v-if="ledgerIndex == 0" :rowspan="Object.keys(bankReconciliation?.ledgerEntries).length"> <nobr>{{ bankReconciliation?.instrument_date }}</nobr> </td>
                <td class="px-4 py-3 text-sm" v-if="ledgerIndex == 0" :rowspan="bankReconciliation.total_ledger">
                  <nobr>
                    <span> {{ bankReconciliation?.bankReconciliation?.reconciliation_date ?? '---' }} </span>
                  </nobr>
                </td>
                <td class="px-4 py-3 text-sm" v-if="ledgerIndex == 0" :rowspan="bankReconciliation.total_ledger">
                  <span v-if="bankReconciliation?.bankReconciliation?.status" class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                          {{ bankReconciliation?.bankReconciliation?.status }}
                  </span>
                  <span v-else class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-300 rounded-full dark:text-gray-100 dark:bg-gray-700">
                    <nobr>Not Posted</nobr>
                  </span>
                </td>
                <td v-if="ledgerIndex == 0" :rowspan="Object.keys(bankReconciliation?.ledgerEntries).length">
                  <span :class="bankReconciliation?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ bankReconciliation?.business_unit }}</span>
                </td>
                <td v-if="ledgerIndex == 0" :rowspan="Object.keys(bankReconciliation?.ledgerEntries).length">
                  <nobr>
                    <action-button :action="'posting'" :class="{'custom_opacity text-green-600': bankReconciliation?.bankReconciliation}" @click="updateBankReconciliationDate($event,bankReconciliation.id,bankReconciliation?.business_unit)"></action-button>
                  </nobr>
                </td>
              </tr>
            </template>
            <LoaderComponent :isLoading = isTableLoading v-if="isTableLoading && bankReconciliations?.data?.length"></LoaderComponent>
          </tbody>
        <tfoot v-if="!bankReconciliations?.data?.length" class="relative h-[250px]">
        <tr v-if="isLoading">
          <td colspan="10"></td>
        </tr>
        <tr v-else-if="isTableLoading">
          <td colspan="10">
            <LoaderComponent :isLoading = isTableLoading ></LoaderComponent>
          </td>
        </tr>
        <tr v-else-if="!bankReconciliations?.data?.data?.length">
          <td colspan="10">No data found.</td>
        </tr>
        </tfoot>
      </table>
    </div>
    <Paginate :data="bankReconciliations" to="acc.bank-reconciliation.index" :page="page"></Paginate>
  </div>
  <div v-show="isOpenReconciliationDateModal" class="fixed inset-0 z-30 flex items-end overflow-y-auto bg-black bg-opacity-50 sm:items-center sm:justify-center">
    <!-- Modal -->
    <form @submit.prevent="updateBankReconciliation(formParams,bankReconciliations?.data)" style="position: absolute;top: 0;">
      <div class="w-full px-6 py-4 overflow-y-auto bg-white rounded-t-lg dark-disabled:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl" role="dialog" id="modal">
        <!-- Remove header if you don't want a close icon. Use modal body to place modal tile. -->
        <header class="flex justify-end">
          <button type="button"
                  class="inline-flex items-center justify-center w-6 h-6 mb-2 text-gray-400 transition-colors duration-150 rounded dark-disabled:hover:text-gray-200 hover: hover:text-gray-700"
                  aria-label="close" @click="closeReconciliationDateModal">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" role="img" aria-hidden="true">
              <path
                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                  clip-rule="evenodd" fill-rule="evenodd"></path>
            </svg>
          </button>
        </header>
        <!-- Modal body -->
        <table class="w-full mb-2 whitespace-no-wrap border-collapse contract-assign-table table2">
          <thead v-once>
          <tr style="background-color: #04AA6D;color: white"
              class="text-xs font-semibold tracking-wide text-gray-500 border-b dark-disabled:border-gray-700 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
            <th colspan="5">Update Reconciliation Date</th>
          </tr>
          </thead>
        </table>
        <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Reconciliation Date<span class="text-red-500">*</span></span>
            <input type="date" v-model.trim="formParams.reconciliation_date" class="form-input" autocomplete="off" required />
          </label>
        </div>
        <footer class="flex flex-col items-center justify-end px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark-disabled:bg-gray-800">
          <button type="button" @click="closeReconciliationDateModal" style="color: #1b1e21"
                  class="w-full px-5 py-3 text-sm font-medium leading-5 text-white text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark-disabled:text-gray-400 sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray">
            Cancel
          </button>
          <button
              class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            Submit
          </button>
        </footer>
      </div>
    </form>
  </div>
</template>

<style lang="postcss" scoped>
#modal {
  min-width: 25rem;
}

</style>