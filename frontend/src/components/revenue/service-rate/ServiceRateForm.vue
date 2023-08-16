<script setup>
    import { ref, watch, onMounted } from 'vue';
    import Error from "../../Error.vue";
    import useService from "../../../composables/revenue/useService.js";

    const props = defineProps({
        serviceRate: { type: Object, required: true }
    });
    const { service, services, searchService } = useService();
    function fetchService(query, loading) {
        searchService(query, loading);
        loading(true)
    }

    watch(() => props.serviceRate.service, (value) => {
        props.serviceRate.service_id = value?.id;
    });

</script>
<template>
    <div class="border-b border-gray-200 dark:border-gray-700">
        <div class="input-group mb-4">
            <label class="label-group">
                <span class="label-item-title">Service <span class="required-style">*</span></span>
                <v-select :options="services" placeholder="--Choose an option--" @search="fetchService"  v-model="serviceRate.service" label="name" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                  <template #search="{attributes, events}">
                    <input
                        class="vs__search"
                        :required="!serviceRate.service"
                        v-bind="attributes"
                        v-on="events"
                    />
                  </template>
                </v-select>
                <input type="hidden" required v-model="serviceRate.service_id" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Rate <span class="required-style">*</span></span>
                <input type="number" required step="0.01" v-model="serviceRate.rate" class="label-item-input" name="mobile" :id="'mobile'" />
            </label>
        </div>
    </div>
</template>
<style lang="postcss" scoped>
    .input-group {
        @apply flex flex-col justify-center w-full md:flex-row md:gap-2;
    }
    .label-group {
        @apply block w-full mt-3 text-sm font-semibold;
    }
    .label-item-title {
        @apply text-gray-700 dark:text-gray-300;
    }
    .label-item-input {
        @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
    }
    .form-input {
        @apply block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
    }
    .vs__selected{
    display: none !important;
    }
    .required-style{
        @apply text-red-400 font-semibold
    }

</style>