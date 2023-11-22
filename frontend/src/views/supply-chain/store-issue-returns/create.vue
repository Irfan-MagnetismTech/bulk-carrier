<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import useStoreIssueReturn from "../../../composables/supply-chain/useStoreIssueReturn";
import useHelper from "../../../composables/useHelper.js";
import StoreIssueReturnForm from "../../../components/supply-chain/store-issue-returns/StoreIssueReturnForm.vue";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { getStoreIssueReturn, storeIssueReturn, storeStoreIssueReturn,materialObject, errors, isLoading } = useStoreIssueReturn();
const page = ref('create');
const { setTitle } = Title();



setTitle('Create Store Issue Return');
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Create Store Issue Return</h2>
        <default-button :title="'PR List'" :to="{ name: 'scm.store-issue-returns.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="storeStoreIssueReturn(storeIssueReturn)">
          <store-issue-return-form v-model:form="storeIssueReturn" :errors="errors" :materialObject="materialObject" :page="page"></store-issue-return-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
        </form>
        
    </div>
</template>
