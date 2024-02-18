<script setup>
import Error from "../Error.vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import {computed, onMounted, ref, watch, watchEffect} from "vue";
import useCrewCommonApiRequest from "../../composables/crew/useCrewCommonApiRequest";
import ErrorComponent from '../../components/utils/ErrorComponent.vue';
import Store from "../../store";
import RemarksComponent from "../utils/RemarksComponent.vue";


const dateFormat = ref(Store.getters.getVueDatePickerTextInputFormat.date);
const { crwAgencies, getCrewAgencyLists, crwAgencyContracts, getCrewAgencyContracts } = useCrewCommonApiRequest();
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});

function addItem() {
  let obj = {
    particular: '',
    description: '',
    per: '',
    quantity: '',
    rate: '',
    amount: '',
  };
  props.form.crwAgencyBillLines.push(obj);
}

function removeItem(index){
  props.form.crwAgencyBillLines.splice(index, 1);
}

watch(() => props.form, (value) => {
  if(value){
    props.form.ops_vessel_id = props.form?.ops_vessel_name?.id ?? '';
  }
}, {deep: true});

function calculateAmount($e,index){
  props.form.crwAgencyBillLines[index].amount = (props.form.crwAgencyBillLines[index].quantity * props.form.crwAgencyBillLines[index].rate).toFixed(2);
}

const grandTotal = computed(() => {
  let total = 0;
  props.form?.crwAgencyBillLines?.forEach((line) => {
    total += parseFloat(line.amount);
  });
  return total;
});

watch(() => grandTotal, (value) => {
  if(value){
    props.form.grand_total = value;
  }
}, {deep: true});

const netAmount = computed(() => {
  let total = 0;
  total = props.form.grand_total - props.form.discount;
  return total;
});

watch(() => netAmount, (value) => {
  if(value){
    props.form.net_amount = value;
  }
}, {deep: true});

watch(() => props.form.business_unit, (newValue, oldValue) => {
  businessUnit.value = newValue;
  if(newValue !== oldValue && oldValue != ''){
    props.form.crw_agency_id = '';
    props.form.crw_agency_contract_id = '';
  }
});

watch(() => props.form.crw_agency_id, (newValue, oldValue) => {
  if(newValue !== oldValue && oldValue != ''){
    props.form.crw_agency_contract_id = '';
  }
});

onMounted(() => {
  watchEffect(() => {
    getCrewAgencyLists(props.form.business_unit);
    getCrewAgencyContracts(null,props.form.crw_agency_id);
  });
});

</script>

<template>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <business-unit-input v-model.trim="form.business_unit"></business-unit-input>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
  </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Agency Name <span class="text-red-500">*</span></span>
      <select class="form-input" v-model.trim="form.crw_agency_id">
        <option value="" selected disabled>--Choose an option--</option>
        <option v-for="(agency,index) in crwAgencies" :value="agency.id" :key="index">{{ agency?.agency_name }}</option>
      </select>
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Agency Contract <span class="text-red-500">*</span></span>
      <select class="form-input" v-model.trim="form.crw_agency_contract_id">
        <option value="" selected disabled>--Choose an option--</option>
        <option v-for="(agencyContract,index) in crwAgencyContracts" :value="agencyContract.id" :key="index">{{ agencyContract?.contract_name }}</option>
      </select>
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Applied Date <span class="text-red-500">*</span></span>
      <VueDatePicker v-model="form.applied_date" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Bill Date <span class="text-red-500">*</span></span>
      <VueDatePicker v-model="form.invoice_date" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>
    </label>
  </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Bill No <span class="text-red-500">*</span></span>
      <input type="text" v-model.trim="form.invoice_no" placeholder="Bill No" class="form-input" autocomplete="off" required />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Bill Type <span class="text-red-500">*</span></span>
      <input type="text" v-model.trim="form.invoice_type" placeholder="Bill Type" class="form-input" autocomplete="off" required />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Billing Currency <span class="text-red-500">*</span></span>
      <select class="form-input" v-model.trim="form.invoice_currency">
        <option value="" selected disabled>--Choose an option--</option>
        <option value="BDT">BDT</option>
        <option value="USD">USD</option>
      </select>
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Bill Amount <span class="text-red-500">*</span></span>
      <input type="number" step=".01" v-model.trim="form.invoice_amount" placeholder="Bill Amount" class="form-input" autocomplete="off" required />
    </label>
  </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <RemarksComponent v-model.trim="form.remarks" :maxlength="500" :fieldLabel="'Remarks'"></RemarksComponent>
  </div>
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
    <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Bill Lines</legend>
    <table class="w-full whitespace-no-wrap" id="table">
      <thead>
      <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
        <th class="px-4 py-3 align-bottom">Particular <span class="text-red-500">*</span></th>
        <th class="px-4 py-3 align-bottom">Description </th>
        <th class="px-4 py-3 align-bottom">Per </th>
        <th class="px-4 py-3 align-bottom">Quantity <span class="text-red-500">*</span></th>
        <th class="px-4 py-3 align-bottom">Rate <span class="text-red-500">*</span></th>
        <th class="px-4 py-3 align-bottom">Amount <span class="text-red-500">*</span></th>
        <th class="px-4 py-3 text-center align-bottom">Action</th>
      </tr>
      </thead>
      <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
      <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(creAgencyBillLine, index) in form.crwAgencyBillLines" :key="creAgencyBillLine.id">
        <td class="px-1 py-1">
          <input type="text" v-model.trim="form.crwAgencyBillLines[index].particular" placeholder="Particular" class="form-input" autocomplete="off" required />
        </td>
        <td class="px-1 py-1">
          <input type="text" v-model.trim="form.crwAgencyBillLines[index].description" placeholder="Description" class="form-input" autocomplete="off" />
        </td>
        <td class="px-1 py-1">
          <input type="text" v-model.trim="form.crwAgencyBillLines[index].per" placeholder="Per" class="form-input" autocomplete="off" />
        </td>
        <td class="px-1 py-1">
          <input type="number" step=".01" v-model.trim="form.crwAgencyBillLines[index].quantity" @input="calculateAmount($event,index)" placeholder="Quantity" class="form-input" autocomplete="off" required />
        </td>
        <td class="px-1 py-1">
          <input type="number" step=".01" v-model.trim="form.crwAgencyBillLines[index].rate" @input="calculateAmount($event,index)" placeholder="Rate" class="form-input" autocomplete="off" required />
        </td>
        <td class="px-1 py-1">
          <input type="number" step=".01" v-model.trim="form.crwAgencyBillLines[index].amount" placeholder="Amount" class="form-input vms-readonly-input" readonly autocomplete="off" required />
        </td>
        <td class="px-1 py-1 text-center">
          <button v-if="index!==0" type="button" @click="removeItem(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
            </svg>
          </button>
          <button v-else type="button" @click="addItem()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
          </button>
        </td>
      </tr>
      <tr class="text-gray-700 dark-disabled:text-gray-400">
        <td class="px-1 py-1" colspan="4"></td>
        <td class="px-1 py-1 font-bold">Grand Total</td>
        <td class="px-1 py-1">
          <input type="number" step=".01" v-model.trim="form.grand_total" placeholder="Grand Total" class="form-input vms-readonly-input" readonly autocomplete="off" />
        </td>
      </tr>
      <tr class="text-gray-700 dark-disabled:text-gray-400">
        <td class="px-1 py-1" colspan="4"></td>
        <td class="px-1 py-1 font-bold">Discount</td>
        <td class="px-1 py-1">
          <input type="number" step=".01" v-model.trim="form.discount" placeholder="Discount" class="form-input" autocomplete="off" />
        </td>
      </tr>
      <tr class="text-gray-700 dark-disabled:text-gray-400">
        <td class="px-1 py-1" colspan="4"></td>
        <td class="px-1 py-1 font-bold">Net Amount</td>
        <td class="px-1 py-1">
          <input type="number" step=".01" v-model.trim="form.net_amount" placeholder="Net Amount" class="form-input vms-readonly-input" readonly autocomplete="off" />
        </td>
      </tr>
      </tbody>
    </table>
  </fieldset>
  <ErrorComponent :errors="errors"></ErrorComponent>
</template>
<style lang="postcss" scoped>
#table, #table th, #table td{
  @apply border border-collapse border-gray-400 text-center text-gray-700 px-1
}
</style>