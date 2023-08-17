<script setup>
import { onMounted,watch, ref, watchEffect } from "vue";
import { orderBy } from "lodash";
import { onClickOutside } from "@vueuse/core";
import ActionButton from "../../../components/buttons/ActionButton.vue";
import moment from "moment";
import Heading from "../../../components/Heading.vue";
import useVoyage from "../../../composables/operation/useVoyage";
import useReport from "../../../composables/operation/useReport";
import Title from "../../../services/title";
import useVoyageExpense from "../../../composables/finance/useVoyageExpense";
import useHelper from "../../../composables/useHelper";
import Paginate from "../../../components/utils/paginate.vue";
import useDebouncedRef from "../../../composables/useDebouncedRef";

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});
const { isLoading, voyageName, getVoyageName } = useVoyage();
const {
  notification,
  expenseInvoices,
  getExpenseInvoices,
  downloadExpenseInvoice,
  downloadExpenseInvoiceByGroup,
  deleteExpenseInvoice
} = useVoyageExpense();
const $helper = new useHelper();
const menu = ref(false);
const showingMenuIndex = ref(-1);
const searchKey = useDebouncedRef('', 800);
const columns = ["port", "invoice_number","entry_group","combined_name"];
const table = "voyage_expense_invoices";
const relation = ["round_voyage"];

watch(searchKey, newQuery => {
  getExpenseInvoices(props.page, columns, searchKey.value, table, relation);
});

onMounted(() => {
    watchEffect(() => {
      getExpenseInvoices(props.page);
    });
});

onClickOutside(menu, (event) => (showingMenuIndex.value = -1));

function toggleMenu(index) {
  if (showingMenuIndex.value === index) {
    showingMenuIndex.value = -1;
  } else {
    showingMenuIndex.value = index;
  }
}

const { setTitle } = Title();

setTitle("Expense Invoices");

function uniqueRoundVoyages(expenseEntries) {
  console.log("expenseEntries", expenseEntries)
  // Grouping and Unique
  let roundVoyageName = [];
  for (const key in expenseEntries) {
    roundVoyageName.push(expenseEntries[key].round_voyage?.combined_name);
  }
  let result = JSON.stringify([...new Set(roundVoyageName)]);
  let output = result.slice(1, result.length - 1);
  return output.replace(/,/g, ", ");
  // return result;
}

function fetchVoyages(search, loading) {
  getVoyageName(search);
}

function print(invoiceId) {
  downloadExpenseInvoice(invoiceId);
}

function bulkprint(entryGroup) {
  downloadExpenseInvoiceByGroup(entryGroup);
}



onMounted(() => {
  watchEffect(() => {
    getExpenseInvoices(props.page);
  });
});
</script>
<template>
  <!-- Heading -->
  <div
    class="my-6 flex w-full flex-col items-center justify-between sm:flex-row"
  >
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
      Expense Invoices
    </h2>
    <!-- <h2 class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Head Wise Entry</h2> -->
  </div>

  <div class="mb-2 flex select-none items-center justify-between">
    <span class="whitespace-no-wrap w-full text-xs font-medium text-gray-500"
      >Showing {{ expenseInvoices?.from }}-{{ expenseInvoices?.to }} of
      {{ expenseInvoices?.total }}</span
    >
    <!-- Search -->
    <div class="relative w-full">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        class="absolute right-0 bottom-2 mr-2 h-5 w-5 text-gray-500"
        viewBox="0 0 20 20"
        fill="currentColor"
      >
        <path
          fill-rule="evenodd"
          d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
          clip-rule="evenodd"
        />
      </svg>
      <input
        type="text"
        v-model.trim="searchKey"
        placeholder="Search..."
        class="search"
      />
    </div>
  </div>
  <!-- Table -->
  <!-- <div class="max-w-screen h-full overflow-x-auto bg-white overflow-y-auto mb-4">
        </div>-->
  <div class="w-full">
    <div class="w-full">
      <table class="whitespace-no-wrap mb-5 w-full">
        <thead v-once>
          <tr
            class="bg-gray-50 text-center text-xs font-semibold uppercase tracking-wide text-gray-500 dark:bg-gray-700 dark:text-gray-200"
          >
            <th>Port</th>
            <th>Voyage</th>
            <th>Created</th>
            <th>Voucher</th>
            <th>Invoice</th>
            <th>Invoice Date</th>
            <th>Total USD</th>
            <th>Total BDT</th>
            <th>Exchange Rate</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody class="divide-y bg-white dark:divide-gray-700 dark:bg-gray-800">
          <tr
            class="text-left text-gray-700 dark:text-gray-400"
            v-for="(invoice, index) in expenseInvoices?.data"
            :key="invoice.id"
          >
            <td class="px-1 text-left">{{ invoice?.port }}</td>
            <td class="px-1 text-left">
              {{ uniqueRoundVoyages(invoice?.expense_entries, index) }}
            </td>
            <td class="px-1 text-left">
              {{
                invoice?.created_at
                  ? moment(invoice?.created_at).format("DD/MM/YYYY")
                  : null
              }}
            </td>
            <td class="text-left font-semibold dark:text-gray-300">
              {{ invoice?.entry_group }}
            </td>
            <td class="text-left font-semibold dark:text-gray-300">
              {{ invoice?.invoice_number }}
            </td>
            <td class="text-left font-semibold dark:text-gray-300">
              {{
                invoice?.date
                  ? moment(invoice?.date).format("DD/MM/YYYY")
                  : null
              }}
            </td>
            <td class="text-right font-semibold dark:text-gray-300">
              {{ $helper.numberFormat(invoice?.total) ?? "0.00" }}
            </td>
            <td class="text-right font-semibold dark:text-gray-300">
              {{ $helper.numberFormat(invoice?.total_bdt) ?? "0.00" }}
            </td>
            <td class="text-right font-semibold dark:text-gray-300">
              {{ $helper.numberFormat(invoice?.exchange_rate) ?? "0.00" }}
            </td>
            <td class="text-gray-600">
              <div class="flex items-center justify-center space-x-2">
                <action-button
                  :action="'show'"
                  :to="{
                    name: 'finance.expense-invoice.show',
                    params: { expenseInvoiceId: invoice?.id ?? -1 },
                  }"
                ></action-button>
                <action-button
                  :action="'edit'"
                  :to="{
                    name: 'finance.expense-invoice.edit',
                    params: { expenseInvoiceId: invoice?.id ?? -1 },
                  }"
                ></action-button>
                <action-button
                  @click="deleteExpenseInvoice(invoice?.id ?? -1)"
                  :action="'delete'"
                ></action-button>
                <action-button
                  @click="print(invoice?.id ?? -1)"
                  :action="'print'"
                ></action-button>
                <action-button
                  @click="bulkprint(invoice?.entry_group ?? -1)"
                  :action="'bulk-print'"
                ></action-button>
              </div>
            </td>
          </tr>
          <tr v-if="pairList?.value?.data?.length === 0">
            <td colspan="8" class="text-center dark:text-gray-400">
              <span v-if="!isLoading">No records available.</span>
              <span
                v-else-if="isLoading"
                class="flex w-full items-center justify-center"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  xmlns:xlink="http://www.w3.org/1999/xlink"
                  aria-hidden="true"
                  role="img"
                  class="iconify iconify--eos-icons h-5 w-5"
                  preserveAspectRatio="xMidYMid meet"
                  viewBox="0 0 24 24"
                >
                  <path
                    d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8a8 8 0 0 1-8 8z"
                    opacity=".5"
                    fill="currentColor"
                  />
                  <path
                    d="M20 12h2A10 10 0 0 0 12 2v2a8 8 0 0 1 8 8z"
                    fill="currentColor"
                  >
                    <animateTransform
                      attributeName="transform"
                      type="rotate"
                      from="0 12 12"
                      to="360 12 12"
                      dur="1s"
                      repeatCount="indefinite"
                    />
                  </path>
                </svg>
              </span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <Paginate
      :data="expenseInvoices"
      to="finance.expense-invoices.index"
      :page="page"
    ></Paginate>
  </div>
</template>
<style lang="postcss" scoped>
th {
  @apply px-3 py-2;
}

td {
  @apply px-3 py-2 text-xs;
}

.dropdown-btn {
  @apply focus:outline-none flex w-full items-center justify-center gap-1.5 rounded-lg bg-gray-900 py-2 px-3 font-semibold text-white hover:bg-gray-700 focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 focus:ring-offset-gray-50 dark:bg-blue-500 dark:hover:bg-blue-400 sm:w-auto;
}

table,
th,
td {
  @apply border-collapse border;
}

.search-result {
  @apply px-4 py-3 text-center text-sm text-gray-600 dark:text-gray-300;
}
.search {
  @apply focus:outline-none  float-right rounded border border-gray-300 pr-10 text-sm focus:border-purple-400 focus:shadow-outline-purple dark:border-0 dark:bg-gray-800 dark:text-gray-200 dark:focus:shadow-outline-gray;
}
</style>
