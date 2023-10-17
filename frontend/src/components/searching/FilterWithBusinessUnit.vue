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
  <div v-if="businessUnit === 'ALL'" class="relative w-full">
    <select :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" class="form-control business_filter_input border-transparent focus:ring-0">
      <option value="ALL" :selected="businessUnit === 'ALL'">ALL</option>
      <option value="PSML" :selected="businessUnit === 'PSML'">PSML</option>
      <option value="TSLL" :selected="businessUnit === 'TSLL'">TSLL</option>
    </select>
  </div>
</template>