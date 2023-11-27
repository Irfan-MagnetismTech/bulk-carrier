<script>
import { ref, onMounted, defineProps, defineEmits } from "vue";
import Store from "../../store";

export default {
  props: {
    modelValue: String,
  },
  setup(props, context) {
    const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
    return {
      businessUnit,
    };
  },
};
</script>

<template>
  <div class="relative w-full">
    <select :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" class="filter_input" :class="{'custom_disabled' : businessUnit !== 'ALL'}">
      <option value="ALL" :selected="businessUnit === 'ALL'">ALL</option>
      <option value="PSML" :selected="businessUnit === 'PSML'">PSML</option>
      <option value="TSLL" :selected="businessUnit === 'TSLL'">TSLL</option>
    </select>
  </div>
</template>