<!-- CustomInput.vue -->
<script setup>
import Error from "../Error.vue";
import Store from './../../store/index.js';
import {ref} from "vue";
defineProps(['modelValue'])
defineEmits(['update:modelValue'])
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

</script>

<template>
  <div v-if="businessUnit === 'ALL'" class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Business Unit <span class="text-red-500">*</span></span>
      <select :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" class="form-input" required>
        <option value="" selected disabled>Select</option>
        <option value="PSML">PSML</option>
        <option value="TSML">TSML</option>
      </select>
    </label>
  </div>
  <input v-else type="hidden" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" required>
</template>