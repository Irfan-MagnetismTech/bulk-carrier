<template>
    <!-- Basic information -->
<!--    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">-->
<!--        <label class="block w-full mt-3 text-sm">-->
<!--            <span class="text-gray-700 dark:text-gray-300">User Name <span class="text-red-500">*</span></span>-->
<!--            <input type="text" v-model="form.name" placeholder="User Name" class="block vms-readonly-input w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" readonly />-->
<!--          <Error v-if="errors?.name" :errors="errors.name" />-->
<!--        </label>-->
<!--      <label class="block w-full mt-3 text-sm">-->
<!--        <span class="text-gray-700 dark:text-gray-300">Email <span class="text-red-500">*</span></span>-->
<!--        <input type="email" v-model="form.email" placeholder="Email" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />-->
<!--        <Error v-if="errors?.email" :errors="errors.email" />-->
<!--      </label>-->
<!--    </div>-->
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Password <span class="text-red-500">*</span></span>
        <input type="password" v-model="form.password" required placeholder="Password"
               class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" autocomplete="off" />
        <Error v-if="errors?.password" :errors="errors.password" />
      </label>
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Confirm Password <span class="text-red-500">*</span></span>
        <input type="password" v-model="form.confirm_password" required placeholder="Confirm Password" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
        <Error v-if="errors?.confirm_password" :errors="errors.confirm_password" />
      </label>
    </div>
</template>
<script setup>
import Error from "../Error.vue";
import Editor from '@tinymce/tinymce-vue';

import useRole from "../../composables/administration/useRole";
import {onMounted} from "vue";
import usePort from "../../composables/usePort";

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
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
  @apply block w-full mt-3 text-sm;
}
.label-item-title {
  @apply text-gray-700 dark:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
}
.form-input {
  @apply block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
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