<template>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <business-unit-input v-model="form.business_unit" :page="'edit'"></business-unit-input>
      <label class="block w-full mt-2 text-sm"></label>
      <label class="block w-full mt-2 text-sm"></label>
      <label class="block w-full mt-2 text-sm"></label>
    </div>

    <h4 class="text-md font-semibold mt-3">Basic Info</h4>
    <div class="flex flex-col w-full md:flex-row md:gap-2">
        <label class="block w-1/2 mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Customer Name <span class="text-red-500">*</span></span>
              <v-select :options="customers" placeholder="--Choose an option--" v-model="form.opsCustomer" label="name_code" class="block form-input" @update:modelValue="profileChanged">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.opsCustomer"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
              <input type="hidden" v-model="form.ops_customer_id">
        </label>
        <label class="block w-1/4 mt-2 text-sm">
          <span class="text-gray-700">Invoice Date <span class="text-red-500">*</span></span>
          <VueDatePicker v-model="form.date" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>

        </label>
    </div>


    <div id="sectors" class="mt-5">
      <h4 class="text-md font-semibold my-3">Voyage Data</h4>
      <table class="w-full whitespace-no-wrap" >
        <thead v-once>
            <tr class="w-full">
              <th class="w-4/12">Voyage <span class="text-red-500">*</span></th>
              <th class="w-2/12"><nobr>Vessel</nobr></th>
              <th class="w-2/12"><nobr>Cargo Type</nobr></th>
              <th class="w-2/12"><nobr>Total Amount</nobr></th>
              <th class="w-1/12py-3 text-center align-center">Details</th>
              <th class="w-1/12" v-if="formType=='create'"><nobr>Action</nobr></th>

            </tr>
          </thead>
          <tbody>
            <tr v-for="(sector, index) in form.opsCustomerInvoiceVoyages" :key="index">
              <td class="!w-1/4">
                <label class="block w-full mt-2 text-sm relative">
                  <!-- <input type="number" step="0.001" v-model.trim="form.opsCustomerInvoiceVoyages[index].opsVoyage" placeholder="Quantity" class="form-input text-right" autocomplete="off" /> -->
                  <!-- <Error v-if="errors?.opsCustomerInvoiceOthers[index]?.quantity" :errors="errors.opsCustomerInvoiceOthers[index]?.quantity" /> -->
                  <v-select :options="voyages" placeholder="--Choose an option--" :readonly="formType=='edit'" :disabled="formType=='edit'" v-model="form.
                    opsCustomerInvoiceVoyages[index].opsVoyage" label="voyage_sequence" class="block form-input" @update:modelValue="opsCustomerInvoiceVoyageChanged(form.opsCustomerInvoiceVoyages[index])">
                    <template #search="{attributes, events}">
                        <input
                            class="vs__search"
                            :required="!form.opsCustomerInvoiceVoyages[index].opsVoyage"
                            v-bind="attributes"
                            v-on="events"
                            />
                    </template>
                </v-select>
                <input type="hidden" v-model="form.opsCustomerInvoiceVoyages[index].ops_voyage_id">
                <span v-show="form.opsCustomerInvoiceVoyages[index].isVoyageDuplicate" class="text-yellow-600 absolute top-2 right-10" title="Duplicate Voyage" v-html="icons.ExclamationTriangle"></span>
                </label>
              </td>

              <!-- opsCustomerInvoiceVoyage.contractTariff = contractTariff;
    opsCustomerInvoiceVoyage.ops_vessel_id = contractTariff.opsVessel.id;
    opsCustomerInvoiceVoyage.opsVessel = contractTariff.opsVessel;
    opsCustomerInvoiceVoyage.ops_voyage_id = opsCustomerInvoiceVoyage.ops_voyage_id;
    opsCustomerInvoiceVoyage.total_amount_bdt = contractTariff.total_amount; -->
              <td>
                <input type="text" :value="form.opsCustomerInvoiceVoyages[index]?.opsVessel?.name" readonly class="form-input vms-readonly-input" autocomplete="off"  />
              </td>
              
              <td>
                <input type="text" :value="form.opsCustomerInvoiceVoyages[index]?.opsCargoType?.cargo_type" readonly class="form-input vms-readonly-input" autocomplete="off"  />
                <!-- <input type="text" :value="form.opsCustomerInvoiceVoyages[index]?.contractTariff?.opsCargoType?.cargo_type" readonly class="form-input vms-readonly-input" autocomplete="off"  /> -->
                  <!-- <label class="block w-full mt-2 text-sm"> -->
                    <!-- <span class="show-block">
                      {{ form.opsCustomerInvoiceVoyages[index].opsVoyage?.opsCargoType?.cargo_type }}
                    </span> -->
                  <!-- <input type="text" v-model.trim="form.opsCustomerInvoiceVoyages[index].opsVoyage.opsCargoType.cargo_type" readonly class="form-input text-right" autocomplete="off" /> -->
                  <!-- <Error v-if="errors?.opsCustomerInvoiceOthers[index]?.quantity" :errors="errors.opsCustomerInvoiceOthers[index]?.quantity" /> -->
                <!-- </label> -->
              </td>
             
             
              <td>
                <label class="block w-full mt-2 text-sm">
                  <!-- <input type="text" :value="form.opsCustomerInvoiceVoyages[index]?.contractTariff?.total_amount" readonly class="form-input vms-readonly-input" autocomplete="off"  /> -->
                  <input type="text" :value="form.opsCustomerInvoiceVoyages[index]?.total_amount_bdt" readonly class="form-input text-right" autocomplete="off" />
                  <!-- <Error v-if="errors?.opsCustomerInvoiceOthers[index]?.quantity" :errors="errors.opsCustomerInvoiceOthers[index]?.quantity" /> -->
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
              <td class="px-1 py-1 text-center"  v-if="formType=='create'">
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


    <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
    <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Other</legend>
    <div id="sectors" class="mt-5">
      <table class="w-full whitespace-no-wrap" >
        <thead v-once>
            <tr class="w-full">
              <th class=" w-4/12">Particulars</th>
              <!-- <th class="">Currency</th> -->
              <th class=" w-1/12">Unit</th>
              <th class=" w-2/12">Quantity <span class="text-red-500">*</span></th>
              <th class=" w-2/12">Rate <span class="text-red-500">*</span></th>
              <!-- <th>Exchange Rate (To USD)</th>
              <th>Exchange Rate (USD To BDT)</th>
              <th>Amount USD</th>
              <th>Amount BDT</th> -->
              <th class=" w-2/12">Amount</th>
              <th class=" w-1/12py-3 text-center align-center"><button  type="button" @click="addOther()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg>
                </button></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(sector, index) in form.opsCustomerInvoiceOthers" :key="index">
              <td>
                <label class="block w-full mt-2 text-sm relative">
                  <input type="text" step="0.001" v-model.trim="form.opsCustomerInvoiceOthers[index].particular" class="form-input" autocomplete="off" :class="{'pr-7' : form.opsCustomerInvoiceOthers[index].isParticularDuplicate}" />
                  <span v-show="form.opsCustomerInvoiceOthers[index].isParticularDuplicate" class="text-yellow-600 absolute pl-2 top-2 right-1" title="Duplicate Particular" v-html="icons.ExclamationTriangle"></span>
                  <!-- <Error v-if="errors?.opsCustomerInvoiceOthers[index]?.quantity" :errors="errors.opsCustomerInvoiceOthers[index]?.quantity" /> -->
                </label>
              </td>
              <!-- <td>
                <label class="block w-full mt-2 text-sm">
                  <select v-model.trim="form.opsCustomerInvoiceOthers[index].currency" class="form-input" aria-placeholder="Select Currency" placeholder="Select Currency" @change="SetCurrencyData($event,index)">
                    <option selected value="" disabled>Select Currency</option>
                     <option v-for="currency in currencies" :value="currency" :key="currency">{{ currency }}</option>
                  </select>
                </label>
              </td> -->
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="text" step="0.001" v-model.trim="form.opsCustomerInvoiceOthers[index].cost_unit" class="form-input" autocomplete="off" />
                 
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" min="1" v-model.trim="form.opsCustomerInvoiceOthers[index].quantity" class="form-input text-right" autocomplete="off" required />
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" min="1" v-model.trim="form.opsCustomerInvoiceOthers[index].rate" class="form-input text-right" autocomplete="off" required />
                </label>
              </td>
              <!-- <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsCustomerInvoiceOthers[index].exchange_rate_usd" class="form-input text-right" autocomplete="off" :readonly="isUSDCurrency(form.opsCustomerInvoiceOthers,index)"/>
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsCustomerInvoiceOthers[index].exchange_rate_bdt" class="form-input text-right" autocomplete="off" :readonly="isBDTCurrency(form.opsCustomerInvoiceOthers,index)"/>
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsCustomerInvoiceOthers[index].amount_usd" class="form-input text-right" autocomplete="off" readonly/>
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsCustomerInvoiceOthers[index].amount_bdt" class="form-input text-right" autocomplete="off" readonly/>
                </label>
              </td> -->
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" min="1" v-model.trim="form.opsCustomerInvoiceOthers[index].amount" class="form-input text-right" autocomplete="off" readonly/>
                </label>
              </td>
              <td class="px-1 py-1 text-center">
                <button type="button" @click="removeOther(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                  </svg>
                </button>
                <!-- <button v-else type="button" @click="addOther()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg>
                </button> -->
              </td>
            </tr>
          </tbody>
          
        </table>     
      
    </div>
    </fieldset>


    <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
        <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Services Taken From Customer</legend>
    <div id="sectors" class="mt-5">

      <table class="w-full whitespace-no-wrap" >
          <thead v-once>
            <tr class="w-full">
              <th class=" w-4/12">Particulars</th>
              <!-- <th>Currency</th> -->
              <th class=" w-1/12">Unit</th>
              <th class=" w-2/12">Quantity <span class="text-red-500">*</span></th>
              <th class=" w-2/12">Rate <span class="text-red-500">*</span></th>
              <!-- <th>Exchange Rate (To USD)</th>
              <th>Exchange Rate (USD To BDT)</th>
              <th>Amount USD</th>
              <th>Amount BDT</th> -->
              <th class=" w-2/12">Amount</th>
              <th class=" w-1/12py-3 text-center align-center"><button type="button" @click="addService()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg>
                </button></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in form.opsCustomerInvoiceServices" :key="index">
              <td>
                <label class="block w-full mt-2 text-sm relative">
                  <input type="text" v-model.trim="form.opsCustomerInvoiceServices[index].particular" class="form-input" autocomplete="off" :class="{'pr-7' : form.opsCustomerInvoiceServices[index].isParticularDuplicate}" />
                  <span v-show="form.opsCustomerInvoiceServices[index].isParticularDuplicate" class="text-yellow-600 absolute top-2 right-1 " title="Duplicate Particular" v-html="icons.ExclamationTriangle"></span>
                  <!-- <Error v-if="errors?.opsCustomerInvoiceServices[index]?.quantity" :errors="errors.opsCustomerInvoiceServices[index]?.quantity" /> -->
                </label>
              </td>
              <!-- <td>
                <label class="block w-full mt-2 text-sm">
                  <select v-model.trim="form.opsCustomerInvoiceServices[index].currency" class="form-input" aria-placeholder="Select Currency" placeholder="Select Currency" @change="SetCurrencyData($event,index)">
                    <option selected value="" disabled>Select Currency</option>
                     <option v-for="currency in currencies" :value="currency" :key="currency">{{ currency }}</option>
                  </select>
                </label>
              </td> -->
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="text" step="0.001" v-model.trim="form.opsCustomerInvoiceServices[index].cost_unit" class="form-input" autocomplete="off" />
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" min="1" v-model.trim="form.opsCustomerInvoiceServices[index].quantity" class="form-input text-right" autocomplete="off" required />
                </label>
              </td>

              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" min="1" v-model.trim="form.opsCustomerInvoiceServices[index].rate" class="form-input text-right" autocomplete="off" required />
                </label>
              </td>
              <!-- <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsCustomerInvoiceServices[index].exchange_rate_usd" class="form-input text-right" autocomplete="off" :readonly="isUSDCurrency(form.opsCustomerInvoiceServices,index)"/>
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsCustomerInvoiceServices[index].exchange_rate_bdt" class="form-input text-right" autocomplete="off" :readonly="isBDTCurrency(form.opsCustomerInvoiceServices,index)"/>
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsCustomerInvoiceServices[index].amount_usd" class="form-input text-right" autocomplete="off" readonly/>
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" v-model.trim="form.opsCustomerInvoiceServices[index].amount_bdt" class="form-input text-right" autocomplete="off" readonly/>
                </label>
              </td> -->
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" step="0.001" min="1" v-model.trim="form.opsCustomerInvoiceServices[index].amount" class="form-input text-right" autocomplete="off" readonly/>
                </label>
              </td>
              <td class="px-1 py-1 text-center">
                <button  type="button" @click="removeService(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                  </svg>
                </button>
                <!-- <button v-else type="button" @click="addService()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg>
                </button> -->
              </td>
            </tr>
          </tbody>
          <!-- <tfoot>
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
          </tfoot> -->
        </table>     
      
    </div>
    </fieldset>
    <div id="sectors" class="mt-5">
      <table class="w-full whitespace-no-wrap" >
        <thead v-once>
              <tr class="w-full">
                <th>Subtotal</th>
                <th>Total Service Fee (Deduction)</th>
                <th>Discount (Deduction)</th>
                <th>Grand Total</th>
              </tr>
            </thead>
        <tbody>
              <tr>
                <td>
                  <span class="show-block !justify-end">
                    {{ numberFormat(props.form.sub_total_amount) }}
                  </span>
                  <input type="text" readonly :value="props.form.sub_total_amount" class="!hidden form-input bg-gray-100 text-right" autocomplete="off" />
                </td>
                <td>
                  <span class="show-block !justify-end">
                    {{ numberFormat(props.form.service_fee_deduction_amount) }}
                  </span>
                  <input type="text" readonly :value="props.form.service_fee_deduction_amount" class="!hidden form-input bg-gray-100 text-right" autocomplete="off" />
                </td>
                <td>
                  <input type="number" step="0.001" v-model="props.form.discounted_amount" class="form-input text-right" autocomplete="off" />
                </td>
                <td>
                  <span class="show-block !justify-end">
                    {{ numberFormat(props.form.grand_total) }}
                  </span>
                  <input type="text" readonly :value="props.form.grand_total" class="!hidden form-input bg-gray-100 text-right" autocomplete="off" />
                </td>
              </tr>
          </tbody>
      </table>
    </div>
    <div v-show="isModalOpen" class="fixed inset-0 z-30 flex items-end overflow-y-auto bg-black bg-opacity-50 sm:items-center sm:justify-center">
    <!-- Modal -->
    <form @submit.prevent="" style="position: absolute;top: 0; width: 100%">
      <div class="px-6 py-4 overflow-y-auto bg-white rounded-t-lg dark-disabled:bg-gray-800 sm:rounded-lg sm:m-4 w-10/12 !mx-auto" role="dialog" id="modal">
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
                <th><nobr>Tariff Name</nobr></th>
                <th><nobr>Loading Point</nobr></th>
                <th><nobr>Unloading Point</nobr></th>
                <th><nobr>Initial Survey Quantity</nobr></th>
                <th><nobr>Final Receipt Quantity</nobr></th>
                <th><nobr>Boat Note Quantity</nobr></th>
                <th><nobr>Final Survey Quantity</nobr></th>
              </tr>
            </thead>
            <tbody>

              <tr v-for="(detail, index) in details" :key="index">
                <td v-if="detail.tariff_name">
                  <router-link :to="{ name: 'ops.configurations.cargo-tariffs.show', params: { cargoTariffId: detail?.tariff_id ?? null } }" target="_blank" class="font-semibold text-blue-600">
                    <nobr>{{ detail.tariff_name }}</nobr>
                  </router-link>
                </td>
                <td>
                  {{ detail.loading_point }}
                </td>
                <td>
                  {{ detail.unloading_point }}
                </td>
                <td>
                  {{ detail.initial_survey_qty }}
                </td>
                <td>
                  {{ detail.final_received_qty }}
                </td>
                <td>
                  {{ detail.boat_note_qty }}
                </td>
                <td>
                  {{ detail.final_survey_qty }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <footer class="flex flex-col items-center justify-end px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark-disabled:bg-gray-800">
          <button type="button" @click="closeModel" style="color: #1b1e21"
                  class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 border border-gray-300 rounded-lg dark-disabled:text-gray-400 sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray">
            OK
          </button>
          <!-- <button type="button" @click="pushBunkerConsumption"
              class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            Submit
          </button> -->
        </footer>
      </div>
    </form>
    </div>
  <ErrorComponent :errors="errors"></ErrorComponent>
</template>
<script setup>
import { ref, watch, onMounted, watchPostEffect } from "vue";
import Error from "../Error.vue";
import useVoyage from "../../composables/operations/useVoyage";
import useVessel from "../../composables/operations/useVessel";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import useCustomer from "../../composables/operations/useCustomer";
import useBusinessInfo from '../../composables/useBusinessInfo';
import LoaderComponent from "../../components/utils/LoaderComponent.vue";
import ErrorComponent from '../../components/utils/ErrorComponent.vue';
import moment from 'moment';
import cloneDeep from 'lodash/cloneDeep';
import useHelper from "../../composables/useHelper";
import useContractAssign from "../../composables/operations/useContractAssign";
import useHeroIcon from "../../assets/heroIcon";

const editInitiated = ref(false);
const icons = useHeroIcon();

const dateFormat = ref(Store.getters.getVueDatePickerTextInputFormat.date);
const { currencies, getCurrencies } = useBusinessInfo();
const { getCustomersByBusinessUnit, customers } = useCustomer();
const { numberFormat } = useHelper();
// const { voyage, voyages, showVoyage, searchVoyages } = useVoyage();
const { vessel, showVessel } = useVessel();
const { voyages, getVoyageByCustomer } = useVoyage();
const { contractTariff, getContractTariffByVoyage } = useContractAssign();
const props = defineProps({
    form: { required: false,default: {},},
    errors: { type: [Object, Array], required: false },
    formType: { type: String, required: false },
    serviceObject: { type: Object, required: false },
    loading: { type: Boolean, required: false },
    otherObject: { type: Object, required: false },
    customerInvoiceVoyageObject: { type: Object, required: false },
});


const addService = () => {
  const service = cloneDeep(props.serviceObject);
  props.form.opsCustomerInvoiceServices.push(service);
};


const addOther = () => {
  const other = cloneDeep(props.otherObject);
  props.form.opsCustomerInvoiceOthers.push(other);
};


const removeService = (index) => {
  props.form.opsCustomerInvoiceServices.splice(index, 1);
};


const removeOther = (index) => {
  props.form.opsCustomerInvoiceOthers.splice(index, 1);
};

const addVoyage = () => {
  const voyage = cloneDeep(props.customerInvoiceVoyageObject);
  //add per ton charge in voyage object
  voyage.rate_per_mt = props.form.opsCustomerContract?.opsCustomerContractsFinancialTerms?.per_ton_charge;
  props.form.opsCustomerInvoiceVoyages.push(voyage);
};

const removeVoyage = (index) => {
  props.form.opsCustomerInvoiceVoyages.splice(index, 1);
};

const SetCurrencyData = (e,index) => {
  console.log(e.target.value,index);
}

const addasd = () => {
  const customerInvoiceVoyageObject = cloneDeep(props.customerInvoiceVoyageObject);
  props.form.opsCustomerInvoiceVoyages.push(customerInvoiceVoyageObject);
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


watch(() => props.form.opsCustomerInvoiceVoyages, (newLines) => {
  console.log(newLines);
  let total_amount = 0.0;
  newLines.forEach((line, index) => {
    // if (line.opsVoyage) {
    //   const selectedItem = voyages.value.find(voyage => voyage.id === line.opsVoyage.id);
    //   if (selectedItem) {
    //     if ( line.ops_voyage_id !== selectedItem.id
    //     ) {
    //       props.form.opsCustomerInvoiceVoyages[index].ops_voyage_id = selectedItem.id;
    //       props.form.opsCustomerInvoiceVoyages[index].cargo_quantity = selectedItem.cargo_quantity;
    //       props.form.opsCustomerInvoiceVoyages[index].total_amount = props.form.opsCustomerInvoiceVoyages[index].cargo_quantity * props.form.opsCustomerInvoiceVoyages[index].rate_per_mt;
    //     }
    //   }
    // }

    // total_amount += parseFloat(props.form.opsCustomerInvoiceVoyages[index].total_amount);
    total_amount += parseFloat((props.form.opsCustomerInvoiceVoyages[index]?.total_amount_bdt > 0) ? props.form.opsCustomerInvoiceVoyages[index]?.total_amount_bdt : 0);
  });
  props.form.total_amount = parseFloat(total_amount.toFixed(2));
  CalculateAll();
  // previousLines.value = cloneDeep(newLines);
}, { deep: true });

function CalculateAll() {
 
  props.form.sub_total_amount = (props.form.total_amount * 1) + (props.form.others_billable_amount * 1);

  props.form.grand_total = (props.form.sub_total_amount * 1) - (props.form.service_fee_deduction_amount * 1 )- (props.form.discounted_amount * 1);
}

//watch opsCustomerInvoiceServices
watch(() => props?.form?.opsCustomerInvoiceServices, (newVal, oldVal) => {
      let total_bdt = 0.0;
      let total_usd = 0.0;
      let total_amount = 0.0;
      newVal?.forEach((line, index) => {
        // const { amount_usd, amount_bdt } = calculateInCurrency(line, index);
        // total_bdt += amount_bdt;
        // total_usd += amount_usd;
        line.amount = parseFloat((line?.rate * line?.quantity).toFixed(2));
        total_amount += parseFloat((line?.rate * line?.quantity).toFixed(2));

      });
  // props.form.service_fee_deduction_amount_usd = total_usd;
  // props.form.service_fee_deduction_amount = total_bdt ;
  props.form.service_fee_deduction_amount = total_amount ;
  CalculateAll();
}, { deep: true }); 

watch(() => props?.form?.opsCustomerInvoiceOthers, (newVal, oldVal) => {
      let total_bdt = 0.0;
  let total_usd = 0.0;
  let total_amount = 0.0;
      newVal?.forEach((line, index) => {
        // const { amount_usd, amount_bdt } = calculateInCurrency(line, index);
        // total_bdt += amount_bdt;
        // total_usd += amount_usd;
        line.amount = parseFloat((line?.rate * line?.quantity).toFixed(2));
        total_amount += parseFloat((line?.rate * line?.quantity).toFixed(2));
      });
  // props.form.others_billable_amount_usd = total_usd;
  // props.form.others_billable_amount = total_bdt;
  props.form.others_billable_amount = total_amount;
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

// function fetchVoyages(searchParam, loading) {
//   loading(true)
//   searchVoyages(searchParam, props.form.business_unit, loading)
// }



// watch(() => props.form.opsCustomerProfile, (value) => {
//     props.form.ops_customer_profile_id = value?.id;
// })

function profileChanged() {
  let value = props.form.opsCustomer ?? null;
  props.form.ops_customer_id = value?.id ?? null;

  voyages.value = [];
  props.form.opsCustomerInvoiceVoyages = [];
  addVoyage();


  if(props.form.business_unit && props.form.ops_customer_id)
    getVoyageByCustomer(props.form.business_unit, props.form.ops_customer_id);
}

function opsCustomerInvoiceVoyageChanged(opsCustomerInvoiceVoyage) {
  opsCustomerInvoiceVoyage.ops_voyage_id = opsCustomerInvoiceVoyage?.opsVoyage?.id;
  // console.log(opsCustomerInvoiceVoyage.opsVoyage);
  opsCustomerInvoiceVoyage.contractTariff = {};
  
  if (opsCustomerInvoiceVoyage.ops_voyage_id) {
    getContractTariffByVoyage(opsCustomerInvoiceVoyage.ops_voyage_id).then(() => {
      opsCustomerInvoiceVoyage.contractTariff = cloneDeep(contractTariff);
      opsCustomerInvoiceVoyage.ops_vessel_id = contractTariff.value?.opsVessel?.id;
      opsCustomerInvoiceVoyage.opsVessel = contractTariff.value?.opsVessel;
      opsCustomerInvoiceVoyage.ops_cargo_type_id = contractTariff.value?.opsCargoType?.id;
      opsCustomerInvoiceVoyage.opsCargoType = contractTariff.value?.opsCargoType;
      opsCustomerInvoiceVoyage.ops_voyage_id = opsCustomerInvoiceVoyage?.ops_voyage_id;
      opsCustomerInvoiceVoyage.total_amount_bdt = contractTariff.value?.total_amount;
    })
    
    // contractTariff.opsVessel.id
  }
  else {
    opsCustomerInvoiceVoyage.contractTariff = {};
    opsCustomerInvoiceVoyage.ops_vessel_id = '';
    opsCustomerInvoiceVoyage.opsVessel = '';
    opsCustomerInvoiceVoyage.ops_cargo_type_id = '';
    opsCustomerInvoiceVoyage.opsCargoType = '';
    opsCustomerInvoiceVoyage.ops_voyage_id = '';
    opsCustomerInvoiceVoyage.total_amount_bdt = '';
  }
}



watch(() => props.form.ops_customer_profile_id, (value) => {
    if (editInitiated.value) {
      props.form.ops_customer_contract_id = '';
      props.form.opsCustomerContract = null;
  }
  //  value ? getCustomerContractsByCharterOwner(value) : customerContracts.value = [];
})


watch(() => props.form.opsCustomerContract, (value) => {
  if(value?.contract_type == 'Voyage Wise') {
    getContractWiseVoyage(value.id);
  } else {  
    voyages.value = [];
    }
    editInitiated.value = true;
})


onMounted(() => {
  // getAllCustomerProfiles();
  getCurrencies();
  getCustomersByBusinessUnit(props.form.business_unit)
})


const isModalOpen = ref(0);
const details = ref([{type: ''}]);
const currentIndex = ref(null);

function showModal(index) {
  isModalOpen.value = 1
  currentIndex.value = index
  if(props.form.opsCustomerInvoiceVoyages[index]?.contractTariff?.opsVoyageSectors) {
    details.value = cloneDeep(props.form.opsCustomerInvoiceVoyages[index]?.contractTariff?.opsVoyageSectors)
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

#dataTable tr, th, td {
  @apply text-[12px]
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
</style>