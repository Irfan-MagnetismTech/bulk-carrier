<script setup>
    import { ref, watch, onMounted } from 'vue';
    import Error from "../../Error.vue";

    const props = defineProps({
        bankAccount: { type: Object, required: true },
    });

</script>
<template>
    <div class="border-b border-gray-200 dark:border-gray-700 pb-5">
        <legend>
            <strong>Bank Info</strong>
            <div class="input-group">
                <label class="label-group">
                    <span class="label-item-title">Bank Name<span class="required-style">*</span></span>
                    <input type="text" v-model="bankAccount.bank_name" class="label-item-input" name="bank_name" :id="'bank_name'" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Branch Name<span class="required-style">*</span></span>
                    <input type="text" v-model="bankAccount.branch_name" class="label-item-input" name="branch_name" :id="'branch_name'" />
                </label>
            </div>
        </legend>
        <legend class="mt-5">
            <strong>Account Owner Info</strong>
            <div class="input-group">
            
                <label class="label-group">
                    <span class="label-item-title">Account Name<span class="required-style">*</span></span>
                    <input type="text" v-model="bankAccount.account_name" class="label-item-input" name="account_name" :id="'account_name'" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Account Holder<span class="required-style">*</span></span>
                    <input type="text" v-model="bankAccount.account_holder" class="label-item-input" name="account_holder" :id="'account_holder'" />
                </label>

                <label class="label-group">
                    <span class="label-item-title">Contact Number<span class="required-style">*</span></span>
                    <input type="text" v-model="bankAccount.contact_number" class="label-item-input" name="contact_number" :id="'contact_number'" />
                </label>
            </div>
            <div class="input-group">
                <label class="label-group">
                    <span class="label-item-title">Account Number <span class="required-style">*</span></span>
                    <input type="text" v-model="bankAccount.account_number" class="label-item-input" name="account_number" :id="'account_number'" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Routing Number <span class="required-style">*</span></span>
                    <input type="text" v-model="bankAccount.routing_number" class="label-item-input" name="routing_number" :id="'routing_number'" />
                </label>
            </div>
        </legend>
        <legend class="mt-5">
            <strong>Account Info</strong>
            <div class="input-group">

                <label class="label-group">
                    <span class="label-item-title">Opening Date<span class="required-style">*</span></span>
                    <input type="date" v-model="bankAccount.opening_date" class="label-item-input" name="opening_date" :id="'opening_date'" />
                </label>

                <label class="label-group">
                    <span class="label-item-title">Opening Balance<span class="required-style">*</span></span>
                    <input type="number" step="0.01" v-model="bankAccount.opening_balance" class="label-item-input" name="opening_balance" :id="'opening_balance'" />
                </label>
                
            </div>
        </legend>
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