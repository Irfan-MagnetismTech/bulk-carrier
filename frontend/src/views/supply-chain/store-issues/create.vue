<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import useStoreIssue from "../../../composables/supply-chain/useStoreIssue";
import useHelper from "../../../composables/useHelper.js";
import StoreIssueForm from "../../../components/supply-chain/store-issues/StoreIssueForm.vue";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { getStoreIssue, storeIssue, storeStoreIssue,getSrWiseSi,materialObject, errors, isLoading } = useStoreIssue();
const page = ref('create');
const { setTitle } = Title();
const props = defineProps({
  sr_id: {
    type: Number,
    default: 1,
    },
});


onMounted(() => {
    getSrWiseSi(props.sr_id);
}); 


setTitle('Create Store Issue');
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Create Store Issue</h2>
        <default-button :title="'PR List'" :to="{ name: 'scm.store-issues.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800 overflow-hidden">
        <form @submit.prevent="storeStoreIssue(storeIssue)">
          <store-issue-form v-model:form="storeIssue" :errors="errors" :materialObject="materialObject" :page="page"></store-issue-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
        </form>
        
    </div>
</template>
