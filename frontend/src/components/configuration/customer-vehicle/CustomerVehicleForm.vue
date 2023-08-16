<script setup>
    import { ref, watch, onMounted } from 'vue';
    import Error from "../../Error.vue";
    import useCustomer from "../../../composables/configuration/useCustomer.js";


    const { customers, searchCustomerByCode, customer } = useCustomer();

    const props = defineProps({
        customerVehicle: { type: Object, required: true },
        vehicleTypes: { type: Object, required: true },
    });

    function fetchCustomer(query, loading) {
        searchCustomerByCode(query, loading);
        loading(true)
    }

    watch(() => props.customerVehicle.customer, (value) => {
        props.customerVehicle.customer_id = value?.id;
        props.customerVehicle.customer_name = value?.name;
    });

</script>
<template>
    <div class="border-b border-gray-200 dark:border-gray-700 pb-5">
        <legend>
            <div class="input-group">
                <label class="label-group">
                    <span class="label-item-title">Customer Code <span class="required-style">*</span></span>
                    <v-select :options="customers" placeholder="--Choose an option--" @search="fetchCustomer"  v-model="customerVehicle.customer" label="code" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                      <template #search="{attributes, events}">
                        <input
                            class="vs__search"
                            :required="!customerVehicle.customer"
                            v-bind="attributes"
                            v-on="events"
                        />
                      </template>
                    </v-select>
                    <input type="hidden" required v-model="customerVehicle.customer_id" class="label-item-input" name="customer_id" :id="'customer_id'" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Customer Name</span>
                    <input type="text" readonly v-model="customerVehicle.customer_name" class="bg-gray-100 label-item-input" name="customer_id" :id="'customer_id'" />
                </label>
<!--                <label class="label-group">-->
<!--                    <span class="label-item-title">Vehicle Type </span>-->
<!--                    <v-select :options="vehicleTypes" placeholder="&#45;&#45;Choose an option&#45;&#45;" v-model="customerVehicle.vehicle_type" label="name" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>-->
<!--                </label>-->
            </div>
        </legend>
        <legend class="mt-5">
            <div class="input-group">
              <label class="label-group">
                <span class="label-item-title">Vehicle Number <span class="required-style">*</span></span>
                <input type="text" required v-model="customerVehicle.vehicle_number" class="label-item-input" name="vehicle_number" :id="'branch_name'" />
              </label>
                <label class="label-group">
                    <span class="label-item-title">Responsible Person Name</span>
                    <input type="text" v-model="customerVehicle.assigned_person" class="label-item-input" name="assigned_person" :id="'assigned_person'" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Responsible Person Number</span>
                    <input type="text" v-model="customerVehicle.assigned_person_contact" class="label-item-input" name="assigned_person_contact" :id="'assigned_person_contact'" />
                </label>

<!--                <label class="label-group">-->
<!--                <span class="label-item-title">Status <span class="required-style">*</span></span>-->
<!--                <div class="my-3 flex">-->
<!--                    <div class="flex items-center">-->
<!--                        <input type="radio" value="1" v-model="customerVehicle.status" class="" name="is_active" :id="'not_active'" />-->
<!--                        <label class="ml-2" for="not_active">Active</label>-->
<!--                    </div>-->
<!--                    <div class="flex items-center ml-8">-->
<!--                        <input type="radio" value="0" v-model="customerVehicle.status" class="" name="is_active" :id="'active'" />-->
<!--                        <label class="ml-2" for="active">Inactive</label>-->
<!--                    </div>-->

<!--                </div>-->
<!--            </label>-->
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