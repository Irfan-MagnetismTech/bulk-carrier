<script setup>
import { ref } from 'vue';

const props = defineProps({
    to: {
        default: {},
        required: false,
    },
    classes: null,
    title: {
        default: null,
        required: false
    },
    icon: {
      default: null,
      required: false
    },
});
</script>
<template>
    <router-link :to="to" class="flex items-center justify-between px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
      <span :title="title" v-html="icon"></span>
    </router-link>
</template>