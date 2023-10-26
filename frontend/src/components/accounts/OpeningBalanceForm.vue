<script setup>
import Error from "../Error.vue";
import {onMounted, ref, watch, watchEffect} from "vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import Store from "../../store";
import useAccountCommonApiRequest from "../../composables/accounts/useAccountCommonApiRequest";

const { allAccountLists, getAccount, allCostCenterLists, getCostCenter } = useAccountCommonApiRequest();

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});

const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

function fetchAccounts(search, loading) {
  if(search.length < 3) {
    return;
  } else {
    getAccount(search, props.form.business_unit, loading);
  }
}

function fetchCostCenters(search, loading) {
  if(search.length < 3) {
    return;
  } else {
    getCostCenter(search, props.form.business_unit, loading);
  }
}

watch(() => props.form, (value) => {
  if(value){
    props.form.acc_account_id = props.form?.acc_account_name?.id ?? '';
    props.form.acc_cost_center_id = props.form?.acc_cost_center_name?.id ?? '';
  }
}, {deep: true});

onMounted(() => {
  props.form.business_unit = businessUnit.value;
  watchEffect(() => {
    getCostCenter(props.form.business_unit);
    getAccount(props.form.business_unit);
  });
});

</script>
<template>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <business-unit-input v-model="form.business_unit"></business-unit-input>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
  </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Cost Center <span class="text-red-500">*</span></span>
        <v-select :options="allCostCenterLists" placeholder="--Choose an option--" @search="fetchCostCenters" v-model="form.acc_cost_center_name" label="name"  class="block w-full rounded form-input">
          <template #search="{attributes, events}">
            <input class="vs__search w-full" style="width: 50%" :required="!form.acc_cost_center_name" v-bind="attributes" v-on="events"/>
          </template>
        </v-select>
        <Error v-if="errors?.acc_cost_center_name" :errors="errors.acc_cost_center_name" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Account <span class="text-red-500">*</span></span>
        <v-select :options="allAccountLists" placeholder="--Choose an option--" @search="fetchAccounts" v-model="form.acc_account_name" label="account_name"  class="block w-full rounded form-input">
          <template #search="{attributes, events}">
            <input class="vs__search w-full" style="width: 50%" :required="!form.acc_account_name" v-bind="attributes" v-on="events"/>
          </template>
        </v-select>
        <Error v-if="errors?.acc_account_name" :errors="errors.acc_account_name" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Date <span class="text-red-500">*</span></span>
        <input type="date" v-model="form.date" class="form-input" autocomplete="off" required />
        <Error v-if="errors?.date" :errors="errors.date" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Debit Amount <span class="text-red-500">*</span></span>
        <input type="number" step=".01" v-model="form.dr_amount" placeholder="Debit Amount" class="form-input" autocomplete="off" required />
        <Error v-if="errors?.dr_amount" :errors="errors.dr_amount" />
      </label>
    </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Credit Amount <span class="text-red-500">*</span></span>
      <input type="number" step=".01" v-model="form.cr_amount" placeholder="Credit Amount" class="form-input" autocomplete="off" required />
      <Error v-if="errors?.cr_amount" :errors="errors.cr_amount" />
    </label>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
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