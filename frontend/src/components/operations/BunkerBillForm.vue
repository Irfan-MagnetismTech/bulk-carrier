<template>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <business-unit-input v-model="form.business_unit" :page="formType"></business-unit-input>
        <label class="block w-full mt-2 text-sm"></label>
        <label class="block w-full mt-2 text-sm"></label>
        <label class="block w-full mt-2 text-sm"></label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Date <span class="text-red-500">*</span></span>
            <input type="date" v-model.trim="form.date" placeholder="Date" class="form-input" required autocomplete="off" />
      </label>

      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Vendor <span class="text-red-500">*</span></span>
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
            <span class="text-gray-700 dark-disabled:text-gray-300">Bill No. <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.vendor_bill_no" placeholder="Bill No." class="form-input" required autocomplete="off" />
      </label>

      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Remarks <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.remarks" placeholder="Remarks" class="form-input" required autocomplete="off" />
      </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2 mt-2">
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Upload file(Supplier Invoice) <span class="text-red-500">*</span><p v-if="props?.formType == 'edit'" class="text-red-600"> {{ getFileName(form.attachment) }}</p></span>
            <input type="file" @change="attachFile" placeholder="Billing Email" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Upload file(SRM Copy) <span class="text-red-500">*</span> <p v-if="props?.formType == 'edit'" class="text-red-600"> {{ getFileName(form.smr_file_path) }}</p></span>
            <input type="file" @change="attachSMRFile" placeholder="Billing Email" class="form-input" autocomplete="off" />
        </label>
    </div>

    <div class="mt-3 md:mt-8" v-if="form?.opsBunkerBillLines?.length > 0">
      <h4 class="text-md font-semibold uppercase mb-2">Bunker Line Information</h4>
      
      <div v-for="(pr, index) in form.opsBunkerBillLines" :key="index"  class="w-full mx-auto p-2 border rounded-md border-gray-400 mb-5 shadow-md">

        <!-- <div class="flex flex-col justify-center w-full md:flex-row md:gap-2 mt-2">
          <label class="block w-1/2 mt-2 text-sm"></label>

          
          <label class="block w-1/2 mt-2 text-sm"></label>

        </div> -->

        <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">PR No. <span class="text-red-500">*</span></span>
            <v-select :options="bunkerRequisitions" placeholder="--Choose an option--" :loading="bunkerLoader"  v-model="form.opsBunkerBillLines[index].opsBunkerRequisition" label="requisition_no" class="block form-input"
            @update:modelValue="initiateBunkerRequisitionItem(index)"
            >
              <template #search="{attributes, events}">
                  <input
                      class="vs__search"
                      :required="!form.opsBunkerBillLines[index].opsBunkerRequisition"
                      v-bind="attributes"
                      v-on="events"
                      />
              </template>
            </v-select>
            <input type="hidden"  step="0.001" required v-model="form.opsBunkerBillLines[index].ops_bunker_requisition_id" class="form-input" autocomplete="off"/>
          </label>
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700">Currency <span class="text-red-500">*</span></span>
              <select v-model.trim="form.opsBunkerBillLines[index].currency" class="form-input" required @change="calculatePrAmounts(index)">
                <option selected value="" disabled>Select Currency</option>
                <option v-for="currency in currencies" :value="currency" :key="currency">{{ currency }}</option>
              </select>
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700">Exchange Rate (To USD) </span>
            <input type="text" v-model="form.opsBunkerBillLines[index].exchange_rate_usd" @input="calculatePrAmounts(index)" placeholder="Exchange Rate (To USD)" class="form-input" :readonly="isUSDCurrency(index)" />
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700">Exchange Rate (USD to BDT) </span>
            <input type="text" v-model="form.opsBunkerBillLines[index].exchange_rate_bdt" @input="calculatePrAmounts(index)" placeholder="Exchange Rate (USD to BDT)" class="form-input" :readonly="isBDTCurrency(index)" />
          </label>
        </div>

        <div class="relative my-3">

          <div class="dt-responsive table-responsive">
            <table class="w-full whitespace-no-wrap" >
              <thead>
                  <tr class="w-full">
                    <th class="w-72">Bunker</th>
                    <th class="w-20">Quantity</th>
                    <th>Rate</th>
                    <th v-if="isOtherCurrency[index]">Amount </th>
                    <th>Amount USD</th>
                    <th>Amount BDT</th>
                  </tr>
              </thead>
              <tbody v-if="form.opsBunkerBillLines[index]?.opsBunkerBillLineItems?.length > 0">
                <tr v-for="(lineItem, itemIndex) in form.opsBunkerBillLines[index].opsBunkerBillLineItems" :key="itemIndex">
                  <td>
                    <span class="show-block">
                      {{ form.opsBunkerBillLines[index].opsBunkerBillLineItems[itemIndex].name }}
                    </span>
                  </td>
                  <td>
                    <input type="text" v-model="form.opsBunkerBillLines[index].opsBunkerBillLineItems[itemIndex].quantity"  @input="calculatePrAmounts(index)" placeholder="Qty" class="form-input" autocomplete="off" />
                  </td>
                  <td>
                    <input type="text" v-model="form.opsBunkerBillLines[index].opsBunkerBillLineItems[itemIndex].rate"  @input="calculatePrAmounts(index)" placeholder="Rate" class="form-input" autocomplete="off" />
                  </td>
                  <td v-if="isOtherCurrency[index]">
                    <input type="text" v-model="form.opsBunkerBillLines[index].opsBunkerBillLineItems[itemIndex].amount" placeholder="Amount" readonly class="form-input" autocomplete="off" />
                  </td>
                  <td>
                      <input type="text" v-model="form.opsBunkerBillLines[index].opsBunkerBillLineItems[itemIndex].amount_usd" placeholder="USD Amount" readonly class="form-input" autocomplete="off" />
                  </td>
                  <td>
                      <input type="text" v-model="form.opsBunkerBillLines[index].opsBunkerBillLineItems[itemIndex].amount_bdt" placeholder="BDT Amount" readonly class="form-input" autocomplete="off" />
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="flex justify-center items-center my-3">
            <button type="button" @click="addBunkerRequisition()" class="px-3 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple flex w-32 justify-center text-center">
              Add More
            </button>
            <button type="button" v-if="index>0" @click="removeBunkerRequisition(index)" class="px-3 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple flex w-32 justify-center text-center ml-3">
              Remove
            </button> 
          </div>

      </div>
      
    </div>
    <ErrorComponent :errors="errors"></ErrorComponent>

</template>
<script setup>
import { ref, watch, onMounted, watchEffect } from 'vue';
import Error from "../Error.vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import useVendor from "../../composables/supply-chain/useVendor.js";
import useBunkerRequisition from "../../composables/operations/useBunkerRequisition";
import useMaterial from '../../composables/supply-chain/useMaterial';
import ErrorComponent from '../../components/utils/ErrorComponent.vue';
import RemarksComponent from '../../components/utils/RemarksComponent.vue';
import DropZoneV2 from '../../components/DropZoneV2.vue';
import useBusinessInfo from '../../composables/useBusinessInfo';
import cloneDeep from 'lodash/cloneDeep';

const editInitiated = ref(false);
const props = defineProps({
    form: {
        required: false,
        default: {}
    },
    errors: { type: [Object, Array], required: false },
    bunkerObject: { type: Object, required: false },
    formType: { type: Object, required: false }
});

const { currencies, getCurrencies } = useBusinessInfo();
const {vendor, vendors, showVendor, searchVendor, isLoading: vendorLoader } = useVendor();
const {  bunkerRequisitions, searchBunkerRequisitions, isLoading: bunkerLoader } = useBunkerRequisition();

watch(() => props.form.business_unit, (newValue, oldValue) => {

    if(newValue) {
      fetchVendors("", false)
      fetchBunkerRequisition("", false)

      props.form.scmVendor = null;
      props.form.opsBunkerBillLines = [];
      props.form.scm_vendor_id = null;

      props.form.opsBunkerBillLines.push(cloneDeep(props.bunkerObject))
    }
}, { deep : true });


function fetchVendors(searchParam, loading) {
  searchVendor(searchParam, props.form.business_unit, loading)
}


function fetchBunkerRequisition(searchParam, loading) {
  searchBunkerRequisitions(searchParam, props.form.business_unit, loading)
}


watch(() => props.form.scmVendor, (newValue, oldValue) => {
    vendor.value = null;
  // if(value) {
    props.form.scm_vendor_id = newValue?.id
  //   showVendor(value?.id)
  // }
}, { deep: true });


function addBunkerRequisition() {
  props.form.opsBunkerBillLines.push(cloneDeep(props.bunkerObject))
}

function removeBunkerRequisition(index) {
  props.form.opsBunkerBillLines.splice(index, 1);
}

const isUSDCurrency = (index) => {
    if(props.form.opsBunkerBillLines[index].currency === 'USD') {
      return true;
    } else {
      return false;
    }
}

const isBDTCurrency = (index) => {
  if(props.form.opsBunkerBillLines[index].currency === 'BDT') {
      return true;
    } else {
      return false;
    }
}

const isOtherCurrency = (index) => {
  if(props.form.opsBunkerBillLines[index].currency !== 'BDT' || props.form.opsBunkerBillLines[index].currency !== 'USD') {
      return true;
    } else {
      return false;
    }
}

const isNotBDTCurrency = (index) => {
  if(props.form.opsBunkerBillLines[index].currency !== 'BDT') {
      return true;
    } else {
      return false;
    }
}

// watch(() => props.form.opsBunkerBillLines, (newValue, oldValue) => {
//   console.log("sdf")
//   if(props.form?.opsBunkerBillLines?.length > 0) {

//     for(const billLineIndex in props.form.opsBunkerBillLines) {
      
//     }

//   }
// }, { deep: true })


const initiateBunkerRequisitionItem = (billLineIndex) => {
  props.form.opsBunkerBillLines[billLineIndex].ops_bunker_requisition_id = props.form.opsBunkerBillLines[billLineIndex].opsBunkerRequisition?.id
  let requisition = bunkerRequisitions.value.filter((requisition) => requisition.id === props.form.opsBunkerBillLines[billLineIndex].opsBunkerRequisition?.id);
  props.form.opsBunkerBillLines[billLineIndex].opsBunkerBillLineItems = requisition[0]?.opsBunkers;

  // calculatePrAmounts(billLineIndex, props.form.opsBunkerBillLines[billLineIndex].opsBunkerBillLineItems)
}

const allCurrencies = ref([]);

const calculatePrAmounts = (billLineIndex) => {

  let currency = props.form.opsBunkerBillLines[billLineIndex].currency;

  const existingCurrency = allCurrencies.value.find(item => item.id === billLineIndex);

  if (existingCurrency) {

    if(currency != existingCurrency?.currency) {
       props.form.opsBunkerBillLines[billLineIndex].exchange_rate_usd = '';
       props.form.opsBunkerBillLines[billLineIndex].exchange_rate_bdt = '';
    }

    existingCurrency.currency = currency;

    
  } else {
    // Push a new object with id and currency properties
    allCurrencies.value.push({
      id: billLineIndex,
      currency: currency
    });
  }


    let exchange_rate_bdt = (props.form.opsBunkerBillLines[billLineIndex].exchange_rate_bdt > 0) ? props.form.opsBunkerBillLines[billLineIndex].exchange_rate_bdt : 0;
    let exchange_rate_usd = (props.form.opsBunkerBillLines[billLineIndex].exchange_rate_usd > 0) ? props.form.opsBunkerBillLines[billLineIndex].exchange_rate_usd : 0;

    if(props.form.opsBunkerBillLines[billLineIndex]?.opsBunkerBillLineItems?.length > 0) {

      props.form.opsBunkerBillLines[billLineIndex].opsBunkerBillLineItems.forEach((line, index) => {
          const { amount, amount_usd, amount_bdt } = calculateInCurrency(currency, exchange_rate_bdt, exchange_rate_usd, line);
          // console.log(amount, amount_usd, amount_bdt)
          props.form.opsBunkerBillLines[billLineIndex].opsBunkerBillLineItems[index].amount_usd = amount_usd
          props.form.opsBunkerBillLines[billLineIndex].opsBunkerBillLineItems[index].amount_bdt = amount_bdt
          props.form.opsBunkerBillLines[billLineIndex].opsBunkerBillLineItems[index].amount = amount
     });
    }
   
}

const calculateInCurrency = (currency, exchange_rate_bdt, exchange_rate_usd, item) => {

  console.log(currency, exchange_rate_bdt, exchange_rate_usd, item)

  item.amount = null;

  if(currency == 'USD'){
    item.amount = parseFloat((item?.rate * item?.quantity).toFixed(2));
    item.amount_usd = parseFloat((item?.rate * item?.quantity).toFixed(2));
    item.amount_bdt = parseFloat((item?.rate * item?.quantity * exchange_rate_bdt).toFixed(2));
  } else if(currency == 'BDT'){
    item.amount = parseFloat((item?.rate * item?.quantity).toFixed(2));
    item.amount_usd = parseFloat((item?.rate * item?.quantity * exchange_rate_usd).toFixed(2));
    item.amount_bdt = parseFloat((item?.rate * item?.quantity).toFixed(2));
  } else {
    item.amount = parseFloat((item?.rate * item?.quantity).toFixed(2));
    item.amount_usd = parseFloat((item?.rate * item?.quantity * exchange_rate_usd).toFixed(2));
    item.amount_bdt = parseFloat((item?.rate * item?.quantity * exchange_rate_usd * exchange_rate_bdt).toFixed(2));
  }

  return {amount : (item.amount > 0) ? item.amount : '', amount_usd: (item.amount_usd > 0) ? item.amount_usd : '', amount_bdt:( item.amount_bdt > 0) ?  item.amount_bdt : ''};
}

onMounted(() => {
  getCurrencies();
})
</script>
<style lang="postcss" scoped>
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
.form-input {
  @apply block mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray;
}
</style>