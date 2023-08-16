<script setup>
    import { ref, watch, onMounted, computed } from 'vue';
    import Error from "../../Error.vue";
    import useCustomer from '../../../composables/configuration/useCustomer.js';
    import moment from "moment";
    import useMoneyReceipt from '../../../composables/revenue/useMoneyReceipt.js';
    import useHelper from '../../../composables/useHelper';
    import useBank from "../../../composables/configuration/useBank.js";
    import useBilling from '../../../composables/revenue/useBilling.js';


    const { customer, customers, searchCustomerByCode } = useCustomer();
    const { paymentReceiptModel, receiptLineModel, storeMoneyReceipt, updateMoneyReceipt } = useMoneyReceipt();
    const { numberFormat, getAllPaymentMethods } = useHelper();
    const { banks, searchBank } = useBank();
    const { customerBills, getCustomerBills } = useBilling();

    const props = defineProps({
        moneyReceipt: { type: Object, required: true },
        formType: { type: String, required: true },
        moneyReceiptId: { type: String, required: true },
    });
    const now = moment();
    const paymentMethods = ref([]);

    function fetchCustomer(query, loading) {
        searchCustomerByCode(query, loading);
        loading(true)
    }

    watch(() => props.moneyReceipt.customer, (value) => {
        props.moneyReceipt.customer_id = value?.id;
        props.moneyReceipt.customer_name = value?.name;

        getCustomerBills(value?.id);
    }, { deep: true });

    const hasUpdated = ref(0);

    watch(() => props.moneyReceipt, (value) => {
        if(props.formType == 'edit' && hasUpdated.value == 0) {
            props.moneyReceipt.moneyReceiptMethods = value?.money_receipt_methods;
            props.moneyReceipt.moneyReceiptLines = value?.money_receipt_lines;
        }
        hasUpdated.value = 1;

    }, { deep: true });


    onMounted(() => {
        if(props.formType=='create') {
            props.moneyReceipt.issue_date = now.format('YYYY-MM-DD');
        }

        getAllPaymentMethods()
        .then(allPaymentMethod => {
            paymentMethods.value = allPaymentMethod
        })
        .catch(error => {
            // Handle the error
            console.error('An error occurred:', error);
        });
    });

    function addPayment(index) {
        let paymentReceiptObject = { ...paymentReceiptModel }
        props.moneyReceipt.moneyReceiptMethods.push(paymentReceiptObject);
    }

    function removePayment(index){
        if(props.moneyReceipt.moneyReceiptMethods.length>1) {
            props.moneyReceipt.moneyReceiptMethods.splice(index, 1);
        }
    }
    

    function addReceiptLine(index) {
        let receiptLineObject = { ...receiptLineModel }
        props.moneyReceipt.moneyReceiptLines.push(receiptLineObject);
    }

    function removeReceiptLine(index){
        if(props.moneyReceipt.moneyReceiptLines.length>1) {
            props.moneyReceipt.moneyReceiptLines.splice(index, 1);
        }
    }

    function fetchBank(query) {
        searchBank(query);
    }

    const totalPayment = ref(0);
    const totalAmount = ref(0);

    watch(() => props.moneyReceipt.moneyReceiptMethods, (method) => {
        totalPayment.value = 0;

        for (let index in method) {
            let item = method[index];

            if(item) {
                totalPayment.value += (item?.amount) ? parseFloat(item?.amount) : 0
            }
        }

        // if(totalAmount.value > 0 && totalPayment.value > 0) {
        //     if(totalPayment.value > totalAmount.value) {
        //         alert('Pay Order amount cannot be bigger than collected amount..')
        //         return false;
        //     }
        // }

    }, { deep : true})

    watch(() => props.moneyReceipt.moneyReceiptLines, (receiptLine) => {
        totalAmount.value = 0;
        for (let index in receiptLine) {
            let item = receiptLine[index];
            if(item?.customer_bill) {
                props.moneyReceipt.moneyReceiptLines[index].customer_bill_id = item?.customer_bill?.id
                props.moneyReceipt.moneyReceiptLines[index].invoice_date = item?.customer_bill?.bill_date
                props.moneyReceipt.moneyReceiptLines[index].invoice_amount = item?.customer_bill?.amount
                props.moneyReceipt.moneyReceiptLines[index].due_amount = item?.customer_bill?.due_amount
            }
            
            if(item) {
                totalAmount.value += (item?.amount) ? parseFloat(item?.amount) : 0
            }
              
        }
        props.moneyReceipt.amount = totalAmount.value
        if(totalAmount.value > 0 && totalPayment.value > 0) {
            if(totalPayment.value > totalAmount.value) {
                alert('Pay Order amount cannot be bigger than collected amount..')
                return false;
            }
        }
        
    }, { deep : true })

    watch(() => customerBills, (value) => {
        initiateMoneyReceiptLinesEdit()
    }, { deep : true })

    function initiateMoneyReceiptLinesEdit() {
        for (let index in props.moneyReceipt.moneyReceiptLines) {
            let line = props.moneyReceipt.moneyReceiptLines[index];
            if(!line?.customer_bill) {

                if(props.moneyReceipt.moneyReceiptLines[index].customer_bill_id) {
                    let customerBillId = props.moneyReceipt.moneyReceiptLines[index].customer_bill_id;
                    let bills = { ...customerBills.value }
                    let item = Object.values(bills).find(customer_bill => customer_bill.id == customerBillId);

                    props.moneyReceipt.moneyReceiptLines[index].customer_bill = item
                    props.moneyReceipt.moneyReceiptLines[index].customer_bill_id = item?.id
                    props.moneyReceipt.moneyReceiptLines[index].invoice_date = item?.bill_date
                    props.moneyReceipt.moneyReceiptLines[index].invoice_amount = item?.amount
                    props.moneyReceipt.moneyReceiptLines[index].due_amount = item?.due_amount
                }
            }
        }
    }

    function saveMoneyReceipt() {

        if(totalAmount.value > 0) {
            if(totalPayment.value > totalAmount.value) {
                 alert('Pay Order amount cannot be bigger than collected amount..')
                return false;
            } else {
                if(props.formType == 'edit') {
                    updateMoneyReceipt(props.moneyReceipt, props.moneyReceiptId)
                } else {
                    storeMoneyReceipt(props.moneyReceipt)
                }
            }
        }
    }

    function attachFile(e) {
     let fileData = e.target.files[0];
      props.moneyReceipt.attachment = fileData;
    }

</script>
<template>
    <div class="border-b border-gray-200 dark:border-gray-700">
        
        <fieldset class="px-3 border border-gray-500 rounded-sm pt-3 pb-5">
            <legend class="px-2">Customer Info:</legend>
                <div class="grid grid-cols-3 gap-4">
                    <label for="">
                        <h2 class="text-md font-semibold dark:text-white">Date</h2>
                        <input type="date" readonly v-model="moneyReceipt.issue_date" class="bg-gray-100 block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                    </label>
                    <label for="">
                        <h2 class="text-md font-semibold dark:text-white">Customer Code</h2>
                        <v-select :options="customers" placeholder="--Choose an option--" @search="fetchCustomer"  v-model="moneyReceipt.customer" label="code" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
                        <input type="hidden" v-model="moneyReceipt.customer_id" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                    </label>
                    <label for="">
                        <h2 class="text-md font-semibold dark:text-white">Customer Name</h2>
                        <input type="text" readonly v-model="moneyReceipt.customer_name" class="bg-gray-100 block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
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
                            <th class="py-1">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="method, index in moneyReceipt.moneyReceiptMethods" :key="index">

                            <tr>
                                <td>
                                    <v-select :options="paymentMethods" placeholder="--Choose an option--" v-model="moneyReceipt.moneyReceiptMethods[index].payment_method" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
                                </td>
                                <td>
                                    <input type="text" v-model="moneyReceipt.moneyReceiptMethods[index].amount" class="!text-right block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                                </td>
                                <td>
                                    <v-select :options="banks" placeholder="--Choose an option--" @search="fetchBank"  v-model="moneyReceipt.moneyReceiptMethods[index].source_name" label="name" :reduce="name => name.name" class="!bg-white block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
                                </td>
                                <td>
                                    <input type="text" v-model="moneyReceipt.moneyReceiptMethods[index].trx_no" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                                </td>
                                <td>
                                    <input type="date" v-model="moneyReceipt.moneyReceiptMethods[index].dated" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                                </td>
                                <td class="flex items-center justify-center mb-2 border-none">
                                    <button v-if="index == 0" type="button" @click="addPayment(index)" :disabled="isLoading" class="flex items-center justify-center px-4 py-2 mt-2 text-sm leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg fon2t-medium mt- active:bg-[#0F6B90] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">+</button>
                                    <button v-else type="button" @click="removePayment(index)" :disabled="isLoading" class="flex items-center justify-center px-4 py-2 mt-2 text-sm leading-5 text-white transition-colors duration-150 bg-red-500 border border-transparent rounded-lg fon2t-medium mt- active:bg-[#0F6B90] hover:bg-red-800 focus:outline-none focus:shadow-outline-purple">-</button>
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
                            <th class="py-1">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="method, index in moneyReceipt.moneyReceiptLines" :key="index">

                            <tr>
                                <td>
                                    <v-select :options="customerBills" placeholder="--Choose an option--"  v-model="moneyReceipt.moneyReceiptLines[index].customer_bill" label="bill_no" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
                                    <input type="hidden" v-model="moneyReceipt.moneyReceiptLines[index].customer_bill_id" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                                </td>
                                <td>
                                    <input type="date" readonly v-model="moneyReceipt.moneyReceiptLines[index].invoice_date" class="bg-gray-100 block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                                </td>
                                <td>
                                    <input type="text" readonly v-model="moneyReceipt.moneyReceiptLines[index].invoice_amount" class="bg-gray-100 block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                                </td>
                                <td>
                                    <input type="text" readonly v-model="moneyReceipt.moneyReceiptLines[index].due_amount" class="bg-gray-100 block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                                </td>
                                <td>
                                    <input type="text" v-model="moneyReceipt.moneyReceiptLines[index].amount" class="!text-right block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                                </td>
                                <td class="flex items-center justify-center mb-2 border-none">
                                    <button v-if="index == 0" type="button" @click="addReceiptLine(index)" :disabled="isLoading" class="flex items-center justify-center px-4 py-2 mt-2 text-sm leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg fon2t-medium mt- active:bg-[#0F6B90] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">+</button>
                                    <button v-else type="button" @click="removeReceiptLine(index)" :disabled="isLoading" class="flex items-center justify-center px-4 py-2 mt-2 text-sm leading-5 text-white transition-colors duration-150 bg-red-500 border border-transparent rounded-lg fon2t-medium mt- active:bg-[#0F6B90] hover:bg-red-800 focus:outline-none focus:shadow-outline-purple">-</button>
                                </td>
                            </tr>

                        </template>
                        <tr>
                            <td colspan="3"></td>
                            <td><strong>Total Amount</strong></td>
                            <td class="!text-right">{{ moneyReceipt.amount }}</td>
                        </tr>
                    </tbody>
                </table>
        </fieldset>
        <fieldset class="px-3 border border-gray-500 rounded-sm pt-3 my-5 pb-5">
            <legend class="px-2">Remarks and Attachment:</legend>
            <div class="flex items-center">
                <textarea type="text" v-model="moneyReceipt.remarks" class="block w-1/2 mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" ></textarea>
                <div class="border ml-3 px-3 py-2 w-1/2">
                    <label>Attachment</label>
                    <input type="file" @change="attachFile" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                </div>
            </div>
        </fieldset>

        <div class="flex items-center justify-center my-5">
            <button @click="saveMoneyReceipt()" type="button" class="my-2 flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg active:bg-[#0F6B61] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">
                <span v-if="formType == 'create'">Save</span>
                <span v-else>Update</span>
            </button>
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
        @apply text-gray-700 dark:text-gray-300;
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

    table, tr, td, th {
        @apply border 
    }
    th, td {
        @apply text-center
    }

</style>