<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import useStoreIssueReturn from "../../../composables/supply-chain/useStoreIssueReturn";
import StoreIssueReturnShow from "../../../components/supply-chain/store-issue-returns/StoreIssueReturnShow.vue";
import { useRoute } from 'vue-router';

const { getStoreIssueReturn, showStoreIssueReturn, storeIssueReturn, updateStoreIssueReturn, materialObject, errors, isLoading } = useStoreIssueReturn();

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { setTitle } = Title();
const route = useRoute();
const storeIssueReturnId = route.params.storeIssueReturnId;
const formType = 'edit';

setTitle('Store Requisition Details');

onMounted(() => {
    showStoreIssueReturn(storeIssueReturnId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Store Issue Return</h2>
        <default-button :title="'Opening Stock List'" :to="{ name: 'scm.store-issue-returns.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
            <store-issue-return-show :form="storeIssueReturn"></store-issue-return-show>
    </div>
</template>
