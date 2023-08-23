<script setup>
import { ref } from 'vue';

const props = defineProps({
  errors: {
    type: [Object, Array],
    required: false,
  },
});
</script>

<template>
  <ul class="text-red-500 -mb-3">
    <li v-for="error in errors" class="py-1">{{ error }}</li>
  </ul>
</template>