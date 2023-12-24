<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import useStoreIssue from "../../../composables/supply-chain/useStoreIssue";
import StoreIssueForm from "../../../components/supply-chain/store-issues/StoreIssueForm.vue";
import { useRoute } from 'vue-router';

const { getStoreIssue, showStoreIssue, storeIssue, updateStoreIssue,materialObject, errors, isLoading } = useStoreIssue();

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { setTitle } = Title();
const route = useRoute();
const storeIssueId = route.params.storeIssueId;
const formType = 'edit';

setTitle('Update Store Issue');

onMounted(() => {
    showStoreIssue(storeIssueId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Edit Store Issue</h2>
        <default-button :title="'Opening Stock List'" :to="{ name: 'scm.store-issues.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="updateStoreIssue(storeIssue, storeIssueId)">
            <store-issue-form :form="storeIssue" :page="formType" :errors="errors" :formType="formType" :materialObject="materialObject"></store-issue-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>
