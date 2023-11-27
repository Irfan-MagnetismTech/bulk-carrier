<template>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <business-unit-input v-model="form.business_unit" :page="formType"></business-unit-input>
      <label class="block w-full mt-2 text-sm"></label>
      <label class="block w-full mt-2 text-sm"></label>
      <label class="block w-full mt-2 text-sm"></label>

    </div>

    <h4 class="text-md font-semibold mt-3">Basic Info</h4>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2 !w-3/5">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Select Charterer Owner</span>
              <v-select :options="chartererProfiles" placeholder="--Choose an option--" v-model="form.opsChartererProfile" label="name_and_code" class="block form-input">
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
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Select Contract</span>
              <v-select :options="chartererContracts" placeholder="--Choose an option--" v-model="form.opsChartererContract" label="contract_name" class="block form-input">
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
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Vessel</span>
              <input type="text" readonly :value="form.opsChartererContract?.opsVessel?.name" placeholder="Vessel" class="form-input bg-gray-100" autocomplete="off" />
        </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2" v-if="form.contract_type == 'Day Wise'">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Bill From</span>
              <input type="date" v-model.trim="form.bill_from" class="form-input bg-gray-100" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Bill Till</span>
              <input type="date" v-model.trim="form.bill_till" class="form-input bg-gray-100" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Total Days</span>
              <input type="text" readonly v-model.trim="form.total_days" class="form-input bg-gray-100" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Charge Per Day</span>
              <input type="text" readonly :value="form.opsChartererContract?.opsChartererContractsFinancialTerms?.per_day_charge" class="form-input bg-gray-100" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Total Amount</span>
              <input type="text" readonly v-model.trim="form.total_amount" class="form-input bg-gray-100" autocomplete="off" />
        </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2" v-if="form.contract_type == 'Voyage Wise'">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Voyage</span>
              <input type="text" readonly :value="form.opsChartererContract?.opsChartererContractsFinancialTerms?.opsVoyage?.voyage_no" class="form-input bg-gray-100" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Cargo Type</span>
              <input type="text" readonly :value="form.opsChartererContract?.opsChartererContractsFinancialTerms?.opsCargoTariff?.opsCargoType.cargo_type" class="form-input bg-gray-100" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Cargo Quantity</span>
              <input type="text" v-model.trim="form.cargo_quantity" class="form-input bg-gray-100" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Initial Loading Point</span>
              <input type="text" readonly class="form-input bg-gray-100" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Final Loading Point</span>
              <input type="text" readonly class="form-input bg-gray-100" autocomplete="off" />
        </label>
    </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2 !w-2/5" v-if="form.contract_type == 'Voyage Wise'">
       <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Rate Per MT</span>
              <input type="text" readonly :value="form.opsChartererContract?.opsChartererContractsFinancialTerms.per_ton_charge" class="form-input bg-gray-100" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Total Amount</span>
              <input type="text" readonly v-model.trim="form.total_amount" class="form-input bg-gray-100" autocomplete="off" />
        </label>
    </div>


    <div id="sectors" class="mt-5">
      <h4 class="text-md font-semibold my-3">Other</h4>

      <table class="w-full whitespace-no-wrap" >
        <thead v-once>
            <tr class="w-full">
              <th>SL</th>
              <th class="">Particulars</th>
              <th class="">Currency</th>
              <th class="">Unit</th>
              <th>Quantity</th>
              <th>Rate</th>
              <th>Exchange Rate (To USD)</th>
              <th>Exchange Rate (To BDT)</th>
              <th>Amount USD</th>
              <th>Amount BDT</th>
              <th class="py-3 text-center align-center">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(sector, index) in form.opsChartererInvoiceOthers">
              <td>
                {{ index+1 }}
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceOthers[index].particular" placeholder="Quantity" class="form-input text-right" autocomplete="off" />
                  <!-- <Error v-if="errors?.opsChartererInvoiceOthers[index]?.quantity" :errors="errors.opsChartererInvoiceOthers[index]?.quantity" /> -->
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <!-- <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceOthers[index].cost_unit" placeholder="Quantity" class="form-input text-right" autocomplete="off" /> -->
                  <!-- <Error v-if="errors?.opsChartererInvoiceOthers[index]?.quantity" :errors="errors.opsChartererInvoiceOthers[index]?.quantity" /> -->
                  <select v-model.trim="form.opsChartererInvoiceOthers[index].currency" class="form-input" aria-placeholder="Select Currency" placeholder="Select Currency" @change="SetCurrencyData($event,index)">
                    <option selected value="" disabled>Select Currency</option>
                     <option v-for="currency in currencies" :value="currency" :key="currency">{{ currency }}</option>
                  </select>
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceOthers[index].cost_unit" placeholder="Quantity" class="form-input text-right" autocomplete="off" />
                  <!-- <Error v-if="errors?.opsChartererInvoiceOthers[index]?.quantity" :errors="errors.opsChartererInvoiceOthers[index]?.quantity" /> -->
                 
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceOthers[index].quantity" placeholder="Quantity" class="form-input text-right" autocomplete="off" />
                  <!-- <Error v-if="errors?.opsChartererInvoiceOthers[index]?.quantity" :errors="errors.opsChartererInvoiceOthers[index]?.quantity" /> -->
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceOthers[index].rate" placeholder="Quantity" class="form-input text-right" autocomplete="off" />
                  <!-- <Error v-if="errors?.opsChartererInvoiceOthers[index]?.quantity" :errors="errors.opsChartererInvoiceOthers[index]?.quantity" /> -->
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceOthers[index].exchange_rate_usd" placeholder="Quantity" class="form-input text-right" autocomplete="off" :readonly="isOtherCurrency(form.opsChartererInvoiceOthers,index)"/>
                  <!-- <Error v-if="errors?.opsChartererInvoiceOthers[index]?.quantity" :errors="errors.opsChartererInvoiceOthers[index]?.quantity" /> -->
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceOthers[index].exchange_rate_bdt" placeholder="Quantity" class="form-input text-right" autocomplete="off" :readonly="isBDTCurrency(form.opsChartererInvoiceOthers,index)"/>
                  <!-- <Error v-if="errors?.opsChartererInvoiceOthers[index]?.quantity" :errors="errors.opsChartererInvoiceOthers[index]?.quantity" /> -->
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceOthers[index].amount_usd" placeholder="Quantity" class="form-input text-right" autocomplete="off" readonly/>
                  <!-- <Error v-if="errors?.opsChartererInvoiceOthers[index]?.quantity" :errors="errors.opsChartererInvoiceOthers[index]?.quantity" /> -->
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceOthers[index].amount_bdt" placeholder="Quantity" class="form-input text-right" autocomplete="off" readonly/>
                  <!-- <Error v-if="errors?.opsChartererInvoiceOthers[index]?.quantity" :errors="errors.opsChartererInvoiceOthers[index]?.quantity" /> -->
                </label>
              </td>
              <td class="px-1 py-1 text-center">
                <button v-if="index!=0" type="button" @click="removeOther(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                  </svg>
                </button>
                <button v-else type="button" @click="addOther()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg>
                </button>
              </td>
            </tr>
          </tbody>
          
        </table>     
      
    </div>
    

    <div id="sectors" class="mt-5">
      <h4 class="text-md font-semibold my-3">Services Taken From Charterer</h4>

      <table class="w-full whitespace-no-wrap" >
          <thead v-once>
            <tr class="w-full">
              <th>SL</th>
              <th>Particulars</th>
              <th>Currency</th>
              <th>Unit</th>
              <th>Quantity</th>
              <th>Rate</th>
              <th>Exchange Rate (To USD)</th>
              <th>Exchange Rate (USD To BDT)</th>
              <th>Amount USD</th>
              <th>Amount BDT</th>
              <th class="py-3 text-center align-center">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in form.opsChartererInvoiceServices">
              <td>
                {{ index+1 }}
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceServices[index].particular" placeholder="Quantity" class="form-input text-right" autocomplete="off" />
                  <!-- <Error v-if="errors?.opsChartererInvoiceServices[index]?.quantity" :errors="errors.opsChartererInvoiceServices[index]?.quantity" /> -->
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <!-- <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceServices[index].currency" placeholder="Quantity" class="form-input text-right" autocomplete="off" /> -->
                  <!-- <Error v-if="errors?.opsChartererInvoiceServices[index]?.quantity" :errors="errors.opsChartererInvoiceServices[index]?.quantity" /> -->
                  <select v-model.trim="form.opsChartererInvoiceServices[index].currency" class="form-input" aria-placeholder="Select Currency" placeholder="Select Currency" @change="SetCurrencyData($event,index)">
                    <option selected value="" disabled>Select Currency</option>
                     <option v-for="currency in currencies" :value="currency" :key="currency">{{ currency }}</option>
                  </select>
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceServices[index].cost_unit" placeholder="Quantity" class="form-input text-right" autocomplete="off" />
                  <!-- <Error v-if="errors?.opsChartererInvoiceServices[index]?.quantity" :errors="errors.opsChartererInvoiceServices[index]?.quantity" /> -->
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceServices[index].quantity" placeholder="Quantity" class="form-input text-right" autocomplete="off" />
                  <!-- <Error v-if="errors?.opsChartererInvoiceServices[index]?.quantity" :errors="errors.opsChartererInvoiceServices[index]?.quantity" /> -->
                </label>
              </td>

              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceServices[index].rate" placeholder="Quantity" class="form-input text-right" autocomplete="off" />
                  <!-- <Error v-if="errors?.opsChartererInvoiceServices[index]?.quantity" :errors="errors.opsChartererInvoiceServices[index]?.quantity" /> -->
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceServices[index].exchange_rate_usd" placeholder="Quantity" class="form-input text-right" autocomplete="off" :readonly="isOtherCurrency(form.opsChartererInvoiceServices,index)"/>
                  <!-- <Error v-if="errors?.opsChartererInvoiceServices[index]?.quantity" :errors="errors.opsChartererInvoiceServices[index]?.quantity" /> -->
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceServices[index].exchange_rate_bdt" placeholder="Quantity" class="form-input text-right" autocomplete="off" :readonly="isBDTCurrency(form.opsChartererInvoiceServices,index)"/>
                  <!-- <Error v-if="errors?.opsChartererInvoiceServices[index]?.quantity" :errors="errors.opsChartererInvoiceServices[index]?.quantity" /> -->
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceServices[index].amount_usd" placeholder="Quantity" class="form-input text-right" autocomplete="off" readonly/>
                  <!-- <Error v-if="errors?.opsChartererInvoiceServices[index]?.quantity" :errors="errors.opsChartererInvoiceServices[index]?.quantity" /> -->
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceServices[index].amount_bdt" placeholder="Quantity" class="form-input text-right" autocomplete="off" readonly/>
                  <!-- <Error v-if="errors?.opsChartererInvoiceServices[index]?.quantity" :errors="errors.opsChartererInvoiceServices[index]?.quantity" /> -->
                </label>
              </td>
              <td class="px-1 py-1 text-center">
                <button v-if="index!=0" type="button" @click="removeService(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                  </svg>
                </button>
                <button v-else type="button" @click="addService()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg>
                </button>
              </td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="8" class="text-right">Total</td>
              <td class="text-right">
                <input type="text" readonly :value="props.form.others_billable_amount_usd" class="form-input bg-gray-100 text-right" autocomplete="off" />
                
              </td>
              <td>
                <input type="text" readonly :value="props.form.others_billable_amount" class="form-input bg-gray-100 text-right" autocomplete="off" />
              </td>
            </tr>
            <tr>
              <td colspan="8" class="text-right">(Service Cost)</td>
              <td class="text-right">
                <input type="text" readonly :value="props.form.service_fee_deduction_amount_usd" class="form-input bg-gray-100 text-right" autocomplete="off" />
              
              </td>
              <td>
                <input type="text" readonly :value="props.form.service_fee_deduction_amount" class="form-input bg-gray-100 text-right" autocomplete="off" />
              </td>
            </tr>
            <tr>
              <td colspan="8" class="text-right">(Service Cost)</td>
              <td class="text-right">
                <input type="text" readonly v-model="props.form.discounted_amount_usd" class="form-input bg-gray-100 text-right" autocomplete="off" />
              </td>
              <td>
                <input type="text" v-model="props.form.discounted_amount" class="form-input bg-gray-100 text-right" autocomplete="off" />
              </td>
            </tr>
            <tr>
              <td colspan="8" class="text-right">Grand Total</td>
              <td class="text-right">
                <input type="text" readonly :value="props.form.grand_total_usd" class="form-input bg-gray-100 text-right" autocomplete="off" />
               
              </td>
              <td>
                <input type="text" readonly :value="props.form.grand_total" class="form-input bg-gray-100 text-right" autocomplete="off" />
              </td>

            </tr>
          </tfoot>
        </table>     
      
    </div>


    
</template>
<script setup>
import { ref, watch, onMounted, watchPostEffect } from "vue";
import Error from "../Error.vue";
import useVoyage from "../../composables/operations/useVoyage";
import useVessel from "../../composables/operations/useVessel";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import useChartererProfile from "../../composables/operations/useChartererProfile";
import useChartererContract from "../../composables/operations/useChartererContract";
import useBusinessInfo from '../../composables/useBusinessInfo';

import moment from 'moment';
import cloneDeep from 'lodash/cloneDeep';

const editInitiated = ref(false);

const { currencies, getCurrencies } = useBusinessInfo();
const { getAllChartererProfiles, chartererProfiles } = useChartererProfile();
const { getChartererContractsByCharterOwner, chartererContracts } = useChartererContract();
const { voyage, voyages, showVoyage, searchVoyages } = useVoyage();
const { vessel, showVessel } = useVessel();
const props = defineProps({
    form: { required: false,default: {},},
    errors: { type: [Object, Array], required: false },
    formType: { type: String, required: false },
    serviceObject: { type: Object, required: false },
    loading: { type: Boolean, required: false },
    otherObject: { type: Object, required: false },
    
});


const addService = () => {
  const service = cloneDeep(props.serviceObject);
  props.form.opsChartererInvoiceServices.push(service);
};


const addOther = () => {
  const other = cloneDeep(props.otherObject);
  props.form.opsChartererInvoiceOthers.push(other);
};


const removeService = (index) => {
  props.form.opsChartererInvoiceServices.splice(index, 1);
};


const removeOther = (index) => {
  props.form.opsChartererInvoiceOthers.splice(index, 1);
};

const SetCurrencyData = (e,index) => {
  console.log(e.target.value,index);
}




const isUSDCurrency = (item,index) => {
  const currency = item[index].currency;
  return currency === 'USD';
};

const isBDTCurrency = (item,index) => {
  const currency = item[index].currency;
  return currency === 'BDT';
};

const isOtherCurrency = (item, index) => {
  const currency = item[index]?.currency;
  return (currency !== 'BDT' || currency !== 'USD');
};

const isNotBDTCurrency = (item,index) => {
  const currency = item[index].currency;
  return currency !== 'BDT';
};

// watch(() => props?.form?.scmPoLines, (newVal, oldVal) => {
//       let total = 0.0;
//       newVal?.forEach((line, index) => {
//         props.form.scmPoLines[index].total_price = parseFloat((line?.rate * line?.quantity).toFixed(2));
//         total += parseFloat(props.form.scmPoLines[index].total_price);
//         if (line.scmMaterial) {
//           setMaterialOtherData(line, index);
//         }
//       });
//       props.form.sub_total = parseFloat(total.toFixed(2));
//       calculateNetAmount();


// }, { deep: true });


//watch opsChartererInvoiceServices
watch(() => props?.form?.opsChartererInvoiceServices, (newVal, oldVal) => {
      let total_bdt = 0.0;
      let total_usd = 0.0;
      newVal?.forEach((line, index) => {
        const { amount_usd, amount_bdt } = calculateInCurrency(line, index);
        total_bdt += amount_bdt;
        total_usd += amount_usd;

      });
  props.form.service_fee_deduction_amount_usd = total_usd;
  props.form.service_fee_deduction_amount = total_bdt ;

}, { deep: true }); 



// watch opsChartererInvoiceOthers
watch(() => props?.form?.opsChartererInvoiceOthers, (newVal, oldVal) => {
      let total_bdt = 0.0;
      let total_usd = 0.0;
      newVal?.forEach((line, index) => {
        // destructure calculateInCurrency
        const { amount_usd, amount_bdt } = calculateInCurrency(line, index);
        total_bdt += amount_bdt;
        total_usd += amount_usd;
      });
      props.form.others_billable_amount_usd = total_usd;
      props.form.others_billable_amount = total_bdt;
}, { deep: true });


const calculateInCurrency = (item,index) => {
  const currency = item.currency;
  if(currency == 'USD'){
    item.amount_usd = parseFloat((item?.rate * item?.quantity).toFixed(2));
    item.amount_bdt = parseFloat((item?.rate * item?.quantity * item?.exchange_rate_bdt).toFixed(2));
  } else if(currency == 'BDT'){
    item.amount_usd = 0;
    item.amount_bdt = parseFloat((item?.rate * item?.quantity).toFixed(2));
  } else {
    item.amount_usd = parseFloat((item?.rate * item?.quantity * item?.exchange_rate_usd).toFixed(2));
    item.amount_bdt = parseFloat((item?.rate * item?.quantity * item?.exchange_rate_usd * item?.exchange_rate_bdt).toFixed(2));
  }
  return {amount_usd: item.amount_usd, amount_bdt: item.amount_bdt};
}

// watch discounted_amount
watch(() => props?.form?.discounted_amount, (newVal, oldVal) => {
     props.form.grand_total = (props.form.others_billable_amount * 1) - (props.form.service_fee_deduction_amount * 1) - newVal;

});

function fetchVoyages(searchParam, loading) {
  loading(true)
  searchVoyages(searchParam, props.form.business_unit, loading)
}

watch(() => props.form.business_unit, (value) => {
  if(props?.formType != 'edit') {
    props.form.opsVoyage = null;
    vessel.value = null;
    props.form.opsVoyageSectors = null;
    props.form.vessel_name = null;
    props.form.ops_voyage_id = null;
  }
})


watch(() => props.form.opsChartererProfile, (value) => {
    props.form.ops_charterer_profile_id = value?.id;
})





watch(() => props.form.ops_charterer_profile_id, (value) => {
    props.form.ops_charterer_contract_id = '';
    props.form.opsChartererContract = null;
    getChartererContractsByCharterOwner(value);
})

watch(() => props.form.opsChartererContract, (value) => {
  props.form.ops_charterer_contract_id = value?.id;
  props.form.contract_type = value?.contract_type;
})


onMounted(() => {
  getAllChartererProfiles();
  getCurrencies();
})

watchPostEffect(() => {
  if(props.form.contract_type == "Day Wise") {
    // props.form.bill_from = props.form.opsChartererContract?.opsChartererContractsFinancialTerms?.bill_from;
    // props.form.bill_till = props.form.opsChartererContract?.opsChartererContractsFinancialTerms?.bill_till;
      const billFrom = moment(props.form.bill_from);
      const billTill = moment(props.form.bill_till);
      if (billFrom.isValid() && billTill.isValid()) {
        const totalDays = billTill.diff(billFrom, 'days');
        props.form.total_days = totalDays;
      } else {
        props.form.total_days = 0;
      }
      props.form.total_amount = props.form.total_days * props.form.opsChartererContract?.opsChartererContractsFinancialTerms?.per_day_charge;
  } else {
      props.form.total_amount = props.form.cargo_quantity * props.form.opsChartererContract?.opsChartererContractsFinancialTerms?.per_ton_charge;
  }
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