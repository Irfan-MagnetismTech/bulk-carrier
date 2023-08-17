<script setup>
  import {onMounted} from "@vue/runtime-core";
  import useVoyage from "../../composables/operation/useVoyage";
  import useCustomer from "../../composables/commercial/useCustomer";
  import {computed, ref, watch, watchEffect} from "vue";
  import useService from "../../composables/commercial/useService";
  import useInvoice from "../../composables/commercial/useInvoice";
  import DropZoneV2 from '../../components/DropZoneV2.vue';
  import {useStore} from "vuex";
  import useDebouncedRef from "../../composables/useDebouncedRef";
  import useChargeType from "../../composables/dataencoding/useChargeType";

  const props = defineProps({
    form: {
      required: false,
      default: {}
    },
    page: {
      required: false,
      default: {},
    },
    invoiceId: {
      required: false,
      default: {},
    }
  });
  const store = useStore();
  const dropZoneFile = ref(computed(() => store.getters.getDropZoneFile));

  const searchContainerKey = useDebouncedRef('', 800);

  watch(dropZoneFile, (value) => {
    if (value !== null && value !== undefined) {
      props.form.attachment = value;
    }
  });

  const { customers, getCustomerWithoutPaginate } = useCustomer();
  const { chargeTypes, getChargeTypeWithoutPaginate } = useChargeType();
  const { customer, invoice, invoiceContainers, getInvoiceContainers, tempInvoiceContainers, isInvoiceContainerModalOpen, storeInvoiceNote, getCustomerDetails, showInvoice, isLoading } = useInvoice();

  let globalIndex = ref(null);
  const formParams = ref( {});

  let total_amount = ref(0);
  let total_revised_amount = 0;
  let total_new_revised_amount = 0;

  function addRow() {
    let obj = {
      id: Math.random(),
      money_receipt_id: '',
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

  watch(() => props.form.invoice_id, () => {
    showInvoice(props.form.invoice_id);
  });

  watch(invoice, () => {
    props.form.amount = parseFloat(invoice?.value?.total).toFixed(2) ?? 0;
  },{deep: true});

  function getCostWiseContainerList(index){

    globalIndex.value = index;
    isInvoiceContainerModalOpen.value = true;

    //getInvoiceContainers(data);

  }

  function closeInvoiceContainerModal() {
    isInvoiceContainerModalOpen.value = 0;
  }

  watch(searchContainerKey, newQuery => {
    // filter invoice containers by container no
    if (newQuery !== '') {
      invoiceContainers.value = invoiceContainers.value.filter((container) => {
        return container.container.toLowerCase().includes(newQuery.toLowerCase());
      });
    } else {
      invoiceContainers.value = tempInvoiceContainers.value;
    }
  });

  // watch props form
  watch(() => props.form, () => {

    if(props.form){

     props.form.parent_invoice_id = props.invoiceId;
     props.form.amount = 0.0;
     //for each invoice lines push a object in  invoice lines
      props.form.invoice_lines.forEach((line, index) => {
        total_amount.value += parseFloat(line.amount);
        props.form.invoice_lines[index].is_additional_billing = 0;
        props.form.invoice_lines[index].adjusted_amount = line.amount;
        props.form.invoice_lines[index].revised_amount = 0;
        props.form.invoice_lines[index].selected_description = line.description;
        props.form.invoice_lines[index].amount_to_be_distributed = parseFloat(line.amount).toFixed(2);

        total_revised_amount += parseFloat(props.form.invoice_lines[index].adjusted_amount);
        total_new_revised_amount += parseFloat(props.form.invoice_lines[index].revised_amount);

        line.invoice_containers.forEach((container, containerIndex) => {
          props.form.invoice_lines[index].invoice_containers[containerIndex].given_amount = parseFloat(container.amount).toFixed(2);
          props.form.invoice_lines[index].invoice_containers[containerIndex].amount_difference = 0;
          props.form.invoice_lines[index].invoice_containers[containerIndex].amount = parseFloat(container.amount).toFixed(2);
        });

      });
      props.form.total_revised_amount = total_revised_amount.toFixed(2);
      props.form.total_new_revised_amount = total_new_revised_amount.toFixed(2);
      formParams.value = props.form;
    }
  });


  function addInvoiceLineRow() {
    let obj = {
      id: '',
      description: '',
      selected_description: '',
      current_amount: 0.0,
      given_amount: 0.0,
      amount: 0.0,
      selected_amount: 0.0,
      charge_code: '',
      invoice_containers: [],
      is_additional_billing: 1,
    };
    //console.log(obj);
    props.form.invoice_lines.push(obj);
  }

  function removeInvoiceLineRow(index){
    formParams.value.invoice_lines.splice(index, 1);
  }

  function updateContainerAdjustedAmount(containerIndex){

    formParams.value.invoice_lines[globalIndex.value].invoice_containers[containerIndex].amount_difference = Number(formParams.value.invoice_lines[globalIndex.value].invoice_containers[containerIndex].given_amount) - Number(formParams.value.invoice_lines[globalIndex.value].invoice_containers[containerIndex].amount);
    updateSelectedAmount();
  }

  function updateSelectedAmount(){
    let totalInvoiceAmount = 0;
    total_revised_amount = 0;
    total_new_revised_amount = 0;

    formParams.value.invoice_lines.forEach((line, lineInternalIndex) => {
      let total = 0;
      //for each container in invoice lines
      formParams.value.invoice_lines[lineInternalIndex].invoice_containers.forEach((container, containerInternalIndex) => {
        total += Number(formParams.value.invoice_lines[lineInternalIndex].invoice_containers[containerInternalIndex].given_amount).toFixed(2);
      });
      //console.log("Container Total: "+total);
      formParams.value.invoice_lines[lineInternalIndex].amount_to_be_distributed = Number(total).toFixed(2);
      formParams.value.invoice_lines[globalIndex.value].adjusted_amount = Number(total).toFixed(2);
      formParams.value.invoice_lines[globalIndex.value].revised_amount = (formParams.value.invoice_lines[globalIndex.value].invoice_containers.reduce((total, container) => total + Number(container.amount_difference), 0)).toFixed(2);

      totalInvoiceAmount += parseFloat(formParams.value.invoice_lines[lineInternalIndex].adjusted_amount).toFixed(2);

      total_revised_amount += parseFloat(formParams.value.invoice_lines[lineInternalIndex].adjusted_amount);
      total_new_revised_amount += parseFloat(formParams.value.invoice_lines[lineInternalIndex].revised_amount);

    });
    formParams.value.total_revised_amount = total_revised_amount.toFixed(2);
    formParams.value.total_new_revised_amount = total_new_revised_amount.toFixed(2);
    formParams.value.amount = parseFloat(totalInvoiceAmount).toFixed(2);
  }

  function updateAdjustedAmount(){
    let totalInvoiceAmount = 0;
    total_revised_amount = 0;
    total_new_revised_amount = 0;

    formParams.value.invoice_lines.forEach((line, lineInternalIndex) => {
      let total = 0;
      //for each container in invoice lines
      formParams.value.invoice_lines[lineInternalIndex].invoice_containers.forEach((container, containerInternalIndex) => {
        total += parseFloat(formParams.value.invoice_lines[lineInternalIndex].invoice_containers[containerInternalIndex].given_amount);
      });
      //console.log("Container Total: "+total);
      formParams.value.invoice_lines[lineInternalIndex].amount_to_be_distributed = total.toFixed(2);
      //alert(formParams.value.invoice_lines[globalIndex.value].amount);
      formParams.value.invoice_lines[globalIndex.value].adjusted_amount = (formParams.value.invoice_lines[globalIndex.value].invoice_containers.reduce((total, container) => total + Number(container.given_amount), 0)).toFixed(2);
      totalInvoiceAmount += parseFloat(formParams.value.invoice_lines[lineInternalIndex].adjusted_amount).toFixed(2);

      total_revised_amount += parseFloat(formParams.value.invoice_lines[lineInternalIndex].adjusted_amount);
      total_new_revised_amount += parseFloat(formParams.value.invoice_lines[lineInternalIndex].revised_amount);
    });

    formParams.value.total_revised_amount = total_revised_amount.toFixed(2);
    formParams.value.total_new_revised_amount = total_new_revised_amount.toFixed(2);

    formParams.value.amount = parseFloat(totalInvoiceAmount).toFixed(2);
    isInvoiceContainerModalOpen.value = 0;
  }

  function distributeAmount(){
    if (!confirm('Are you sure you want to distribute this amount?')) {
      return;
    }
    //formParams.value.invoice_lines[globalIndex.value].containers foreach
    let totalContainer = formParams.value.invoice_lines[globalIndex.value].invoice_containers.length;
    let distributedAmount = formParams.value.invoice_lines[globalIndex.value].amount_to_be_distributed / totalContainer;

    formParams.value.invoice_lines[globalIndex.value].invoice_containers.forEach((container, containerInternalIndex) => {
      formParams.value.invoice_lines[globalIndex.value].invoice_containers[containerInternalIndex].given_amount = distributedAmount;
      formParams.value.invoice_lines[globalIndex.value].invoice_containers[containerInternalIndex].amount_difference = Number(formParams.value.invoice_lines[globalIndex.value].invoice_containers[containerInternalIndex].given_amount) - Number(formParams.value.invoice_lines[globalIndex.value].invoice_containers[containerInternalIndex].amount);
    });

    formParams.value.invoice_lines[globalIndex.value].adjusted_amount = (formParams.value.invoice_lines[globalIndex.value].invoice_containers.reduce((total, container) => total + Number(container.given_amount), 0)).toFixed(2);
    let adjustedAmount = Number(formParams.value.invoice_lines[globalIndex.value].adjusted_amount);
    let amount = Number(formParams.value.invoice_lines[globalIndex.value].amount);

    let revisedAmount = Number(amount) + Number(adjustedAmount);
    formParams.value.invoice_lines[globalIndex.value].revised_amount = (formParams.value.invoice_lines[globalIndex.value].invoice_containers.reduce((total, container) => total + Number(container.amount_difference), 0)).toFixed(2);

    //for each invoice line
    total_revised_amount = 0;
    total_new_revised_amount = 0;

    formParams.value.invoice_lines.forEach((line, lineInternalIndex) => {
      total_revised_amount += parseFloat(formParams.value.invoice_lines[lineInternalIndex].adjusted_amount);
      total_new_revised_amount += parseFloat(formParams.value.invoice_lines[lineInternalIndex].revised_amount);
    });

    formParams.value.total_revised_amount = total_revised_amount;
    formParams.value.total_new_revised_amount = total_new_revised_amount;
  }

  function updateChargeDescription(e, index){
    let chargeType = chargeTypes.value.find((item) => item.code === e.target.value);
    formParams.value.invoice_lines[index].selected_description = chargeType.name;
    formParams.value.invoice_lines[index].selected_amount = parseFloat(formParams.value.invoice_lines[index].amount).toFixed(2);
  }

  onMounted(() => {
    getCustomerWithoutPaginate();
    getChargeTypeWithoutPaginate();
  });
</script>

<template>
  <form @submit.prevent="storeInvoiceNote(formParams)">
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300">Adjustments</legend>
    <table class="w-full whitespace-no-wrap" id="table">
      <thead>
      <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th class="px-4 py-3 align-bottom">SL</th>
        <th class="px-4 py-3 align-bottom">Description</th>
        <th class="px-4 py-3 align-bottom">Per</th>
        <th class="px-4 py-3 align-bottom">Invoiced <br> Amount</th>
        <th class="px-4 py-3 align-bottom"> Revised Amount <br> <strong>(+/-)</strong></th>
        <th class="px-4 py-3 align-bottom"> Net Effect</th>
        <th class="px-4 py-3 text-center align-bottom">Action</th>
      </tr>
      </thead>

      <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
      <template v-for="(line,lineIndex) in formParams.invoice_lines" :key="lineIndex">
        <tr class="text-gray-700 dark:text-gray-400" v-if="!formParams.invoice_lines[lineIndex].is_additional_billing">
          <td>{{ lineIndex   + 1 }}</td>
          <td width="30%">
            {{ line.description }}
          </td>
          <td width="10%">
              {{ line.per }}
          </td>
          <td width="20%">
            <input style="margin-bottom: .25rem;text-align: right" v-model="formParams.invoice_lines[lineIndex].amount" class="w-full text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input vms-readonly-input" type="number" step=".01" readonly >
          </td>
          <td width="20%">
            <input style="margin-bottom: .25rem;text-align: right" v-model="formParams.invoice_lines[lineIndex].adjusted_amount" class="w-full text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input vms-readonly-input" type="number" step=".01" readonly >
          </td>
          <td width="20%">
            <input style="margin-bottom: .25rem;text-align: right" v-model="formParams.invoice_lines[lineIndex].revised_amount"  class="w-full text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input vms-readonly-input" type="number" step=".01" readonly >
          </td>
          <td class="">
            <button type="button" @click="getCostWiseContainerList(lineIndex)" title="Amount Adjustment" class="items-center justify-center px-2 py-2 text-sm font-sm text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
              <span>Adjustment</span>
            </button>
          </td>
        </tr>
      </template>
      <tr class="text-gray-700 dark:text-gray-400">
        <th colspan="3">Total</th>
        <th style="text-align: right">
          <input style="margin-bottom: .25rem;text-align: right;font-weight: bold" :value="total_amount.toFixed(2)" class="w-full text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input vms-readonly-input" type="number" step=".01" readonly >
        </th>
        <th style="text-align: right">
          <input style="margin-bottom: .25rem;text-align: right;font-weight: bold" v-model="formParams.total_revised_amount" class="w-full text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input vms-readonly-input" type="number" step=".01" readonly >
        </th>
        <th style="text-align: right">
          <input style="margin-bottom: .25rem;text-align: right;font-weight: bold" v-model="formParams.total_new_revised_amount" class="w-full text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input vms-readonly-input" type="number" step=".01" readonly >
        </th>
      </tr>
      </tbody>
    </table>
  </fieldset>
    <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
      <legend class="px-2 text-gray-700 dark:text-gray-300">Others Info</legend>
      <table class="w-full whitespace-no-wrap border-collapse contract-assign-table">
        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
        <tr class="text-center text-gray-700 dark:text-gray-400">
          <td width="20%" class="text-sm font-bold break-all">Billing Party</td>
          <td class="py-1 text-sm break-all">
            <input type="text" placeholder="Billing Party" v-model="formParams.billing_party"
                   class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
        </tr>
        <tr class="text-center text-gray-700 dark:text-gray-400">
          <td width="20%" class="text-sm font-bold break-all">Billing Address</td>
          <td class="py-1 text-sm break-all">
            <input type="text" placeholder="Billing Address" v-model="formParams.billing_address"
                   class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
        </tr>
        <tr class="text-center text-gray-700 dark:text-gray-400">
          <td width="20%" class="text-sm font-bold break-all">Billing Emails</td>
          <td class="py-1 text-sm break-all">
            <input type="text" placeholder="Billing Emails" v-model="formParams.billing_emails"
                   class="block w-full text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
        </tr>
        <tr class="text-center text-gray-700 dark:text-gray-400">
          <td width="20%" class="text-sm font-bold break-all">Billing Style</td>
          <td class="py-1 text-sm break-all">
            <input type="text" placeholder="Billing Style" v-model="formParams.billing_style"
                   class="block w-full text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
        </tr>
        <tr class="text-center text-gray-700 dark:text-gray-400">
          <td width="20%" class="text-sm font-bold break-all">WO/PO/Cont. Ref</td>
          <td class="py-1 text-sm break-all">
            <input type="text" placeholder="WO/PO/Cont. Ref" v-model="formParams.customer_reference"
                   class="block w-full text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
        </tr>
        <tr class="text-center text-gray-700 dark:text-gray-400">
          <td class="text-sm font-bold break-all">HRL Reference</td>
          <td class="py-1 text-sm break-all">
            <input type="text" placeholder="HRL reference" v-model="formParams.hrl_reference"
                   class="block w-full text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          </td>
        </tr>
        <tr class="text-center text-gray-700 dark:text-gray-400">
          <td class="text-sm font-bold break-all">Note To Customer</td>
          <td class="py-1 text-sm break-all">
                <textarea type="text" placeholder="note to customer" v-model="formParams.customer_note"
                          class="block w-full text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></textarea>
          </td>
        </tr>
        <tr class="text-center text-gray-700 dark:text-gray-400">
          <td class="text-sm font-bold break-all">HRL Internal Note</td>
          <td class="py-1 text-sm break-all">
                <textarea type="text" placeholder="HRL internal note" v-model="formParams.hrl_internal_note"
                          class="block w-full text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></textarea>
          </td>
        </tr>
        <tr class="text-center text-gray-700 dark:text-gray-400">
          <td class="text-sm font-bold break-all">Attachment</td>
          <td class="py-1 text-sm break-all">
            <DropZoneV2></DropZoneV2>
          </td>
        </tr>
        </tbody>
      </table>
    </fieldset>
<!--    <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">-->
<!--      <legend class="px-2 text-gray-700 dark:text-gray-300">Additional Billing</legend>-->
<!--      <table class="w-full whitespace-no-wrap" id="table">-->
<!--        <thead>-->
<!--        <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">-->
<!--          <th class="px-4 py-3 align-bottom">SL.</th>-->
<!--          <th class="px-4 py-3 align-bottom" width="50%">Description</th>-->
<!--          <th class="px-4 py-3 align-bottom">Amount</th>-->
<!--          <th class="px-4 py-3 align-bottom">Amount <strong>(+/-)</strong></th>-->
<!--          <th class="px-4 py-3 text-center align-bottom">Action</th>-->
<!--          <th class="px-4 py-3 text-center align-bottom">-->
<!--            <button @click="addInvoiceLineRow" type="button" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple" data-v-28863478=""><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" data-v-28863478=""><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" data-v-28863478=""></path></svg></button>-->
<!--          </th>-->
<!--        </tr>-->
<!--        </thead>-->

<!--        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">-->
<!--        <template v-for="(method, index) in form.invoice_lines" :key="method.id">-->
<!--          <tr v-if="form.invoice_lines[index].is_additional_billing" class="text-gray-700 dark:text-gray-400">-->
<!--            <td>{{ index + 1 }}</td>-->
<!--            <input type="hidden" v-model="form.invoice_lines[index].charge_code">-->
<!--            <td width="50%">-->
<!--              <select v-model="form.invoice_lines[index].charge_code" @change="updateChargeDescription($event,index)" id="" class="w-full text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">-->
<!--                <option value="">Select</option>-->
<!--                <option v-for="(charge, index) in chargeTypes" :value="charge.code" :selected="charge?.code === form?.invoice_lines[index]?.charge_code">{{ charge.name }}</option>-->
<!--              </select>-->
<!--            </td>-->
<!--            &lt;!&ndash;        <td width="50%">{{ method?.description }}</td>&ndash;&gt;-->
<!--            <input type="hidden" v-model="form.invoice_lines[index].description">-->
<!--            <td width="20%">-->
<!--              <input style="margin-bottom: .25rem" v-model="form.invoice_lines[index].current_amount" class="w-full text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input vms-readonly-input" type="number" step=".01" readonly >-->
<!--            </td>-->
<!--            <td width="20%">-->
<!--              <input style="margin-bottom: .25rem" v-model="form.invoice_lines[index].amount" class="w-full text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input vms-readonly-input" type="number" step=".01" readonly >-->
<!--            </td>-->
<!--            <td class="">-->
<!--              <button type="button" @click="getCostWiseContainerList(index)" title="Amount Adjustment" class="items-center justify-center px-2 py-2 text-sm font-sm text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">-->
<!--                <span>Adjustment</span>-->
<!--              </button>-->
<!--            </td>-->
<!--            <td>-->
<!--              <button @click="removeInvoiceLineRow(index)" type="button" class="px-2 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple" data-v-28863478=""><svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" data-v-28863478=""><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" data-v-28863478=""></path></svg></button>-->
<!--            </td>-->
<!--          </tr>-->
<!--        </template>-->
<!--        </tbody>-->
<!--      </table>-->
<!--    </fieldset>-->
  <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Submit</button>
  </form>
  <template v-if="formParams.invoice_lines">
    <div v-if="formParams.invoice_lines[globalIndex]?.invoice_containers" v-show="isInvoiceContainerModalOpen" class="fixed inset-0 z-30 flex items-end overflow-y-auto bg-black bg-opacity-50 sm:items-center sm:justify-center">
      <!-- Modal -->

      <div class="w-full px-6 py-4 overflow-y-auto bg-white rounded-t-lg dark:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl" role="dialog" id="modal">
        <!-- Remove header if you don't want a close icon. Use modal body to place modal tile. -->
        <header class="flex justify-end">
          <button type="button"
                  class="inline-flex items-center justify-center w-6 h-6 mb-2 text-gray-400 transition-colors duration-150 rounded dark:hover:text-gray-200 hover: hover:text-gray-700"
                  aria-label="close" @click="closeInvoiceContainerModal">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" role="img" aria-hidden="true">
              <path
                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                  clip-rule="evenodd" fill-rule="evenodd"></path>
            </svg>
          </button>
        </header>
        <!-- Modal body -->
        <table class="w-full mb-2 whitespace-no-wrap border-collapse contract-assign-table table2">
          <thead v-once>
          <tr style="background-color: #04AA6D;color: white" class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th colspan="5" style="color: white">Details</th>
          </tr>
          </thead>
          <div class="w-full flex items-center mt-2 justify-between select-none">
            <input type="text" v-model="formParams.invoice_lines[globalIndex].selected_description" style="margin-bottom: 4px;" readonly placeholder="Description" class="vms-readonly-input block w-full px-2 py-2 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            <input min="1" type="number" v-model="formParams.invoice_lines[globalIndex].amount_to_be_distributed" style="margin-bottom: 4px;width: 200px" step=".01" placeholder="Amount" class="ml-1 block w-full px-2 py-2 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            <button type="button" title="Distribute Amount" @click="distributeAmount" style="width: 260px" class="w-full relative text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent ml-1 rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
              Apply
            </button>
          </div>
          <!--          <div style="float: left" class="flex items-center mt-2 justify-between select-none">-->
          <!--            &lt;!&ndash; Search &ndash;&gt;-->
          <!--            <div class="relative w-full">-->
          <!--              <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-0 w-5 h-5 mr-2 text-gray-500 bottom-2" viewBox="0 0 20 20" fill="currentColor">-->
          <!--                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />-->
          <!--              </svg>-->
          <!--              <input type="text" v-model.trim="searchContainerKey" placeholder="Search..." class="search" />-->
          <!--            </div>-->
          <!--          </div>-->
          <button type="button" @click="updateAdjustedAmount" class="w-full px-5 py-3 mt-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            Save and Close
          </button>
        </table>

        <table class="w-full mb-2 whitespace-no-wrap border-collapse contract-assign-table table2">
          <thead v-once>
<!--          <tr class="text-center text-gray-700 dark:text-gray-400">-->
<!--            <td class="text-sm" colspan="2"></td>-->
<!--            <td class="text-sm">-->
<!--              <input readonly style="margin-bottom: .25rem" value="15000" type="text" placeholder="Current amount" class="block w-full px-2 py-2 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input vms-readonly-input">-->
<!--            </td>-->
<!--          </tr>-->
          <tr style="background-color: #04AA6D;color: white" class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th style="color: white">SL.</th>
            <th style="color: white" width="40%">Container No </th>
            <th style="color: white" width="20%">Invoiced Amount</th>
            <th style="color: white" width="20%">Revised Amount </th>
            <th style="color: white" width="20%">Net Effect </th>
          </tr>
          </thead>
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr v-for="(container,containerIndex) in formParams.invoice_lines[globalIndex].invoice_containers" :key="containerIndex" class="text-center text-gray-700 dark:text-gray-400">
            <td class="text-sm">{{ containerIndex + 1 }}</td>
            <td class="text-sm">{{ container?.container }}</td>
            <td class="text-sm">
              <input readonly style="margin-bottom: .25rem" v-model="formParams.invoice_lines[globalIndex].invoice_containers[containerIndex].amount" type="text" placeholder="Current  amount" class="block w-full px-2 py-2 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input vms-readonly-input">
            </td>
            <td class="text-sm">
              <input @input="updateContainerAdjustedAmount(containerIndex)" style="margin-bottom: .25rem" v-model="formParams.invoice_lines[globalIndex].invoice_containers[containerIndex].given_amount" type="text" placeholder="Adjusted amount" class="block w-full px-2 py-2 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="text-sm">
              <input min="1" style="margin-bottom: .25rem" v-model="formParams.invoice_lines[globalIndex].invoice_containers[containerIndex].amount_difference" type="number" step=".01" readonly placeholder="Adjusted amount" class="vms-readonly-input block w-full px-2 py-2 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
          </tr>
          <tr class="text-center text-gray-700 dark:text-gray-400 bg-green-300">
            <td class="text-sm" colspan="2"><strong>TOTAL</strong></td>
            <td class="text-sm">
              <input readonly style="margin-bottom: .25rem" :value="(formParams.invoice_lines[globalIndex].invoice_containers.reduce((total, container) => total + Number(container.amount), 0)).toFixed(2)" type="text" class="block w-full px-2 py-2 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input vms-readonly-input">
            </td>
            <td class="text-sm">
              <input readonly style="margin-bottom: .25rem" :value="(formParams.invoice_lines[globalIndex].invoice_containers.reduce((total, container) => total + Number(container.given_amount), 0)).toFixed(2)" type="text" class="block w-full px-2 py-2 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input vms-readonly-input">
            </td>
            <td class="text-sm">
              <input readonly style="margin-bottom: .25rem" :value="(formParams.invoice_lines[globalIndex].invoice_containers.reduce((total, container) => total + Number(container.amount_difference), 0)).toFixed(2)" type="text" class="block w-full px-2 py-2 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input vms-readonly-input">
            </td>
          </tr>
          </tbody>
          <tfoot v-if="!formParams.invoice_lines[globalIndex].invoice_containers?.length" class="bg-white dark:bg-gray-800">
          <tr v-if="isLoading">
            <td colspan="4">Loading...</td>
          </tr>
          <tr v-else-if="!formParams.invoice_lines[globalIndex].invoice_containers?.length">
            <td colspan="4" style="font-weight: bold">No container found.</td>
          </tr>
          </tfoot>
        </table>
        <footer v-if="formParams.invoice_lines[globalIndex].invoice_containers?.length" class="flex items-center justify-center px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark:bg-gray-800">
          <button type="button" @click="updateAdjustedAmount" class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            Save and Close
          </button>
        </footer>
      </div>

    </div>
  </template>
</template>
<style lang="postcss" scoped>
@tailwind components;

#modal {
  min-width: 50rem;
  height: 100%;
}

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

.contract-assign-table th {
  font-size: 10px;
}

.contract-assign-table th, .contract-assign-table tr,.contract-assign-table td {
  text-align: center;
  font-size: 12px;
  @apply border border-collapse border-gray-400 text-center text-gray-700 px-1
}

.search {
  @apply float-right  pr-10 text-sm border border-gray-300 rounded dark:bg-gray-800 dark:text-gray-200 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray dark:border-0;
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