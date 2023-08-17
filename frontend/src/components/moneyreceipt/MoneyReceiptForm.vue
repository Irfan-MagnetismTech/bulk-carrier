<script setup>
  import {onMounted} from "@vue/runtime-core";
  import useVoyage from "../../composables/operation/useVoyage";
  import useCustomer from "../../composables/commercial/useCustomer";
  import {computed, ref, watch, watchEffect} from "vue";
  import useService from "../../composables/commercial/useService";
  import useInvoice from "../../composables/commercial/useInvoice";
  import DropZoneV2 from '../../components/DropZoneV2.vue';
  import {useStore} from "vuex";

  const props = defineProps({
    form: {
      required: false,
      default: {}
    },
    page: {
      required: false,
      default: {},
    }
  });
  const store = useStore();
  const dropZoneFile = ref(computed(() => store.getters.getDropZoneFile));

  watch(dropZoneFile, (value) => {
    if (value !== null && value !== undefined) {
      props.form.attachment = value;
    }
  });

  const { customers, getCustomerWithoutPaginate } = useCustomer();
  const { customer, invoice, getCustomerDetails, showInvoice } = useInvoice();

  let activeCollectionInput = ref('USD');

  function addRow() {
    let obj = {
      id: Math.random(),
      money_receipt_id: '',
      currency: 'USD',
      payment_method: '',
      source_name: '',
      trx_no: '',
      dated: '',
      amount: 0.0,
      sub_amount: 0.0,
    };
    props.form.money_receipt_methods.push(obj);
  }

  function removeRow(index){
    props.form.money_receipt_methods.splice(index, 1);
  }

  function addInvoiceRow(){
    let obj = {
      id: Math.random(),
      invoice_id: '',
      is_edit_data: false,
      currency: '',
      amount: 0.0,
      date: '',
      invoice_amount: 0.0,
      amount_in_bdt: 0.0,
    };
    props.form.money_receipt_invoices.push(obj);
  }

  function removeInvoiceRow(index){
    props.form.money_receipt_invoices.splice(index, 1);
  }

  function changeAmount() {
    let subTotal = parseFloat(props.form.sub_total) ?? 0;
    let vat = props.form.vat !== '' ? subTotal * (parseFloat(props.form.vat) / 100) : 0;
    let ait = props.form.ait !== '' ? subTotal * (parseFloat(props.form.ait) / 100) : 0;
    props.form.amount = parseFloat(subTotal + vat + ait).toFixed(2) ?? 0;
    props.form.total = parseFloat(subTotal + vat + ait).toFixed(2) ?? 0;
  }

  watch(() => props.form.customer_id, () => {
    getCustomerDetails(props.form.customer_id);
  });

  // watch(() => props.form.invoice_id, () => {
  //   showInvoice(props.form.invoice_id);
  // });

  watch(invoice, () => {
    props.form.amount = invoice?.value?.total;
  },{deep: true});

  watch(() => props.form.money_receipt_methods, () => {
    if(props.form.money_receipt_methods?.length) {
      let total = 0;
      props.form.money_receipt_methods.forEach((method, index) => {
        total += parseFloat(props.form.money_receipt_methods[index].amount);
      });
      props.form.collected_amount = total.toFixed(2) ?? 0;
      props.form.due_amount = (props.form.amount - props.form.collected_amount).toFixed(2) ?? 0;
      //changeAmount();
    }
  }, { deep: true });

  function getInvoiceAmount(index){
    //let invoiceAmount = 0;

    if(props.form.money_receipt_invoices[index].invoice_id !== ''){
      if(customer?.value?.invoices?.length) {
        let selected_invoice = customer?.value?.invoices.find(item => item.id === props.form.money_receipt_invoices[index].invoice_id);
        if(selected_invoice !== undefined){
          props.form.money_receipt_invoices[index].currency = selected_invoice?.currency;
          props.form.money_receipt_invoices[index].exchange_rate = parseFloat(selected_invoice?.exchange_rate).toFixed(2);
          props.form.money_receipt_invoices[index].date = selected_invoice?.issue_date;
          props.form.money_receipt_invoices[index].invoice_amount = selected_invoice?.amount;
          props.form.money_receipt_invoices[index].due_amount = selected_invoice?.due_amount.toFixed(2);
          props.form.money_receipt_invoices[index].due_amount_in_bdt = (selected_invoice?.due_amount * selected_invoice?.exchange_rate).toFixed(2);
        }
      }
    }
  }

  watch(() => props.form.money_receipt_invoices, () => {
    if(props.form.money_receipt_invoices?.length) {
      let totalPaymentAmount = 0;
      let totalInvoiceAmount = 0;
      let totalInvoiceAmountInBDT = 0;

      props.form.money_receipt_methods.forEach((method, index) => {
        totalPaymentAmount += parseFloat(props.form.money_receipt_methods[index].amount);
        activeCollectionInput.value = props.form.money_receipt_methods[index].currency;
      });

      props.form.money_receipt_invoices.forEach((invoice, index) => {
        totalInvoiceAmount += parseFloat(props.form.money_receipt_invoices[index].amount);
        totalInvoiceAmountInBDT += parseFloat(props.form.money_receipt_invoices[index].amount_in_bdt);
        getInvoiceAmount(index);
      });

      props.form.total_collected_amount = totalInvoiceAmount.toFixed(2) ?? 0;
      props.form.total_collected_amount_in_bdt = totalInvoiceAmountInBDT.toFixed(2) ?? 0;

    }
  }, { deep: true });

  function setCurrencyWiseCollectionAmountInput($e,index){
    activeCollectionInput.value = $e.target.value;

    props.form.money_receipt_invoices.forEach((invoice, index) => {
      props.form.money_receipt_invoices[index].amount = 0;
      props.form.money_receipt_invoices[index].amount_in_bdt = 0;
    });

  }

  function calculateAmountInBDT(index){
    props.form.money_receipt_invoices[index].amount_in_bdt = (props.form.money_receipt_invoices[index].amount * props.form.money_receipt_invoices[index].exchange_rate).toFixed(2);
    props.form.money_receipt_invoices[index].due_amount = (props.form.money_receipt_invoices[index].invoice_amount - props.form.money_receipt_invoices[index].amount).toFixed(2);
    props.form.money_receipt_invoices[index].due_amount_in_bdt = (props.form.money_receipt_invoices[index].due_amount * props.form.money_receipt_invoices[index].exchange_rate).toFixed(2);
  }

  function calculateAmountInUSD(index){
    props.form.money_receipt_invoices[index].amount = (props.form.money_receipt_invoices[index].amount_in_bdt / props.form.money_receipt_invoices[index].exchange_rate).toFixed(2);
    props.form.money_receipt_invoices[index].due_amount = (props.form.money_receipt_invoices[index].invoice_amount - props.form.money_receipt_invoices[index].amount).toFixed(2);
    props.form.money_receipt_invoices[index].due_amount_in_bdt = (props.form.money_receipt_invoices[index].due_amount * props.form.money_receipt_invoices[index].exchange_rate).toFixed(2);
  }

  onMounted(() => {
    getCustomerWithoutPaginate();
  });
</script>

<template>
    <!-- Basic information -->
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300">Customer Info</legend>
    <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Received Date <span class="text-red-500">*</span></span>
        <input type="date" v-model="form.issue_date" class="w-full text-sm rounded form-input" required>
        <input type="hidden" v-model="form.issue_date">
      </label>
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Customer Code <span class="text-red-500">*</span></span>
        <v-select :options="customers" placeholder="--Choose an option--" v-model="form.customer_id" :reduce="customer => customer.id" label="customer_code" required></v-select>
      </label>
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Customer Name</span>
        <input type="text" :value="customer?.customer_name" placeholder="Customer name" readonly class="w-full text-sm rounded form-input bg-gray-300">
      </label>
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Exchange Rate</span>
        <input type="text" :value="customer?.customer_name" placeholder="Customer name" class="w-full text-sm rounded form-input bg-gray-300">
      </label>
    </div>
  </fieldset>
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300">Payment Methods</legend>
    <table class="w-full whitespace-no-wrap" id="table">
      <thead>
      <tr class="text-xs font-semibold tracking-wide text-center text-gray-500  bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th class="p-2 align-bottom">Method</th>
        <th class="p-2 align-bottom">Currency</th>
        <th class="p-2 align-bottom">Amount</th>
        <th class="p-2 align-bottom">Bank Name</th>
        <th class="p-2 align-bottom">Trx Ref</th>
        <th class="p-2 align-bottom">Doc Date</th>
        <th class="p-2 text-center align-bottom">Action</th>
      </tr>
      </thead>

      <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
      <tr class="text-gray-700 dark:text-gray-400" v-for="(method, index) in form.money_receipt_methods" :key="method.id">
        <td class="px-1 py-1" style="width: 18%">
          <select v-model="form.money_receipt_methods[index].payment_method" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            <option value="" selected disabled>Select</option>
            <option value="Cash">Cash</option>
            <option value="Cheque">Cheque</option>
            <option value="Pay Order">Pay Order</option>
            <option value="Wire Transfer">Wire Transfer</option>
            <option value="Swift">Swift</option>
            <option value="Bank Transfer">Bank Transfer</option>
            <option value="Agent Collection">Agent Collection</option>
            <option value="Others">Others</option>
          </select>
        </td>
        <td class="px-1 py-1" style="width: 20%">
          <select v-model="form.money_receipt_methods[index].currency" @change="setCurrencyWiseCollectionAmountInput($event,index)" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            <option value="USD">USD</option>
            <option value="BDT">BDT</option>
          </select>
<!--          <input type="text" v-model="form.money_receipt_methods[index].currency" placeholder="Currency" minlength="3" maxlength="3" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">-->
        </td>
        <td class="px-1 py-1" style="width: 20%">
          <input type="number" v-model="form.money_receipt_methods[index].amount" placeholder="Amount" step=".01" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        </td>
        <td class="px-1 py-1" style="width: 30%">
          <input type="text" placeholder="Bank name" v-model="form.money_receipt_methods[index].source_name" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        </td>
        <td class="px-1 py-1" style="width: 24%">
          <input type="text" placeholder="Trx no" v-model="form.money_receipt_methods[index].trx_no" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        </td>
        <td class="px-1 py-1" style="width: 15%">
          <input type="date" v-model="form.money_receipt_methods[index].dated" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        </td>
        <td class="px-1 py-1 text-center">
          <button v-if="index!=0" type="button" @click="removeRow(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
            </svg>
          </button>
          <button v-else type="button" @click="addRow()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
          </button>
        </td>
      </tr>
      </tbody>
    </table>
  </fieldset>
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300">Invoice No</legend>
    <table class="w-full whitespace-no-wrap" id="table">
      <thead>
      <tr class="text-xs font-semibold tracking-wide text-center text-gray-500  bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th class="p-2 align-bottom">List of Invoice</th>
        <th class="p-2 align-bottom">Currency</th>
        <th class="p-2 align-bottom"><nobr>Ex. Rate</nobr></th>
        <th class="p-2 align-bottom">Date</th>
        <th class="p-2 align-bottom"><nobr>In. Amount</nobr></th>
        <th class="p-2 align-bottom"><nobr>Due Amount(USD)</nobr></th>
        <th class="p-2 align-bottom"><nobr>Due Amount(BDT)</nobr></th>
        <th class="p-2 align-bottom"><nobr>Collected Amount(USD)</nobr></th>
        <th class="p-2 align-bottom"><nobr>Collected Amount(BDT)</nobr></th>
        <th class="p-2 text-center align-bottom">Action</th>
      </tr>
      </thead>

      <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
      <tr class="text-gray-700 dark:text-gray-400" v-for="(invoice, index) in form.money_receipt_invoices" :key="invoice.id">
        <td class="px-1 py-1" style="width: 20%">
          <template v-if="form.money_receipt_invoices[index].is_edit_data">
            <input type="text" readonly v-model="form.money_receipt_invoices[index].invoice_number" step=".01" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input vms-readonly-input">
          </template>
          <template v-else>
            <v-select :options="customer?.invoices" placeholder="--Choose an option--"
                      v-model="form.money_receipt_invoices[index].invoice_id" :reduce="invoice => invoice.id"
                      label="invoice_number" required>
            </v-select>

            <!--            <select v-model="form.money_receipt_invoices[index].invoice_id" @change="getInvoiceAmount(index)" required class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">-->
<!--              <option value="" selected disabled>Select</option>-->
<!--              <option :value="customerInvoice.id" v-for="(customerInvoice,index) in customer?.invoices">{{ customerInvoice?.invoice_number }}</option>-->
<!--            </select>-->
          </template>
        </td>
        <td class="px-1 py-1">
          <input type="text" readonly v-model="form.money_receipt_invoices[index].currency" step=".01" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input vms-readonly-input">
        </td>

        <td class="px-1 py-1" style="width: 10%">
          <input type="text" readonly v-model="form.money_receipt_invoices[index].exchange_rate" step=".01" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input vms-readonly-input">
        </td>

        <td class="px-1 py-1" style="width: 10%">
          <input type="text" readonly v-model="form.money_receipt_invoices[index].date" class="block w-full rounded xt-sm 1 vms-readonly-input dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        </td>
        <td class="px-1 py-1" style="width: 15%">
          <input type="number" readonly v-model="form.money_receipt_invoices[index].invoice_amount" step=".01" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input vms-readonly-input">
        </td>
        <td class="px-1 py-1" style="width: 15%">
          <input type="number" readonly v-model="form.money_receipt_invoices[index].due_amount" step=".01" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input vms-readonly-input">
        </td>
        <td class="px-1 py-1" style="width: 15%">
          <input type="number" readonly v-model="form.money_receipt_invoices[index].due_amount_in_bdt" step=".01" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input vms-readonly-input">
        </td>
        <td class="px-1 py-1" style="width: 20%">
          <input type="number" :readonly="activeCollectionInput === 'BDT'" :class="{'vms-readonly-input': activeCollectionInput==='BDT'}" v-model="form.money_receipt_invoices[index].amount"
                 @input="calculateAmountInBDT(index)"
                 placeholder="Amount" step=".01" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        </td>
        <td class="px-1 py-1" style="width: 20%">
          <input type="number" :readonly="activeCollectionInput === 'USD'" :class="{'vms-readonly-input': activeCollectionInput==='USD'}" v-model="form.money_receipt_invoices[index].amount_in_bdt"
                 @input="calculateAmountInUSD(index)"
                 placeholder="Amount" step=".01" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        </td>
        <td class="px-1 py-1 text-center">
          <button v-if="index!=0" :disabled="form.money_receipt_invoices[index].is_edit_data" :class="{'vms-readonly-button': form.money_receipt_invoices[index].is_edit_data}" type="button" @click="removeInvoiceRow(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
            </svg>
          </button>
          <button v-else type="button" @click="addInvoiceRow()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
          </button>
        </td>
      </tr>
      <tr>
        <td colspan="7" class="px-1 py-1 text-right" style="text-align: right">
          <strong>Total Amount</strong>
        </td>
        <td colspan="" class="px-1 py-1 text-right">
          <input type="number" readonly v-model="form.total_collected_amount" step=".01" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input vms-readonly-input">
        </td>
        <td colspan="" class="px-1 py-1 text-right">
          <input type="number" readonly v-model="form.total_collected_amount_in_bdt" step=".01" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input vms-readonly-input">
        </td>
      </tr>
      </tbody>
    </table>
  </fieldset>
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300">Remarks & Attachment</legend>
    <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Remarks</span>
        <textarea v-model="form.remarks" placeholder="Write your remarks here.." class="w-full text-sm rounded form-input"></textarea>
      </label>
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Attachment</span>
        <DropZoneV2></DropZoneV2>
<!--        <input type="file" :value="form.attachment" class="w-full text-sm rounded form-input bg-gray-300">-->
      </label>
    </div>
  </fieldset>
</template>
<style lang="postcss" scoped>
#table, #table th, #table td{
  @apply border border-collapse border-gray-400 text-center text-gray-700 px-1
}

.input-group {
  @apply flex flex-col items-center justify-center w-full md:flex-row md:gap-2;
}
.label-group {
  @apply block w-full mt-3 text-sm;
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
#modal{
  max-width: 60rem;
}

.contract-assign-table th {
  font-size: 10px;
}

.contract-assign-table th, .contract-assign-table tr,.contract-assign-table td {
  text-align: center;
  font-size: 12px;
  @apply border border-collapse border-gray-400 text-center text-gray-700 px-1
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