<template>
    <div class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2">
        <span>Update Cost Head:</span>
        <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700">#{{ headId }}</span>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="updateHead(expenditureHead, headId)">
            <!-- Booking Form -->
          <voyage-expenditure-head-form v-model:form="expenditureHead" :errors="errors"></voyage-expenditure-head-form>
            <!-- Submit button -->
            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update Expenditure Head</button>
        </form>
    </div>
</template>
<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';
import VoyageExpenditureHeadForm from '../../../components/finance/VoyageExpendtiureHeadForm.vue';
import useVoyageExpenditureHead from "../../../composables/finance/useVoyageExpenditureHead";
import Title from "../../../services/title";

const route = useRoute();
const headId = route.params.headId;
const { expenditureHead, showHead, updateHead, errors } = useVoyageExpenditureHead();

const { setTitle } = Title();

setTitle('Edit Cost Head');

// watch(service, (value) => {
//   if(value?.sectors?.length) {
//     value.sectors.forEach((sector, index) => {
//       service.value.sectors[index].pol_code_name = sector?.sector_pol ?? '';
//       service.value.sectors[index].pod_code_name = sector?.sector_pod ?? '';
//     });
//   }
// });

onMounted(() => {
    showHead(headId);
});
</script>