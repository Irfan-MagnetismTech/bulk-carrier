<script setup>
    import { onMounted, computed, ref, watchEffect } from 'vue';
    import { orderBy } from 'lodash';
    import { onClickOutside } from '@vueuse/core';
    import ActionButton from '../../../components/buttons/ActionButton.vue';
    import moment from 'moment';
    import Heading from '../../../components/Heading.vue';
    import useVoyage from '../../../composables/operation/useVoyage';
    import useReport from '../../../composables/operation/useReport';
    import Title from "../../../services/title";
    import useVoyageExpense from "../../../composables/finance/useVoyageExpense";
    import useHelper from "../../../composables/useHelper";
    import { useRoute } from 'vue-router';
    const route = useRoute();
    const expenseInvoiceId = route.params.expenseInvoiceId;

    const props = defineProps({
        page: {
            type: Number,
            default: '',
        },
    });
    
    const { expenseInvoice, getInvoiceDetails } = useVoyageExpense();
    const $helper = new useHelper();
    
    const { setTitle } = Title();
    
    setTitle('Invoice Details');
    
    onMounted(() => {
      watchEffect(() => {
        getInvoiceDetails(expenseInvoiceId);
      });
    });
    </script>
    <template>
        <!-- Heading -->
        <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row">
          <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Invoice # {{ expenseInvoice?.invoice_number }}</h2>
          <!-- <h2 class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Head Wise Entry</h2> -->
            
        </div>
        <div class="mb-2">
            <table>
                <tbody>
                    <tr>
                        <td>Port:</td>
                        <td><strong>{{ expenseInvoice?.port }}</strong></td>
                    </tr>
                    <tr>
                        <td>Invoice Date:</td>
                        <td><strong>{{ expenseInvoice?.date ? moment(expenseInvoice?.date).format('DD/MM/YYYY') : null }}</strong></td>
                    </tr>
                    <tr>
                        <td>Exchange Rate</td>
                        <td class="text-right">
                            <strong>{{ $helper.numberFormat(expenseInvoice?.exchange_rate) ?? '0.00' }}</strong>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    
        <!-- Table -->
        <!-- <div class="max-w-screen h-full overflow-x-auto bg-white overflow-y-auto mb-4">
        </div>-->
        <div class="w-full">
            <div class="w-full">
                <table class="w-full whitespace-no-wrap mb-5">
                    <thead v-once>
                        <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                            <th>Voyage</th>
                            <th>Created</th>
                            <th>Total USD</th>
                            <th>Total BDT</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        <tr class="text-gray-700 text-left dark:text-gray-400" v-for="(entry) in expenseInvoice?.expense_entries" :key="entry.id">
                            <td class="px-1 text-left">{{ entry?.round_voyage?.combined_name }}</td>
                            <td class="px-1 text-left">{{ entry?.created_at ? moment(entry?.created_at).format('DD/MM/YYYY') : null }}</td>
                            <td class="font-semibold text-right dark:text-gray-300">
                                {{ $helper.numberFormat(entry?.amount) ?? '0.00' }}
                            </td>
                            <td class="font-semibold text-right dark:text-gray-300">
                                {{ $helper.numberFormat(entry?.amount_bdt) ?? '0.00' }}
                            </td>
                            
                        </tr>
                        <tr v-if="pairList?.value?.data?.length === 0">
                            <td colspan="8" class="text-center dark:text-gray-400">
                                <span v-if="!isLoading">No records available.</span>
                                <span v-else-if="isLoading" class="flex items-center justify-center w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="w-5 h-5 iconify iconify--eos-icons" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                        <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8a8 8 0 0 1-8 8z" opacity=".5" fill="currentColor" />
                                        <path d="M20 12h2A10 10 0 0 0 12 2v2a8 8 0 0 1 8 8z" fill="currentColor">
                                            <animateTransform attributeName="transform" type="rotate" from="0 12 12" to="360 12 12" dur="1s" repeatCount="indefinite" />
                                        </path>
                                    </svg>
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
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
        @apply bg-gray-900 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 focus:ring-offset-gray-50 text-white font-semibold py-2 px-3 rounded-lg w-full flex items-center justify-center gap-1.5 sm:w-auto dark:bg-blue-500 dark:hover:bg-blue-400;
    }
    
    table, th,td{
      @apply border border-collapse;
    }
    </style>