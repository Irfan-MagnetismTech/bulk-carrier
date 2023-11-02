<!--<script setup>-->
<!--import Error from "../Error.vue";-->
<!--import Store from './../../store/index.js';-->
<!--import { ref, onMounted } from "vue";-->

<!--const businessUnit = ref(Store.getters.getCurrentUser.business_unit);-->

<!--onMounted(() => {-->
<!--  emit('update:modelValue', businessUnit.value);-->
<!--});-->
<!--</script>-->

<script>
import { ref, onMounted, defineProps, defineEmits } from "vue";
import Store from "../../store";

export default {
  props: {
    modelValue: String,
    page: String,
  },
  setup(props, context) {
    const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

    onMounted(() => {
      if(businessUnit.value !== 'ALL')
      context.emit("update:modelValue", businessUnit.value);
    });

    return {
      businessUnit,
    };
  },
};
</script>

<template>
  <div v-if="businessUnit === 'ALL'" class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Business Unit <span class="text-red-500">*</span></span>
      <select v-if="page === 'create'" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" class="form-input" required>
        <option value="" selected disabled>Select</option>
        <option value="PSML">PSML</option>
        <option value="TSLL">TSLL</option>
      </select>
      <input v-else type="text" :value="modelValue" class="form-input vms-readonly-input" readonly autocomplete="off" required />
    </label>
  </div>
  <input type="hidden" class="form-input" @input="$emit('update:modelValue', $event.target.value)" v-else :value="modelValue" required>
</template>