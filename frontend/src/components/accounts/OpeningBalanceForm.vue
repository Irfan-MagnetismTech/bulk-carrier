<script setup>
import Error from "../Error.vue";
import {onMounted, ref, watch, watchEffect} from "vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import Store from "../../store";
import useAccountCommonApiRequest from "../../composables/accounts/useAccountCommonApiRequest";
import ErrorComponent from '../../components/utils/ErrorComponent.vue';

const { allAccountLists, getAccount, allCostCenterLists, getCostCenter, isLoading } = useAccountCommonApiRequest();

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

// function fetchAccounts(search, loading) {
//   if(search.length < 3) {
//     return;
//   } else {
//     getAccount(search, props.form.business_unit, loading);
//   }
// }

// function fetchCostCenters(search, loading) {
//   if(search.length < 3) {
//     return;
//   } else {
//     getCostCenter(search, props.form.business_unit, loading);
//   }
// }

watch(() => props.form, (value) => {
  if(value){
    props.form.acc_account_id = props.form?.acc_account_name?.id ?? '';
    props.form.acc_cost_center_id = props.form?.acc_cost_center_name?.id ?? '';
  }
}, {deep: true});

onMounted(() => {
  //props.form.business_unit = businessUnit.value;
  watchEffect(() => {
    getCostCenter(null,props.form.business_unit);
    getAccount(null,props.form.business_unit);
  });
});

</script>
<template>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>

  </div>
    <div class="flex flex-col justify-center md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm w-1/3">
        <span class="text-gray-700 ">Cost Center <span class="text-red-500">*</span></span>
        <v-select :options="allCostCenterLists" :loading="isLoading" placeholder="--Choose an option--" v-model.trim="form.acc_cost_center_name" label="name"  class="block w-full rounded form-input">
          <template #search="{attributes, events}">
            <input class="vs__search w-full" style="width: 50%" :required="!form.acc_cost_center_name" v-bind="attributes" v-on="events"/>
          </template>
        </v-select>
      </label>
      <label class="block w-full mt-2 text-sm w-2/3">
        <span class="text-gray-700 ">Account Name <span class="text-red-500">*</span></span>
        <v-select :options="allAccountLists" :loading="isLoading" placeholder="--Choose an option--" v-model.trim="form.acc_account_name" label="account_name"  class="block w-full rounded form-input">
          <template #search="{attributes, events}">
            <input class="vs__search w-full" style="width: 50%" :required="!form.acc_account_name" v-bind="attributes" v-on="events"/>
          </template>
        </v-select>
      </label>
    </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Date <span class="text-red-500">*</span></span>
      <input type="date" v-model.trim="form.date" class="form-input" autocomplete="off" required />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Debit Amount <span class="text-red-500">*</span></span>
      <input type="number" step=".01" v-model.trim="form.dr_amount" placeholder="Debit Amount" class="form-input" autocomplete="off" required />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Credit Amount <span class="text-red-500">*</span></span>
      <input type="number" step=".01" v-model.trim="form.cr_amount" placeholder="Credit Amount" class="form-input" autocomplete="off" required />
    </label>
  </div>
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
  @apply text-gray-700 ;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded   focus:border-purple-400 focus:outline-none focus:shadow-outline-purple  disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed ;
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