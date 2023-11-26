<script setup>
import Error from "../Error.vue";
import useVessel from "../../composables/operations/useVessel";
import {onMounted, ref, watch, watchEffect, getCurrentInstance } from "vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import Store from "../../store";
import useAccountCommonApiRequest from "../../composables/accounts/useAccountCommonApiRequest";
import ErrorComponent from '../utils/ErrorComponent.vue';
const { vessels, searchVessels } = useVessel();

const { allAccountLists, allBankLists, allCostCenterLists,  allFixedAssetCategoryList, getFixedAssetCategory, getAccount, getBank, getCostCenter, isLoading } = useAccountCommonApiRequest();

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

// v-select for change unit depend on material start
// watch(() => props.form, (value) => {
//   if(value){
//     props.form.acc_account_id = props.form?.acc_account_name?.id ?? '';
//     props.form.acc_cost_center_id = props.form?.acc_cost_center_name?.id ?? '';
//   }
// }, {deep: true});

// v-select for change unit depend on material start
watch(() => props.form.costCenter, (value) => {
  if(value){
    props.form.acc_cost_center_id = value?.id ?? '';
  }
});

// v-select for change unit depend on material start
watch(() => props.form.fixedAssetCategory, (value) => {
  if(value){
    props.form.acc_parent_account_id = value?.id ?? '';
  }
});


onMounted(() => {
  //props.form.business_unit = businessUnit.value;
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
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> Cost Center <span class="text-red-500">*</span></span>
        <v-select :options="allCostCenterLists" placeholder="--Choose an option--" :loading="isLoading" v-model.trim="form.costCenter" label="name"  class="block w-full rounded form-input">
          <template #search="{attributes, events}">
            <input class="vs__search w-full" style="width: 50%" :required="!form.costCenter" v-bind="attributes" v-on="events"/>
          </template>
        </v-select>
      </label>

      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> MRR No <span class="text-red-500">*</span></span>
        <v-select :options="allCostCenterLists" placeholder="--Choose an option--" :loading="isLoading" v-model.trim="form.acc_cost_center_name" label="name"  class="block w-full rounded form-input">
          <template #search="{attributes, events}">
            <input class="vs__search w-full" style="width: 50%" :required="!form.acc_cost_center_name" v-bind="attributes" v-on="events"/>
          </template>
        </v-select>
      </label>

      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> Asset Name <span class="text-red-500">*</span></span>
        <v-select :options="allCostCenterLists" placeholder="--Choose an option--" :loading="isLoading" v-model.trim="form.acc_cost_center_name" label="name"  class="block w-full rounded form-input">
          <template #search="{attributes, events}">
            <input class="vs__search w-full" style="width: 50%" :required="!form.acc_cost_center_name" v-bind="attributes" v-on="events"/>
          </template>
        </v-select>
      </label>      
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="label-group">
        <span class="label-item-title"> Brand </span>
        <input type="text" class="label-item-input" placeholder="Brand" v-model.trim="form.brand" />
      </label>

      <label class="label-group">
        <span class="label-item-title"> Model </span>
        <input type="text" class="label-item-input" placeholder="Model" v-model.trim="form.model" />
      </label>

      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> Serial </span>
        <input type="text" class="label-item-input" placeholder="Serial" v-model.trim="form.serial" />
      </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> Asset Category <span class="text-red-500">*</span></span>
        <v-select :options="allFixedAssetCategoryList" placeholder="--Choose an option--" :loading="isLoading" v-model.trim="form.fixedAssetCategory" label="account_name"  class="block w-full rounded form-input" required>
          <template #search="{attributes, events}">
            <input class="vs__search w-full" style="width: 50%" :required="!form.fixedAssetCategory" v-bind="attributes" v-on="events"/>
          </template>
        </v-select>
      </label>

      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> Account Name <span class="text-red-500">*</span></span>
        <input type="text" v-model.trim="form.total_installment" placeholder="Account Name" class="form-input vms-readonly-input" autocomplete="off" required readonly />
      </label>

      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> Asset Tag <span class="text-red-500">*</span></span>
        <input type="text" v-model.trim="form.asset_tag" placeholder="Asset Tag" class="form-input" autocomplete="off" required />
      </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> Useful Life (in Years) <span class="text-red-500">*</span></span>
        <input type="number" v-model.trim="form.useful_life" placeholder="Useful Life" class="form-input" autocomplete="off" required />
      </label>

      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> Depreciation Rate (%) <span class="text-red-500">*</span></span>
        <input type="number" step=".01" v-model.trim="form.depreciation_rate" placeholder="Depreciation Rate" class="form-input" autocomplete="off" required />
      </label>

      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> Acquisition Date <span class="text-red-500">*</span></span>
        <input type="date" v-model.trim="form.acquisition_date" class="form-input" autocomplete="off" required />
      </label>   
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> Location </span>
        <input type="text" v-model.trim="form.location" placeholder="Location" class="form-input" autocomplete="off" />
      </label>
    </div>

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