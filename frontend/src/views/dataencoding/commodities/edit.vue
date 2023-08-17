<template>
    <div class="flex items-center gap-2 my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200" v-once>
        <span>Update Port:</span>
        <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700">#{{ commodityId }}</span>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="updateCommodity(commodity, commodityId)">
            <!-- Port Form -->
            <commodity-Form v-model:form="commodity"></commodity-Form>
            <!-- Submit button -->
            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update Port</button>
        </form>
    </div>
</template>
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import CommodityForm from '../../../components/dataencoding/CommodityForm.vue'
import useCommodity from '../../../composables/dataencoding/useCommodity';

const route = useRoute();
const commodityId = route.params.commodityId;
const { commodity, showCommodity, updateCommodity, isLoading } = useCommodity();

onMounted(() => {
    showCommodity(commodityId);
});

</script>