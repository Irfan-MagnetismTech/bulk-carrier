<script setup>
import MoneyReceiptForm from '../../../components/revenue/money-receipt/MoneyReceiptForm.vue';
import {ref} from "vue";
import { onMounted } from '@vue/runtime-core';
import Title from "../../../services/title";
import useMoneyReceipt from "../../../composables/revenue/useMoneyReceipt.js";
import useHelper from '../../../composables/useHelper';
import { useRoute } from 'vue-router';
import moment from 'moment';


const { moneyReceipt, showMoneyReceipt, updateMoneyReceipt } = useMoneyReceipt();
const { setTitle } = Title();
const { numberFormat } = useHelper();
const route = useRoute();

const moneyReceiptId = route.params.moneyReceiptId;
const formType = 'edit';

setTitle('Edit Bill');

onMounted(() => {
    showMoneyReceipt(moneyReceiptId);
});

</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Update Money Receipt</h2>
        <router-link :to="{ name: 'revenue.money-receipt.index' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg active:bg-[#0F6B61] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
            </svg>
        </router-link>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <fieldset class="px-3 border border-gray-500 rounded-sm pt-3 pb-5">
            <legend class="px-2">Customer Info:</legend>
                <div class="grid grid-cols-3 gap-4">
                    <label for="">
                        <h2 class="text-md font-semibold dark:text-white">Date</h2>
                        <input type="date" readonly v-model="moneyReceipt.issue_date" class="bg-gray-100 block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                    </label>
                    <label for="">
                        <h2 class="text-md font-semibold dark:text-white">Customer Code</h2>
                        <v-select :disabled="true" :options="customers" placeholder="--Choose an option--" @search="fetchCustomer"  v-model="moneyReceipt.customer" label="code" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
                        <input type="hidden" v-model="moneyReceipt.customer_id" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                    </label>
                    <label for="">
                        <h2 class="text-md font-semibold dark:text-white">Customer Name</h2>
                        <span  class="bg-gray-100 block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input h-8 p-2 border border-black"> {{ moneyReceipt?.customer?.name }}</span>
                    </label>
                    
                </div>
        </fieldset>
        <fieldset class="px-3 border border-gray-500 rounded-sm pt-3 pb-5 mt-5">
            <legend class="px-2">Payment Method:</legend>
                <table class="w-full">
                    <thead>
                        <tr class="uppercase text-gray-600 text-sm">
                            <th class="py-1 w-48">Method</th>
                            <th class="py-1">Amount</th>
                            <th class="py-1 w-64">Bank Name</th>
                            <th class="py-1">TRX REF</th>
                            <th class="py-1">DOC Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="method, index in moneyReceipt.money_receipt_methods" :key="index">

                            <tr>
                                <td>
                                    <v-select :disabled="true" :options="paymentMethods" placeholder="--Choose an option--" v-model="moneyReceipt.money_receipt_methods[index].payment_method" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
                                </td>
                                <td>
                                    <span  class="bg-gray-100 block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input h-9 p-2 border border-gray-300 !text-right"> {{ numberFormat(moneyReceipt?.money_receipt_methods[index]?.amount) }}</span>
                                </td>
                                <td>
                                    <v-select :disabled="true" :options="banks" placeholder="--Choose an option--" @search="fetchBank"  v-model="moneyReceipt.money_receipt_methods[index].source_name" label="name" :reduce="name => name.name" class="!bg-white block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
                                </td>
                                <td>
                                    <input type="text" disabled v-model="moneyReceipt.money_receipt_methods[index].trx_no" class="bg-gray-100 block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                                </td>
                                <td>
                                    <input type="date" disabled v-model="moneyReceipt.money_receipt_methods[index].dated" class="bg-gray-100 block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                                </td>
                            </tr>

                        </template>
                    </tbody>
                </table>
        </fieldset>
        <fieldset class="px-3 border border-gray-500 rounded-sm pt-3 my-5 pb-5">
            <legend class="px-2">Invoice No:</legend>
                <table class="w-full">
                    <thead>
                        <tr class="uppercase text-gray-600 text-sm">
                            <th class="py-1 w-64">List of Invoice</th>
                            <th class="py-1">Invoice Date</th>
                            <th class="py-1">Invoice Amount</th>
                            <th class="py-1">Due Amount</th>
                            <th class="py-1">Collected Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="method, index in moneyReceipt.money_receipt_lines" :key="index">

                            <tr>
                                <td>
                                    <span  class="bg-gray-100 block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input h-8 p-2 border border-black"> {{ moneyReceipt?.money_receipt_lines[index]?.customer_bill?.bill_no }}</span>
                                </td>
                                <td>
                                    <span  class="bg-gray-100 block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input h-8 p-2 border border-black"> {{ moneyReceipt?.money_receipt_lines[index]?.customer_bill?.bill_date }}</span>
                                </td>
                                <td>
                                    <span  class="bg-gray-100 block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input h-8 p-2 border border-black !text-right"> {{ numberFormat(moneyReceipt?.money_receipt_lines[index]?.customer_bill?.amount) }}</span>
                                </td>
                                <td>
                                    <span  class="bg-gray-100 block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input h-8 p-2 border border-black !text-right"> {{ numberFormat(moneyReceipt?.money_receipt_lines[index]?.due_amount || null) }}</span>
                                </td>
                                <td>
                                    <span  class="bg-gray-100 block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input h-8 p-2 border border-black !text-right"> {{ numberFormat(moneyReceipt?.money_receipt_lines[index]?.amount) }}</span>
                                    
                                </td>
                            </tr>

                        </template>
                        <tr>
                            <td colspan="3"></td>
                            <td class="!text-right"><strong>Total Amount</strong></td>
                            <td class="!text-right font-bold py-5">{{ numberFormat(moneyReceipt.amount) }}</td>
                        </tr>
                    </tbody>
                </table>
        </fieldset>
        <fieldset class="px-3 border border-gray-500 rounded-sm pt-3 my-5 pb-5">
            <legend class="px-2">Remarks and Attachment:</legend>
            <div class="flex items-center">
                <textarea type="text" disabled v-model="moneyReceipt.remarks" class="block w-1/2 mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" ></textarea>
                <div class="border ml-3 px-3 py-2 w-1/2">
                    <a class="block text-blue-500 hover:text-blue-600 duration-150 ease-linear" :href="`http://serverb.qctrading.net/${moneyReceipt?.attachment}`" target="_blank">Attachment</a>
                </div>
            </div>
        </fieldset>
    </div>
</template>
