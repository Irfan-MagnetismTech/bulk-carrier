<template>
    <!-- Basic information -->
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Business Unit <span class="text-red-500">*</span></span>
      <select v-model="form.business_unit" class="form-input" required>
        <option value="" disabled selected>Select</option>
        <option value="PSML">PSML</option>
        <option value="TSLL">TSLL</option>
        <option value="ALL">ALL</option>
      </select>
      <Error v-if="errors?.business_unit" :errors="errors.business_unit" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Role <span class="text-red-500">*</span></span>
      <select v-model="form.role" class="form-input" required>
        <option value="" disabled selected>Select</option>
        <option v-for="role in roles" :value="role.id">{{ role.name }}</option>
      </select>
      <Error v-if="errors?.role" :errors="errors.role" />
    </label>
  </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">User Name <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.name" placeholder="User Name" class="form-input" autocomplete="off" />
          <Error v-if="errors?.name" :errors="errors.name" />
        </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Email <span class="text-red-500">*</span></span>
        <input type="email" v-model="form.email" placeholder="Email" class="form-input" autocomplete="off" />
        <Error v-if="errors?.email" :errors="errors.email" />
      </label>
    </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Password <span class="text-red-500">*</span></span>
        <input type="password" v-model="form.password" placeholder="Password" class="form-input" autocomplete="off" />
        <Error v-if="errors?.password" :errors="errors.password" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Confirm Password <span class="text-red-500">*</span></span>
        <input type="password" v-model="form.confirm_password" placeholder="Confirm Password" class="form-input" autocomplete="off" />
        <Error v-if="errors?.confirm_password" :errors="errors.confirm_password" />
      </label>
    </div>
<!--  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">-->
<!--    <label class="block w-full mt-2 text-sm">-->
<!--      <span class="text-gray-700 dark:text-gray-300">Email Signature</span>-->
<!--      <editor v-model="form.email_signature" class="block w-full text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" api-key="wljvu7gtfjb8h5ou2rcxw8d5tykej98zy10x8ot83jclsm3o" />-->
<!--      <Error v-if="errors?.email_signature" :errors="errors.email_signature" />-->
<!--    </label>-->
<!--  </div>-->
</template>
<script setup>
import Error from "../Error.vue";
import Editor from '@tinymce/tinymce-vue';

import useRole from "../../composables/administration/useRole";
import {onMounted} from "vue";

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});

const { roles, getRoles } = useRole();

onMounted(() => {
  getRoles();
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