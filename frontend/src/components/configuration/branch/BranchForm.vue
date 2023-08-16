<script setup>
    import { ref, watch, onMounted } from 'vue';
    import Error from "../../Error.vue";

    const props = defineProps({
        branch: { type: Object, required: true }
    });

</script>
<template>
    <div class="border-b border-gray-200 dark:border-gray-700">
        <div class="input-group">
            <label class="label-group">
                <span class="label-item-title">Branch Name <span class="required-style">*</span></span>
                <input type="text" v-model="branch.name" class="label-item-input" name="branch_name" :id="'branch_name'" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Branch Code<span class="required-style">*</span></span>
                <input type="text" v-model="branch.branch_code" class="label-item-input" name="branch_code" :id="'branch_code'" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Mobile <span class="required-style">*</span></span>
                <input type="text" v-model="branch.mobile" class="label-item-input" name="mobile" :id="'mobile'" />
            </label>
        </div>
        <div class="input-group">
            <label class="label-group">
                <span class="label-item-title">Status <span class="required-style">*</span></span>
                <div class="my-3 flex">
                    <div class="flex items-center">
                        <input type="radio" value="1" v-model="branch.is_active" class="" name="is_active" :id="'not_active'" />
                        <label class="ml-2" for="not_active">Active</label>
                    </div>
                    <div class="flex items-center ml-8">
                        <input type="radio" value="0" v-model="branch.is_active" class="" name="is_active" :id="'active'" />
                        <label class="ml-2" for="active">Inactive</label>
                    </div>

                </div>
            </label>
            <label class="label-group">
                <span class="label-item-title">Is Corporate Wings? <span class="required-style">*</span></span>
                <div class="my-3 flex">
                    <div class="flex items-center">
                        <input type="radio" value="1" v-model="branch.is_corporate" class="" name="is_corporate" :id="'not_corporate'" />
                        <label class="ml-2" for="not_corporate">Yes</label>
                    </div>
                    <div class="flex items-center ml-8">
                        <input type="radio" value="0" v-model="branch.is_corporate" class="" name="is_corporate" :id="'corporate'" />
                        <label class="ml-2" for="corporate">No</label>
                    </div>

                </div>
            </label>
        </div>
        <div class="input-group mb-4">
            
            <label class="label-group">
                <span class="label-item-title">Address<span class="required-style">*</span></span>
                <textarea type="text" v-model="branch.address" class="label-item-input" name="port_code" :id="'port_code'"></textarea>
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