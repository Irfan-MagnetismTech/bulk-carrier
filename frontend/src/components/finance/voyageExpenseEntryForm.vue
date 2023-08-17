<script setup>

import { onMounted, watch } from 'vue';
import Error from "../Error.vue";
import {useRoute} from "vue-router";
import usePort from "../../composables/usePort";
import useVoyageExpense from "../../composables/finance/useVoyageExpense";
import useVoyagePairing from "../../composables/finance/useVoyagePairing";

const route = useRoute();
const pairId = route.params.pairId;
const { ports, getPortsByNameOrCode } = usePort();
const { pairs, showVoyagePair } = useVoyagePairing();
const { categories, getExpenseCategories, presets, entryType, getPortPresets } = useVoyageExpense();

const props = defineProps({
  form: {
    required: false,
    default: {}
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

function addExpenseRow(index, headIndex) {

  if(headIndex == 'head')
  {
    let duplicateExpense = JSON.stringify(props.form.entries[index]);
    duplicateExpense = JSON.parse(duplicateExpense);
    duplicateExpense.deletable = true;

    // console.log(duplicateExpense);
    props.form.entries.splice(index+1, 0, duplicateExpense);

  } else {

    let duplicateExpense = JSON.stringify(props.form.entries[index].voyage_expenditure_head.sub_head[headIndex]);
    duplicateExpense = JSON.parse(duplicateExpense);
    duplicateExpense.deletable = true;
    props.form.entries[index].voyage_expenditure_head.sub_head.splice(headIndex+1, 0, duplicateExpense);
  }
}

function removeExpenseRow(index, headIndex){

  // console.log(index, headIndex)
  if(headIndex == 'head')
  {
    let isDeleteable = props.form.entries[index].deletable;
    if(isDeleteable) {
      props.form.entries.splice(index, 1);
    }
  } else {

    let itemId = '';

    if(entryType.value == 'entries')
    {
      // alert('You are inside entries');
      itemId = props.form.entries[index].voyage_expenditure_head.sub_head[headIndex].particular_id;
    } else {
      itemId = props.form.entries[index].voyage_expenditure_head.sub_head[headIndex].id;
    }

    let isExist =  removeableSiblings( props.form.entries[index]?.voyage_expenditure_head?.sub_head, itemId);

    if(isExist > 1) {
      props.form.entries[index].voyage_expenditure_head.sub_head.splice(headIndex, 1);
    }
  }
}

function removeableSiblings(siblings, ownID)
{
    let siblingsArray = siblings.map(function (item) {
      return (entryType.value == 'entries') ? item.particular_id : item.id;
    })

    console.log(siblingsArray)
    // console.log(ownID)
    let counter = 0;

  siblingsArray.forEach(function(item) {
    console.log("item "+item);
    if(item == ownID)
    {
      counter++;
    }

  });
  console.log("own:"+ownID+" count:"+counter);
    return counter;
}

watch(() => props.form.port_name, (value) => {
  if (value) {
    props.form.port = value.code;
  }
}, {deep: true});


watch(() => presets, (value) => {
  if (value) {
    props.form.entries = value.value;
  }
}, {deep: true});

watch(() => pairs, (value) => {
  if (value) {
    props.form.pairs = value.value;
    props.form.voyage_id = value.value.id;
  }
}, {deep: true});

onMounted(() => {
      showVoyagePair(pairId);
  });

</script>
<template>
  <!-- Basic information -->
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-3 text-sm md:w-2/6">
      <span class="text-gray-700 dark:text-gray-300">Voyage <span class="text-red-500">*</span></span>
      <input type="text" readonly  :value="form?.pairs?.combined_name" placeholder="Voyage no" class="block w-full mt-1 text-sm rounded bg-gray-200 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
      <input type="hidden" v-model="form.voyage_id">
      <Error v-if="errors?.code" :errors="errors.code" />
    </label>
    <label class="block w-full mt-3 text-sm md:w-3/6">
      <span class="text-gray-700 dark:text-gray-300">Port <span class="text-red-500">*</span></span>
      <v-select v-model="form.port_name" :id="'port_name' + index" @search="fetchOptions" value="id" :options="ports" label="code_name" placeholder="Enter Port Code or Name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
      <input type="hidden" v-model="form.port" name="port_code" :id="'port_code' + index" />
      <Error v-if="errors?.name" :errors="errors.name" />
    </label>
    <label class="block w-full mt-3 text-sm md:w-2/6">
      <span class="text-gray-700 dark:text-gray-300">Date <span class="text-red-500">*</span></span>
      <input type="date" v-model="form.date" required placeholder="Ex: BES" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
      <Error v-if="errors?.code" :errors="errors.code" />
    </label>
    <label class=" w-full mt-3 text-sm md:w-1/6 block">
      <button type="button" class="btn-submit-default md:mt-6" @click="getPortPresetsData()">Process</button>
    </label>
  </div>


  <!-- Expense entries -->
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300">Expense Particulars </legend>
    <table class="w-full whitespace-no-wrap" id="table">
      <thead>
        <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
          <th class="px-4 py-3 align-bottom">Group</th>
          <th class="px-4 py-3 align-bottom">Invoice Date</th>
          <th class="px-4 py-3 align-bottom">Invoice No</th>
          <th class="px-4 py-3 align-bottom">Currency</th>
          <th class="px-4 py-3 align-bottom">Amount</th>
          <th class="px-4 py-3 align-bottom">Remarks</th>
          <th class="px-4 py-3 text-center align-bottom">Action</th>
        </tr>
      </thead>

      <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800" v-if="form?.entries?.length >= 1" v-for="(entry, index) in form.entries" :key="index">
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