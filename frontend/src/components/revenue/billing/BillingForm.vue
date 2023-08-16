<script setup>
    import { ref, watch, onMounted, computed } from 'vue';
    import Error from "../../Error.vue";
    import useCustomer from '../../../composables/configuration/useCustomer.js';
    import moment from "moment";
    import useBilling from '../../../composables/revenue/useBilling.js';
    import useHelper from '../../../composables/useHelper';


    const { customer, customers, searchCustomerByCode } = useCustomer();
    const { unpaidBills, getPendingBills, storeBill, updateBill } = useBilling();
    const { numberFormat } = useHelper();

    const props = defineProps({
        bill: { type: Object, required: true },
        formType: { type: String, required: true },
        billId: { type: String, required: true },
    });
    const now = moment();

    function fetchCustomer(query, loading) {
        searchCustomerByCode(query, loading);
        loading(true)
    }

    watch(() => props.bill.customer, (value) => {
        props.bill.customer_id = value?.id;
        props.bill.customer_name = value?.name;

        props.bill.billing_address = value?.billing_address
        props.bill.billing_name = value?.name
        props.bill.billing_email = value?.billing_email
        props.bill.billing_contact = value?.official_contact
        props.bill.credit_days = value?.credit_days

    }, { deep: true });

    function fetchSlips() {
        if(props.bill.customer_id == '') {
            alert('Please choose a customer');
            return false;
        }
        if(props.bill.billing_from && props.bill.billing_till && props.bill.customer_id) {
            getPendingBills(props.bill.billing_from, props.bill.billing_till, props.bill.customer_id);
        }
    }

    function removeSaleFromBill(index) {
        props.bill.unpaid_bills.splice(index, 1);
    }

    function removePreviousUnpaidBills(index) {
        props.bill.previous_unpaid_bills.splice(index, 1);
    }

    watch(() => unpaidBills, (value) => {
        
        if(value) {
            props.bill.unpaid_bills = value.value.unpaid_bills
            props.bill.previous_unpaid_bills = value.value.previous_unpaid_bills
        }
    }, { deep: true })

    onMounted(() => {
        if(props.formType=='create') {
            props.bill.bill_date = now.format('YYYY-MM-DD');

            // Get the start date of the previous month
            const startDate = now.clone().subtract(1, 'month').startOf('month');

            // Get the end date of the previous month
            const endDate = now.clone().subtract(1, 'month').endOf('month');

            // Format the dates as desired
            const startDateFormatted = startDate.format('YYYY-MM-DD');
            const endDateFormatted = endDate.format('YYYY-MM-DD');

            props.bill.billing_from = startDateFormatted;
            props.bill.billing_till = endDateFormatted;
        }
    });

    watch(() => props.bill, (value) => {

        let total = 0;
        props.bill.unpaid_bills.forEach((unpaidBill) => {
            let amount = (unpaidBill.amount != '') ? unpaidBill.amount : 0;
            total += parseFloat(amount);
        });

        props.bill.previous_unpaid_bills.forEach((previousUnpaidBill) => {
            let amount = (previousUnpaidBill.amount != '') ? previousUnpaidBill.amount : 0;
            total += parseFloat(amount);
        });
        
        props.bill.sub_total = total;

        let vatAmount = (props.bill.vat_amount != '') ? props.bill.vat_amount : 0;

        props.bill.amount = total + ((vatAmount != null ) ? parseFloat(vatAmount) : 0);

        return parseFloat(total).toFixed(2);
    }, {deep:true} );

    watch(() => props.bill.vat_percentage, (value) => {
        if(value) {
            props.bill.vat_amount = (props.bill.sub_total * (value/100)).toFixed(2)
        }
    }, { deep: true })

    function checkVatAmount() {
        props.bill.vat_percentage = null;
    }

    function processData() {

        if(props.formType=='create') {
            storeBill(props.bill)
        } else if(props.formType=='edit') {
            updateBill(props.bill, props.billId)
        }
    }

</script>
<template>
    <div class="border-b border-gray-200 dark:border-gray-700">
        <div class="flex justify-between mb-5">
            <label for="" class="flex w-3/12 items-center">
                <h2 class="text-md font-semibold dark:text-white w-36">Bill Date <span class="required-style">*</span></h2>
                <input type="date" required v-model="bill.bill_date" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
            </label>
        </div>
        <fieldset class="px-3 border border-gray-500 rounded-sm pt-3 pb-5">
            <legend class="px-2">Customer Info:</legend>
                <div class="grid grid-cols-4 gap-4">
                    <label for="">
                        <h2 class="text-md font-semibold dark:text-white">Customer Code <span class="required-style">*</span></h2>
                        <v-select :options="customers" placeholder="--Choose an option--" @search="fetchCustomer"  v-model="bill.customer" label="code" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                            <template #search="{attributes, events}">
                                <input
                                    class="vs__search"
                                    :required="!bill.customer"
                                    v-bind="attributes"
                                    v-on="events"
                                    />
                            </template>
                        </v-select>
                        <input type="hidden" required v-model="bill.customer_id" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                    </label>
                    <label for="">
                        <h2 class="text-md font-semibold dark:text-white">Customer Name</h2>
                        <input type="text" readonly v-model="bill.customer_name" class="bg-gray-100 block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                    </label>
                    <label for="">
                        <h2 class="text-md font-semibold dark:text-white">Credit Days</h2>
                        <input type="text" v-model="bill.credit_days" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                    </label>
                    <label for="">
                        <h2 class="text-md font-semibold dark:text-white">Billing Name</h2>
                        <input type="text" v-model="bill.billing_name" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                    </label>
                    <label for="">
                        <h2 class="text-md font-semibold dark:text-white">Billing Address</h2>
                        <input type="text" v-model="bill.billing_address" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                    </label>
                    <label for="">
                        <h2 class="text-md font-semibold dark:text-white">Billing Contact</h2>
                        <input type="text" v-model="bill.billing_contact" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                    </label>
                    <label for="">
                        <h2 class="text-md font-semibold dark:text-white">Billing Email</h2>
                        <input type="text" v-model="bill.billing_email" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                    </label>
                    <label for="">
                        <h2 class="text-md font-semibold dark:text-white">Billing CC Email</h2>
                        <input type="text" v-model="bill.billing_cc_emails" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                    </label>
                </div>
        </fieldset>
        <fieldset class="px-3 border border-gray-500 rounded-sm pt-3 pb-5">
            <legend class="px-2">Billing Period:</legend>
                <div class="grid grid-cols-5 gap-4">
                    <label for="">
                        <h2 class="text-md font-semibold dark:text-white">From <span class="required-style">*</span></h2>
                        <input type="date" required v-model="bill.billing_from" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                    </label>
                    <label for="">
                        <h2 class="text-md font-semibold dark:text-white">Till <span class="required-style">*</span></h2>
                        <input type="date" required v-model="bill.billing_till" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                    </label>
                    <label for="">
                        <button @click="fetchSlips()" type="button" class="flex items-center justify-between gap-1 px-4 py-2 mt-6 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-[#0F6B61]  border border-transparent rounded-lg active:bg-[#0F6B90]  hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                            Search
                        </button>
                    </label>
                </div>
        </fieldset>
        <div v-if="bill.unpaid_bills.length > 0">
            <div class="flex justify-center items-center my-2">
                <h2 class="text-xl font-semibold text-gray-600">
                    Credit Sales List
                </h2>
            </div>
            <table class="w-full">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Credit Slip No</th>
                        <th>Product Names</th>
                        <th>Quantity</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="sale, index in bill.unpaid_bills" :key="index">
                        <tr>
                            <td>{{ index+1 }}</td>
                            <td>{{  moment(sale.date_time).format('DD/MM/YYYY')  }}</td>
                            <td>{{ sale.slip_no }}</td>
                            <td>
                                <template v-for="(stockable,index) in sale?.stockable" :key="index">
                                    <span class="p-1 bg-gray-100 rounded-md m-1 inline-block" v-if="stockable.material != null">
                                        {{ stockable?.material?.name }}
                                    </span>
                                </template>
                            </td>
                            <td>
                                {{ sale.stockable.reduce((acc, item) => acc + Math.abs(item.quantity), 0) }}
                            </td>
                            <td class="!text-right">{{ numberFormat(sale.stockable.reduce((acc, item) => acc + Math.abs(item.amount), 0)) }}</td>
                            <td>
                                <button @click="removeSaleFromBill(index)" type="button" class="my-2 mx-auto flex items-center justify-center text-sm leading-5 text-white transition-colors duration-150 bg-red-500 border border-transparent rounded-full active:bg-[#0F6B90] hover:bg-red-800 focus:outline-none focus:shadow-outline-purple">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </template>
                    <tr v-if="bill.previous_unpaid_bills.length > 0">
                        <td colspan="7" class="!text-left pl-2 w-full py-2 font-semibold text-lg bg-gray-200">
                            Previous Unpaid Bills
                        </td>
                    </tr>
                    <template v-for="sale, index in bill.previous_unpaid_bills" :key="index">
                        <tr>
                            <td>{{ index+1 }}</td>
                            <td>{{  moment(sale.date_time).format('DD/MM/YYYY')  }}</td>
                            <td>{{ sale.slip_no }}</td>
                            <td>
                                <template v-for="(stockable,index) in sale?.stockable" :key="index">
                                    <span class="p-1 bg-gray-100 rounded-md m-1 inline-block" v-if="stockable.material != null">
                                        {{ stockable?.material?.name }}
                                    </span>
                                </template>
                            </td>
                            <td>
                                {{ sale.stockable.reduce((acc, item) => acc + Math.abs(item.quantity), 0) }}
                            </td>
                            <td class="!text-right">{{ numberFormat(sale.stockable.reduce((acc, item) => acc + Math.abs(item.amount), 0)) }}</td>
                            <td>
                                <button @click="removePreviousUnpaidBills(index)" type="button" class="my-2 mx-auto flex items-center justify-center text-sm leading-5 text-white transition-colors duration-150 bg-red-500 border border-transparent rounded-full active:bg-[#0F6B90] hover:bg-red-800 focus:outline-none focus:shadow-outline-purple">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </template>

                    <tr>
                        <td colspan="5" class="!text-right pr-2 w-full py-2 font-semibold text-lg bg-gray-200">
                            Sub Total
                        </td>
                        <td class="!text-right">
                            {{ numberFormat(bill.sub_total) }}
                        </td>
                        <td>
                            
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td></td>
                        <td class="text-right">
                            <div class="flex justify-end items-center">
                                <span class="pr-3">
                                    Vat (%)
                                </span>
                                <input type="number" v-model="bill.vat_percentage" class="!text-right m-1 block w-32 mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                            </div>
                        </td>
                        <td>Vat Amount</td>
                        <td>
                            <input type="number" required @keydown="checkVatAmount" v-model="bill.vat_amount" class="!text-right m-1 block w-32 mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="5" class="!text-right pr-2 w-full py-2 font-semibold text-lg bg-gray-200">
                            Grand Total
                        </td>
                        <td class="!text-right">
                            {{ numberFormat(bill.amount) }}
                        </td>
                        <td>
                            
                        </td>
                    </tr>
                </tbody>

            </table>
            <div class="flex justify-center mb-4">
                <button type="button" @click="processData()" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg fon2t-medium mt- active:bg-[#0F6B61] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">Submit</button>
            </div>
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