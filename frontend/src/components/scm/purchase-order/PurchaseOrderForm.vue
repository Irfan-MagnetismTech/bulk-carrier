<script setup>
    import { ref, watch, onMounted } from 'vue';
    import Error from "../../Error.vue";
    import useVendor from "../../../composables/configuration/useVendor.js";
    import usePurchaseRate from "../../../composables/revenue/usePurchaseRate.js";
    import useWarehouse from "../../../composables/configuration/useWarehouse.js";
    import useBank from "../../../composables/configuration/useBank.js";
    import useTank from "../../../composables/configuration/useTank.js";
    import useMaterialRequisition from "../../../composables/scm/useMaterialRequisition";
    import useHelper from '../../../composables/useHelper';

    const { vendor, vendors, searchVendor } = useVendor();
    const { materials, searchMaterialWithVendorRate } = usePurchaseRate();
    const { warehouse, warehouses, searchWarehouse } = useWarehouse();
    const { tank, tanks, searchTank } = useTank();
    const { banks, searchBank, bankBranches, searchBankBranch } = useBank();
    const { getAllPendingMaterialRequisitions, filteredMaterialRequisitions } = useMaterialRequisition();
    const { numberFormat } = useHelper();


    const props = defineProps({
        purchaseOrder: { type: Object, required: true },
        units: { type: Object, required: true },
        materialModel: { type: Object, required: true },
        payOrderModel: { type: Object, required: true },
        defaultVendor: { type: Object, required: true },
        page: {type: Number, default: 1},
    });

    const activeBankId = ref(null);

    function fetchVendor(query, loading) {
        searchVendor(query, loading);
        loading(true);
    }

    function fetchMaterial(query, loading) {
        if(!props.purchaseOrder.vendor_id) {
            alert('Please choose a vendor first.');
        } else {
            searchMaterialWithVendorRate(props.purchaseOrder.vendor_id, query, loading);
        loading(true);
        }
    }

    function fetchWarehouse(query, loading) {
        searchWarehouse(query);
        loading(true);
    }

    function fetchTank(query, loading) {
        searchTank(query, loading);
        loading(true);
    }

    function fetchBank(query, loading) {
        searchBank(query, loading);
        loading(true)
    }

    function fetchBankBranch(query, loading) {
        searchBankBranch(query, activeBankId.value, loading);
        loading(true)
    }

    function getCurrentBankId(index) {
        activeBankId.value =  props.purchaseOrder.pay_orders[index].bank_id
    }


    const hasUpdated = ref(0);
    watch(() => props.purchaseOrder.vendor, (value) => {
        props.purchaseOrder.vendor_id = value?.id;

        if(props.purchaseOrder?.id) {
            if(hasUpdated.value == 1) {
                props.purchaseOrder.materials = []
            }
        } else {
            props.purchaseOrder.materials = []
        }

        hasUpdated.value = 1
    }, { deep: true });

    watch(() => props.defaultVendor, (value) => {
        props.purchaseOrder.vendor = value
    }, { deep: true })

    watch(() => props.purchaseOrder.pay_orders, (payOrders) => {
        for (let index in payOrders) {
            let item = payOrders[index];
            props.purchaseOrder.pay_orders[index].bank_id = item?.bank?.id
            props.purchaseOrder.pay_orders[index].bank_branch_id = item?.bank_branch?.id

        }
        bankBranches.value = []
    }, { deep : true })

    watch(() => props.purchaseOrder.materials, (materials) => {
        console.log('material change')
        for (let index in materials) {
            let item = materials[index];
            // props.purchaseOrder.materials[index].warehouse_id = item?.warehouse?.id
            // props.purchaseOrder.materials[index].tank_id = item?.tank?.id
            props.purchaseOrder.materials[index].material_id = item?.material?.id

            if(props.purchaseOrder.material_requisition_id !== '') {
                props.purchaseOrder.materials[index].unit_price = item?.unit_price
            } else {
                props.purchaseOrder.materials[index].unit_price = item?.material?.rate
            }
            props.purchaseOrder.materials[index].unit = item?.material?.unit
        }

    }, {deep : true});


    function addMaterial(index) {

        let materialObject = { ...props.materialModel }
        props.purchaseOrder.materials.push(materialObject);
    }

    function removeMaterial(index){
        console.log("removing", index)
        props.purchaseOrder.materials.splice(index, 1);
        calculateAmount();
    }

    function addPayOrder() {
        let payOrderObject = { ...props.payOrderModel }
        props.purchaseOrder.pay_orders.push(payOrderObject);
    }

    function removePayOrder(index){
        if(props.purchaseOrder.pay_orders.length>1) {
            props.purchaseOrder.pay_orders.splice(index, 1);
        }
    }

    function calculateAmount(index = -1) {

        let totalAmount = 0;
        let payOrderAmount = 0;

        if(index>=0) {
            let unitPrice = props.purchaseOrder.materials[index].unit_price
            let quantity = props.purchaseOrder.materials[index].quantity

            if(unitPrice == '') {
                unitPrice = 1
            }
            if(quantity == '') {
                quantity = 1
            }
            totalAmount = parseFloat(unitPrice) * parseFloat(quantity)
            props.purchaseOrder.materials[index].amount = parseFloat(totalAmount).toFixed(2)

            console.log("inside here")
        }

        totalAmount = 0;
        for (let index in props.purchaseOrder.materials) {
            totalAmount += parseFloat(props.purchaseOrder.materials[index].amount)
        }

        for (let index in props.purchaseOrder.pay_orders) {
            payOrderAmount += parseFloat(props.purchaseOrder.pay_orders[index].amount) ?? 0
        }
        
        let carryingCharge = props.purchaseOrder.carrying_charge
        let labourCharge = props.purchaseOrder.labour_charge
        let discount = props.purchaseOrder.discount

        if(carryingCharge == '' || carryingCharge == null || carryingCharge == 'undefined') {
            carryingCharge = 0
        }
        if(labourCharge == '' || labourCharge == null || labourCharge == 'undefined') {
            labourCharge = 0
        }
        if(discount == '' || discount == null || discount == 'undefined') {
            discount = 0
        }

        let subTotal = totalAmount + parseFloat(carryingCharge) + parseFloat(labourCharge)
        props.purchaseOrder.sub_total = parseFloat(subTotal).toFixed(2)
        props.purchaseOrder.round_off = Math.round(subTotal)

        let grandTotal = Math.round(subTotal) - parseFloat(discount)
        props.purchaseOrder.grand_total = parseFloat(grandTotal)

        if(payOrderAmount != '') {
            payOrderAmount = parseFloat(payOrderAmount)

            if(payOrderAmount > parseFloat(grandTotal)) {
                alert("Pay Order amount cannot be greater than Grand Total")
                if(props.purchaseOrder.material_requisition_id != '') {

                } else {
                    props.purchaseOrder.materials[index].quantity = 0
                    props.purchaseOrder.materials[index].amount = 0
                }
                
                props.purchaseOrder.hasError = 1;
            } else {
                props.purchaseOrder.hasError = 0;
            }
        }

    }

    // watch(() => props.purchaseOrder, (value) => {
    //     props.purchaseOrder.material_requisition_id = value?.material_requisition?.id;
    //     //find full object from filteredMaterialRequisitions where id = value?.material_requisition?.id
    //     let materialRequisition = filteredMaterialRequisitions.value.find(item => item.id == value?.material_requisition?.id);
    //     console.log("Tottal: " +materialRequisition?.stockable);
    // }, { deep: true });

    watch(() => props.purchaseOrder.material_requisition, (value) => {
        if(props.purchaseOrder.material_requisition) {
            props.purchaseOrder.material_requisition_id = props.purchaseOrder.material_requisition.id;
        }
    //   let materialRequisition = filteredMaterialRequisitions.value.find(item => item.id == value?.id);
    
    if(props.purchaseOrder?.material_requisition?.stockable) {
        props.purchaseOrder.materials = [...props.purchaseOrder?.material_requisition?.stockable];
    }
      calculateAmount();
    }, { deep: true });
    
    onMounted(() => {
        getAllPendingMaterialRequisitions();
    });

</script>
<template>
    <div class="border-b border-gray-200 dark:border-gray-700 pb-5">
        <legend>
            <strong>Purchase Order Info</strong>
            <div class="input-group">
                <label class="label-group">
                    <span class="label-item-title">Vendor <span class="required-style">*</span></span>
                    <v-select :options="vendors" placeholder="--Choose an option--" @search="fetchVendor"  v-model="purchaseOrder.vendor" label="name" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
                    <input type="hidden" required v-model="purchaseOrder.vendor_id" class="label-item-input" name="bank_name" :id="'bank_name'" />
                </label>
              <label class="label-group">
                <span class="label-item-title">Date <span class="required-style">*</span></span>
                <input type="date" required v-model="purchaseOrder.date" class="label-item-input" name="purchase_order_date" :id="'purchase_order_date'" />
              </label>
              <label class="label-group">
                <span class="label-item-title">Material Requisition</span>
                <v-select :options="filteredMaterialRequisitions" placeholder="--Choose an option--" v-model="purchaseOrder.material_requisition" label="sequence" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                </v-select>
                <input type="hidden" required v-model="purchaseOrder.material_requisition_id" class="label-item-input" />
              </label>
            </div>
        </legend>
        <legend class="mt-5">
            <strong>Pay Order Info</strong>
            <div v-for="(pay_order, index) in purchaseOrder.pay_orders" :key="index" class="bg-green-50 my-5 p-3 rounded-lg shadow-md">
                <div class="mx-auto w-full flex justify-end">
                    <button type="button" @click="addPayOrder(index)" :disabled="isLoading" class="flex items-center justify-center px-4 py-2 mt-2 text-sm leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg fon2t-medium mt- active:bg-[#0F6B90] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">+</button>
                    <button v-if="index > 0"  type="button" @click="removePayOrder(index)" :disabled="isLoading" class="ml-3 flex items-center justify-center px-4 py-2 mt-2 text-sm leading-5 text-white transition-colors duration-150 bg-red-500 border border-transparent rounded-lg fon2t-medium mt- active:bg-[#0F6B90] hover:bg-red-800 focus:outline-none focus:shadow-outline-purple">-</button>
                </div>
                <div class="input-group">
                    <label class="label-group">
                        <span class="label-item-title">Pay Order No </span>
                        <input type="text" v-model="purchaseOrder.pay_orders[index].no" class="label-item-input" name="pay_order_no" :id="'pay_order_no'" />
                    </label>
                    <label class="label-group">
                        <span class="label-item-title">Date </span>
                        <input type="date" v-model="purchaseOrder.pay_orders[index].date" class="label-item-input" name="pay_order_date" :id="'pay_order_date'" />
                    </label>
                </div>
                <div class="input-group">
                    
                    <label class="label-group">
                        <span class="label-item-title">Bank Name</span>
                        <v-select :options="banks" placeholder="--Choose an option--" @search="fetchBank"  v-model="purchaseOrder.pay_orders[index].bank" label="name" class="!bg-white block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
                        <input type="hidden" v-model="purchaseOrder.pay_orders[index].bank_id" class="label-item-input" name="bank_id" :id="'bank_id'" />
                    </label>
                    <label class="label-group">
                        <span class="label-item-title">Branch Name</span>
                        <v-select :options="bankBranches" @keydown="getCurrentBankId(index)" placeholder="--Choose an option--" @search="fetchBankBranch"  v-model="purchaseOrder.pay_orders[index].bank_branch" label="name" class="!bg-white block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>

                        <input type="hidden" v-model="purchaseOrder.pay_orders[index].bank_branch_id" class="label-item-input" name="branch_name" :id="'branch_name'" />
                    </label>
                    <label class="label-group">
                        <span class="label-item-title">Amount </span>
                        <input type="number" step="0.01" @keyup="calculateAmount()" v-model="purchaseOrder.pay_orders[index].amount" class="label-item-input" name="pay_order_amount" :id="'pay_order_amount'" />

                    </label>
                </div>
                
            </div>
           
        </legend>
        
        <legend class="mt-5">
            <strong>Purchase Order Details</strong>
            <table class="w-full">
                <tr class="">
                    <td class="font-semibold w-72">
                        <span class="label-item-title">Material <span class="required-style">*</span></span>
                    </td>
                  <td class="font-semibold w-28">
                    <span class="label-item-title">Unit</span>
                  </td>
                    <td class="font-semibold w-28">
                        <span class="label-item-title">Qty. <span class="required-style">*</span></span>
                    </td>
                    <td class="font-semibold w-24">
                        <span class="label-item-title ">Unit Price <span class="required-style">*</span></span>
                    </td>
                    
                    <td class="font-semibold w-40">
                        <span class="label-item-title">Amount</span>
                    </td>
                    <td class="font-semibold w-96">
                        <span class="label-item-title">Remarks</span>
                    </td>
                    <td class="font-semibold flex flex-col justify-center items-center">
                        <span class="label-item-title">Action</span>
                        <button type="button" @click="addMaterial(poIndex)" :disabled="isLoading" class="flex items-center justify-center px-4 py-2 mt-2 text-sm leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg fon2t-medium mt- active:bg-[#0F6B90] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">+</button>
                    </td>
                </tr>
                <tr class="" v-for="(item, poIndex) in purchaseOrder.materials" :key="poIndex">
                    <td class="font-semibold w-72">
                        <v-select :options="materials" placeholder="--Choose an option--" @search="fetchMaterial"  v-model="purchaseOrder.materials[poIndex].material" label="name" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                          <template #search="{attributes, events}">
                            <input
                                class="vs__search"
                                :required="!purchaseOrder.materials[poIndex].material"
                                v-bind="attributes"
                                v-on="events"
                            />
                          </template>
                        </v-select>
                        <input type="hidden" required v-model="purchaseOrder.materials[poIndex].material_id" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />

                    </td>
                  <td class="font-semibold w-28">
                    <input type="text" readonly v-model="purchaseOrder.materials[poIndex].material.unit" class="label-item-input bg-gray-100" name="opening_balance" :id="'opening_balance'" />
                  </td>
                    <td class="font-semibold w-28">
                        <input type="number" :readonly="purchaseOrder.material_requisition_id !== ''" @keyup="calculateAmount(poIndex)" v-model="purchaseOrder.materials[poIndex].quantity" class="label-item-input" :class="(purchaseOrder.material_requisition_id !== '') ? 'bg-gray-100' : ''" name="opening_balance" :id="'opening_balance'" />
                    </td>
                    <td class="font-semibold w-24">
                        <input type="number" readonly required step="0.01" @keyup="calculateAmount(poIndex)" v-model="purchaseOrder.materials[poIndex].unit_price" class="label-item-input bg-gray-100" name="opening_balance" :id="'opening_balance'" />
                    </td>
                    
                    <td class="font-semibold w-40">
                        <input type="hidden" readonly v-model="purchaseOrder.materials[poIndex].amount" class="bg-gray-100 label-item-input" name="opening_balance" :id="'opening_balance'" />
                        <span class="bg-gray-100 label-item-input font-normal p-2 h-9 border border-gray-500 !text-right">
                            {{ numberFormat(purchaseOrder.materials[poIndex].amount) }}
                        </span>
                    </td>
                    <td class="font-semibold w-96">
                        <input type="text" v-model="item.remarks" class="label-item-input" name="opening_balance" :id="'opening_balance'" />
                    </td>
                    <td class="font-semibold flex flex-col justify-center items-center">
                        <button type="button" @click="removeMaterial(poIndex)" :disabled="isLoading" class="flex items-center justify-center px-4 py-2 mt-2 text-sm leading-5 text-white transition-colors duration-150 bg-red-500 border border-transparent rounded-lg fon2t-medium mt- active:bg-[#0F6B90] hover:bg-red-800 focus:outline-none focus:shadow-outline-purple">-</button>
                    </td>
                </tr>
                <tr class="bg-white">
                    <td colspan="7" class="py-6">&nbsp;</td>
                </tr>
                <tr class="bg-gray-200">
                    <td colspan="2"></td>
                    <td class="font-semibold text-right pr-3" colspan="2">
                        <span class="label-item-title">Carrying Charge</span>
                    </td>
                    <td>
                        <input type="number" step="0.01" @keyup="calculateAmount()" v-model="purchaseOrder.carrying_charge" class="label-item-input" name="opening_balance" :id="'opening_balance'" />
                    </td>
                    <td class=""></td>
                    <td class=""></td>

                </tr>
                <tr class="bg-gray-200">
                    <td colspan="2"></td>
                    <td class="font-semibold text-right pr-3" colspan="2">
                        <span class="label-item-title">Labour Charge</span>
                    </td>
                    <td>
                        <input type="number" step="0.01" @keyup="calculateAmount()" v-model="purchaseOrder.labour_charge" class="label-item-input" name="opening_balance" :id="'opening_balance'" />
                    </td>
                    <td></td>
                    <td></td>

                </tr>
                <tr class="bg-gray-200">
                    <td colspan="2"></td>
                    <td class="font-semibold text-right pr-3" colspan="2">
                        <span class="label-item-title">Sub Total</span>
                    </td>
                    <td>
                        <input type="hidden" readonly v-model="purchaseOrder.sub_total" class="bg-gray-100 label-item-input" name="opening_balance" :id="'opening_balance'" />
                        <span class="bg-gray-100 label-item-input font-normal p-2 h-9 border border-gray-500 !text-right">
                            {{ numberFormat(purchaseOrder.sub_total) }}
                        </span>
                    </td>
                    <td></td>
                    <td></td>

                </tr>
                <tr class="bg-gray-200">
                    <td colspan="2"></td>
                    <td class="font-semibold text-right pr-3" colspan="2">
                        <span class="label-item-title">Round Off</span>
                    </td>
                    <td>
                        <input type="hidden" readonly v-model="purchaseOrder.round_off" class="bg-gray-100 label-item-input" name="opening_balance" :id="'opening_balance'" />
                        <span class="bg-gray-100 label-item-input font-normal p-2 h-9 border border-gray-500 !text-right">
                            {{ numberFormat(purchaseOrder.round_off) }}
                        </span>
                    </td>
                    <td></td>
                    <td></td>

                </tr>
                <tr class="bg-gray-200">
                    <td colspan="2"></td>
                    <td class="font-semibold text-right pr-3" colspan="2">
                        <span class="label-item-title">Discount</span>
                    </td>
                    <td>
                        <input type="number" step="0.01" @keyup="calculateAmount()" v-model="purchaseOrder.discount" class="label-item-input" name="opening_balance" :id="'opening_balance'" />
                    </td>
                    <td></td>
                    <td></td>

                </tr>
                <tr class="bg-gray-200">
                    <td colspan="2"></td>
                    <td class="font-semibold text-right pr-3" colspan="2">
                        <span class="label-item-title">Grand Total</span>
                    </td>
                    <td>
                        <input type="hidden" readonly v-model="purchaseOrder.grand_total" class="bg-gray-100 label-item-input" name="opening_balance" :id="'opening_balance'" />
                        <span class="bg-gray-100 label-item-input font-normal p-2 h-9 border border-gray-500 !text-right">
                            {{ numberFormat(purchaseOrder.grand_total) }}
                        </span>
                    </td>
                    <td class=""></td>
                    <td class=""></td>

                </tr>
                <tr class="bg-gray-200">
                    <td colspan="7" class="pt-3"></td>
                </tr>
                <tr class="bg-gray-200">
                    <td colspan="2"></td>
                    <td class="font-semibold text-right" colspan="2">
                        <span class="label-item-title">Tax Applicable?</span>
                    </td>
                    <td>
                        <input type="radio" value="1" v-model="purchaseOrder.is_src_tax_applicable" class="ml-1" name="is_src_tax_applicable" :id="'tax_applicable'" />
                        <label class="ml-2" for="tax_applicable">Yes</label>
                        <input type="radio" value="0" v-model="purchaseOrder.is_src_tax_applicable" class="ml-3" name="is_src_tax_applicable" :id="'tax_not_applicable'" />
                        <label class="ml-2" for="tax_not_applicable">No</label>
                    </td>
                    <td class=""></td>
                    <td class=""></td>

                </tr>
                <tr class="bg-gray-200">
                    <td colspan="7" class="pb-3"></td>
                </tr>
                <tr class="bg-gray-200">
                    <td colspan="2"></td>
                    <td class="font-semibold text-right" colspan="2">
                        <span class="label-item-title">Vat Applicable?</span>
                    </td>
                    <td>
                        <input type="radio" value="1" v-model="purchaseOrder.is_src_vat_applicable" class="ml-1" name="is_src_vat_applicable" :id="'vat_applicable'" />
                        <label class="ml-2" for="vat_applicable">Yes</label>
                        <input type="radio" value="0" v-model="purchaseOrder.is_src_vat_applicable" class="ml-3" name="is_src_vat_applicable" :id="'vat_not_applicable'" />
                        <label class="ml-2" for="vat_not_applicable">No</label>
                    </td>
                    <td class=""></td>
                    <td class=""></td>

                </tr>
                <tr class="bg-gray-200">
                    <td colspan="7" class="pb-3"></td>
                </tr>
            </table>
        </legend>
        <legend class="mt-5">
            <strong>Terms &amp; Remarks</strong>
            <div class="input-group">
                
                <label class="label-group">
                    <span class="label-item-title">Terms</span>
                    <textarea v-model="purchaseOrder.terms" class="label-item-input" name="terms" :id="'terms'" style="min-height: 100px"></textarea>
                </label>
                <label class="label-group">
                    <span class="label-item-title">Remarks</span>
                    <textarea type="text" v-model="purchaseOrder.remarks" class="label-item-input" name="remarks" :id="'remarks'"  style="min-height: 100px"></textarea>
                </label>
            </div>
        </legend>
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
        @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
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

    table, tr, th, td {
        @apply border border-collapse p-1
    }

</style>