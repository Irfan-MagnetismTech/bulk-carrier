<script setup>
    import { ref, watch, onMounted } from 'vue';
    import Error from "../../Error.vue";

    const props = defineProps({
        service: { type: Object, required: true },
    });


</script>
<template>
    <div class="border-b border-gray-200 dark:border-gray-700">
        <div class="input-group mb-4">
            <label class="label-group">
                <span class="label-item-title">Service Name <span class="required-style">*</span></span>
                <input type="text" required v-model="service.name" class="label-item-input" name="service_name" :id="'service_name'" />
            </label>
            <!-- <label class="label-group">
                <span class="label-item-title">Vehicle Type<span class="required-style">*</span></span>
                <v-select :options="vehicles" placeholder="--Choose an option--" v-model="service.vehicle_type" label="name" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
            </label> -->
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