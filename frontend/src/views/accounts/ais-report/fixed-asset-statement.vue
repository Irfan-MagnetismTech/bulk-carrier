<script setup>
import Title from "../../../services/title";
import {onMounted, ref, watch, watchEffect} from "vue";
import useAisReport from "../../../composables/accounts/useAisReport";
import useAccountCommonApiRequest from "../../../composables/accounts/useAccountCommonApiRequest";
import Store from "../../../store";

const { fixedAssetStatements, getFixedAssetStatement, isLoading} = useAisReport();
const { allFixedAssetLists, getFixedAsset} = useAccountCommonApiRequest();

const dateFormat = ref(Store.getters.getVueDatePickerTextInputFormat.date);
const { setTitle } = Title();

let expenses = ref(0.00);
let incomes = ref(0.00);

setTitle('AIS Report - Fixed Asset Statement');

const searchParams = ref({
  asset_tag: null,
  from_date: '2022-01-01',
  till_date: '2022-12-31',
});
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

onMounted(() => {
  watchEffect(() => {
    getFixedAsset(null,businessUnit.value);
  });
});

</script>

<template>
  <!-- Table -->
  <!--  <button type="button"> Click Me </button>-->
  <form @submit.prevent="getFixedAssetStatement(searchParams)">
    <div class="w-full flex items-center justify-between mb-2 my-2 select-none">
      <fieldset class="w-full grid grid-cols-4 gap-1 px-2 pb-3 border border-gray-500 rounded dark-disabled:border-gray-400">
        <legend class="px-2 text-gray-700 uppercase dark-disabled:text-gray-300">Fixed Asset Statement</legend>
        <div>
          <label for="" class="text-xs" style="margin-left: .01rem">Asset Tag</label>
          <v-select :options="allFixedAssetLists" placeholder="--Choose an option--" v-model.trim="searchParams.asset_tag" :reduce="allFixedAssetLists => allFixedAssetLists.asset_tag" label="asset_tag"  class="block w-full rounded form-input">
            <template #search="{attributes, events}">
              <input class="vs__search w-full" style="width: 50%" v-bind="attributes" v-on="events"/>
            </template>
          </v-select>
        </div>
        <div>
          <label for="" class="text-xs" style="margin-left: .01rem">From Date <span class="text-red-500">*</span></label>
          <VueDatePicker v-model.trim="searchParams.from_date" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>
        </div>
        <div>
          <label for="" class="text-xs" style="margin-left: .01rem">Till Date <span class="text-red-500">*</span></label>
          <VueDatePicker v-model.trim="searchParams.till_date" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>
        </div>
        <div>
          <label for="">&nbsp;</label>
          <button type="submit" :disabled="isLoading" class="w-full flex items-center justify-center px-2 mt-1 py-2 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Submit</button>
        </div>
      </fieldset>
    </div>
  </form>
  <!-- Table -->
  <div class="w-full mb-10">
    <table class="w-full whitespace-no-wrap top_table">
      <thead>
      <tr class="text-sm font-semibold tracking-wide text-center">
        <th class="bg-green-600 text-white" rowspan="2">Particulars</th>
        <th class="bg-green-600 text-white" rowspan="2">Dep. rate</th>
        <th class="bg-green-600 text-white" rowspan="2">Acquisition Date</th>
        <th class="bg-green-600 text-white" colspan="4">Cost</th>
        <th class="bg-green-600 text-white" colspan="4">Depreciation</th>
        <th class="bg-green-600 text-white" rowspan="2">WDV</th>
      </tr>
      <tr class="text-sm font-semibold tracking-wide text-center">
        <th class="bg-green-600 text-white">Opening</th>
        <th class="bg-green-600 text-white">Addition</th>
        <th class="bg-green-600 text-white">Deletion</th>
        <th class="bg-green-600 text-white">Closing</th>
        <th class="bg-green-600 text-white">Opening</th>
        <th class="bg-green-600 text-white">Addition</th>
        <th class="bg-green-600 text-white">Deletion</th>
        <th class="bg-green-600 text-white">Closing</th>
      </tr>
      </thead>
      <tbody class="bg-white dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
      <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(fixedAssetData,index) in fixedAssetStatements" :key="index">
        <td class="text-sm">{{ fixedAssetData?.particular }}</td>
        <td class="text-sm">{{ fixedAssetData?.dep_rate }}</td>
        <td class="text-sm">{{ fixedAssetData?.acquisition_date }}</td>
        <td class="text-sm">{{ fixedAssetData?.opening_cost }}</td>
        <td class="text-sm">{{ fixedAssetData?.addition_cost }}</td>
        <td class="text-sm">{{ fixedAssetData?.delation_cost }}</td>
        <td class="text-sm">{{ fixedAssetData?.closing_cost }}</td>
        <td class="text-sm">{{ fixedAssetData?.opening_depreciation }}</td>
        <td class="text-sm">{{ fixedAssetData?.addition_depreciation }}</td>
        <td class="text-sm">{{ fixedAssetData?.delation_depreciation }}</td>
        <td class="text-sm">{{ fixedAssetData?.closing_depreciation }}</td>
        <td class="text-sm">{{ fixedAssetData?.wdv }}</td>
      </tr>
      </tbody>
      <tfoot v-if="!fixedAssetStatements?.length" class="bg-white dark:bg-gray-800">
      <tr v-if="isLoading">
        <td colspan="12">Loading...</td>
      </tr>
      <tr v-else-if="!fixedAssetStatements?.length">
        <td colspan="12">No data found.</td>
      </tr>
      </tfoot>
    </table>
  </div>
</template>
<style lang="postcss" scoped>
@tailwind components;

@layer components {
  .tab {
    @apply p-1.5 text-xs;
  }
  thead tr {
    @apply font-semibold tracking-wide text-left text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800;
  }
  th {
    @apply text-center;
  }

  tbody tr {
    @apply text-gray-700 dark-disabled:text-gray-400;
  }
  tbody tr td {
    @apply tab;
  }
  tfoot td {
    @apply tab;
  }
  table th {
    @apply tab;
  }
}

.balance_header {
  font-weight: bold;
  text-align: left;
}

.balance_line_style {
  font-weight: bold;
  padding-left: 30px;
  cursor: pointer;
}

.balance_line_parent {
  padding-left: 50px;
  cursor: pointer;
}

.balance_line_parent_child {
  padding-left: 70px;
  cursor: pointer;
}

.balance_line_parent_grand_child {
  padding-left: 90px;
}

table tr,td,th {
  border: 1px solid #cbc5c5;
}

#close_tr td {
  color: #f1ebeb;
}

#opening_tr td {
  color: #f1ebeb;
}

.income_statement_top_div{
  padding: 10px;
  border: 1px solid #727272;
}

.input-group {
  @apply flex flex-col items-center justify-center w-full md:flex-row md:gap-2;
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
table, th,td{
  @apply border border-collapse;
}
.search-result {
  @apply px-4 py-3 text-sm text-center text-gray-600 dark-disabled:text-gray-300;
}
.search {
  @apply float-right  pr-10 text-sm border border-gray-300 rounded dark-disabled:bg-gray-800 dark-disabled:text-gray-200 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray dark-disabled:border-0;
}
.transaction_col:hover{
  text-decoration: underline;
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