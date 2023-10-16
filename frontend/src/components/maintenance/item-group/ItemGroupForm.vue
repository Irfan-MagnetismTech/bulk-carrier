<template>
    <business-unit-input v-model="form.business_unit"></business-unit-input>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Department <span class="text-red-500">*</span></span>
          <v-select placeholder="Select Department" :options="shipDepartments" @search="" v-model="form.mnt_ship_department" label="name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
          <input type="hidden" v-model="form.mnt_ship_department_id">
        <Error v-if="errors?.mnt_ship_department_id" :errors="errors.mnt_ship_department_id" />
      </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Name <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.name" placeholder="Item Group Name" class="form-input" />
          <Error v-if="errors?.name" :errors="errors.name" />
        </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Short Code <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.short_code" placeholder="Short Code" class="form-input" />
        <Error v-if="errors?.short_code" :errors="errors.short_code" />
      </label>
    </div>
    
</template>
<script setup>
import Error from "../../Error.vue";
import Editor from '@tinymce/tinymce-vue';

import useShipDepartment from "../../../composables/maintenance/useShipDepartment";
import {onMounted, watch} from "vue";
import BusinessUnitInput from "../../input/BusinessUnitInput.vue";

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});

watch(() => props.form.mnt_ship_department, (value) => {
  props.form.mnt_ship_department_id = value?.id;
});
const { shipDepartments, getShipDepartmentsWithoutPagination } = useShipDepartment();


onMounted(() => {
  getShipDepartmentsWithoutPagination();
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