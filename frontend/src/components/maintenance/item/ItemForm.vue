<template>
    <!-- Basic information -->
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Ship Department <span class="text-red-500">*</span></span>
            <!-- <input type="text" v-model="form.name" placeholder="Ship Department Name" class="form-input" /> -->
            <select v-model="form.ship_department_id" required name="ship_department_id" id="ship_department_id" class="form-input">
                    <!-- <option v-for="unit, index in units">{{ unit }}</option> -->
              
            </select>
          <Error v-if="errors?.ship_department_id" :errors="errors.ship_department_id" />
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Item Group <span class="text-red-500">*</span></span>
          <!-- <input type="text" v-model="form.item_group_id" placeholder="Short Name" class="form-input" /> -->
          <select v-model="form.item_group_id" required name="item_group_id" id="item_group_id" class="form-input">
                    <!-- <option v-for="unit, index in units">{{ unit }}</option> -->
              
            </select>
          <Error v-if="errors?.item_group_id" :errors="errors.item_group_id" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Item Code <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.item_code" placeholder="Item Code" class="form-input" />
          <Error v-if="errors?.item_code" :errors="errors.item_code" />
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Item Name <span class="text-red-500">*</span></span>
          <input type="text" v-model="form.name" placeholder="Item Name" class="form-input" />
          <Error v-if="errors?.name" :errors="errors.name" />
        </label>
    </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Description <span class="text-red-500">*</span></span>
            <!-- <input type="text" v-model="form.name" placeholder="Ship Department Name" class="form-input" /> -->
            <textarea v-model="form.description" placeholder="Description" class="form-input"></textarea>
          <Error v-if="errors?.description" :errors="errors.description" />
        </label>
    </div>
    

    
</template>
<script setup>
import Error from "../../Error.vue";
import Editor from '@tinymce/tinymce-vue';

import useShipDepartment from "../../../composables/maintenance/useShipDepartment";
import {onMounted} from "vue";

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});


// const { shipDepartments, getShipDepartments } = useShipDepartment();


// onMounted(() => {
//   getRoles();
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