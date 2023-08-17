<script setup>

const props = defineProps({
  voyageInfo: {
    required: false,
    default: {}
  },
});

// const { customers, getCustomers, getCustomerWithoutPaginate } = useCustomer();
// const { voyages, voyageCustomers, getVoyageName } = useVoyage();
//
// const { formParams, voyageInfo, voyageSlotUsesStatementReport, isLoading } = useVoyageSlotUsesStatementReport();
//
// function fetchOptions(search) {
//   getVoyageName(search);
// }
//
// onMounted(() => {
//     getCustomerWithoutPaginate();
// });

</script>
<template>
        <div class="w-full my-2 overflow-hidden border rounded-lg shadow-xs dark:border-0">
            <h1 class="py-1 font-bold text-center text-white bg-green-500 text-capitalize"> Laden Container </h1>
            <div class="w-full">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                            <td> Container Size </td>
                            <td> Rate </td>
                            <td> Quantity </td>
                            <td> Amount </td>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        <tr>
                            <td> 20' </td>
                            <td> {{voyageInfo?.gp_20_ldn_rate}} </td>
                            <td> {{voyageInfo?.gp_20_ldn_quantity}} </td>
                            <td> {{voyageInfo?.gp_20_ldn_amount}} </td>
                        </tr>
                        <tr>
                            <td> 40' </td>
                            <td> {{voyageInfo?.gp_40_ldn_rate}} </td>
                            <td> {{voyageInfo?.gp_40_ldn_quantity}} </td>
                            <td> {{voyageInfo?.gp_40_ldn_amount}} </td>
                        </tr>
                        <tr>
                            <td> 45' </td>
                            <td> {{voyageInfo?.gp_45_ldn_rate}} </td>
                            <td> {{voyageInfo?.gp_45_ldn_quantity}} </td>
                            <td> {{voyageInfo?.gp_45_ldn_amount}} </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr
                            class="text-xs font-bold text-gray-600 bg-green-100 dark:text-gray-400 dark:text-gray-50 dark:bg-gray-700">
                            <td colspan="3" class="px-2 py-2 text-right">Total Amount (Laden)</td>
                            <td class="px-2 py-2 text-center"> $ {{voyageInfo?.total_ldn_amount}}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="w-full my-2 overflow-hidden border rounded-lg shadow-xs dark:border-0">
            <h1 class="py-1 font-bold text-center text-white bg-green-500 text-capitalize"> Empty Container </h1>
            <div class="w-full">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                            <td> Container Size </td>
                            <td> Rate </td>
                            <td> Quantity </td>
                            <td> Amount </td>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        <tr>
                            <td> 20' </td>
                            <td> {{voyageInfo?.gp_20_mty_rate}} </td>
                            <td> {{voyageInfo?.gp_20_mty_quantity}} </td>
                            <td> {{voyageInfo?.gp_20_mty_amount}} </td>
                        </tr>
                        <tr>
                            <td> 40' </td>
                            <td> {{voyageInfo?.gp_40_mty_rate}} </td>
                            <td> {{voyageInfo?.gp_40_mty_quantity}} </td>
                            <td> {{voyageInfo?.gp_40_mty_amount}} </td>
                        </tr>
                        <tr>
                            <td> 45' </td>
                            <td> {{voyageInfo?.gp_45_mty_rate}} </td>
                            <td> {{voyageInfo?.gp_45_mty_quantity}} </td>
                            <td> {{voyageInfo?.gp_45_mty_amount}} </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr
                            class="text-xs font-bold text-gray-600 bg-green-100 dark:text-gray-400 dark:text-gray-50 dark:bg-gray-700">
                            <td colspan="3" class="px-2 py-2 text-right">Total Amount (Empty)</td>
                            <td class="px-2 py-2 text-center"> $ {{voyageInfo?.total_mty_amount}}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="w-full my-2 overflow-hidden border rounded-lg shadow-xs dark:border-0">
            <h1 class="py-1 font-bold text-center text-white bg-green-500 text-capitalize"> Reffer Container (Per : {{voyageInfo?.rf_unit}}) </h1>
            <div class="w-full">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                            <td> Container Size </td>
                            <td> Rate </td>
                            <td> Quantity </td>
                            <td> Amount </td>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        <tr>
                            <td> 20' </td>
                            <td> {{voyageInfo?.rf_20_rate}} </td>
                            <td> {{voyageInfo?.rf_20_quantity}} </td>
                            <td> {{voyageInfo?.rf_20_amount}} </td>
                        </tr>
                        <tr>
                            <td> 40' </td>
                            <td> {{voyageInfo?.rf_40_rate}} </td>
                            <td> {{voyageInfo?.rf_40_quantity}} </td>
                            <td> {{voyageInfo?.rf_40_amount}} </td>
                        </tr>
                        <tr>
                            <td> 45' </td>
                            <td> {{voyageInfo?.rf_45_rate}} </td>
                            <td> {{voyageInfo?.rf_45_quantity}} </td>
                            <td> {{voyageInfo?.rf_45_amount}} </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr
                            class="text-xs font-bold text-gray-600 bg-green-100 dark:text-gray-400 dark:text-gray-50 dark:bg-gray-700">
                            <td colspan="3" class="px-2 py-2 text-right">Total Amount (Reffer)</td>
                            <td class="px-2 py-2 text-center"> $ {{voyageInfo?.total_rf_amount}}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        
        <div class="w-full my-2 overflow-hidden border rounded-lg shadow-xs dark:border-0">
            <h1 class="py-1 font-bold text-center text-white bg-green-500 text-capitalize"> Tank Container (Per : {{voyageInfo?.tk_unit}}) </h1>
            <div class="w-full">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                            <td> Container Size </td>
                            <td> Rate </td>
                            <td> Quantity </td>
                            <td> Amount </td>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        <tr>
                            <td> 20' </td>
                            <td> {{voyageInfo?.tk_20_rate}} </td>
                            <td> {{voyageInfo?.tk_20_quantity}} </td>
                            <td> {{voyageInfo?.tk_20_amount}} </td>
                        </tr>
                        <tr>
                            <td> 40' </td>
                            <td> {{voyageInfo?.tk_40_rate}} </td>
                            <td> {{voyageInfo?.tk_40_quantity}} </td>
                            <td> {{voyageInfo?.tk_40_amount}} </td>
                        </tr>
                        <tr>
                            <td> 45' </td>
                            <td> {{voyageInfo?.tk_45_rate}} </td>
                            <td> {{voyageInfo?.tk_45_quantity}} </td>
                            <td> {{voyageInfo?.tk_45_amount}} </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr
                            class="text-xs font-bold text-gray-600 bg-green-100 dark:text-gray-400 dark:text-gray-50 dark:bg-gray-700">
                            <td colspan="3" class="px-2 py-2 text-right">Total Amount (TK)</td>
                            <td class="px-2 py-2 text-center"> $ {{voyageInfo?.total_tk_amount}}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        
        <div class="w-full my-2 overflow-hidden border rounded-lg shadow-xs dark:border-0">
            <h1 class="py-1 font-bold text-center text-white bg-green-500 text-capitalize"> OOG Container (Per : {{voyageInfo?.oog_unit}} ) </h1>
            <div class="w-full">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                            <td> Container Size </td>
                            <td> Rate </td>
                            <td> Quantity </td>
                            <td> Amount </td>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        <tr>
                            <td> 20' </td>
                            <td>  </td>
                            <td> {{voyageInfo?.oog_20_rate}} </td>
                            <td> {{voyageInfo?.oog_20_quantity}} </td>
                            <td> {{voyageInfo?.oog_20_amount}} </td>
                        </tr>
                        <tr>
                            <td> 40' </td>
                            <td> {{voyageInfo?.oog_unit}} </td>
                            <td> {{voyageInfo?.oog_40_rate}} </td>
                            <td> {{voyageInfo?.oog_40_quantity}} </td>
                            <td> {{voyageInfo?.oog_40_amount}} </td>
                        </tr>
                        <tr>
                            <td> 45' </td>
                            <td> {{voyageInfo?.oog_unit}} </td>
                            <td> {{voyageInfo?.oog_45_rate}} </td>
                            <td> {{voyageInfo?.oog_45_quantity}} </td>
                            <td> {{voyageInfo?.oog_45_amount}} </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr
                            class="text-xs font-bold text-gray-600 bg-green-100 dark:text-gray-400 dark:text-gray-50 dark:bg-gray-700">
                            <td colspan="4" class="px-2 py-2 text-right">Total Amount (OOG)</td>
                            <td class="px-2 py-2 text-center"> $ {{voyageInfo?.total_oog_amount}}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        
        <div class="w-full my-2 overflow-hidden border rounded-lg shadow-xs dark:border-0">
            <h1 class="py-1 font-bold text-center text-white bg-green-500 text-capitalize"> DG Surcharge (Per : {{voyageInfo?.dg_unit}}) </h1>
            <div class="w-full">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                            <td rowspan="2"> Container Size </td>
                            <td colspan="3"> DG-1 </td>
                            <td colspan="3"> DG-2 </td>
                            <td colspan="3"> DG-3 </td>
                            <td colspan="3"> DG-C </td>
                            <td rowspan="2"> Total </td>
                        </tr>                        
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                            <td> Rate </td>
                            <td> Qty </td>
                            <td> Amount </td>
                            <td> Rate </td>
                            <td> Qty </td>
                            <td> Amount </td>
                            <td> Rate </td>
                            <td> Qty </td>
                            <td> Amount </td>
                            <td> Rate </td>
                            <td> Qty </td>
                            <td> Amount </td>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        <tr>
                            <td> 20' </td>
                            <td> {{voyageInfo?.dg_1_20_rate}} </td>
                            <td> {{voyageInfo?.dg_1_20_quantity}} </td>
                            <td> {{voyageInfo?.dg_1_20_amount}} </td>
                            
                            <td> {{voyageInfo?.dg_2_20_rate}} </td>
                            <td> {{voyageInfo?.dg_2_20_quantity}} </td>
                            <td> {{voyageInfo?.dg_2_20_amount}} </td>
                            
                            <td> {{voyageInfo?.dg_3_20_rate}} </td>
                            <td> {{voyageInfo?.dg_3_20_quantity}} </td>
                            <td> {{voyageInfo?.dg_3_20_amount}} </td>
                            
                            <td> {{voyageInfo?.dg_c_20_rate}} </td>
                            <td> {{voyageInfo?.dg_c_20_quantity}} </td>
                            <td> {{voyageInfo?.dg_c_20_amount}} </td>
                            <td> {{voyageInfo?.dg_20_total}} </td>
                        </tr>
                        <tr>
                            <td> 40' </td>
                            <td> {{voyageInfo?.dg_1_40_rate}} </td>
                            <td> {{voyageInfo?.dg_1_40_quantity}} </td>
                            <td> {{voyageInfo?.dg_1_40_amount}} </td>
                            
                            <td> {{voyageInfo?.dg_2_40_rate}} </td>
                            <td> {{voyageInfo?.dg_2_40_quantity}} </td>
                            <td> {{voyageInfo?.dg_2_40_amount}} </td>
                            
                            <td> {{voyageInfo?.dg_3_40_rate}} </td>
                            <td> {{voyageInfo?.dg_3_40_quantity}} </td>
                            <td> {{voyageInfo?.dg_3_40_amount}} </td>
                            
                            <td> {{voyageInfo?.dg_c_40_rate}} </td>
                            <td> {{voyageInfo?.dg_c_40_quantity}} </td>
                            <td> {{voyageInfo?.dg_c_40_amount}} </td>
                            <td> {{voyageInfo?.dg_40_total}} </td>
                        </tr>
                        <tr>
                            <td> 45' </td>
                            <td> {{voyageInfo?.dg_1_45_rate}} </td>
                            <td> {{voyageInfo?.dg_1_45_quantity}} </td>
                            <td> {{voyageInfo?.dg_1_45_amount}} </td>    
                             
                            <td> {{voyageInfo?.dg_2_45_rate}} </td>
                            <td> {{voyageInfo?.dg_2_45_quantity}} </td>
                            <td> {{voyageInfo?.dg_2_45_amount}} </td>    
                             
                            <td> {{voyageInfo?.dg_3_45_rate}} </td>
                            <td> {{voyageInfo?.dg_3_45_quantity}} </td>
                            <td> {{voyageInfo?.dg_3_45_amount}} </td>   
                             
                            <td> {{voyageInfo?.dg_c_45_rate}} </td>
                            <td> {{voyageInfo?.dg_c_45_quantity}} </td>
                            <td> {{voyageInfo?.dg_c_45_amount}} </td>                            
                            <td> {{voyageInfo?.dg_45_total}} </td>                            
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr
                            class="text-xs font-bold text-gray-600 bg-green-100 dark:text-gray-400 dark:text-gray-50 dark:bg-gray-700">
                            <td colspan="13" class="px-2 py-2 text-right">Total Amount (DG)</td>
                            <td class="px-2 py-2 text-center"> $ {{voyageInfo?.dg_total_amount}}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>


    <div class="flex items-center justify-center w-full overflow-hidden md:justify-end">
        <div class="w-6/12 my-2 overflow-hidden border rounded rounded-lg shadow-xs dark:border-0">
            <h1 class="py-1 font-bold text-center text-white bg-green-500">Summary</h1>
            <div class="w-full rounded-md">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                            <td>Revenue Head</td>
                            <td>Amount</td>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        <tr>
                            <td> Ocean Freight Laden </td>
                            <td> $ {{voyageInfo?.total_ldn_amount}} </td>
                        </tr>
                        <tr>
                            <td> Ocean Freight Empty</td>
                            <td> $ {{voyageInfo?.total_mty_amount}} </td>
                        </tr>
                        <tr>
                            <td> Ocean Freight Reffer </td>
                            <td> $ {{voyageInfo?.total_rf_amount}} </td>
                        </tr>
                        <tr>
                            <td>Ocean Freight OOG </td>
                            <td> $ {{voyageInfo?.total_oog_amount}} </td>
                        </tr>
                        <tr>
                            <td>Ocean Freight Tank </td>
                            <td> $ {{voyageInfo?.total_tk_amount}} </td>
                        </tr>
                        <tr>
                            <td>Surcharges DG </td>
                            <td> $ {{voyageInfo?.dg_total_amount}} </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr
                            class="text-xs font-bold text-gray-600 bg-green-100 dark:text-gray-400 dark:text-gray-50 dark:bg-yellow-500">
                            <td class="px-2 py-2 text-right">Total</td>
                            <td class="px-2 py-2 text-center"> $ {{ voyageInfo?.grandTotalAmount }} </td>
                        </tr>
                        <tr class="text-xs font-bold text-gray-600 bg-green-100 dark:text-gray-400 dark:text-gray-50 dark:bg-yellow-500">
                            <td class="px-2 py-2 text-right"> Notes (Dr/CR) </td>
                            <td class="px-2 py-2 text-center"> {{ voyageInfo?.invoice_notes }}</td>
                        </tr>                        
                    </tfoot>
                </table>
            </div>
        </div>
    </div>    

</template>
<style lang="postcss" scoped>
@tailwind components;

@layer components {
    th {
        @apply p-10 border-r text-center;
    }

    tbody td,
    thead td,
    thead th {
        @apply px-1 py-1 text-xs border-r text-center;
    }
}
>>> {
  --vs-controls-color: #374151;
  --vs-border-color: #4b5563;

  --vs-dropdown-bg: #282c34;
  --vs-dropdown-color: #eeeeee;
  --vs-dropdown-option-color: #eeeeee;

  --vs-selected-bg: #664cc3;
  --vs-selected-color: #374151;

  --vs-search-input-color: #4b5563;

  --vs-dropdown-option--active-bg: #664cc3;
  --vs-dropdown-option--active-color: #eeeeee;
}
</style>