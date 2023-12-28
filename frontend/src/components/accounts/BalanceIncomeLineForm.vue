<script setup>
import Error from "../Error.vue";
import {onMounted, ref, watch, watchEffect} from "vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import Store from "../../store";
import useCrewCommonApiRequest from "../../composables/accounts/useAccountCommonApiRequest";
import useAccountCommonApiRequest from "../../composables/accounts/useAccountCommonApiRequest";
import ErrorComponent from '../../components/utils/ErrorComponent.vue';

const { balanceIncomeLineLists, getBalanceIncomeLineLists, isLoading } = useAccountCommonApiRequest();

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

watch(() => props.form, (newEntries, oldEntries) => {
      props.form.parent_id = props.form?.parent_id_name?.id ?? '';
    }, { deep: true }
);

onMounted(() => {
  //props.form.business_unit = businessUnit.value;
  watchEffect(() => {
    getBalanceIncomeLineLists(props.form.business_unit);
  });
});

</script>
<template>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <business-unit-input :page="page" v-model.trim="form.business_unit"></business-unit-input>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Parent Line </span>
    <v-select :options="balanceIncomeLineLists" :loading="isLoading" placeholder="--Choose an option--" v-model="form.parent_id_name" label="line_text"  class="block w-full rounded form-input">
      <template #search="{attributes, events}">
        <input class="vs__search w-full" style="width: 50%" v-bind="attributes" v-on="events"/>
      </template>
    </v-select>
    </label>
  </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Line Name <span class="text-red-500">*</span></span>
        <input type="text" v-model.trim="form.line_text" placeholder="Line Name" class="form-input" autocomplete="off" required />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Value Type <span class="text-red-500">*</span></span>
        <select class="form-input" v-model.trim="form.value_type" autocomplete="off" required >
          <option value="" disabled selected>Select</option>
          <option value="D">Debit</option>
          <option value="C">Credit</option>
        </select>
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Line Type <span class="text-red-500">*</span></span>
        <select class="form-input" v-model.trim="form.line_type" autocomplete="off" required >
          <option value="" disabled selected>Select</option>
          <option value="base_header">base_header</option>
          <option value="balance_header">balance_header</option>
          <option value="income_header">income_header</option>
          <option value="balance_line">balance_line</option>
          <option value="income_line">income_line</option>
        </select>
      </label>
<!--      <label class="block w-full mt-2 text-sm">-->
<!--        <span class="text-gray-700 dark-disabled:text-gray-300">Parent Line</span>-->
<!--        <select class="form-input" v-model="form.parent_id" autocomplete="off" >-->
<!--          <option value="" disabled selected>Select</option>-->
<!--          <option v-for="balanceIncomeLine in balanceIncomeLineLists" :value="balanceIncomeLine.id" :key="balanceIncomeLine.id">{{ balanceIncomeLine.line_text }}</option>-->
<!--        </select>-->
<!--        <Error v-if="errors?.parent_id" :errors="errors.parent_id" />-->
<!--      </label>-->
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