<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import useWorkCs from "../../../composables/supply-chain/useWorkCs";
import useHelper from "../../../composables/useHelper.js";
import WorkCsForm from "../../../components/supply-chain/work-cs/WorkCsForm.vue";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { workCs, getWorkCs, storeWorkCs, errors, isLoading, workObj, workList} = useWorkCs();
const page = ref('create');
const { setTitle } = Title();

const props = defineProps({
    pr_id: {
    type: Number,
    default: 1,
  },
});

// onMounted(() => {
//     getPrWiseCs(props.pr_id);
// }); 

setTitle('Create Work CS');
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Create Work CS</h2>
        <default-button :title="'Work CS List'" :to="{ name: 'scm.work-cs.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800 overflow-hidden">
        <form @submit.prevent="storeWorkCs(workCs)">
          <work-cs-form v-model:form="workCs" :errors="errors" :page="page" :workObj="workObj" :workList="workList"></work-cs-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
        </form>
        
    </div>
</template>
