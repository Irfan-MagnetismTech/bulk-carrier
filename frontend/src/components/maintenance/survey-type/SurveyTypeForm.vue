<template>
  <div class="justify-center w-full grid grid-cols-1 md:grid-cols-3 md:gap-2">
      <!-- <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input> -->
      
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Survey Type Name <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.survey_type_name" placeholder="Survey Type Name" class="form-input" required/>
          <Error v-if="errors?.survey_type_name" :errors="errors.survey_type_name" />
        </label>
      
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Due Period <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.due_period" placeholder="Due Period" class="form-input" required/>
          <Error v-if="errors?.due_period" :errors="errors.due_period" />
        </label>
      
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Window Period <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.window_period" placeholder="Window Period" class="form-input" required/>
          <Error v-if="errors?.window_period" :errors="errors.window_period" />
        </label>


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
import useCriticalItemCategory from "../../../composables/maintenance/useCriticalItemCategory";
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


// watch(() => props.form.business_unit, (newValue, oldValue) => {
//   businessUnit.value = newValue;
// });

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