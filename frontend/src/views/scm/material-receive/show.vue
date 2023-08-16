<script setup>
import {ref} from "vue";
import { onMounted } from '@vue/runtime-core';

import Title from "../../../services/title";
import usePurchaseOrder from "../../../composables/scm/usePurchaseOrder.js";
import useHelper from "../../../composables/useHelper.js";
import PurchaseOrderForm from '../../../components/scm/purchase-order/PurchaseOrderForm.vue';
import { useRoute } from 'vue-router';
import moment from 'moment';

const { materialObject, purchaseOrder, showPurchaseOrder, updatePurchaseOrder  } = usePurchaseOrder();
const { getAllUnit, numberFormat } = useHelper();
const { setTitle } = Title();
const route = useRoute();
const purchaseOrderId = route.params.purchaseOrderId;
const units = ref()
setTitle('Purchase Order Details');

onMounted(() => {
    showPurchaseOrder(purchaseOrderId);
    getAllUnit()
    .then(allUnits => {
        units.value = allUnits
    })
    .catch(error => {
        // Handle the error
        console.error('An error occurred:', error);
    });
});
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Purchase Order</h2>
        <router-link :to="{ name: 'scm.purchase-order.index' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-[#0F6B61]  border border-transparent rounded-lg active:bg-[#0F6B61]  hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
            </svg>
        </router-link>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <div class="border-b border-gray-200 dark:border-gray-700 pb-5">
        <legend>
            <strong>Purchase Order Info</strong>
            <div class="input-group">
                <label class="label-group">
                    <span class="label-item-title">Vendor<span class="required-style">*</span></span>
                    <span class="label-item-input" >
                        {{  purchaseOrder?.vendor?.name  }}
                    </span>

                </label>
            </div>
        </legend>
        <legend class="mt-5">
            <strong>Pay Order Info</strong>
            <div v-for="(pay_order, index) in purchaseOrder.pay_orders" :key="index" class="bg-green-50 my-5 px-3 py-5 rounded-lg shadow-md">

                <div class="input-group">
                    <label class="label-group">
                        <span class="label-item-title">Pay Order No<span class="required-style">*</span></span>
                        <span class="label-item-input" >
                            {{  purchaseOrder?.pay_orders[index]?.no  }}
                        </span>
                    </label>
                    <label class="label-group">
                        <span class="label-item-title">Date<span class="required-style">*</span></span>
                        <span class="label-item-input" >
                            {{  moment(purchaseOrder?.pay_orders[index]?.date).format('DD/MM/YYYY')  }}
                        </span>
                    </label>
                </div>
                <div class="input-group">
                    
                    <label class="label-group">
                        <span class="label-item-title">Bank Name<span class="required-style">*</span></span>
                        <span class="label-item-input" >
                            {{  purchaseOrder?.pay_orders[index]?.bank?.name  }}
                        </span>
                    </label>
                    <label class="label-group">
                        
                        <span class="label-item-title">Branch Name<span class="required-style">*</span></span>
                        <span class="label-item-input" >
                            {{  purchaseOrder?.pay_orders[index]?.bank_branch?.name  }}
                        </span>
                    </label>
                    <label class="label-group">
                        
                        <span class="label-item-title">Amount<span class="required-style">*</span></span>
                        <span class="label-item-input justify-end" >
                            {{  numberFormat(purchaseOrder?.pay_orders[index]?.amount)  }}
                        </span>
                    </label>
                </div>
            </div>
        </legend>
        
        <legend class="mt-5">
            <strong>Purchase Order Details</strong>
            <table class="w-full">
                <tr class="" v-for="(item, index) in purchaseOrder.materials" :key="index">

                    
                    <td class="font-semibold w-72">
                        <span class="label-item-title">Material<span class="required-style">*</span></span>
                        <span class="label-item-input" >
                            {{  purchaseOrder?.materials[index]?.material?.name  }}
                        </span>
                        

                    </td>

                    
                    <td class="font-semibold w-28">
                        <span class="label-item-title">Quantity<span class="required-style">*</span></span>
                        <span class="label-item-input" >
                            {{  purchaseOrder?.materials[index]?.quantity  }}
                        </span>

                    </td>
                    <td class="font-semibold w-28">
                        <span class="label-item-title">Unit<span class="required-style">*</span></span>
                        <span class="label-item-input" >
                            {{  purchaseOrder?.materials[index]?.unit  }}
                        </span>
                        
                    </td>
                    <td class="font-semibold w-24">
                        <span class="label-item-title">Unit Price<span class="required-style">*</span></span>
                        <span class="label-item-input justify-end" >
                            {{  purchaseOrder?.materials[index]?.unit_price  }}
                        </span>
                    </td>
                    <td class="font-semibold w-96">
                        <span class="label-item-title">Remarks<span class="required-style">*</span></span>
                        <span class="label-item-input" >
                            {{  item?.remarks  }}
                        </span>

                    </td>
                    <td class="font-semibold w-48">
                        <span class="label-item-title">Amount<span class="required-style">*</span></span>
                        <span class="label-item-input justify-end" >
                            {{  numberFormat(purchaseOrder?.materials[index]?.amount)  }}
                        </span>

                    </td>
                    
                </tr>
                <tr class="block my-3"></tr>
                <tr class="bg-gray-200">
                    <td colspan="7" class="pt-3"></td>
                </tr>
                <tr class="bg-gray-200">
                    <td colspan="3"></td>
                    <td class="font-semibold text-right" colspan="2">
                        <span class="label-item-title">Carrying Charge<span class="required-style">*</span></span>
                    </td>
                    <td>
                        <span class="label-item-input justify-end" >
                            {{  purchaseOrder?.carrying_charge  }}
                        </span>
                    </td>
                    <td class=""></td>

                </tr>
                <tr class="bg-gray-200">
                    <td colspan="3"></td>
                    <td class="font-semibold text-right" colspan="2">
                        <span class="label-item-title">Labour Charge<span class="required-style">*</span></span>
                    </td>
                    <td>
                        <span class="label-item-input justify-end" >
                            {{  numberFormat(purchaseOrder?.labour_charge)  }}
                        </span>
                    </td>
                    <td></td>

                </tr>
                <tr class="bg-gray-200">
                    <td colspan="3"></td>
                    <td class="font-semibold text-right" colspan="2">
                        <span class="label-item-title">Sub Total<span class="required-style">*</span></span>
                    </td>
                    <td>
                        <span class="label-item-input justify-end" >
                            {{  numberFormat(purchaseOrder?.sub_total)  }}
                        </span>
                    </td>
                    <td></td>

                </tr>
                <tr class="bg-gray-200">
                    <td colspan="3"></td>
                    <td class="font-semibold text-right" colspan="2">
                        <span class="label-item-title">Round Off<span class="required-style">*</span></span>
                    </td>
                    <td>
                        <span class="label-item-input justify-end" >
                            {{  numberFormat(purchaseOrder?.round_off)  }}
                        </span>
                    </td>
                    <td></td>

                </tr>
                <tr class="bg-gray-200">
                    <td colspan="3"></td>
                    <td class="font-semibold text-right" colspan="2">
                        <span class="label-item-title">Discount<span class="required-style">*</span></span>
                    </td>
                    <td>
                        <span class="label-item-input justify-end" >
                            {{  numberFormat(purchaseOrder?.discount)  }}
                        </span>
                    </td>
                    <td></td>

                </tr>
                <tr class="bg-gray-200">
                    <td colspan="3"></td>
                    <td class="font-semibold text-right" colspan="2">
                        <span class="label-item-title">Grand Total<span class="required-style">*</span></span>
                    </td>
                    <td>
                        <span class="label-item-input justify-end" >
                            {{  numberFormat(purchaseOrder?.grand_total)  }}
                        </span>
                    </td>
                    <td class=""></td>

                </tr>
                <tr class="bg-gray-200">
                    <td colspan="7" class="pt-3"></td>
                </tr>
                <tr class="bg-gray-200">
                    <td colspan="3"></td>
                    <td class="font-semibold text-right" colspan="2">
                        <span class="label-item-title">Tax Applicable?<span class="required-style">*</span></span>
                    </td>
                    <td>
                        <input type="radio" disabled value="1" v-model="purchaseOrder.is_src_tax_applicable" class="ml-1" name="is_src_tax_applicable" :id="'tax_applicable'" />
                        <label class="ml-2" for="tax_applicable">Yes</label>
                        <input type="radio" disabled value="0" v-model="purchaseOrder.is_src_tax_applicable" class="ml-3" name="is_src_tax_applicable" :id="'tax_not_applicable'" />
                        <label class="ml-2" for="tax_not_applicable">No</label>
                    </td>
                    <td class=""></td>

                </tr>
                <tr class="bg-gray-200">
                    <td colspan="7" class="pb-3"></td>
                </tr>
                <tr class="bg-gray-200">
                    <td colspan="3"></td>
                    <td class="font-semibold text-right" colspan="2">
                        <span class="label-item-title">Vat Applicable?<span class="required-style">*</span></span>
                    </td>
                    <td>
                        <input type="radio" disabled value="1" v-model="purchaseOrder.is_src_vat_applicable" class="ml-1" name="is_src_vat_applicable" :id="'vat_applicable'" />
                        <label class="ml-2" for="vat_applicable">Yes</label>
                        <input type="radio" disabled value="0" v-model="purchaseOrder.is_src_vat_applicable" class="ml-3" name="is_src_vat_applicable" :id="'vat_not_applicable'" />
                        <label class="ml-2" for="vat_not_applicable">No</label>
                    </td>
                    <td class=""></td>

                </tr>
                <tr class="bg-gray-200">
                    <td colspan="8" class="pb-3"></td>
                </tr>
            </table>
        </legend>
        <legend class="mt-5">
            <strong>Terms &amp; Remarks</strong>
            <div class="input-group">
                
                <label class="label-group">
                    <span class="label-item-title">Terms<span class="required-style">*</span></span>
                    <span class="label-item-input" >
                            {{  purchaseOrder?.terms  }}
                    </span>
                </label>
                <label class="label-group">
                    <span class="label-item-title">Remarks<span class="required-style">*</span></span>
                    <span class="label-item-input" >
                            {{  purchaseOrder?.remarks  }}
                    </span>
                </label>
            </div>
        </legend>
    </div>
    </div>
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