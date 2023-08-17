<script setup>

import { onMounted, watch, ref } from 'vue';
import Error from "../Error.vue";
import {useRoute} from "vue-router";
import usePort from "../../composables/usePort";
import useVoyageExpense from "../../composables/finance/useVoyageExpense";
import useVoyageBudget from "../../composables/finance/useVoyageBudget";
import useService from "../../composables/commercial/useService"
import moment from 'moment';

const { service, uniqueServicePorts, getServiceUniquePorts } = useService();

const route = useRoute();

const voyagePairId = route.params.pairId
const { ports, getPortsByNameOrCode } = usePort();
const { budget, notification, errors, budgets, globalHeads, showBudget, budgetPresets, getBudgetPreset, budgetDetails, searchBudget, assignBudget } = useVoyageBudget();

const { categories, getExpenseCategories, presets, entryType, getPortPresets } = useVoyageExpense();
const entryGroup = ref([]);
const isAssignAble = ref(true)
const props = defineProps({
  form: {
    required: false,
    default: {},
    entryGroup: {},
  },
  heads: { type: [Object, Array], required: true },
  errors: { type: [Object, Array], required: false },
  budgetId: { required: true},
  vesselId: { required: true},
});

function fetchBudgets(search) {
      if(search != null) {
        searchBudget(search)
      }
}
const budgetId = ref();

function getPortPresetsData()
{
  getPortPresets(props.form.port, props.form.voyage_id);
}

function calculateBudget(type, headIndex, subIndex) {

  if(type == 'GlobalHead') {
    let output, item = null;

    if(subIndex != undefined) {

      item = props.form.budgets.globalHeads[headIndex].subheads[subIndex];
      output = calculateAmount(item.currency, item.rate, item.unit);

    } else {

      item = props.form.budgets.globalHeads[headIndex];
      output = calculateAmount(item.currency, item.rate, item.unit);

    }
    item.amount = parseFloat(output.amount).toFixed(2)
    item.usd_amount = parseFloat(output.usd_amount).toFixed(2)
    // console.log("Global", output)

  } else {
    let output, item = null;

    if(subIndex != undefined) {

      item = props.form.budgets.entryGroup[type][headIndex].subheads[subIndex];
      output = calculateAmount(item.currency, item.rate, item.unit);

    } else {

      item = props.form.budgets.entryGroup[type][headIndex];
      output = calculateAmount(item.currency, item.rate, item.unit);

    }

    item.amount = parseFloat(output.amount).toFixed(2)
    item.usd_amount = parseFloat(output.usd_amount).toFixed(2)
    // console.log(type, item)
  }
}

function calculateAmount(currency, rate, unit) {

  let amount, usd_amount = 0;
  if(!rate) {
    rate = 1;
  }

  if(!unit) {
    unit = 1
  }

  if(currency == 'USD') {
    usd_amount = parseFloat(rate * unit).toFixed(2);
    amount = parseFloat(usd_amount * props.form.exchange_rate).toFixed(2);
  } else {
    amount = parseFloat(rate * unit).toFixed(2);
    usd_amount = parseFloat(amount / props.form.exchange_rate).toFixed(2);
  }

  return {
    amount: amount,
    usd_amount: usd_amount
  }

}

watch(() => props.form.exchange_rate, (value) => {
  if (value) {
    
    // Working on Global Heads

    let globalHeads = props.form.budgets.globalHeads;

    for (const headIndex in globalHeads) {
      
      if(globalHeads[headIndex].subheads.length != 0) {

        let subheads = globalHeads[headIndex].subheads;
        for (const subIndex in subheads) {
          
          if(subheads[subIndex].rate != undefined) {
            calculateBudget('GlobalHead', headIndex, subIndex)
          }
        }

      } else {

        if(globalHeads[headIndex].rate != undefined) {
          calculateBudget('GlobalHead', headIndex)
        }
      }
    }

    // Working on Port Wise Costs
    let entryGroup = props.form.budgets.entryGroup;

    for (const port in entryGroup) {
      let items = entryGroup[port];

      for(const headIndex in items) {

        if(items[headIndex].subheads.length != 0) {
          let subheads = items[headIndex].subheads;

          for (const subIndex in subheads) {
            
            if(subheads[subIndex].rate != undefined) {
              calculateBudget(port, headIndex, subIndex)
            }
          }
        } else {
          if(items[headIndex].rate != undefined) {
            calculateBudget(port, headIndex)
          }
        }
      }
    }
  }
}, {deep: true});


watch(() => uniqueServicePorts, (value) => {
  if (value) {
    getBudgetPreset(value?.value, budgetId?.value)
  }
}, {deep: true});

// watchEffect(() => {
//   if(props.budgetId){

//   }
// })
watch(() => props.budgetId, (bid) => {
  budgetId.value = bid;
});


watch(() => globalHeads, (value) => {
  if(value) {
    props.form.budgets.globalHeads = globalHeads?.value
    props.form.exchange_rate = budgetDetails?.value?.exchange_rate

  }
}, {deep: true})

watch(() => budget, (value) => {
  

  let budgetVesselId = value?.value?.vessel?.id;
  let pairVesselId = props.vesselId;

  if(budgetVesselId != pairVesselId) {
    // budget.value = null;
    isAssignAble.value = false;
    alert('You cannot set this budget for this vessel.')
  } else {
    isAssignAble.value = true;

    if(value) {
      getServiceUniquePorts(value?.value?.service_id)
    }

    errors.type = null;
  }

}, {deep : true});

watch(() => budgetPresets, (value) => {
  entryGroup.value = value?.value
  props.form.budgets.entryGroup = entryGroup.value

}, { deep: true });

watch(() => budgetId, (value) => {
  if(value?.value != null) {
    showBudget(value?.value);
    props.form.budgets.budgetId = value?.value
  } else {
    budgetPresets.value = null;
  }
}, { deep: true });

  const openTab = ref(0);
const toggleTabs = (tabNumber) => {
  openTab.value = tabNumber;
}

function assignVoyageBudget() {
    
    assignBudget(voyagePairId, budgetId.value)
}
</script>
<template>
    <div class="flex items-center">
      
        <div class="w-5/12 my-3">
                    <label class="block w-full mt-3 text-sm">
                        <span class="text-gray-700 dark:text-gray-300 font-semibold">Budget <span class="text-red-500">*</span></span>
                    </label>
                    <v-select :options="budgets" placeholder="--Choose an option--" @search="fetchBudgets" v-model="budgetId" label="title" :reduce="budgets => budgets.id"></v-select>
                </div>

                <div class="w-5/12 my-3 ml-2">
                    <button @click="assignVoyageBudget()" type="button" class="mt-8 flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        Assign Budget
                    </button>
                </div>
    </div>
            <ul class="text-red-400" v-if="errors?.type == 'vesselMismatch' && isAssignAble==false">
                {{ errors?.message }} 
            </ul>

  <!-- Budget entries -->
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300">Budget Details </legend>

      <div class="w-full mb-2">
          <ul class="flex flex-wrap mt-2 -mb-px">
          <li class="mr-2 hover:cursor-pointer" v-for="(entryPort, index, key) in entryGroup" :key="key" >
            <span class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(key)" v-bind:class="{'text-purple-600 bg-white': openTab !== key, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active': openTab === key}">
              {{ index }}
            </span>
          </li>
          <li class="mr-2 hover:cursor-pointer" v-for="(globalHead) in props.form.budgets.globalHeads" :key="globalHead.id" >
            <span class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(globalHead.id)" v-bind:class="{'text-purple-600 bg-white': openTab !== globalHead.id, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active': openTab === globalHead.id}">
              {{ globalHead.name }}
            </span>
          </li>
        </ul>
      </div>

      <template class="w-full whitespace-no-wrap" id="table" v-for="(entryPort, portName, key) in entryGroup" :key="key">
        <table v-on:click="toggleTabs(key)" v-bind:class="{'hidden': openTab !== key, 'block': openTab === key}">
          <thead>
              <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
              <th class="px-4 py-3 align-bottom">Head</th>
              <th class="px-4 py-3 align-bottom">CUR</th>
              <th class="px-4 py-3 align-bottom">Rate</th>
              <th class="px-4 py-3 align-bottom">Units</th>
              <th class="px-4 py-3 align-bottom">Amount</th>
              <th class="px-4 py-3 align-bottom">USD Amount</th>
              <th class="px-4 py-3 align-bottom">Note</th>
              </tr>
          </thead>

          <tbody v-for="(head, index) in entryPort" :key="index">
            <tr class="text-gray-700 dark:text-gray-400">
                  <td class="px-1 py-1" style="width: 20%" colspan="9">
                    <input type="text" readonly :value="head.name" class="block w-full mt-1 text-sm rounded bg-green-200 dark:text-gray-300 dark:border-gray-600 dark:bg-green-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                  </td>
            </tr>

            <template v-if="head?.subheads?.length > 0">
              <tr v-for="(subhead, subIndex) in head?.subheads" :key="subhead.id">
                <td class="px-1 py-1" style="width: 20%">
                  <input type="text" readonly :value="subhead?.name" name="port_code" class="block w-full mt-1 text-sm rounded bg-gray-200 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                </td>
                
                <td class="px-1 py-1">
                  <table class="w-full">
                    <tr>
                      <td class="w-full" style="border: 0px">
                        
                        <select v-model="subhead.currency" @change="calculateBudget(portName , index, subIndex)" class="block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" name="" id="">
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
                      <td class="w-full" style="border: 0px">
                        <input type="text" v-model="subhead.rate" @keyup="calculateBudget(portName , index, subIndex)" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="px-1 py-1">
                  <table class="w-full">
                    <tr>
                      <td class="w-full" style="border: 0px">
                        <input type="text" v-model="subhead.unit" @keyup="calculateBudget(portName , index, subIndex)" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="px-1 py-1">
                  <table class="w-full">
                    <tr>
                      <td class="w-full" style="border: 0px">
                        <input type="text" v-model="subhead.amount" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="px-1 py-1">
                  <table class="w-full">
                    <tr>
                      <td class="w-full" style="border: 0px">
                        <input type="text" v-model="subhead.usd_amount" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="px-1 py-1">
                  <table class="w-full">
                    <tr>
                      <td class="w-full" style="border: 0px">
                        <input type="text" v-model="subhead.remark" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </template>
            <template v-else>
              <tr class="text-gray-700 dark:text-gray-400">
                    <td class="px-1 py-1" style="width: 20%">
                  <input type="text" readonly :value="head?.name" name="port_code" class="block w-full mt-1 text-sm rounded bg-gray-200 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                </td>
                <td class="px-1 py-1">
                  <table class="w-full">
                    <tr>
                      <td class="w-full" style="border: 0px">
                        
                        <select v-model="head.currency" @change="calculateBudget(portName , index)" class="block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" name="" id="">
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
                      <td class="w-full" style="border: 0px">
                        <input type="text" v-model="head.rate" @keyup="calculateBudget(portName , index)" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="px-1 py-1">
                  <table class="w-full">
                    <tr>
                      <td class="w-full" style="border: 0px">
                        <input type="text" v-model="head.unit" @keyup="calculateBudget(portName , index)" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="px-1 py-1">
                  <table class="w-full">
                    <tr>
                      <td class="w-full" style="border: 0px">
                        <input type="text" v-model="head.amount" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="px-1 py-1">
                  <table class="w-full">
                    <tr>
                      <td class="w-full" style="border: 0px">
                        <input type="text" v-model="head.usd_amount" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="px-1 py-1">
                  <table class="w-full">
                    <tr>
                      <td class="w-full" style="border: 0px">
                        <input type="text" v-model="head.remark" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </template>
            
          </tbody>
        </table>
      </template>

      <template class="w-full whitespace-no-wrap" id="table" v-for="(globalHead, index) in props.form.budgets.globalHeads" :key="globalHead.id">
        <table v-on:click="toggleTabs(globalHead.id)" v-bind:class="{'hidden': openTab !== globalHead.id, 'block': openTab === globalHead.id}">
          <thead>
              <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
              <th class="px-4 py-3 align-bottom">Head</th>
              <th class="px-4 py-3 align-bottom">CUR</th>
              <th class="px-4 py-3 align-bottom">Rate</th>
              <th class="px-4 py-3 align-bottom">Units</th>
              <th class="px-4 py-3 align-bottom">Amount</th>
              <th class="px-4 py-3 align-bottom">USD Amount</th>
              <th class="px-4 py-3 align-bottom">Note</th>
              </tr>
          </thead>

          <template v-if="globalHead.subheads.length > 0">
            <tbody v-for="(subhead, subIndex) in globalHead.subheads" :key="subhead.id">
              <tr class="text-gray-700 dark:text-gray-400">
                    <td class="px-1 py-1" style="width: 20%">
                  <input type="text" readonly :value="subhead?.name" name="port_code" class="block w-full mt-1 text-sm rounded bg-gray-200 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                </td>
                <td class="px-1 py-1">
                  <table class="w-full">
                    <tr>
                      <td class="w-full" style="border: 0px">
                        <select v-model="subhead.currency" @change="calculateBudget('GlobalHead' , index, subIndex)" class="block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" name="" id="">
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
                      <td class="w-full" style="border: 0px">
                        <input type="text" v-model="subhead.rate" @keyup="calculateBudget('GlobalHead' , index, subIndex)" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="px-1 py-1">
                  <table class="w-full">
                    <tr>
                      <td class="w-full" style="border: 0px">
                        <input type="text" v-model="subhead.unit" @keyup="calculateBudget('GlobalHead' , index, subIndex)" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="px-1 py-1">
                  <table class="w-full">
                    <tr>
                      <td class="w-full" style="border: 0px">
                        <input type="text" v-model="subhead.amount" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="px-1 py-1">
                  <table class="w-full">
                    <tr>
                      <td class="w-full" style="border: 0px">
                        <input type="text" v-model="subhead.usd_amount" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="px-1 py-1">
                  <table class="w-full">
                    <tr>
                      <td class="w-full" style="border: 0px">
                        <input type="text" v-model="subhead.remark" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </tbody>
          </template>
          <template v-else>
            <tr class="text-gray-700 dark:text-gray-400">
                    <td class="px-1 py-1" style="width: 20%">
                  <input type="text" readonly :value="globalHead?.name" name="port_code" class="block w-full mt-1 text-sm rounded bg-gray-200 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                </td>
                <td class="px-1 py-1">
                  <table class="w-full">
                    <tr>
                      <td class="w-full" style="border: 0px">
                        
                        <select v-model="globalHead.currency" @change="calculateBudget('GlobalHead', index)" class="block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" name="" id="">
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
                      <td class="w-full" style="border: 0px">
                        <input type="text" v-model="globalHead.rate" @keyup="calculateBudget('GlobalHead', index)" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="px-1 py-1">
                  <table class="w-full">
                    <tr>
                      <td class="w-full" style="border: 0px">
                        <input type="text" v-model="globalHead.unit" @keyup="calculateBudget('GlobalHead', index)" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="px-1 py-1">
                  <table class="w-full">
                    <tr>
                      <td class="w-full" style="border: 0px">
                        <input type="text" v-model="globalHead.amount" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="px-1 py-1">
                  <table class="w-full">
                    <tr>
                      <td class="w-full" style="border: 0px">
                        <input type="text" v-model="globalHead.usd_amount" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="px-1 py-1">
                  <table class="w-full">
                    <tr>
                      <td class="w-full" style="border: 0px">
                        <input type="text" v-model="globalHead.remark" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
          </template>

        </table>
      </template>

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

.info-table tr td:nth-child(2) {
  @apply bg-blue-200
}

.info-table tr td:nth-child(4) {
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