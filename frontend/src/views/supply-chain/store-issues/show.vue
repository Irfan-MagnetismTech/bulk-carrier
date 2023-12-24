<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";

import useStoreIssue from "../../../composables/supply-chain/useStoreIssue";
import StoreRequisitionShow from "../../../components/supply-chain/store-requisitions/StoreRequisitionShow.vue";
import StoreIssueShow from "../../../components/supply-chain/store-issues/StoreIssueShow.vue";
import { useRoute } from 'vue-router';

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
const icons = useHeroIcon();

const { getStoreIssue, showStoreIssue, storeIssue, updateStoreIssue,materialObject, errors, isLoading } = useStoreIssue();
const { setTitle } = Title();
const route = useRoute();
const storeIssueId = route.params.storeIssueId;
const formType = 'edit';

setTitle('Store Issue Show');

onMounted(() => {
    showStoreIssue(storeIssueId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Store Issue Show</h2>
        <default-button :title="'Store Issue List'" :to="{ name: 'scm.store-issues.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800 overflow-hidden">
            <store-issue-show :form="storeIssue"></store-issue-show>
    </div>
</template>
