<script setup>
    import { ref, watch, onMounted } from 'vue';
    import Error from "../../Error.vue";

    const props = defineProps({
        feedingUnit: { type: Object, required: true },
        units: { type: Object, required: true },
    });

</script>
<template>
    <div class="border-b border-gray-200 dark:border-gray-700 pb-5">
        <div class="input-group">
            
            <label class="label-group">
                <span class="label-item-title" for="feedingUnit_name">Select Unit Name  <span class="required-style">*</span></span>
                <select v-model="feedingUnit.name" name="feedingUnit_name" id="feedingUnit_name" class="label-item-input">
                    <option v-for="unit, index in units">{{ unit }}</option>
                </select>
            </label>
            <label class="label-group">
                <span class="label-item-title">Quantity<span class="required-style">*</span></span>
                <input type="text" v-model="feedingUnit.quantity" class="label-item-input" name="feedingUnit_quantity" :id="'feedingUnit_quantity'" />
            </label>
            <label class="label-group">
                <span class="label-item-title">DIP MM <span class="required-style">*</span></span>
                <input type="text" v-model="feedingUnit.dip_mm" class="label-item-input" name="feedingUnit_dip_mm" :id="'feedingUnit_dip_mm'" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Status <span class="required-style">*</span></span>
                <div class="my-3 flex">
                    <div class="flex items-center">
                        <input type="radio" value="1" v-model="feedingUnit.status" class="" name="status" :id="'not_active'" />
                        <label class="ml-2" for="not_active">Active</label>
                    </div>
                    <div class="flex items-center ml-8">
                        <input type="radio" value="0" v-model="feedingUnit.status" class="" name="status" :id="'active'" />
                        <label class="ml-2" for="active">Inactive</label>
                    </div>

                </div>
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