<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import useStoreIssueReturn from "../../../composables/supply-chain/useStoreIssueReturn";
import StoreIssueReturnForm from "../../../components/supply-chain/store-issue-returns/StoreIssueReturnForm.vue";
import { useRoute } from 'vue-router';

const { getStoreIssueReturn, showStoreIssueReturn, storeIssueReturn, updateStoreIssueReturn,materialObject, errors, isLoading } = useStoreIssueReturn();

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { setTitle } = Title();
const route = useRoute();
const storeIssueReturnId = route.params.storeIssueReturnId;
const formType = 'edit';

setTitle('Update Store Issue Return');

onMounted(() => {
    showStoreIssueReturn(storeIssueReturnId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Edit Store Issue Return</h2>
        <default-button :title="'Store Issue Return List'" :to="{ name: 'scm.store-issue-returns.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="updateStoreIssueReturn(storeIssueReturn, storeIssueReturnId)">
            <store-issue-return-form :form="storeIssueReturn" :page="formType" :errors="errors" :formType="formType" :materialObject="materialObject"></store-issue-return-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>
