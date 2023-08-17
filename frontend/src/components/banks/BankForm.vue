<script setup>
import Error from "../Error.vue";
import {onMounted, watchEffect} from "vue";
import useCurrency from "../../composables/dataencoding/useCurrency";

const { currencies, getCurrencyWithoutPaginate } = useCurrency();
const props = defineProps({
  form: {
    required: false,
    default: {
      beneficiary_bank: '',
      beneficiary_bank_branch: '',
      beneficiary_bank_address : '',
      swift_code : '',
      beneficiary_routing_no : '',
      beneficiary_account_no : '',
      beneficiary_iban_No : '',
      beneficiary_name : '',
      beneficiary_address : '',
      beneficiary_bank_currency : null,
      correspondent_bank_name : '',
      correspondent_bank_account : '',
      correspondent_bank_swift : '',
      correspondent_bank_address : '',
      correspondent_bank_currency : '',
    }
  },
  errors: { type: [Object, Array], required: false },
});

onMounted(() => {
  getCurrencyWithoutPaginate();
});
</script>

<template>
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300">Beneficiary Bank Info</legend>
      <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
            <label class="block w-full mt-3 text-sm">
                <span class="text-gray-700 dark:text-gray-300">Bank Name <span class="text-red-500">*</span></span>
                <input type="text" v-model="form.beneficiary_bank" required placeholder="Enter Bank" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
              <Error v-if="errors?.beneficiary_bank" :errors="errors.beneficiary_bank" />
            </label>
          <label class="block w-full mt-3 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Bank Branch <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.beneficiary_bank_branch" required placeholder="Enter Bank Branch" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
            <Error v-if="errors?.beneficiary_bank_branch" :errors="errors.beneficiary_bank_branch" />
          </label>
        </div>  
        <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-3 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Bank Address <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.beneficiary_bank_address" required placeholder="Enter Bank Address" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
            <Error v-if="errors?.beneficiary_bank_address" :errors="errors.beneficiary_bank_address" />
          </label>          
        </div>
        <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
            <label class="block w-full mt-3 text-sm">
                <span class="text-gray-700 dark:text-gray-300">Swift Code <span class="text-red-500">*</span></span>
                <input type="text" v-model="form.swift_code" required placeholder="Enter Swift Code" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
              <Error v-if="errors?.swift_code" :errors="errors.swift_code" />
            </label>
            <label class="block w-full mt-3 text-sm">
                <span class="text-gray-700 dark:text-gray-300">Routing No <span class="text-red-500">*</span></span>
                <input type="text" v-model="form.beneficiary_routing_no" required placeholder="Enter Routing No" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
              <Error v-if="errors?.beneficiary_routing_no" :errors="errors.beneficiary_routing_no" />
            </label>
          <label class="block w-full mt-3 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Account No <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.beneficiary_account_no" required placeholder="Enter Account No" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
            <Error v-if="errors?.beneficiary_account_no" :errors="errors.beneficiary_account_no" />
          </label>
        </div>  
        <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
            <label class="block w-full mt-3 text-sm">
                <span class="text-gray-700 dark:text-gray-300">IBAN No </span>
                <input type="text" v-model="form.beneficiary_iban_No" placeholder="Enter IBAN No" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
              <Error v-if="errors?.beneficiary_iban_No" :errors="errors.beneficiary_iban_No" />
            </label>
          <label class="block w-full mt-3 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Beneficiary Name <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.beneficiary_name" required placeholder="Enter Beneficiary Name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
            <Error v-if="errors?.beneficiary_name" :errors="errors.beneficiary_name" />
          </label>
        </div> 
        <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-3 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Currency <span class="text-red-500">*</span></span>
            <select v-model="form.beneficiary_bank_currency" required class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
              <option value="" selected disabled>Choose Currency</option>
              <option :value="currency?.code" v-for="(currency,index) in currencies" :key="index">{{ currency?.code }}</option>
            </select>
            <Error v-if="errors?.beneficiary_bank_currency" :errors="errors.beneficiary_bank_currency" />
          </label>          
          <label class="block w-full mt-3 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Beneficiary Address <span class="text-red-500">*</span></span>
              <input type="text" v-model="form.beneficiary_address" required placeholder="Enter Beneficiary Address" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
            <Error v-if="errors?.beneficiary_address" :errors="errors.beneficiary_address" />
          </label>
        </div>
  </fieldset>
  
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300">Correspondent Bank Info</legend>
        <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
            <label class="block w-full mt-3 text-sm">
                <span class="text-gray-700 dark:text-gray-300">Bank Name</span>
                <input type="text" v-model="form.correspondent_bank_name" placeholder="Enter Bank Name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
              <Error v-if="errors?.correspondent_bank_name" :errors="errors.correspondent_bank_name" />
            </label>
          <label class="block w-full mt-3 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Account No</span>
            <input type="text" v-model="form.correspondent_bank_account" placeholder="Enter Account No" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
            <Error v-if="errors?.correspondent_bank_account" :errors="errors.correspondent_bank_account" />
          </label>
        </div>
        
        <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
            <label class="block w-full mt-3 text-sm">
                <span class="text-gray-700 dark:text-gray-300">Swift</span>
                <input type="text" v-model="form.correspondent_bank_swift" placeholder="Enter Bank Swift" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
              <Error v-if="errors?.correspondent_bank_swift" :errors="errors.correspondent_bank_swift" />
            </label>
          <label class="block w-full mt-3 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Currency</span>
            <select v-model="form.correspondent_bank_currency" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
              <option value="" selected disabled>Choose Currency</option>
              <option :value="currency?.code" v-for="(currency,index) in currencies" :key="index">{{ currency?.code }}</option>
            </select>
            <Error v-if="errors?.correspondent_bank_currency" :errors="errors.correspondent_bank_currency" />
          </label>            
        </div>
        
        <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-3 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Address </span>
            <input type="text" v-model="form.correspondent_bank_address" placeholder="Enter Address" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
            <Error v-if="errors?.correspondent_bank_address" :errors="errors.correspondent_bank_address" />
          </label>
        </div>
  </fieldset>
  
  
</template>