<script setup>
import Error from "../Error.vue";
import {onMounted, ref} from "vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import Store from "../../store";

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

function checkWhitespace(value) {
  if (/^\s+$/.test(props.form.bank_name)) {props.form.bank_name = '';}
  if (/^\s+$/.test(props.form.branch_name)) {props.form.branch_name = '';}
  if (/^\s+$/.test(props.form.account_type)) {props.form.account_type = '';}
  if (/^\s+$/.test(props.form.account_name)) {props.form.account_name = '';}
  if (/^\s+$/.test(props.form.account_number)) {props.form.account_number = '';}
  if (/^\s+$/.test(props.form.routing_number)) {props.form.routing_number = '';}
  if (/^\s+$/.test(props.form.contact_number)) {props.form.contact_number = '';}
}

onMounted(() => {
  //props.form.business_unit = businessUnit.value;
});

</script>
<template>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
  </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Bank Name <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.bank_name" @input="checkWhitespace" placeholder="Bank name" class="form-input" autocomplete="off" required />
        <Error v-if="errors?.bank_name" :errors="errors.bank_name" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Branch Name <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.branch_name" @input="checkWhitespace" placeholder="Bank name" class="form-input" autocomplete="off" required />
        <Error v-if="errors?.branch_name" :errors="errors.branch_name" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Account Type <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.account_type" @input="checkWhitespace" placeholder="Account type" class="form-input" autocomplete="off" required />
        <Error v-if="errors?.account_type" :errors="errors.account_type" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Account Name <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.account_name" @input="checkWhitespace" placeholder="Account name" class="form-input" autocomplete="off" required />
        <Error v-if="errors?.account_name" :errors="errors.account_name" />
      </label>
    </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Account No <span class="text-red-500">*</span></span>
      <input type="text" v-model="form.account_number" @input="checkWhitespace" placeholder="Account no" class="form-input" autocomplete="off" required />
      <Error v-if="errors?.account_number" :errors="errors.account_number" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Routing No <span class="text-red-500">*</span></span>
      <input type="text" v-model="form.routing_number" @input="checkWhitespace" placeholder="Routing no" class="form-input" autocomplete="off" required />
      <Error v-if="errors?.routing_number" :errors="errors.routing_number" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Contact No <span class="text-red-500">*</span></span>
      <input type="text" v-model="form.contact_number" @input="checkWhitespace" placeholder="Contact no" class="form-input" autocomplete="off" required />
      <Error v-if="errors?.contact_number" :errors="errors.contact_number" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Opening Date <span class="text-red-500">*</span></span>
      <input type="date" v-model="form.opening_date" class="form-input" autocomplete="off" required />
      <Error v-if="errors?.opening_date" :errors="errors.opening_date" />
    </label>
  </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Opening Balance <span class="text-red-500">*</span></span>
      <input type="number" step=".01" v-model="form.opening_balance" placeholder="Opening balance" class="form-input" autocomplete="off" required />
      <Error v-if="errors?.opening_balance" :errors="errors.opening_balance" />
    </label>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
  </div>
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