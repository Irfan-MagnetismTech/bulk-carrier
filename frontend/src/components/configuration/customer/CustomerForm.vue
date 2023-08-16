<script setup>
    import { ref, watch, onMounted } from 'vue';
    import Error from "../../Error.vue";

    const props = defineProps({
        customer: { type: Object, required: true }
    });

</script>
<template>
    <div class="border-b border-gray-200 dark:border-gray-700 pb-5">
        <div class="input-group">
            <label class="label-group">
                <span class="label-item-title">Customer Name <span class="required-style">*</span></span>
                <input type="text" required v-model="customer.name" class="label-item-input" name="customer_name" :id="'customer_name'" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Customer Code <span class="required-style">*</span></span>
                <input type="text" required v-model="customer.code" class="label-item-input" name="customer_mobile" :id="'customer_mobile'" />
            </label>
            
            <label class="label-group">
                <span class="label-item-title">Address</span>
                <input type="text" v-model="customer.address" class="label-item-input" name="customer_address" :id="'customer_address'" />
            </label>
        </div>
        <div class="input-group">
<!--            <label class="label-group">-->
<!--                <span class="label-item-title">Official Email</span>-->
<!--                <input type="email" v-model="customer.official_email" class="label-item-input" name="official_email" :id="'official_email'" />-->
<!--            </label>-->
            <label class="label-group">
                <span class="label-item-title">Billing Contact</span>
                <input type="text" v-model="customer.official_contact" class="label-item-input" name="official_contact" :id="'official_contact'" />
            </label>
          <label class="label-group">
            <span class="label-item-title">Billing Email</span>
            <input type="email" v-model="customer.billing_email" class="label-item-input" name="billing_email" :id="'billing_email'" />
          </label>
          <label class="label-group">
            <span class="label-item-title">Billing Address</span>
            <input type="text" v-model="customer.billing_address" class="label-item-input" name="billing_address" :id="'billing_address'" />
          </label>
        </div>
        <div class="input-group">
            <label class="label-group">
                <span class="label-item-title">Contact Person</span>
                <input type="text" v-model="customer.contact_person" class="label-item-input" name="contact_person" :id="'contact_person'" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Contact Person Mobile</span>
                <input type="text" v-model="customer.contact_person_mobile" class="label-item-input" name="contact_person_mobile" :id="'contact_person_mobile'" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Remarks</span>
                <input type="text" v-model="customer.remarks" class="label-item-input" name="remarks" :id="'remarks'" />
            </label>
        </div>
        <div class="input-group">
            <label class="label-group">
                <span class="label-item-title">Opening Date</span>
                <input type="date" v-model="customer.opening_date" class="label-item-input" name="opening_date" :id="'opening_date'" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Opening Due</span>
                <input type="number" step="0.01" v-model="customer.opening_due" class="label-item-input" name="opening_due" :id="'opening_due'" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Advanced Amount</span>
                <input type="number" step="0.01" v-model="customer.deposited_amount" class="label-item-input" name="opening_due" :id="'opening_due'" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Credit Limit</span>
                <input type="number" step="0.01" v-model="customer.credit_limit" class="label-item-input" name="opening_due" :id="'opening_due'" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Credit Days</span>
                <input type="number" v-model="customer.credit_days" class="label-item-input" name="opening_due" :id="'opening_due'" />
            </label>
        </div>
        <div class="input-group">

            <div class="label-group">
                <span class="label-item-title">Status</span>
                <div class="my-3 flex">
                    <div class="flex items-center">
                        <input type="radio" value="1" v-model="customer.status" class="" name="status" :id="'not_active'" />
                        <label class="ml-2" for="not_active">Active</label>
                    </div>
                    <div class="flex items-center ml-8">
                        <input type="radio" value="0" v-model="customer.status" class="" name="status" :id="'active'" />
                        <label class="ml-2" for="active">Inactive</label>
                    </div>
                </div>
            </div>
            <label class="label-group" v-if="customer.status == 0">
                <span class="label-item-title">Returned Amount </span>
                <input type="number" step="0.01" v-model="customer.returned_amount" class="label-item-input !w-1/2" name="opening_due" :id="'opening_due'" />
            </label>
            <div class="label-group">
                <span class="label-item-title">Is Corporate Customer?</span>
                <div class="my-3 flex">
                    <div class="flex items-center">
                        <input type="radio" value="1" v-model="customer.is_corporate" class="" name="is_corporate" :id="'not_corporate'" />
                        <label class="ml-2" for="not_corporate">Yes</label>
                    </div>
                    <div class="flex items-center ml-8">
                        <input type="radio" value="0" v-model="customer.is_corporate" class="" name="is_corporate" :id="'corporate'" />
                        <label class="ml-2" for="corporate">No</label>
                    </div>

                </div>
            </div>
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