<template>
  <div class="justify-center w-full grid grid-cols-1 md:grid-cols-3 md:gap-2">
      <!-- <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input> -->
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Critical Function <span class="text-red-500">*</span></span>
          <v-select placeholder="Select Critical Function" :loading="isCriticalFunctionLoading"  :options="criticalFunctions" @search="" v-model="form.mnt_critical_function_name" label="function_name" class="block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray form-input">
            <template #search="{attributes, events}">
            <input
                class="vs__search"
                :required="!form.mnt_critical_function_name"
                v-bind="attributes"
                v-on="events"
            />
          </template>
          </v-select>
          <input type="hidden" v-model="form.mnt_critical_function_id">
        <Error v-if="errors?.mnt_critical_function_id" :errors="errors.mnt_critical_function_id" />
      </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Catrgory Name <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.category_name" placeholder="Category Name" class="form-input" required/>
          <Error v-if="errors?.category_name" :errors="errors.category_name" />
        </label>
      <!-- <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Notes</span>
        <input type="text" v-model.trim="form.notes" placeholder="Notes" class="form-input"/>
        <Error v-if="errors?.notes" :errors="errors.notes" />
      </label> -->
      <RemarksComponent v-model.trim="form.notes" :maxlength="300" :fieldLabel="'Notes'"></RemarksComponent>
    </div>
    <ErrorComponent :errors="errors"></ErrorComponent>
</template>
<script setup>
import Error from "../../Error.vue";
import Editor from '@tinymce/tinymce-vue';

import useShipDepartment from "../../../composables/maintenance/useShipDepartment";
import {onMounted, watch, watchEffect, ref} from "vue";
import BusinessUnitInput from "../../input/BusinessUnitInput.vue";
import useCriticalFunction from "../../../composables/maintenance/useCriticalFunction";
import ErrorComponent from "../../utils/ErrorComponent.vue";
import RemarksComponent from "../../utils/RemarksComponent.vue";
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

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

watch(() => props.form.mnt_critical_function_name, (value) => {
  props.form.mnt_critical_function_id = value?.id;
});
const { criticalFunctions, getCriticalFunctionsWithoutPagination, isCriticalFunctionLoading } = useCriticalFunction();

watch(() => props.form.business_unit, (newValue, oldValue) => {
  businessUnit.value = newValue;
});

onMounted(() => {
  getCriticalFunctionsWithoutPagination();
});

</script>

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