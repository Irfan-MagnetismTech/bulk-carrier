<script setup>
import {ref, watch} from "vue";
import { onMounted } from '@vue/runtime-core';

import Title from "../../../services/title";
import useSupplierBill from "../../../composables/scm/useSupplierBill.js";
import useHelper from "../../../composables/useHelper.js";
import SupplierBillForm from '../../../components/scm/supplier-bill/SupplierBillForm.vue';
import { useRoute } from 'vue-router';
import moment from 'moment';

const { supplierBill, showSupplierBill, updateSupplierBill  } = useSupplierBill();
const { getAllUnit, numberFormat } = useHelper();
const { setTitle } = Title();
const route = useRoute();
const supplierBillId = route.params.supplierBillId;
const units = ref()
setTitle('Supplier Bill Details');

onMounted(() => {
    showSupplierBill(supplierBillId);
});

</script>
<template>
  <div>
    <h2 class="text-2xl p-3 font-semibold text-center bg-gray-200 mt-3 rounded-md">
      Supplier Bill: {{ supplierBill.bill_no }} Details
    </h2>
  </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-3 text-sm md:w-4/6">
      <span class="text-gray-700 dark:text-gray-300">Date <span class="text-red-500">*</span></span>
      <span class="vms-readonly-input block w-full form-input p-2 h-9">
                {{ moment(supplierBill.date).format('DD/MM/YYYY') }}
            </span>
    </label>
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Supplier <span class="text-red-500">*</span></span>
      <span class="vms-readonly-input block w-full form-input py-2 px-2 h-9">
                {{ supplierBill.supplier?.name }}
            </span>
    </label>
  </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Bill No <span class="text-red-500">*</span></span>
      <span class="vms-readonly-input block w-full form-input py-2 px-2 h-9">
                {{ supplierBill.bill_no }}
            </span>
    </label>
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Remarks</span>
      <span class="vms-readonly-input block w-full form-input py-2 px-2 h-9">
                {{ supplierBill.remarks }}
            </span>
    </label>
    
  </div>
  
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300">Bill Details <span class="text-red-500">*</span></legend>
    <table class="w-full whitespace-no-wrap" id="table">
      <thead>
      <tr class="text-xs text-center text-gray-500 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th class="px-4 py-3 align-center">MRR</th>
        <th class="px-4 py-3 align-center">PO No.</th>
        <th class="px-4 py-3 align-center">Amount</th>
      </tr>
      </thead>

      <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr class="text-gray-700 dark:text-gray-400" v-for="(mrr, index) in supplierBill.mrrs" :key="index">
            <td class="w-1/3">
              
              <span class="vms-readonly-input block w-full form-input py-2 !text-center pr-2">
                {{ supplierBill.mrrs[index]?.material_receive?.challan_no }}
              </span>

              <input type="hidden" name="" v-model="supplierBill.mrrs[index].material_receive_id">

            </td>
            <td class="w-1/3 px-2">
              <span class="vms-readonly-input block w-full form-input py-2 !text-center pr-2">
                {{ supplierBill.mrrs[index]?.material_receive?.purchase_order?.sequence }}
              </span>
            </td>
            <td class="w-1/3 px-2">
              <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2">
                {{ numberFormat(supplierBill.mrrs[index].amount) }}
              </span>
            </td>
          
          </tr>
          <tr>
            <td colspan="4" class="h-10"></td>
          </tr>
        <tr>
          <td colspan="2" class="!text-right pr-2 font-semibold">Sub Total</td>
          <td>
            
            <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2">
                {{ numberFormat(supplierBill.sub_total) }}
            </span>
          </td>
        <td></td>

        </tr>
      <tr>
        <td colspan="2" class="!text-right pr-2 font-semibold">Discount</td>
        <td>
          <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2">
                {{ numberFormat(supplierBill.discount) }}
            </span>
        </td>
        <td></td>

      </tr>
      
      <tr>
        <td colspan="2" class="!text-right pr-2 font-semibold">Grand Total</td>
        <td>
          <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2 h-8">
                {{ numberFormat(supplierBill.final_total) }}
            </span>
        </td>
        <td></td>
      </tr>
          
      
      
      </tbody>
    </table>
  </fieldset>
</template>
<style lang="postcss" scoped>
    .input-group {
        @apply flex flex-col justify-center w-full md:flex-row md:gap-2;
    }
    .label-group {
        @apply block w-full mt-3 text-sm font-semibold;
    }
    .label-item-title {
        @apply text-gray-700 dark:text-gray-300 text-sm;
    }
    .label-item-input {
        @apply bg-gray-100 block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
    }
    .form-input {
        @apply block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
    }
    .vs__selected{
    display: none !important;
    }
    .required-style{
        @apply text-red-400 font-semibold
    }

    span.label-item-input {
        @apply bg-gray-100 py-2 h-9 flex items-center border border-black px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray
    }

</style>