<template>
    <!-- Basic information -->
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Business Unit <span class="text-red-500">*</span></span>
      <select v-model="form.business_unit" class="form-input" required>
        <option value="" disabled selected>Select</option>
        <option value="PSML">PSML</option>
        <option value="TSLL">TSLL</option>
        <option value="ALL">ALL</option>
      </select>
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Role <span class="text-red-500">*</span></span>
      <select v-model="form.role" class="form-input" required>
        <option value="" disabled selected>Select</option>
        <option v-for="role in roles" :value="role.id">{{ role.name }}</option>
      </select>
    </label>
  </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">User Name <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.name" placeholder="User Name" class="form-input" autocomplete="off" required />
        </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 ">Email <span class="text-red-500">*</span></span>
        <input type="email" v-model="form.email" placeholder="Email" class="form-input" autocomplete="off" required />
      </label>
    </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 ">Password <span class="text-red-500">*</span></span>
        <input type="password" v-model="form.password" placeholder="Password" class="form-input" autocomplete="off" required />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 ">Confirm Password <span class="text-red-500">*</span></span>
        <input type="password" v-model="form.confirm_password" placeholder="Confirm Password" class="form-input" autocomplete="off" required />
      </label>
    </div>
<!--  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">-->
<!--    <label class="block w-full mt-2 text-sm">-->
<!--      <span class="text-gray-700 ">Email Signature</span>-->
<!--      <editor v-model="form.email_signature" class="block w-full text-sm rounded   focus:border-purple-400 focus:outline-none focus:shadow-outline-purple  form-input" api-key="wljvu7gtfjb8h5ou2rcxw8d5tykej98zy10x8ot83jclsm3o" />-->
<!--      <Error v-if="errors?.email_signature" :errors="errors.email_signature" />-->
<!--    </label>-->
<!--  </div>-->
  <ErrorComponent :errors="errors"></ErrorComponent>
</template>
<script setup>
import Error from "../Error.vue";
import Editor from '@tinymce/tinymce-vue';
import useRole from "../../composables/administration/useRole";
import {onMounted} from "vue";
import useAdministrationCommonApiRequest from "../../composables/administration/useAdministrationCommonApiRequest";
import ErrorComponent from '../../components/utils/ErrorComponent.vue';

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});

const { roles, getRoleList } = useAdministrationCommonApiRequest();

onMounted(() => {
  getRoleList();
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