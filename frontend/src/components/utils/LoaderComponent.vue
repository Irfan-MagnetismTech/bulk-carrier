<script setup>
const props = defineProps({
    isLoading: {
        type: Boolean,
        default: false,
    },
    
})

</script>
<template>
    <div v-show="isLoading"  class="absolute inset-0 z-10 flex items-center justify-center bg-gray-200 opacity-50">
                  <div>
                    <div class="flex justify-center items-center">
                      <div class="h-2.5 w-2.5 bg-[#6C2BD9] rounded-full mx-1 animate-pulse1"></div>
                      <div class="h-2.5 w-2.5 bg-[#6C2BD9] rounded-full mx-1 animate-pulse2"></div>
                      <div class="h-2.5 w-2.5 bg-[#6C2BD9] rounded-full mx-1 animate-pulse3"></div>
                    </div>
                  </div>
                </div>
</template>
<style lang="postcss" scoped>

    @keyframes pulse {
    0%, 100% {
        transform: scale(0.8);
    }
    50% {
        transform: scale(1.2);
    }
    }

    .animate-pulse1 {
    animation: pulse 1s infinite ease-in-out;
    }

    .animate-pulse2 {
    animation: pulse 1s infinite ease-in-out;
    animation-delay: 0.25s; /* Delayed start */
    }

    .animate-pulse3 {
    animation: pulse 1s infinite ease-in-out;
    animation-delay: 0.5s; /* Delayed start */
    }
</style>