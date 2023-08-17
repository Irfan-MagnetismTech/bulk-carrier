<script setup>

    import { onMounted, watch } from 'vue';
    import Error from "../Error.vue";
    import {useRoute} from "vue-router";
    import usePort from "../../composables/usePort";
    import useVoyageExpense from "../../composables/finance/useVoyageExpense";
    import useVoyageBudget from "../../composables/finance/useVoyageBudget";
    import useService from "../../composables/commercial/useService"
    import moment from 'moment';
    
    const { service, uniqueServicePorts, getServiceUniquePorts } = useService();
    
    const route = useRoute();
    const budgetId = route.params.budgetId;
    const { ports, getPortsByNameOrCode } = usePort();
    const { budget, showBudget, budgetPresets, getBudgetPreset } = useVoyageBudget();
    const { categories, getExpenseCategories, presets, entryType, getPortPresets } = useVoyageExpense();
    
    const props = defineProps({
      form: {
        required: false,
        default: {},
        entryGroup: {}
      },
      heads: { type: [Object, Array], required: true },
      errors: { type: [Object, Array], required: false }
    });
    
    function fetchOptions(search, loading) {
      getPortsByNameOrCode(search);
    }
    
    function fetchCategories(search, loading) {
      getExpenseCategories(props.form.port, search);
    }
    
    function getPortPresetsData()
    {
      getPortPresets(props.form.port, props.form.voyage_id);
    }
    
    
    watch(() => props.form.port, (value) => {
      if (value) {
        props.form.port = value.code;
      }
    }, {deep: true});
    
    
    watch(() => uniqueServicePorts, (value) => {
      if (value) {
        // console.log(value?.value)
        getBudgetPreset(value?.value)
      }
    }, {deep: true});
    
    watch(() => budget, (value) => {
      if(value) {
        getServiceUniquePorts(value?.value?.service_id)
      }
    }, {deep : true});
    
    watch(() => budgetPresets, (value) => {
      props.form.entryGroup = value?.value
      alert('dd')
    }, { deep: true });
    
    onMounted(() => {
      showBudget(budgetId);
      });
    
    </script>
    <template>
      <!-- Basic information -->
      <div class="w-full mb-3">
        <strong>Title: </strong>{{ budget?.title }} <strong>Vessel: </strong> {{ budget?.vessel?.name }} 
        <strong>Active:</strong> {{ (budget?.start_date) ? moment(budget?.start_date).format('DD/MM/YYYY') : null }} to {{ (budget?.end_date) ? moment(budget?.end_date).format('DD/MM/YYYY') : null }}
        <br />
        <strong>{{ budget?.service?.name }}</strong> 
      </div>
    
      <div class="w-full">
        <table id="table">
          <tbody class="uppercase">
            <tr>
              <td>Vessel Name</td>
              <td>{{ budget?.vessel?.name }}</td>
              <td>Gross Registered Tonnage (MT)</td>
              <td>{{ budget?.vessel?.grt }}</td>
            </tr>
            <tr>
              <td>IMO/ Call Sign</td>
              <td>{{ budget?.vessel?.imo_number }}</td>
              <td>Net Registered Tonnage (MT)</td>
              <td>{{ budget?.vessel?.nrt }}</td>
            </tr>
            <tr>
              <td>Owner</td>
              <td></td>
              <td>LOA/ BREDTH/DEPTH</td>
              <td></td>
            </tr>
            <tr>
              <td>CHARTERER</td>
              <td></td>
              <td>Cargo Details</td>
              <td></td>
            </tr>
            <tr>
              <td>Operator</td>
              <td></td>
              <td>EPDA Fir The Port Of</td>
              <td>CGP</td>
            </tr>
            <tr>
              <td>Local Agent at CGP</td>
              <td></td>
              <td>Common Ex.Rate (BDT:1USD)</td>
              <td>
                <input type="number" step="any" class="border-none focus:border-none bg-green-200" placeholder="Write Exchange Rate" />
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    
    
      <!-- Budget entries -->
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
        <legend class="px-2 text-gray-700 dark:text-gray-300">Budget Particulars </legend>
        <table class="w-full whitespace-no-wrap" id="table">
          <thead>
            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
              <th class="px-4 py-3 align-bottom">Head</th>
              <th class="px-4 py-3 align-bottom">Per</th>
              <th class="px-4 py-3 align-bottom">CUR</th>
              <th class="px-4 py-3 align-bottom">Rate</th>
              <th class="px-4 py-3 align-bottom">Units</th>
              <th class="px-4 py-3 align-bottom">Amount</th>
              <th class="px-4 py-3 align-bottom">Ex. RT</th>
              <th class="px-4 py-3 align-bottom">USD Amount</th>
              <th class="px-4 py-3 align-bottom">Note</th>
              <th class="px-4 py-3 text-center align-bottom">Action</th>
            </tr>
          </thead>
    
          <tbody v-if="form?.entryGroup?.length >= 1" v-for="(entryPort, index) in form?.entryGroup" :key="index">
            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800" v-if="entryPort?.length >= 1" v-for="(entry, index) in entryPort" :key="index">
              <tr class="text-gray-700 dark:text-gray-400">
                <td class="px-1 py-1" style="width: 20%" colspan="7">
                  <input type="text" readonly v-model="form.entries[index].voyage_expenditure_head.name" name="port_code" class="block w-full mt-1 text-sm rounded bg-green-200 dark:text-gray-300 dark:border-gray-600 dark:bg-green-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                </td>
              </tr>
              <tr v-if="form.entries[index]?.voyage_expenditure_head?.sub_head?.length > 0" v-for="(subhead, headIndex) in form.entries[index].voyage_expenditure_head.sub_head">
                <td class="px-1 py-1" style="width: 20%">
                  <input type="text" readonly :value="(form.entries[index]?.ishead == 0) ? ((entryType == 'entries') ? subhead?.sub_head?.name : subhead?.name) : ((form.entries[index]?.ishead == 1 && entryType == 'entries') ? form.entries[index].voyage_expenditure_head?.name : subhead?.name)" name="port_code" class="block w-full mt-1 text-sm rounded bg-gray-200 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                </td>
                <td class="px-1 py-1">
                  <table class="w-full">
                    <tr>
                      <td class="w-full" style="border: 0px">
                        <input type="date" v-model="subhead.invoice_date" placeholder="Ex: BES" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="px-1 py-1">
                  <table class="w-full">
                    <tr>
                      <td class="" style="border: 0px">
                        <input type="text" v-model="subhead.invoice_no" placeholder="Invoice no" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="px-1 py-1">
                  <table class="w-full">
                    <tr>
                      <td class="" width="10%" style="border: 0px">
                        <select v-model="subhead.currency" class="block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" name="" id="">
                          <option value="USD" selected>USD</option>
                          <option value="BDT">BDT</option>
                        </select>
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="px-1 py-1">
                  <table class="w-full">
                    <tr>
                      <td class="" style="border: 0px">
                        <input type="number" step=".00000001" v-model="subhead.amount" placeholder="Amount" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="px-1 py-1">
                  <table class="w-full">
                    <tr>
                      <td class="" style="border: 0px">
                        <input type="text" v-model="subhead.remarks" placeholder="Remarks" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="px-1 py-3 text-center grid grid-cols-2 gap-x-2 h-auto border-none w-20">
                  
                  <button type="button" @click="addExpenseRow(index, headIndex)" class="px-1 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                  </button>
                  <button type="button" @click="removeExpenseRow(index, headIndex)" class="px-1 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg>
                  </button>
                </td>
              </tr>
    
              <tr v-else-if="form.entries[index]?.voyage_expenditure_head?.sub_head?.length == 0 && form.entries[index]?.hasChild == false">
                <td class="px-1 py-1" style="width: 20%">
                  <input type="text" readonly :value="form.entries[index].voyage_expenditure_head?.name" name="port_code" class="block w-full mt-1 text-sm rounded bg-gray-200 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                </td>
                <td class="px-1 py-1">
                  <table class="w-full">
                    <tr>
                      <td class="w-full" style="border: 0px">
                        <input type="date" v-model="form.entries[index].invoice_date" placeholder="Ex: BES" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="px-1 py-1">
                  <table class="w-full">
                    <tr>
                      <td class="" style="border: 0px">
                        <input type="text" v-model="form.entries[index].invoice_no" placeholder="Invoice no" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="px-1 py-1">
                  <table class="w-full">
                    <tr>
                      <td class="" width="10%" style="border: 0px">
                        <select v-model="form.entries[index].currency" class="block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" name="" id="">
                          <option value="USD" selected>USD</option>
                          <option value="BDT">BDT</option>
                        </select>
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="px-1 py-1">
                  <table class="w-full">
                    <tr>
                      <td class="" style="border: 0px">
                        <input type="number" step=".01" v-model="form.entries[index].amount" placeholder="Amount" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="px-1 py-1">
                  <table class="w-full">
                    <tr>
                      <td class="" style="border: 0px">
                        <input type="text" v-model="form.entries[index].remarks" placeholder="Remarks" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="px-1 py-3 text-center grid grid-cols-2 gap-x-2 h-auto border-none w-20">
                  
                  <button type="button" @click="addExpenseRow(index, 'head')" class="px-1 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                  </button>
                  <button type="button" @click="removeExpenseRow(index, 'head')" class="px-1 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg>
                  </button>
                </td>
              </tr>
            </tbody>
          </tbody>
        </table>
      </fieldset>
      <!-- Previous Method -->
      <!-- <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
        <legend class="px-2 text-gray-700 dark:text-gray-300">Expense Head <span class="text-red-500">*</span></legend>
        <table class="w-full whitespace-no-wrap" id="table">
          <thead>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th class="px-4 py-3 align-bottom">Head</th>
            <th class="px-4 py-3 align-bottom">Invoice Date</th>
            <th class="px-4 py-3 align-bottom">Invoice No</th>
            <th class="px-4 py-3 align-bottom">Currency</th>
            <th class="px-4 py-3 align-bottom">Amount</th>
            <th class="px-4 py-3 align-bottom">Remarks</th>
            <th class="px-4 py-3 text-center align-bottom">Action</th>
          </tr>
          </thead>
    
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr class="text-gray-700 dark:text-gray-400" v-for="(entry, index) in form.entries" :key="index">
            <td class="px-1 py-1" style="width: 20%">
              <v-select v-model="form.entries[index].voyage_expenditure_sub_head" :id="'name' + index" @search="fetchCategories" value="id" :options="categories" label="category_head" placeholder="Enter Category" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
              <input type="hidden" v-model="form.port" name="port_code" :id="'port_code' + index" />
            </td>
            <td class="px-1 py-1">
              <table class="w-full">
                <tr>
                  <td class="w-full" style="border: 0px">
                    <input type="date" v-model="form.entries[index].invoice_date" placeholder="Ex: BES" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                  </td>
                </tr>
              </table>
            </td>
            <td class="px-1 py-1">
              <table class="w-full">
                <tr>
                  <td class="" style="border: 0px">
                    <input type="text" v-model="form.entries[index].invoice_no" placeholder="Invoice no" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                  </td>
                </tr>
              </table>
            </td>
            <td class="px-1 py-1">
              <table class="w-full">
                <tr>
                  <td class="" width="10%" style="border: 0px">
                    <select v-model="form.entries[index].currency" class="block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" name="" id="">
                      <option value="USD" selected>USD</option>
                      <option value="BDT">BDT</option>
                    </select>
                  </td>
                </tr>
              </table>
            </td>
            <td class="px-1 py-1">
              <table class="w-full">
                <tr>
                  <td class="" style="border: 0px">
                    <input type="number" step=".01" v-model="form.entries[index].amount" placeholder="Amount" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                  </td>
                </tr>
              </table>
            </td>
            <td class="px-1 py-1">
              <table class="w-full">
                <tr>
                  <td class="" style="border: 0px">
                    <input type="text" v-model="form.entries[index].remarks" placeholder="Remarks" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                  </td>
                </tr>
              </table>
            </td>
            <td class="px-1 py-1 text-center">
              <button v-if="index!=0" type="button" @click="removeExpenseRow(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg>
              </button>
              <button v-else type="button" @click="addExpenseRow()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
              </button>
            </td>
          </tr>
          </tbody>
        </table>
      </fieldset> -->
    </template>
    <style lang="postcss" scoped>
    #table, #table th, #table td{
      @apply border border-collapse border-gray-400 text-center text-gray-700 px-1
    }
    
    #table tr td:nth-child(2) {
      @apply bg-blue-200
    }
    
    #table tr td:nth-child(4) {
      @apply bg-green-200
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
    .form-input {
      @apply block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
    }
    
    table th{
      text-transform: capitalize;
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