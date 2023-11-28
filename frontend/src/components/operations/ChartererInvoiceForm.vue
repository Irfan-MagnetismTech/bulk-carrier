<template>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <business-unit-input v-model="form.business_unit" :page="formType"></business-unit-input>
      <label class="block w-full mt-2 text-sm"></label>
      <label class="block w-full mt-2 text-sm"></label>
      <label class="block w-full mt-2 text-sm"></label>

    </div>

    <h4 class="text-md font-semibold mt-3">Basic Info</h4>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Select Charterer Owner</span>
              <v-select :options="chartererProfiles" placeholder="--Choose an option--" v-model="form.opsChartererProfile" label="name_and_code" class="block form-input">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.opsChartererProfile"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Select Contract</span>
              <v-select :options="chartererContracts" placeholder="--Choose an option--" v-model="form.opsChartererContract" label="contract_name" class="block form-input">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.opsChartererContract"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Vessel</span>
              <input type="text" readonly :value="form.opsChartererContract?.opsVessel?.name" placeholder="Vessel" class="form-input bg-gray-100" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm" v-if="form.contract_type == 'Voyage Wise'">
              <span class="text-gray-700 dark-disabled:text-gray-300">Cargo Type</span>
              <input type="text" readonly :value="form.opsChartererContract?.opsChartererContractsFinancialTerms?.opsCargoTariff?.opsCargoType.cargo_type" class="form-input bg-gray-100" autocomplete="off" />
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

    <!-- <div class="flex flex-col justify-center w-full md:flex-row md:gap-2" v-if="form.contract_type == 'Voyage Wise'">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Voyage</span>
              <input type="text" readonly :value="form.opsChartererContract?.opsChartererContractsFinancialTerms?.opsVoyage?.voyage_no" class="form-input bg-gray-100" autocomplete="off" />
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
    </div> -->


    <div id="sectors" class="mt-5" v-if="form.contract_type == 'Voyage Wise'">
      <h4 class="text-md font-semibold my-3">Voyage Data</h4>

      <table class="w-full whitespace-no-wrap" >
        <thead v-once>
            <tr class="w-full">
              <th>SL</th>
              <th class="">Voyage</th>
              <th class="">Cargo Quantity</th>
              <th class="">Rate Per MT</th>
              <th>Total Amount</th>
              <th>Details</th>
              <th class="py-3 text-center align-center">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(sector, index) in form.opsChartererInvoiceVoyages">
              <td>
                {{ index+1 }}
              </td>
              <td class="!w-1/4">
                <label class="block w-full mt-2 text-sm">
                  <!-- <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceVoyages[index].opsVoyage" placeholder="Quantity" class="form-input text-right" autocomplete="off" /> -->
                  <!-- <Error v-if="errors?.opsChartererInvoiceOthers[index]?.quantity" :errors="errors.opsChartererInvoiceOthers[index]?.quantity" /> -->
                  <v-select :options="voyages" placeholder="--Choose an option--" v-model="form.opsChartererInvoiceVoyages[index].opsVoyage" label="voyage_no" class="block form-input">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.opsChartererInvoiceVoyages[index].opsVoyage"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
                </label>
              </td>
              <td>
                  <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceVoyages[index].cargo_quantity" placeholder="Quantity" readonly class="form-input text-right" autocomplete="off" />
                  <!-- <Error v-if="errors?.opsChartererInvoiceOthers[index]?.quantity" :errors="errors.opsChartererInvoiceOthers[index]?.quantity" /> -->
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceVoyages[index].rate_per_mt" readonly placeholder="Quantity" class="form-input text-right" autocomplete="off" />
                  <!-- <Error v-if="errors?.opsChartererInvoiceOthers[index]?.quantity" :errors="errors.opsChartererInvoiceOthers[index]?.quantity" /> -->
                 
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceVoyages[index].total_amount" placeholder="Quantity" readonly class="form-input text-right" autocomplete="off" />
                  <!-- <Error v-if="errors?.opsChartererInvoiceOthers[index]?.quantity" :errors="errors.opsChartererInvoiceOthers[index]?.quantity" /> -->
                </label>
              </td>
              <td>
                <a @click="showModal(index)" style="display: inline-block;cursor: pointer" class="relative tooltip">
                  <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                  </svg>
                  <span class="tooltiptext">Details</span>
                </a>
              </td>
              <td class="px-1 py-1 text-center">
                <button v-if="index!=0" type="button" @click="removeVoyage(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                  </svg>
                </button>
                <button v-else type="button" @click="addVoyage()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
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
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceOthers[index].exchange_rate_usd" placeholder="Quantity" class="form-input text-right" autocomplete="off" :readonly="isUSDCurrency(form.opsChartererInvoiceOthers,index)"/>
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
                  <input type="number" step="0.001" v-model.trim="form.opsChartererInvoiceServices[index].exchange_rate_usd" placeholder="Quantity" class="form-input text-right" autocomplete="off" :readonly="isUSDCurrency(form.opsChartererInvoiceServices,index)"/>
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
                <input type="text" readonly :value="props.form.sub_total_amount_usd" class="form-input bg-gray-100 text-right" autocomplete="off" />
                
              </td>
              <td>
                <input type="text" readonly :value="props.form.sub_total_amount" class="form-input bg-gray-100 text-right" autocomplete="off" />
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


    // model

    <div v-show="isModalOpen" class="fixed inset-0 z-30 flex items-end overflow-y-auto bg-black bg-opacity-50 sm:items-center sm:justify-center">
    <!-- Modal -->
    <form @submit.prevent="" style="position: absolute;top: 0;">
      <div class="w-full px-6 py-4 overflow-y-auto bg-white rounded-t-lg dark-disabled:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl" role="dialog" id="modal">
        <!-- Remove header if you don't want a close icon. Use modal body to place modal tile. -->
        <header class="flex justify-end">
          <button type="button"
                  class="inline-flex items-center justify-center w-6 h-6 mb-2 text-gray-400 transition-colors duration-150 rounded dark-disabled:hover:text-gray-200 hover: hover:text-gray-700"
                  aria-label="close" @click="closeModel">
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
          <tr style="background-color: #04AA6D;color: white"
              class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b dark-disabled:border-gray-700 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
            <th colspan="5">Details</th>
          </tr>
          </thead>
        </table>

        <div class="dt-responsive table-responsive">
          <table id="dataTable" class="w-full table table-striped table-bordered">
            <thead>
              <tr>
                <th>Loading Point</th>
                <th>Unloading point</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(detail, index) in details" :key="index">
                <td>
                  <input type="text" :value="detail.loading_point" class="form-input text-right" placeholder="Head" />
                </td>
                <td>
                  <input type="text" :value="detail.unloading_point" class="form-input text-right" placeholder="Head" />
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <footer class="flex flex-col items-center justify-end px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark-disabled:bg-gray-800">
          <button type="button" @click="closeModel" style="color: #1b1e21"
                  class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 border border-gray-300 rounded-lg dark-disabled:text-gray-400 sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray">
            Cancel
          </button>
          <!-- <button type="button" @click="pushBunkerConsumption"
              class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            Submit
          </button> -->
        </footer>
      </div>
    </form>
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
const { getChartererContractsByCharterOwner, chartererContracts,getContractWiseVoyage,voyages } = useChartererContract();
// const { voyage, voyages, showVoyage, searchVoyages } = useVoyage();
const { vessel, showVessel } = useVessel();
const props = defineProps({
    form: { required: false,default: {},},
    errors: { type: [Object, Array], required: false },
    formType: { type: String, required: false },
    serviceObject: { type: Object, required: false },
    loading: { type: Boolean, required: false },
    otherObject: { type: Object, required: false },
    chartererInvoiceVoyageObject: { type: Object, required: false },
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

const addVoyage = () => {
  const voyage = cloneDeep(props.chartererInvoiceVoyageObject);
  //add per ton charge in voyage object
  voyage.rate_per_mt = props.form.opsChartererContract?.opsChartererContractsFinancialTerms?.per_ton_charge;
  props.form.opsChartererInvoiceVoyages.push(voyage);
};

const removeVoyage = (index) => {
  props.form.opsChartererInvoiceVoyages.splice(index, 1);
};

const SetCurrencyData = (e,index) => {
  console.log(e.target.value,index);
}

const addasd = () => {
  const chartererInvoiceVoyageObject = cloneDeep(props.chartererInvoiceVoyageObject);
  props.form.opsChartererInvoiceVoyages.push(chartererInvoiceVoyageObject);
};



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



watch(() => props.form.opsChartererInvoiceVoyages, (newLines) => {
  let total_amount = 0.0;
  newLines.forEach((line, index) => {
    if (line.opsVoyage) {
      const selectedItem = voyages.value.find(voyage => voyage.id === line.opsVoyage.id);
      if (selectedItem) {
        if ( line.ops_voyage_id !== selectedItem.id
        ) {
          props.form.opsChartererInvoiceVoyages[index].ops_voyage_id = selectedItem.id;
          props.form.opsChartererInvoiceVoyages[index].cargo_quantity = selectedItem.cargo_quantity;
          props.form.opsChartererInvoiceVoyages[index].total_amount = props.form.opsChartererInvoiceVoyages[index].cargo_quantity * props.form.opsChartererInvoiceVoyages[index].rate_per_mt;
        }
      }
    }

    total_amount += parseFloat(props.form.opsChartererInvoiceVoyages[index].total_amount);
    props.form.total_amount = parseFloat(total_amount.toFixed(2));
  });
  CalculateAll();
  // previousLines.value = cloneDeep(newLines);
}, { deep: true });

function CalculateAll() {
 
  // props.form.others_billable_amount = total_bdt;
  // props.form.total_amount +  props.form.others_billable_amount - props.form.service_fee_deduction_amount
  // props.form.service_fee_deduction_amount_usd
  // props.form.others_billable_amount_usd = total_usd;
  // props.form.others_billable_amount = total_bdt;
  // props.form.service_fee_deduction_amount_usd = total_usd;
  // props.form.service_fee_deduction_amount = total_bdt ;
  // let total_service_fee_bdt = 0.0;
  // let total_service_fee_usd = 0.0;
  // props.form.opsChartererInvoiceServices.forEach((line, index) => {
  //   const { amount_usd, amount_bdt } = calculateInCurrency(line, index);
  //   total_bdt += total_service_fee_bdt;
  //   total_usd += total_service_fee_usd;
  // });
  

//  total_amount = props.form.total_amount;

  // total_others_billable_amount_bdt = 0.0;
  // total_others_billable_amount_usd = 0.0;
  // props.form.opsChartererInvoiceOthers.forEach((line, index) => {
  //   const { amount_usd, amount_bdt } = calculateInCurrency(line, index);
  //   total_bdt += total_others_billable_amount_bdt;
  //   total_usd += total_others_billable_amount_usd;
  // });

  // props.form.others_billable_amount_usd = total_others_billable_amount_usd;
  // props.form.others_billable_amount = total_others_billable_amount_bdt;

  // props.form.service_fee_deduction_amount ;
  props.form.sub_total_amount = (props.form.total_amount * 1) + (props.form.others_billable_amount * 1);

  props.form.grand_total = (props.form.sub_total_amount * 1) - (props.form.service_fee_deduction_amount * 1 )- (props.form.discounted_amount * 1);
}
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
  CalculateAll();
}, { deep: true }); 

//watch opsChartererInvoiceVoyages
// watch(() => props?.form?.opsChartererInvoiceVoyages, (newVal, oldVal) => {

//       // newVal?.forEach((line, index) => {
//       //   const { amount_usd, amount_bdt } = calculateInCurrency(line, index);
//       //   total_bdt += amount_bdt;
//       //   total_usd += amount_usd;
//       // });
//       // props.form.total_amount_usd = total_usd;
//       // props.form.total_amount = total_bdt;
// }, { deep: true });

// watch opsChartererInvoiceOthers
watch(() => props?.form?.opsChartererInvoiceOthers, (newVal, oldVal) => {
      let total_bdt = 0.0;
      let total_usd = 0.0;
      newVal?.forEach((line, index) => {
        const { amount_usd, amount_bdt } = calculateInCurrency(line, index);
        total_bdt += amount_bdt;
        total_usd += amount_usd;
      });
      props.form.others_billable_amount_usd = total_usd;
  props.form.others_billable_amount = total_bdt;
  CalculateAll(); 
}, { deep: true });


const calculateInCurrency = (item,index) => {
  const currency = item.currency;
  if(currency == 'USD'){
    item.amount_usd = parseFloat((item?.rate * item?.quantity).toFixed(2));
    item.amount_bdt = parseFloat((item?.rate * item?.quantity * item?.exchange_rate_bdt).toFixed(2));
  } else if(currency == 'BDT'){
    item.amount_usd = parseFloat((item?.rate * item?.quantity * item?.exchange_rate_usd).toFixed(2));
    item.amount_bdt = parseFloat((item?.rate * item?.quantity).toFixed(2));
  } else {
    item.amount_usd = parseFloat((item?.rate * item?.quantity * item?.exchange_rate_usd).toFixed(2));
    item.amount_bdt = parseFloat((item?.rate * item?.quantity * item?.exchange_rate_usd * item?.exchange_rate_bdt).toFixed(2));
  }
  return {amount_usd: item.amount_usd, amount_bdt: item.amount_bdt};
}

// watch discounted_amount
watch(() => props?.form?.discounted_amount, (newVal, oldVal) => {
     props.form.grand_total = (props.form.others_billable_amount * 1 ) - (props.form.service_fee_deduction_amount * 1) - newVal;
  CalculateAll();
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
  getAllChartererProfiles(value);
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
  if(value.contract_type == 'Voyage Wise') {
    getContractWiseVoyage(value.id);
    props.form.opsChartererInvoiceVoyages[0].rate_per_mt = props.form.opsChartererContract?.opsChartererContractsFinancialTerms?.per_ton_charge
  }
})


onMounted(() => {
  // getAllChartererProfiles();
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
    CalculateAll();
  } else {
      // props.form.total_amount = props.form.cargo_quantity * props.form.opsChartererContract?.opsChartererContractsFinancialTerms?.per_ton_charge;
  }
})


const isModalOpen = ref(0);
const details = ref([{type: ''}]);
const currentIndex = ref(null);


function showModal(index) {
  isModalOpen.value = 1
  currentIndex.value = index
  if(props.form.opsChartererInvoiceVoyages[index].opsVoyage?.opsVoyageSectors) {
    details.value = cloneDeep(props.form.opsChartererInvoiceVoyages[index].opsVoyage?.opsVoyageSectors)
  } else {
    details.value = [{type: ''}]
  }
}

function closeModel() {
  isModalOpen.value = 0
  details.value = [{type: ''}]
}


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