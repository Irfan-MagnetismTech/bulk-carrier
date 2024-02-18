<script setup>
import Error from "../Error.vue";
import { watch, ref, onMounted, watchEffect } from 'vue';
// import useVesselVoyageBudget from "../../composables/operations/useVesselVoyageBudget";
import useVoyageBudget from "../../composables/operations/useVoyageBudget";
import useVessel from "../../composables/operations/useVessel";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import ErrorComponent from '../utils/ErrorComponent.vue';
import useVesselExpenseHead from "../../composables/operations/useVesselExpenseHead";
import useVoyage from "../../composables/operations/useVoyage";
import useBusinessInfo from "../../composables/useBusinessInfo";

const { voyageBudgets, getVoyageBudgets, showHead, isLoading, errors } = useVoyageBudget();
const { vessel, vessels, getVesselList, showVessel } = useVessel();
const { voyages, searchVoyages } = useVoyage();
const { vesselExpenseHeads, showVesselWiseExpenseHead } = useVesselExpenseHead();
const { currencies, getCurrencies } = useBusinessInfo();

const subheads = ref([]);

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  formType: { type: String, required : false },
  heads: { type: [Object, Array], required: true },
  errors: { type: [Object, Array], required: false },
});

function fetchVesselExpenseHeads(ops_vessel_id, loading) {
  props.form.heads = []
  showVesselWiseExpenseHead(ops_vessel_id, loading).then(() => {
      if(props.formType == 'create') {
        props.form.heads = vesselExpenseHeads.value
        // props.form.heads = voyageBudgets.value
      }
    })
}

function fetchVesselWiseVoyages(ops_vessel_id) {
  searchVoyages("", props.form.business_unit, ops_vessel_id)
}

watch(() => props.form.opsVoyage, (newValue, oldValue) => {
    props.form.ops_voyage_id = null;
    if(newValue){
      props.form.ops_voyage_id = newValue?.id;
      
    }

});

watch(() => props.form.opsVessel, (newValue, oldValue) => {
    props.form.ops_vessel_id = null;
    // props.form.ops_vessel_id = newValue?.id;
    voyages.value = []
    props.form.opsVoyage = null;
    props.form.ops_voyage_id = null

    if(newValue){
      props.form.ops_vessel_id = newValue?.id;
      
      fetchVesselExpenseHeads(newValue?.id, false);
      fetchVesselWiseVoyages(props.form.ops_vessel_id, false);
    }

});

watch(() => props.form.business_unit, (newValue, oldValue) => {

  props.form.business_unit = newValue;

  if(newValue !== oldValue && oldValue != null && newValue != undefined){
    props.form.opsVessel = null;
    vessels.value = []
    props.form.heads = []
  }

  vessels.value = []

  if (newValue) {    
    getVesselList(props.form.business_unit);
  }
  
}, {deep: true});

const isUSDCurrency = ref(true)
const isBDTCurrency = ref(true)
const isOtherCurrency = ref(true)
const isNotBDTCurrency = ref(true)

watch(() => props.form.currency, (newValue, oldValue) => {

  isUSDCurrency.value = false;
  isBDTCurrency.value = false;
  isOtherCurrency.value = false;
  isNotBDTCurrency.value = false;

  if(props.form.currency != '') {
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

}, { deep: true })

watch(() => props.form.heads, (newValue, oldValue) => {
  // let total_bdt = 0.0;
  //     let total_usd = 0.0;
      newValue?.forEach((line, index) => {

        if(props.form.heads[index].opsSubHeads.length > 0) {
          
          props.form.heads[index].opsSubHeads.forEach((subHead, subIndex) => {
            const { amount_usd, amount_bdt } = calculateInCurrency(subHead);

            console.log("subhead", subHead, amount_usd, amount_bdt)

            props.form.heads[index].opsSubHeads[subIndex].amount_usd = amount_usd
            props.form.heads[index].opsSubHeads[subIndex].amount_bdt = amount_bdt
          })


        } else {
            const { amount_usd, amount_bdt } = calculateInCurrency(line);
            props.form.heads[index].amount_usd = amount_usd
            props.form.heads[index].amount_bdt = amount_bdt
        }
        // total_bdt += amount_bdt;
        // total_usd += amount_usd;

      });
}, { deep: true })

const calculateInCurrency = (item) => {

  if(props.form.currency == 'USD'){
    item.amount_usd = parseFloat((item?.rate * item?.quantity).toFixed(2));
    item.amount_bdt = parseFloat((item?.rate * item?.quantity * props.form?.exchange_rate_bdt).toFixed(2));
  } else if(props.form.currency == 'BDT'){
    item.amount_usd = parseFloat((item?.rate * item?.quantity * props.form?.exchange_rate_usd).toFixed(2));
    item.amount_bdt = parseFloat((item?.rate * item?.quantity).toFixed(2));
  } else {
    item.amount_usd = parseFloat((item?.rate * item?.quantity * props.form?.exchange_rate_usd).toFixed(2));
    item.amount_bdt = parseFloat((item?.rate * item?.quantity * props.form?.exchange_rate_usd * props.form?.exchange_rate_bdt).toFixed(2));
  }

  return {amount_usd: (item.amount_usd > 0) ? item.amount_usd : '', amount_bdt:( item.amount_bdt > 0) ?  item.amount_bdt : ''};
}



onMounted(() => {
  getCurrencies();
})
</script>
<template>
  <!-- Basic information -->
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <business-unit-input class="w-1/2" v-model="form.business_unit" :page="formType"></business-unit-input>
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Vessel <span class="text-red-500">*</span></span>
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
            <label class="block w-1/2 mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Voyage <span class="text-red-500">*</span></span>
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
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700">Currency <span class="text-red-500">*</span></span>
        <select v-model.trim="form.currency" class="form-input" required>
          <option selected value="" disabled>Select Currency</option>
          <option v-for="currency in currencies" :value="currency" :key="currency">{{ currency }}</option>
        </select>
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700">Exchange Rate (To USD) </span>
      <input type="text" v-model="form.exchange_rate_usd" placeholder="Exchange Rate (To USD)" class="form-input" :readonly="isUSDCurrency" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700">Exchange Rate (USD to BDT) </span>
      <input type="text" v-model="form.exchange_rate_bdt" placeholder="Exchange Rate (USD to BDT)" class="form-input" :readonly="isBDTCurrency" />
    </label>
    <label class="block w-full mt-2 text-sm"></label>

  </div>
  <!-- South Sectors -->

  <div class="relative my-3" v-if="form?.heads?.length > 0">

    <div class="dt-responsive table-responsive">
      <table class="w-full whitespace-no-wrap" >
        <thead>
            <tr class="w-full">
              <th class="">Expesne Head</th>
              <!-- <th class="">Currency</th> -->
              <!-- <th class="">Unit</th> -->
              <th>Quantity</th>
              <th>Rate</th>
              <!-- <th>Exchange Rate (To USD)</th>
              <th>Exchange Rate (USD To BDT)</th> -->
              <th v-if="isOtherCurrency && form.currency!=''">Amount {{ form.currency }}</th>
              <th>Amount USD</th>
              <th>Amount BDT</th>
            </tr>
          </thead>
          <tbody>
            
            <template v-for="(costGroup, index) in form.heads" :key="index">
              <template v-if="costGroup.opsSubHeads.length > 0">
                <tr v-once>
                  <td colspan="9" class="bg-green-100 font-semibold !text-left">
                      <nobr>{{ costGroup?.name }}</nobr>
                  </td>
                </tr>
                <tr v-for="(subhead, subIndex) in costGroup.opsSubHeads" :key="subIndex" class="text-gray-700 dark:text-gray-400">
                  <td class="px-1 py-1">
                    <span class="show-block">
                      <nobr>{{ subhead?.name }}</nobr>
                    </span>
                  </td>
                  <!-- <td>
                    <select v-model.trim="form.heads[index].opsSubHeads[subIndex].currency" class="form-input" aria-placeholder="Select Currency" placeholder="Select Currency" @change="SetCurrencyData($event,index)">
                      <option selected value="" disabled>Select Currency</option>
                      <option v-for="currency in currencies" :value="currency" :key="currency">{{ currency }}</option>
                    </select>
                  </td> -->
                  <!-- <td>
                    <input type="text" v-model="form.heads[index].opsSubHeads[subIndex].unit" placeholder="Unit" class="form-input" autocomplete="off" />
                  </td> -->
                  <td>
                    <input type="text" v-model="form.heads[index].opsSubHeads[subIndex].quantity" placeholder="Quantity" class="form-input" autocomplete="off" />
                  </td>
                  <td>
                    <input type="text" v-model="form.heads[index].opsSubHeads[subIndex].rate" placeholder="Rate" class="form-input" autocomplete="off" />
                  </td>
                  <!-- <td>
                    <input type="text" v-model="form.heads[index].opsSubHeads[subIndex].exchange_rate_usd" placeholder="Ex. Rate" class="form-input" autocomplete="off" />
                  </td>
                  <td>
                    <input type="text" v-model="form.heads[index].opsSubHeads[subIndex].exchange_rate_usd" placeholder="Ex. Rate" class="form-input" autocomplete="off" />
                  </td> -->
                  <td v-if="isOtherCurrency && form.currency!=''">
                    <input type="text" v-model="form.heads[index].opsSubHeads[subIndex].amount" placeholder="Amount" class="form-input" autocomplete="off" readonly />
                  </td>

                  <td>
                    <input type="text" v-model="form.heads[index].opsSubHeads[subIndex].amount_usd" placeholder="USD Amount" class="form-input" autocomplete="off" readonly />
                  </td>
                  <td>
                    <input type="text" v-model="form.heads[index].opsSubHeads[subIndex].amount_bdt" placeholder="BDT Amount" class="form-input" autocomplete="off" readonly />
                  </td>
                </tr>
              </template>
              <template v-else>
                <tr v-once>
                  <td colspan="9" class="bg-green-100 font-semibold !text-left">
                      <nobr>{{ costGroup?.name }}</nobr>
                  </td>
                </tr>
                <tr>
                  <td>
                    <span class="show-block">
                      <nobr>{{ costGroup?.name }}</nobr>
                    </span>
                  </td>
                  <!-- <td>
                    <select v-model.trim="form.heads[index].currency" class="form-input" aria-placeholder="Select Currency" placeholder="Select Currency" @change="SetCurrencyData($event,index)">
                      <option selected value="" disabled>Select Currency</option>
                      <option v-for="currency in currencies" :value="currency" :key="currency">{{ currency }}</option>
                    </select>
                  </td> -->
                  <!-- <td>
                    <input type="text" v-model="form.heads[index].unit" placeholder="Unit" class="form-input" autocomplete="off" />
                  </td> -->
                  <td>
                    <input type="text" v-model="form.heads[index].quantity" placeholder="Quantity" class="form-input" autocomplete="off" />
                  </td>
                  <td>
                    <input type="text" v-model="form.heads[index].rate" placeholder="Rate" class="form-input" autocomplete="off" />
                  </td>
                  <!-- <td>
                    <input type="text" v-model="form.heads[index].exchange_rate_usd" placeholder="Ex. Rate" class="form-input" autocomplete="off" />
                  </td>
                  <td>
                    <input type="text" v-model="form.heads[index].exchange_rate_bdt" placeholder="Ex. Rate" class="form-input" autocomplete="off" />
                  </td> -->
                  <td v-if="isOtherCurrency && form.currency!=''">
                    <input type="text" v-model="form.heads[index].amount" placeholder="Amount" class="form-input" autocomplete="off" readonly />
                  </td>

                  <td>
                    <input type="text" v-model="form.heads[index].amount_usd" placeholder="USD Amount" class="form-input" autocomplete="off" readonly />
                  </td>
                  <td>
                    <input type="text" v-model="form.heads[index].amount_bdt" placeholder="BDT Amount" class="form-input" autocomplete="off" readonly />
                  </td>
                </tr>
              </template>
            </template>
            
          </tbody>
        </table>
    </div>

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
  @apply text-gray-700 dark:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
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