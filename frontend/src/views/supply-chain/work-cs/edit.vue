<script setup>
import {ref,onMounted, watch} from "vue";

import Title from "../../../services/title";
import useWorkCs from "../../../composables/supply-chain/useWorkCs";
import WorkCsForm from "../../../components/supply-chain/work-cs/WorkCsForm.vue";
import { useRoute } from 'vue-router';

const { getWorkCs, showWorkCs, workCs, updateWorkCs, errors, isLoading,serviceObj,serviceList, getWrWiseServiceList, wrServiceList} = useWorkCs();
// const { getPrWiseMaterialList, prMaterialList } = useMaterialCs();

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { setTitle } = Title();
const route = useRoute();
const workCsId = route.params.workCsId;
const formType = 'edit';

setTitle('Edit Work CS');

onMounted(() => {
    showWorkCs(workCsId);
});

watch(() => workCs, (val) => {
    //foreach materialCS.scmCsMaterials
    val.scmWcsServices.forEach((scmWcsService, index) => {
        serviceList.push([]);
        getWrWiseServiceList(scmWcsService.scm_wr_id).then((res) => {
            serviceList[index] = res;
        });
    });
});
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Edit Work CS</h2>
        <default-button :title="'Work CS List'" :to="{ name: 'scm.work-cs.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800 overflow-hidden">
        <form @submit.prevent="updateWorkCs(workCs, workCsId)">
            <work-cs-form :form="workCs" :page="formType" :errors="errors" :formType="formType" :serviceObj="serviceObj" :serviceList="serviceList"></work-cs-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>
