<script setup>
import Error from "../Error.vue";
import { watch, ref, onMounted, watchEffect } from 'vue';
// import useVesselVoyageBudget from "../../composables/operations/useVesselVoyageBudget";
import useVessel from "../../composables/operations/useVessel";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import ErrorComponent from '../../components/utils/ErrorComponent.vue';
import useVoyage from "../../composables/operations/useVoyage";
import useBusinessInfo from "../../composables/useBusinessInfo";
import useHeroIcon from "../../assets/heroIcon";
import RemarksComponet from '../../components/utils/RemarksComponent.vue';
import useVendor from "../../composables/supply-chain/useVendor.js";

const { vessel, vessels, getVesselList, showVessel } = useVessel();
const { voyages, searchVoyages } = useVoyage();
const { currencies, getCurrencies } = useBusinessInfo();
const {vendor, vendors, showVendor, searchVendor, isLoading: vendorLoader } = useVendor();

const icons = useHeroIcon();

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  formType: { type: String, required : false },
  heads: { type: [Object, Array], required: true },
  errors: { type: [Object, Array], required: false },
});

const dateFormat = ref(Store.getters.getVueDatePickerTextInputFormat.date);

const editInitiated = ref(0)

function fetchVendors(searchParam, loading) {
  searchVendor(searchParam, props.form.business_unit, loading)
}

function fetchVesselDetails(ops_vessel_id, loading) {
  showVessel(ops_vessel_id, loading).then(() => {
     editInitiated.value = 1
      if(props.formType == 'create') {
        props.form.bunkerItems = vessel.value.opsBunkers.map(function (bunker) {
          delete bunker.particular;
          return bunker;
        })
      }


    })
}

function fetchVesselWiseVoyages(ops_vessel_id, loading) {
  searchVoyages("", props.form.business_unit, loading, ops_vessel_id)
}

watch(() => props.form.opsVoyage, (newValue, oldValue) => {

    if(editInitiated.value == 1 || props.formType == 'create') {  
      props.form.ops_voyage_id = newValue?.id;
    }

}, { deep: true });

watch(() => props.form.opsVessel, (newValue, oldValue) => {

    props.form.ops_vessel_id = null;
    props.form.opsBunkers = [];
    props.form.bunkerItems = []
    // props.form.ops_vessel_id = newValue?.id;
    if(editInitiated.value == 1 || props.formType == 'create') {
      voyages.value = []
      props.form.opsVoyage = null;
      props.form.ops_voyage_id = null
    }
    

    if(newValue){
      props.form.ops_vessel_id = newValue?.id;
      
      fetchVesselDetails(newValue?.id, false);
      fetchVesselWiseVoyages(props.form.ops_vessel_id, false);
    }

}, { deep: true });

watch(() => props.form.business_unit, (newValue, oldValue) => {

  props.form.business_unit = newValue;

  if(newValue !== oldValue && oldValue != null && newValue != undefined){
    props.form.opsVessel = null;
    vessels.value = []
    props.form.opsBunkers = []
    props.form.bunkerItems = []
  }

  vessels.value = []

  if (newValue) {    
    getVesselList(props.form.business_unit);
    fetchVendors("", false);
  }
  
}, {deep: true});

const isUSDCurrency = ref(true)
const isBDTCurrency = ref(true)
const isOtherCurrency = ref(false)
const isNotBDTCurrency = ref(true)

watch(() => props.form.currency, (newValue, oldValue) => {

    isUSDCurrency.value = false;
    isBDTCurrency.value = false;
    isOtherCurrency.value = false;
    isNotBDTCurrency.value = false;

    if(editInitiated.value == 1 || props.formType == 'create') {
    
    props.form.exchange_rate_usd = '';
    props.form.exchange_rate_bdt = '';

    if(props.form.currency === 'USD') {
      isUSDCurrency.value = true;
    } else if(props.form.currency === 'BDT') {
      isBDTCurrency.value = true;
    } else if(props.form.currency !== 'BDT' || props.form.currency !== 'USD') {
      isOtherCurrency.value = true;
    } else if(props.form.currency !== 'BDT') {
      isNotBDTCurrency.value = true;
    }
  }
    calculateHeadAmounts()

}, { deep: true })

watch(() => props.form.exchange_rate_usd, (value) => {
  calculateHeadAmounts()
}, { deep: true })

watch(() => props.form.exchange_rate_bdt, (value) => {
  calculateHeadAmounts()
}, { deep: true })

const calculateHeadAmounts = () => {
    props.form.opsBunkers.forEach((line, index) => {

            const { amount, amount_usd, amount_bdt } = calculateInCurrency(line);
            props.form.opsBunkers[index].amount_usd = (amount_usd * 1);
            props.form.opsBunkers[index].amount_bdt = (amount_bdt * 1);
            props.form.opsBunkers[index].amount = (amount * 1);
      });
}

const calculateInCurrency = (item) => {

  item.amount = null;

  if(props.form.currency == 'USD'){
    item.amount = parseFloat((item?.rate * item?.quantity).toFixed(2));
    item.amount_usd = parseFloat((item?.rate * item?.quantity).toFixed(2));
    item.amount_bdt = parseFloat((item?.rate * item?.quantity * props.form?.exchange_rate_bdt).toFixed(2));
  } else if(props.form.currency == 'BDT'){
    item.amount = parseFloat((item?.rate * item?.quantity).toFixed(2));
    item.amount_usd = parseFloat((item?.rate * item?.quantity * props.form?.exchange_rate_usd).toFixed(2));
    item.amount_bdt = parseFloat((item?.rate * item?.quantity).toFixed(2));
  } else {
    item.amount = parseFloat((item?.rate * item?.quantity).toFixed(2));
    item.amount_usd = parseFloat((item?.rate * item?.quantity * props.form?.exchange_rate_usd).toFixed(2));
    item.amount_bdt = parseFloat((item?.rate * item?.quantity * props.form?.exchange_rate_usd * props.form?.exchange_rate_bdt).toFixed(2));
  }

  return {amount : (item.amount > 0) ? item.amount : 0, amount_usd: (item.amount_usd > 0) ? item.amount_usd : 0, amount_bdt:( item.amount_bdt > 0) ?  item.amount_bdt : 0};
}

function addHead() {
  
  let lastItem = props.form.opsBunkers[props.form.opsBunkers.length - 1];

  props.form.opsBunkers.push({
    particular: lastItem?.particular,
    opsBunkerInfo: {}
  });
  
}

function removeHead(index){
    props.form.opsBunkers.splice(index, 1);
}

function setScmMaterial(index) {
  props.form.opsBunkers[index].scm_material_id = props.form.opsBunkers[index].opsBunkerInfo.scm_material_id
  props.form.opsBunkers[index].unit = props.form.opsBunkers[index].opsBunkerInfo.unit
}

onMounted(() => {
  getCurrencies();
})
</script>
<template>
  <!-- Basic information -->
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <business-unit-input v-model="form.business_unit" :page="formType"></business-unit-input>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700">Type <span class="text-red-500">*</span></span>
        <select v-model="form.type" class="form-input" required>
          <option disabled selected value="">Select Option</option>
          <option value="Stock In">Stock In</option>
          <option value="Stock Out">Stock Out</option>
          <option value="Reconciliation">Reconciliation</option>
        </select>
      </label>
      <label v-if="form.type != ''" class="block w-full mt-2 text-sm">
        <span class="text-gray-700">{{ form.type }} Type <span class="text-red-500">*</span></span>
        <select v-model="form.usage_type" class="form-input" required>
          <option disabled selected value="">Select Option</option>
          <option value="Idle">Idle</option>
          <option value="Voyage Wise">Voyage Wise</option>
        </select>
      </label>
      <!-- <label v-if="form.type=='Reconciliation'" class="block w-full mt-2 text-sm"></label> -->
      <label class="block w-full mt-2 text-sm" v-if="form.type=='Reconciliation'">
        <span class="text-gray-700">Reconfiliation Option <span class="text-red-500">*</span></span>
        <select v-model="form.reconciliation_type" class="form-input" required>
          <option disabled selected value="">Select Option</option>
          <option value="Addition">Addition</option>
          <option value="Deletion">Deletion</option>
        </select>
      </label>
      <label v-else class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm" v-if="form.type==''"></label>

  </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700">Date <span class="text-red-500">*</span></span>
      <VueDatePicker v-model="form.date" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>
      <!-- <input type="date" v-model="form.date" required placeholder="Effective From" class="form-input" autocomplete="off" /> -->
    </label>

    <label class="block w-full mt-2 text-sm" v-if="form.usage_type=='Idle' && form.type!='Stock In'">
      <span class="text-gray-700">From Date <span class="text-red-500">*</span></span>
      <VueDatePicker v-model="form.from_date" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>
    </label>
    <label class="block w-full mt-2 text-sm" v-if="form.usage_type=='Idle' && form.type!='Stock In'">
      <span class="text-gray-700">Till Date <span class="text-red-500">*</span></span>
      <VueDatePicker v-model="form.till_date" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>
    </label>
    <label class="block w-full mt-2 text-sm" v-if="form.usage_type!='Idle' && form.type!='Stock In'"></label>
    <label class="block w-full mt-2 text-sm" v-if="form.usage_type!='Idle' && form.type!='Stock In'"></label>
    <label class="block w-full mt-2 text-sm" v-if="form.type=='Stock In'"></label>
    <label class="block w-full mt-2 text-sm" v-if="form.type=='Stock In'"></label>
    <label class="block w-full mt-2 text-sm"></label>

  </div>
  <div class="flex w-full md:flex-row md:gap-2">
    <label class="block w-1/2 mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Vessel Name <span class="text-red-500">*</span></span>
              <v-select :options="vessels" placeholder="--Choose an option--" v-model="form.opsVessel" label="name" class="block form-input">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.opsVessel"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
              <input type="hidden" v-model="form.ops_vessel_id" />
    </label> 
    <label class="block w-1/2 mt-2 text-sm" v-if="form.usage_type=='Voyage Wise'">
              <span class="text-gray-700 dark-disabled:text-gray-300">Voyage No <span class="text-red-500">*</span></span>
              <v-select :options="voyages" placeholder="--Choose an option--" v-model="form.opsVoyage" label="voyage_sequence" class="block form-input">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.opsVoyage"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
              <input type="hidden" v-model="form.ops_voyage_id" />
    </label> 
  </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2" v-if="form.type=='Stock In'">
    <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Vendor Name <span class="text-red-500">*</span></span>
        <v-select :options="vendors" placeholder="--Choose an option--" :loading="vendorLoader" v-model="form.scmVendor" label="name" class="block form-input" >
            <template #search="{attributes, events}">
                <input
                    class="vs__search"
                    :required="!form.scmVendor"
                    v-bind="attributes"
                    v-on="events"
                    />
            </template>
        </v-select>
        <input type="hidden" v-model="form.scm_vendor_id" />
    </label>
    <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700">Currency <span class="text-red-500">*</span></span>
        <select v-model.trim="form.currency" class="form-input" required>
          <option selected value="" disabled>Select Currency</option>
          <option v-for="currency in currencies" :value="currency" :key="currency">{{ currency }}</option>
        </select>
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700">Exchange Rate (To USD) </span>
      <input type="number" step="0.0001" v-model="form.exchange_rate_usd" placeholder="Exchange Rate (To USD)" class="form-input" :readonly="isUSDCurrency" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700">Exchange Rate (USD to BDT) </span>
      <input type="number" step="0.0001" v-model="form.exchange_rate_bdt" placeholder="Exchange Rate (USD to BDT)" class="form-input" :readonly="isBDTCurrency" />
    </label>

  </div>

  <div class="relative my-3" v-if="form.type!='' && form.bunkerItems.length">

    <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
      <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">{{ form.type }}</legend>
      <table class="w-full" id="table">
        <thead>
        <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
          <th v-if="form.type == 'Stock Out' && form.usage_type=='Voyage Wise'"><nobr>Particular <span class="text-red-500">*</span></nobr></th>
          <th class="w-44">Bunker <span class="text-red-500">*</span></th>
          <th><nobr>Quantity <span class="text-red-500">*</span></nobr></th>
          <th v-if="form.type == 'Stock In'"><nobr>Rate <span class="text-red-500">*</span></nobr></th>
          <th v-if="isOtherCurrency && form.type == 'Stock In'">Amount </th>
          <th v-if="form.type == 'Stock In'">Amount USD</th>
          <th v-if="form.type == 'Stock In'">Amount BDT</th>
          <th>
            <button type="button" @click="addHead()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
              </svg>
            </button>
          </th>
        </tr>
        </thead>
        <tbody>
          <template v-for="(bunker, index) in form.opsBunkers" :key="index">
            <tr>
              <td v-if="form.type == 'Stock Out' && form.usage_type=='Voyage Wise'">
                <input type="text" required v-model="form.opsBunkers[index].particular" placeholder="Particular" class="form-input" autocomplete="off" />
              </td>

              <td>

                <v-select :options="form.bunkerItems" placeholder="Select Bunker" :reduce="id" v-model="form.opsBunkers[index].opsBunkerInfo" label="name" class="block form-input" @update:modelValue="setScmMaterial(index)">
                    <template #search="{attributes, events}">
                        <input
                            class="vs__search"
                            :required="!form.opsBunkers[index].opsBunkerInfo"
                            v-bind="attributes"
                            v-on="events"
                            :reaonly="true"
                            />
                    </template>
                </v-select>
              </td>
              <td>
                  <input type="number" step="0.0001" min="0" @input="calculateHeadAmounts()" required v-model="form.opsBunkers[index].quantity" placeholder="Qty" class="form-input" autocomplete="off" />
              </td>
              <td v-if="form.type == 'Stock In'">
                <input type="number" step="0.0001" min="0" @input="calculateHeadAmounts()" required v-model="form.opsBunkers[index].rate" placeholder="Rate" class="form-input" autocomplete="off" />
              </td>
              <td v-if="isOtherCurrency && form.type == 'Stock In'">
                <input type="number" step="0.0001" @input="calculateHeadAmounts()" v-model="form.opsBunkers[index].amount" placeholder="Amount" readonly class="form-input" autocomplete="off" />
              </td>
              <td v-if="form.type == 'Stock In'">
                  <input type="number" step="0.0001" v-model="form.opsBunkers[index].amount_usd" placeholder="USD Amount" readonly class="form-input" autocomplete="off" />
              </td>
              <td v-if="form.type == 'Stock In'">
                  <input type="number" step="0.0001" v-model="form.opsBunkers[index].amount_bdt" placeholder="BDT Amount" readonly class="form-input" autocomplete="off" />
              </td>
              <td>
                <button type="button" @click="removeHead(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                  </svg>
                </button>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </fieldset>

    <RemarksComponet v-model="form.remarks" :maxlength="300" :fieldLabel="'Remarks'"></RemarksComponet>

  </div>


  <ErrorComponent :errors="errors"></ErrorComponent>
</template>
<style lang="postcss" scoped>
#table, #table th, #table td{
  @apply border border-collapse border-gray-400 text-center text-gray-700 px-1 text-xs
}

.input-group {
  @apply flex flex-col justify-center w-full h-full md:flex-row md:gap-2;
}

.label-group {
  @apply block w-full mt-3 text-sm;
}
.label-item-title {
  @apply text-gray-700 dark-disabled:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark-disabled:disabled:bg-gray-900;
}

/* Hide the default number input arrows */
input[type=number] {
  -moz-appearance: textfield; /* Firefox */
  -webkit-appearance: textfield; /* Chrome, Safari, Edge */
  appearance: textfield; /* Standard syntax */
}

/* Hide the spin buttons in Chrome */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
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