<template>
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row">
        <slot>
            <h2 class="text-2xl font-semibold text-gray-700 ">{{ label }}</h2>
            <router-link :to="to" v-if="type !== 'none'" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg v-if="type === 'create'" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                <svg v-else-if="type === 'list'" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                </svg>
            </router-link>
        </slot>
    </div>
</template>
<script setup>
const props = defineProps({
    label: {
        type: String,
        default: "",
    },
    type: {
        type: String,
        default: "create",
    },
    to: {
        type: Object,
        default: () => ({}),
    },
});
</script>