<template>
    <!-- Basic information -->
    <div class="justify-center w-full grid grid-cols-1 md:grid-cols-4 md:gap-2 ">
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Vessel Name {{ form.formType }} </span>
            <div v-if="form.form_type === 'create'">
              <v-select placeholder="Select Item" :options="form.dept_wise_items" @search="" v-model="form.item_name" label="name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
              <input type="hidden" v-model="form.mnt_item_id">
            </div>
            <input v-else type="text" v-model="form.name" placeholder="Item Group Name" class="form-input" readonly />
            
          <Error v-if="errors?.name" :errors="errors.name" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Department </span>
            <div v-if="form.form_type === 'create'">
              <v-select placeholder="Select Item" :options="form.dept_wise_items" @search="" v-model="form.item_name" label="name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
              <input type="hidden" v-model="form.mnt_item_id">
            </div>
            <input v-else type="text" v-model="form.name" placeholder="Item Group Name" class="form-input" readonly />
          <Error v-if="errors?.name" :errors="errors.name" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Item Group </span>
            <div v-if="form.form_type === 'create'">
              <v-select placeholder="Select Item" :options="form.dept_wise_items" @search="" v-model="form.item_name" label="name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
              <input type="hidden" v-model="form.mnt_item_id">
            </div>
            <input v-else type="text" v-model="form.name" placeholder="Item Group Name" class="form-input" readonly />
          <Error v-if="errors?.name" :errors="errors.name" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Item Name </span>
            <div v-if="form.form_type === 'create'">
              <v-select placeholder="Select Item" :options="form.dept_wise_items" @search="" v-model="form.item_name" label="name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
              <input type="hidden" v-model="form.mnt_item_id">
            </div>
            <input v-else type="text" v-model="form.name" placeholder="Item Group Name" class="form-input" readonly />
          <Error v-if="errors?.name" :errors="errors.name" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Previous Run Hour </span>
            <input type="text" v-model="form.previous_run_hour" placeholder="Previous Run Hour" class="form-input" :readonly="1" />
          <Error v-if="errors?.previous_run_hour" :errors="errors.previous_run_hour" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Present Run Hour </span>
            <input type="text" v-model="form.present_run_hour" placeholder="Present Run Hour" class="form-input"  />
          <Error v-if="errors?.present_run_hour" :errors="errors.present_run_hour" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Updated On </span>
            <input type="date" v-model="form.updated_on" placeholder="Updated on" class="form-input"  />
          <Error v-if="errors?.updated_on" :errors="errors.updated_on" />
        </label>
        
    </div>
    
</template>
<script setup>
import Error from "../../Error.vue";
import Editor from '@tinymce/tinymce-vue';

import useRunHour from "../../../composables/maintenance/useRunHour";
import {onMounted} from "vue";

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});


// const { shipDepartments, getShipDepartments } = useShipDepartment();


onMounted(() => {
  const today = new Date().toISOString().substr(0, 10);
  props.form.updated_on = today;
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