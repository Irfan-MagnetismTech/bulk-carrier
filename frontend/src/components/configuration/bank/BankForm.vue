<script setup>
    import { ref, watch, onMounted } from 'vue';
    import Error from "../../Error.vue";

    const props = defineProps({
        bank: { type: Object, required: true },
        branchObject: { type: Object, required: true },
    });

    function addBranch() {
        props.bank.branches.push({ ...props.branchObject });
    }

    function removeBranch(index) {
        if(props.bank.branches.length>1) {
            props.bank.branches.splice(index, 1);
        }
    }

</script>
<template>
    <div class="border-b border-gray-200 dark:border-gray-700">
        <div class="input-group">
            <label class="label-group">
                <span class="label-item-title">Bank Name <span class="required-style">*</span></span>
                <input type="text" required v-model="bank.name" class="label-item-input" name="bank_name" :id="'bank_name'" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Bank Code <span class="required-style">*</span></span>
                <input type="text" required v-model="bank.code" class="label-item-input" name="bank_code" :id="'bank_code'" />
            </label>
        </div>
    </div>
    <legend class="mt-5">
            <strong>Branches</strong>
            <table class="w-full">
                <tr class="" v-for="(item, index) in bank.branches" :key="index">
                    
                    <td class="font-semibold">
                        <span class="label-item-title">Name <span class="required-style">*</span></span>
                        <input type="text" required v-model="bank.branches[index].name" class="label-item-input" name="opening_balance" :id="'opening_balance'" />
                    </td>
                    <td class="font-semibold">
                        <span class="label-item-title">Code</span>
                        <input type="text" v-model="bank.branches[index].branch_code" class="label-item-input" name="opening_balance" :id="'opening_balance'" />
                    </td>
                    <td class="font-semibold">
                        <span class="label-item-title">Swift Code</span>
                        <input type="text" v-model="bank.branches[index].swift_code" class="label-item-input" name="opening_balance" :id="'opening_balance'" />
                    </td>
                    <td class="font-semibold">
                        <span class="label-item-title">Routing Number</span>
                        <input type="text" v-model="bank.branches[index].routing_number" class="label-item-input" name="opening_balance" :id="'opening_balance'" />
                    </td>
                    <td class="font-semibold flex flex-col">
                        <span class="label-item-title">Action</span>
                        <button v-if="index == 0" type="button" @click="addBranch(index)" :disabled="isLoading" class="flex items-center justify-center px-4 py-2 mt-1 text-sm leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg fon2t-medium mt- active:bg-[#0F6B90] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">+</button>
                        <button v-else type="button" @click="removeBranch(index)" :disabled="isLoading" class="flex items-center justify-center px-4 py-2 mt-1 text-sm leading-5 text-white transition-colors duration-150 bg-red-500 border border-transparent rounded-lg fon2t-medium mt- active:bg-[#0F6B90] hover:bg-red-800 focus:outline-none focus:shadow-outline-purple">-</button>
                    </td>
                </tr>
            </table>
    </legend>
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