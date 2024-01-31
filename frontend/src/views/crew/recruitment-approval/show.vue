<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useRecruitmentApproval from "../../../composables/crew/useRecruitmentApproval";
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import env from '../../../config/env';

const icons = useHeroIcon();

const route = useRoute();
const recruitmentApprovalId = route.params.recruitmentApprovalId;
const { recruitmentApproval, showRecruitmentApproval, errors } = useRecruitmentApproval();

const { setTitle } = Title();

setTitle('Recruitment Approval Details');

onMounted(() => {
  showRecruitmentApproval(recruitmentApprovalId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Recruitment Approval Details # {{recruitmentApprovalId}}</h2>
    <default-button :title="'Recruitment Approval'" :to="{ name: 'crw.recruitmentApprovals.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <div class="flex md:gap-4">
        <div class="w-full">
          <table class="w-full">
            <thead>
            <tr>
              <td class="!text-center text-white font-bold uppercase bg-green-600" colspan="2">Basic Info</td>
            </tr>
            </thead>
            <tbody>
              <tr>
                <th class="w-40 text-left">Business Unit</th>
                <td class="text-left"><span :class="recruitmentApproval?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ recruitmentApproval?.business_unit }}</span></td>
              </tr>
              <tr>
                <th class="w-40 text-left">Applied Date</th>
                <td class="text-left">{{ recruitmentApproval?.applied_date }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Page Title</th>
                <td class="text-left">{{ recruitmentApproval?.page_title }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Subject</th>
                <td class="text-left">{{ recruitmentApproval?.subject }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Total Approved</th>
                <td class="text-left">{{ recruitmentApproval?.total_approved }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Agreed To Join</th>
                <td class="text-left">{{ recruitmentApproval?.crew_agreed_to_join }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Total Selected</th>
                <td class="text-left">{{ recruitmentApproval?.crew_selected }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Total Panel</th>
                <td class="text-left">{{ recruitmentApproval?.crew_panel }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Total Rest</th>
                <td class="text-left">{{ recruitmentApproval?.crew_rest }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Body</th>
                <td class="text-left">{{ recruitmentApproval?.body }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Remarks</th>
                <td class="text-left">{{ recruitmentApproval?.remarks }}</td>
              </tr>
            </tbody>
          </table>
          <table class="w-full mt-1" id="profileDetailTable">
            <thead>
            <tr>
              <td class="!text-center text-white uppercase bg-green-600 font-bold" colspan="8">Candidate List</td>
            </tr>
            <tr>
              <th>#</th>
              <th>Rank</th>
              <th>Candidate Name</th>
              <th>Contact</th>
              <th>Email</th>
              <th>Remarks</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(crewRecruitmentData,index) in recruitmentApproval?.crwRecruitmentApprovalLines" :key="index">
              <td>{{ index + 1 }}</td>
              <td class="text-left">{{ crewRecruitmentData?.crwRank?.name }}</td>
              <td>{{ crewRecruitmentData?.candidate_name }}</td>
              <td>{{ crewRecruitmentData?.candidate_contact }}</td>
              <td>{{ crewRecruitmentData?.candidate_email }}</td>
              <td>{{ crewRecruitmentData?.remarks }}</td>
            </tr>
            </tbody>
            <tfoot v-if="recruitmentApproval?.crwCrewRequisitionLines?.length">
            <tr>
              <td colspan="6">No data found.</td>
            </tr>
            </tfoot>
          </table>
        </div>
      </div>
  </div>
</template>
<style lang="postcss" scoped>
  th, td, tr {
    @apply border-gray-500
  }

  tfoot td{
    @apply text-center
  }

  #profileDetailTable th{
    text-align: center;
  }
  #profileDetailTable thead tr{
    @apply bg-gray-200
  }

</style>