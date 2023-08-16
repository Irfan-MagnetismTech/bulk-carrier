<script setup>
    import { ref, watch, onMounted } from 'vue';
    import Error from "../../Error.vue";

    const props = defineProps({
        vendor: { type: Object, required: true }
    });

</script>
<template>
    <div class="border-b border-gray-200 dark:border-gray-700 pb-5">
        <div class="input-group">
            <label class="label-group">
                <span class="label-item-title">Vendor Name <span class="required-style">*</span></span>
                <input type="text" required v-model="vendor.name" class="label-item-input" name="vendor_name" :id="'vendor_name'" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Mobile</span>
                <input type="text" v-model="vendor.mobile" class="label-item-input" name="vendor_mobile" :id="'vendor_mobile'" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Email</span>
                <input type="text" v-model="vendor.email" class="label-item-input" name="vendor_email" :id="'vendor_email'" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Address</span>
                <input type="text" v-model="vendor.address" class="label-item-input" name="vendor_address" :id="'vendor_address'" />
            </label>
        </div>
        <div class="input-group">
<!--            <label class="label-group">-->
<!--                <span class="label-item-title" for="vendor_type">Vendor Type  <span class="required-style">*</span></span>-->
<!--                <select v-model="vendor.vendor_type" name="vendor_type" id="vendor_type" class="label-item-input">-->
<!--                    <option value="Local">Local</option>-->
<!--                    <option value="Foreign">Foreign</option>-->
<!--                </select>-->
<!--            </label>-->
            <label class="label-group">
                <span class="label-item-title">Credit Limit</span>
                <input type="number" step="0.01" v-model="vendor.credit_limit" class="label-item-input" name="credit_limit" :id="'credit_limit'" />
            </label>
<!--            <label class="label-group">-->
<!--                <span class="label-item-title">Status <span class="required-style">*</span></span>-->
<!--                <div class="my-3 flex">-->
<!--                    <div class="flex items-center">-->
<!--                        <input type="radio" value="1" v-model="vendor.status" class="" name="status" :id="'not_active'" />-->
<!--                        <label class="ml-2" for="not_active">Active</label>-->
<!--                    </div>-->
<!--                    <div class="flex items-center ml-8">-->
<!--                        <input type="radio" value="0" v-model="vendor.status" class="" name="status" :id="'active'" />-->
<!--                        <label class="ml-2" for="active">Inactive</label>-->
<!--                    </div>-->

<!--                </div>-->
<!--            </label>-->
            <label class="label-group">
                <span class="label-item-title">Is Fuel Vendor?</span>
                <div class="my-3 flex">
                    <div class="flex items-center">
                        <input type="radio" value="1" v-model="vendor.is_fuel_vendor" class="" name="is_fuel_vendor" :id="'fuel_vendor'" />
                        <label class="ml-2" for="fuel_vendor">Yes</label>
                    </div>
                    <div class="flex items-center ml-8">
                        <input type="radio" value="0" v-model="vendor.is_fuel_vendor" class="" name="is_fuel_vendor" :id="'not_fuel_vendor'" />
                        <label class="ml-2" for="not_fuel_vendor">No</label>
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