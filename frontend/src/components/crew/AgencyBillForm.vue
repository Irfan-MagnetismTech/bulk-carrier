<script setup>
import Error from "../Error.vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import {computed, onMounted, ref, watch, watchEffect} from "vue";
import useCrewCommonApiRequest from "../../composables/crew/useCrewCommonApiRequest";
import Store from "../../store";

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
    quantity: 0.0,
    rate: 0.0,
    amount: 0.0,
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

onMounted(() => {
  props.form.business_unit = businessUnit.value;
  watchEffect(() => {
    getCrewAgencyLists(props.form.business_unit);
    getCrewAgencyContracts(props.form.business_unit,props.form.crw_agency_id);
  });
});

</script>

<template>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <business-unit-input v-model="form.business_unit"></business-unit-input>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
  </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Agency Name <span class="text-red-500">*</span></span>
      <select class="form-input" v-model="form.crw_agency_id">
        <option value="" selected disabled>Select</option>
        <option v-for="(agency,index) in crwAgencies" :value="agency.id" :key="index">{{ agency?.name }}</option>
      </select>
      <Error v-if="errors?.crw_agency_id" :errors="errors.crw_agency_id" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Agency Contract <span class="text-red-500">*</span></span>
      <select class="form-input" v-model="form.crw_agency_contract_id">
        <option value="" selected disabled>Select</option>
        <option v-for="(agencyContract,index) in crwAgencyContracts" :value="agencyContract.id" :key="index">{{ agencyContract?.crw_agency_id }}</option>
      </select>
      <Error v-if="errors?.crw_agency_contract_id" :errors="errors.crw_agency_contract_id" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Applied Date <span class="text-red-500">*</span></span>
      <input type="date" v-model="form.applied_date" class="form-input" autocomplete="off" required />
      <Error v-if="errors?.applied_date" :errors="errors.applied_date" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Bill Date <span class="text-red-500">*</span></span>
      <input type="date" v-model="form.invoice_date" class="form-input" autocomplete="off" required />
      <Error v-if="errors?.invoice_date" :errors="errors.invoice_date" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Bill No <span class="text-red-500">*</span></span>
      <input type="text" v-model="form.invoice_no" placeholder="Invoice no" class="form-input" autocomplete="off" required />
      <Error v-if="errors?.invoice_no" :errors="errors.invoice_no" />
    </label>
  </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Bill Type <span class="text-red-500">*</span></span>
      <input type="text" v-model="form.invoice_type" placeholder="Invoice type" class="form-input" autocomplete="off" required />
      <Error v-if="errors?.invoice_type" :errors="errors.invoice_type" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Billing Currency <span class="text-red-500">*</span></span>
      <select class="form-input" v-model="form.invoice_currency">
        <option value="" selected disabled>Select</option>
        <option value="BDT">BDT</option>
        <option value="USD">USD</option>
      </select>
      <Error v-if="errors?.invoice_currency" :errors="errors.invoice_currency" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Bill Amount <span class="text-red-500">*</span></span>
      <input type="number" step=".01" v-model="form.invoice_amount" placeholder="Bill amount" class="form-input" autocomplete="off" required />
      <Error v-if="errors?.invoice_amount" :errors="errors.invoice_amount" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Remarks</span>
      <input type="text" v-model="form.remarks" placeholder="Remarks" class="form-input" autocomplete="off" />
      <Error v-if="errors?.remarks" :errors="errors.remarks" />
    </label>
    <label class="block w-full mt-2 text-sm"></label>
  </div>
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded ">
    <legend class="px-2 text-gray-700 ">Billing Info</legend>
    <table class="w-full whitespace-no-wrap" id="table">
      <thead>
      <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50  ">
        <th class="px-4 py-3 align-bottom">Particular <span class="text-red-500">*</span></th>
        <th class="px-4 py-3 align-bottom">Description </th>
        <th class="px-4 py-3 align-bottom">Per </th>
        <th class="px-4 py-3 align-bottom">Quantity <span class="text-red-500">*</span></th>
        <th class="px-4 py-3 align-bottom">Rate <span class="text-red-500">*</span></th>
        <th class="px-4 py-3 align-bottom">Amount <span class="text-red-500">*</span></th>
        <th class="px-4 py-3 text-center align-bottom">Action</th>
      </tr>
      </thead>
      <tbody class="bg-white divide-y ">
      <tr class="text-gray-700 " v-for="(creAgencyBillLine, index) in form.crwAgencyBillLines" :key="creAgencyBillLine.id">
        <td class="px-1 py-1">
          <input type="text" v-model="form.crwAgencyBillLines[index].particular" placeholder="Particular" class="form-input" autocomplete="off" />
        </td>
        <td class="px-1 py-1">
          <input type="text" v-model="form.crwAgencyBillLines[index].description" placeholder="Description" class="form-input" autocomplete="off" />
        </td>
        <td class="px-1 py-1">
          <input type="text" v-model="form.crwAgencyBillLines[index].per" placeholder="Per" class="form-input" autocomplete="off" />
        </td>
        <td class="px-1 py-1">
          <input type="number" step=".01" v-model="form.crwAgencyBillLines[index].quantity" @input="calculateAmount($event,index)" placeholder="Quantity" class="form-input" autocomplete="off" />
        </td>
        <td class="px-1 py-1">
          <input type="number" step=".01" v-model="form.crwAgencyBillLines[index].rate" @input="calculateAmount($event,index)" placeholder="Rate" class="form-input" autocomplete="off" />
        </td>
        <td class="px-1 py-1">
          <input type="number" step=".01" v-model="form.crwAgencyBillLines[index].amount" placeholder="Amount" class="form-input vms-readonly-input" readonly autocomplete="off" />
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
      <tr class="text-gray-700 ">
        <td class="px-1 py-1" colspan="4"></td>
        <td class="px-1 py-1 font-bold uppercase">Grand Total</td>
        <td class="px-1 py-1">
          <input type="number" step=".01" v-model="form.grand_total" placeholder="Amount" class="form-input vms-readonly-input" readonly autocomplete="off" />
        </td>
      </tr>
      <tr class="text-gray-700 ">
        <td class="px-1 py-1" colspan="4"></td>
        <td class="px-1 py-1 font-bold uppercase">Discount</td>
        <td class="px-1 py-1">
          <input type="number" step=".01" v-model="form.discount" placeholder="Amount" class="form-input" autocomplete="off" />
        </td>
      </tr>
      <tr class="text-gray-700 ">
        <td class="px-1 py-1" colspan="4"></td>
        <td class="px-1 py-1 font-bold uppercase">Net Amount</td>
        <td class="px-1 py-1">
          <input type="number" step=".01" v-model="form.net_amount" placeholder="Amount" class="form-input vms-readonly-input" readonly autocomplete="off" />
        </td>
      </tr>
      </tbody>
    </table>
  </fieldset>
</template>
<style lang="postcss" scoped>
#table, #table th, #table td{
  @apply border border-collapse border-gray-400 text-center text-gray-700 px-1
}

.input-group {
  @apply flex flex-col justify-center w-full h-full md:flex-row md:gap-2;
}

.label-group {
  @apply block w-full mt-2 text-sm;
}
.label-item-title {
  @apply text-gray-700 ;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded   focus:border-purple-400 focus:outline-none focus:shadow-outline-purple  disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed ;
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