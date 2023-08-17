
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import Title from '../../../services/title';
import useVessel from '../../../composables/dataencoding/useVessel';
import Heading from '../../../components/Heading.vue';

const route = useRoute();
const vesselId = route.params.vesselId;
const { vessel, isLoading, showVessel } = useVessel();
const { setTitle } = Title();

setTitle('Vessel Details');

onMounted(() => {
    showVessel(vesselId);
});
</script>
<template>
    <!-- Heading -->
    <heading label="Vessel Details" type="list" :to="{ name: 'dataencoding.vessels.index' }"></heading>
    <div v-if="isLoading" class="px-4 py-3 mb-8 bg-white divide-y rounded-lg shadow-md dark:bg-gray-800 text-center">
        <span class="text-base text-gray-600 dark:text-gray-400">Loading...</span>
    </div>
    <div v-else class="px-8 py-3 mb-8 bg-white divide-y rounded-lg shadow-md dark:bg-gray-800 dark:divide-gray-600">
        <!-- Name -->
        <div class="text-center py-2">
            <span class="text-lg font-medium text-gray-600 dark:text-gray-200">{{ vessel?.name }}</span>
        </div>
        <!-- Flag, Year Built & Call Sign -->
        <div class="mb-4 group">
            <div class="group-item">
                <span class="group-title" v-once>Flag :</span>
                <span class="group-content">{{ vessel?.flag }}</span>
            </div>
            <div class="group-item">
                <span class="group-title" v-once>Year Built :</span>
                <span class="group-content">{{ vessel?.year_built }}</span>
            </div>
            <div class="group-item">
                <span class="group-title" v-once>Call Sign :</span>
                <span class="group-content">{{ vessel?.call_sign }}</span>
            </div>
        </div>
        <!-- GRT, NRT & DWT -->
        <div class="mb-4 group">
            <div class="group-item">
                <span class="group-title">GRT:</span>
                <span class="group-content">{{ vessel?.grt }}</span>
            </div>
            <div class="group-item">
                <span class="group-title">NRT:</span>
                <span class="group-content">{{ vessel?.nrt }}</span>
            </div>
            <div class="group-item">
                <span class="group-title">DWT:</span>
                <span class="group-content">{{ vessel?.dwt }}</span>
            </div>
        </div>
        <!-- Speed, Capacity teus, Reefer -->
        <div class="mb-4 group">
            <div class="group-item">
                <span class="group-title">Speed (Knots):</span>
                <span class="group-content">{{ vessel?.speed }}</span>
            </div>
            <div class="group-item">
                <span class="group-title">Capacity TEUs:</span>
                <span class="group-content">{{ vessel?.capacity_tues }}</span>
            </div>
            <div class="group-item">
                <span class="group-title">Reefer (420V) / 110V:</span>
                <span class="group-content">{{ vessel?.reefer_capacity }}</span>
            </div>
        </div>
        <!-- IMO, classification -->
        <div class="mb-4 group">
            <div class="group-item">
                <span class="group-title">IMO/Lloyd Number:</span>
                <span class="group-content">{{ vessel?.imo_number }}</span>
            </div>
            <div class="group-item">
                <span class="group-title">Classification:</span>
                <span class="group-content">{{ vessel?.classification }}</span>
            </div>
            <div class="group-empty"></div>
        </div>
        <!-- Remarks -->
        <div class="w-full mb-4">
            <div class="flex-col group-item">
                <span class="underline group-title md:no-underline">Remarks</span>
                <br />
                <span class="group-content ml-0">{{ vessel?.remarks }}</span>
            </div>
        </div>
    </div>
</template>
<style lang="postcss" scoped>
@tailwind components;

@layer components {
    .group {
        @apply flex flex-col items-center justify-center w-full md:flex-row md:gap-2;
    }
    .group-item {
        @apply md:block w-full mt-3 text-sm flex items-center justify-between;
    }
    .group-title {
        @apply font-semibold uppercase text-gray-600 dark:text-gray-200;
    }
    .group-content {
        @apply ml-1 dark:text-gray-300 md:text-xs;
    }
    .group-empty {
        @apply hidden md:block md:w-full;
    }
    .label-item-title {
        @apply text-gray-700 dark:text-gray-300;
    }
    .label-item-input {
        @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
    }
    .form-input {
        @apply block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
    }
}
</style>