<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';

import RecruitmentApprovalForm from '../../../components/crew/RecruitmentApprovalForm.vue';
import useRecruitmentApproval from '../../../composables/crew/useRecruitmentApproval';
import Title from "../../../services/title";

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const route = useRoute();
const recruitmentApprovalId = route.params.recruitmentApprovalId;
const { recruitmentApproval, showRecruitmentApproval, updateRecruitmentApproval, errors } = useRecruitmentApproval();
const icons = useHeroIcon();

const { setTitle } = Title();

setTitle('Edit Recruitment Approval');

onMounted(() => {
  showRecruitmentApproval(recruitmentApprovalId);
});
</script>
<template>
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 ">Update Recruitment Approval</h2>
    <default-button :title="'Crew Recruitment Approval List'" :to="{ name: 'crw.recruitmentApprovals.index' }" :icon="icons.DataBase"></default-button>
  </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md ">
        <form @submit.prevent="updateRecruitmentApproval(recruitmentApproval, recruitmentApprovalId)">
            <!-- Booking Form -->
          <recruitment-approval-form v-model:form="recruitmentApproval" :errors="errors"></recruitment-approval-form>
            <!-- Submit button -->
            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>