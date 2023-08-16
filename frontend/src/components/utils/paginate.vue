<script setup>
const props = defineProps({
    page: {
        type: Number,
        default: 1,
    },
    data: {
        type: [Array, Object],
        default: () => [],
    },
    to: {
        type: String,
        default: '',
    },
})
</script>
<template>
    <div v-if="data?.data?.length && data.last_page !== 1" class="wrapper">
        <span class="flex items-center col-span-3">Showing {{ data?.from }}-{{ data?.to }} of {{ data?.total }}</span>
        <span class="col-span-2"></span>
        <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
            <nav aria-label="Table navigation">
                <ul class="inline-flex items-center">
                    <li v-for="(link, index) in data?.links" :key="index">
                        <router-link v-if="link.label.includes('Previous')" :disabled="data.current_page === 1" :to="{ name: to, query: { page: data.current_page >= 2 ? data.current_page - 1 : 1 } }" class="paginate-btn">
                            <svg aria-hidden="true" class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                <path d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" fill-rule="evenodd" />
                            </svg>
                        </router-link>
                        <router-link v-else-if="link.label.includes('Next')" :disabled="data.current_page === data.last_page" :to="{ name: to, query: { page: data.current_page <= data.last_page - 1 ? data.current_page + 1 : data.last_page } }" class="paginate-btn">
                            <svg class="w-4 h-4 fill-current" aria-hidden="true" viewBox="0 0 20 20">
                                <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" fill-rule="evenodd" />
                            </svg>
                        </router-link>
                        <button v-else-if="link.label == '...'" class="paginate-btn">
                            <span>{{ link?.label }}</span>
                        </button>
                        <router-link v-else :to="{ name: to, query: { page: parseInt(link.label) } }" class="paginate-btn" :class="{ 'paginate-active': link?.active }">
                            <span>{{ link?.label }}</span>
                        </router-link>
                    </li>
                </ul>
            </nav>
        </span>
    </div>
</template>
<style lang="postcss" scoped>
@tailwind components;

@layer components {
    .wrapper {
        @apply grid px-4 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800;
    }
    .paginate-btn {
        @apply px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple;
    }
}
.paginate-active{
  background-color: #0F6B8C !important;
  color: white;
}
</style>