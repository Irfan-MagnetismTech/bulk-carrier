<script setup>
import Error from "../Error.vue";
import useVessel from "../../composables/operations/useVessel";
import {onMounted, ref, watch, watchEffect, getCurrentInstance } from "vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import Store from "../../store";
import useAccountCommonApiRequest from "../../composables/accounts/useAccountCommonApiRequest";
import useMaterialReceiptReport from "../../composables/supply-chain/useMaterialReceiptReport";
import ErrorComponent from '../utils/ErrorComponent.vue';
const { vessels, searchVessels } = useVessel();

const { allAccountLists, allBankLists, allCostCenterLists,  allFixedAssetCategoryList, getFixedAssetCategory, getAccount, getBank, getCostCenter, isLoading } = useAccountCommonApiRequest();
const { searchMrr, filteredMaterialReceiptReports } = useMaterialReceiptReport();


const { emit } = getCurrentInstance();

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  page: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

let allMaterialLists = ref([]);

watch(() => props.form.acc_cost_center_name, (value) => {
  if(value){
    props.form.acc_cost_center_id = value?.id ?? '';
    searchMrr(props.form.business_unit, props.form.acc_cost_center_id); 
  }
});

watch(() => props.form.scm_mrr_name, (value) => {
  if(value){
    props.form.scm_mrr_id = value?.id ?? '';
    let materialData = filteredMaterialReceiptReports.value.find(doc => doc.id === value?.id);
    if(materialData){
      allMaterialLists.value = materialData?.scmMaterials;
    }
  }
});

watch(() => props.form.scm_material_name, (value) => {
  if(value){
    props.form.scm_material_id = value?.id ?? '';
  }
});

// v-select for change unit depend on material start
watch(() => props.form.acc_parent_account_name, (value) => {
  if(value){
    props.form.acc_parent_account_id = value?.id ?? '';
  }
});

function fixedAssetCosts() {
  let obj = {
    particular: '',
    amount: '',
    remarks: '',
  };
  props.form.fixedAssetCosts.push(obj);
}

function removeFixedAssetCosts(index){
  props.form.fixedAssetCosts.splice(index, 1);
}

watch(
    () => props.form,
    (newEntries, oldEntries) => {

      if(newEntries?.fixedAssetCosts?.length > 0) {
        let total_grand_amount = 0.0;
        newEntries?.fixedAssetCosts?.forEach((item) => {
          if(item.amount) {
            total_grand_amount += parseFloat(item.amount);
          }
        });
        if(!isNaN(total_grand_amount)) {
          props.form.acquisition_cost = total_grand_amount;
        }
      }
    },
    { deep: true }
);

onMounted(() => {
  watchEffect(() => {
    getCostCenter(null,props.form.business_unit);
    getAccount(null,props.form.business_unit);
    getBank(null,props.form.business_unit);
    getFixedAssetCategory(null,props.form.business_unit);
  });
});

</script>

<template>
  <ErrorComponent :errors="errors"></ErrorComponent>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
      <label class="block w-full mt-2 text-sm"></label>
      <label class="block w-full mt-2 text-sm"></label>
      <label class="block w-full mt-2 text-sm"></label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="label-group">
        <span class="label-item-title"> Year </span>
        <input type="number" class="label-item-input" placeholder="Year" v-model.trim="form.month_year" />
      </label>
      <label class="label-group">
        <span class="label-item-title"> Applied Date </span>
        <input type="date" class="label-item-input" v-model.trim="form.applied_date" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> Cost Center <span class="text-red-500">*</span></span>
        <v-select :options="allCostCenterLists" placeholder="--Choose an option--" :loading="isLoading" v-model.trim="form.acc_cost_center_name" label="name"  class="block w-full rounded form-input">
          <template #search="{attributes, events}">
            <input class="vs__search w-full" style="width: 50%" :required="!form.acc_cost_center_name" v-bind="attributes" v-on="events"/>
          </template>
        </v-select>
      </label>
    </div>
    <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
        <legend class="px-2 text-gray-700 dark-disabled:text-gray-300"> Depreciation Line <span class="text-red-500">*</span></legend>
        <table class="w-full whitespace-no-wrap" id="table">
          <thead>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
            <th class="px-4 py-3 align-bottom"> Asset Name <span class="text-red-500">*</span></th>
            <th class="px-4 py-3 align-bottom"> Asset Tag <span class="text-red-500">*</span></th>
            <th class="px-4 py-3 align-bottom"> Useful Life (Year) <span class="text-red-500">*</span></th>
            <th class="px-4 py-3 align-bottom"> Depreciation Rate (%) <span class="text-red-500">*</span></th>
            <th class="px-4 py-3 align-bottom"> Aquisition Cost <span class="text-red-500">*</span></th>
            <th class="px-4 py-3 align-bottom"> Amount <span class="text-red-500">*</span></th>
            <th class="px-4 py-3 text-center align-bottom">Action</th>
          </tr>
          </thead>

          <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
          <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(depreciationLine, index) in form.depreciationLine" :key="depreciationLine.id">
            <td class="px-1 py-1">
              <input type="text" v-model.trim="form.depreciationLine[index].asset_name" placeholder="Asset Name" class="form-input" autocomplete="off" required />
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model.trim="form.depreciationLine[index].asset_tag" placeholder="Asset Tag" class="form-input" autocomplete="off" required />
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model.trim="form.depreciationLine[index].useful_life" placeholder="Useful Life" class="form-input" autocomplete="off" required />
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model.trim="form.depreciationLine[index].depreciation_rate" placeholder="Depreciation Rate" class="form-input" autocomplete="off" required />
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model.trim="form.depreciationLine[index].aquisition_cost" placeholder="Aquisition Cost" class="form-input" autocomplete="off" required />
            </td>
            <td class="px-1 py-1">
              <input type="number" step=".01" v-model.trim="form.fixedAssetCosts[index].amount" class="form-input !text-right" autocomplete="off" required />
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model.trim="form.fixedAssetCosts[index].remarks" placeholder="Remarks" class="form-input" autocomplete="off" />
            </td>
            <td class="px-1 py-1 text-center">
              <button v-if="index!==0" type="button" @click="removeFixedAssetCosts(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg>
              </button>
              <button v-else type="button" @click="fixedAssetCosts()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
              </button>
            </td>
          </tr>
          <tr class="text-gray-700 dark-disabled:text-gray-400">
            <td class="px-1 py-1 font-bold !text-right">Acquisition Cost</td>
            <td class="px-1 py-1 font-bold text-right">
              <input type="number" v-model.trim="form.acquisition_cost" class="block w-full rounded form-input vms-readonly-input !text-right" readonly>
            </td>
            <td class="px-1 py-1 font-bold text-right"></td>
          </tr>
          </tbody>
        </table>
    </fieldset>

  <ErrorComponent :errors="errors"></ErrorComponent>
</template>
<style lang="postcss" scoped>
#table, #table th, #table td{
  @apply border border-collapse border-gray-400 text-center text-gray-700 px-1
}

.input-group {
  @apply flex flex-col justify-center w-full h-full md:flex-row md:gap-2;
}

.label-group {
  @apply block w-full mt-2 text-sm;
}
.label-item-title {
  @apply text-gray-700 dark-disabled:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark-disabled:disabled:bg-gray-900;
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