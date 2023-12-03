<script setup>
import Error from "../Error.vue";
import useCrewCommonApiRequest from "../../composables/crew/useCrewCommonApiRequest";
import useAgencyContract from "../../composables/crew/useAgencyContract";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import {onMounted, ref, watchEffect} from "vue";
import Store from "../../store";
import ErrorComponent from '../../components/utils/ErrorComponent.vue';
import RemarksComponent from "../utils/RemarksComponent.vue";

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
const { crwRankLists, getCrewRankLists } = useCrewCommonApiRequest();

function addItem() {
  let obj = {
    crw_rank_id: '',
    candidate_name: '',
    candidate_contact: '',
    candidate_email: '',
    remarks: '',
  };
  props.form.crwRecruitmentApprovalLines.push(obj);
}

function removeItem(index){
  props.form.crwRecruitmentApprovalLines.splice(index, 1);
}

onMounted(() => {
  watchEffect(() => {
    getCrewRankLists(props.form.business_unit);
  });
});

</script>

<template>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <business-unit-input v-model="form.business_unit"></business-unit-input>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
  </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Applied Date <span class="text-red-500">*</span></span>
        <input type="date" v-model.trim="form.applied_date" class="form-input" autocomplete="off" required />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Page Title <span class="text-red-500">*</span></span>
        <input type="text" v-model.trim="form.page_title" placeholder="Page Title" class="form-input" autocomplete="off" required />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Subject <span class="text-red-500">*</span></span>
        <input type="text" v-model.trim="form.subject" placeholder="Subject" class="form-input" autocomplete="off" required />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Total Approved <span class="text-red-500">*</span></span>
        <input type="number" v-model.trim="form.total_approved" placeholder="Ex: 10" class="form-input" autocomplete="off" required />
      </label>
    </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Agreed To Join <span class="text-red-500">*</span></span>
      <input type="number" v-model.trim="form.crew_agreed_to_join" placeholder="Ex: 5" class="form-input" autocomplete="off" required />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Total Selected <span class="text-red-500">*</span></span>
      <input type="number" v-model.trim="form.crew_selected" placeholder="Ex: 4" class="form-input" autocomplete="off" required />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Total Panel <span class="text-red-500">*</span></span>
      <input type="number" v-model.trim="form.crew_panel" placeholder="Ex: 4" class="form-input" autocomplete="off" required />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Total Rest <span class="text-red-500">*</span></span>
      <input type="number" v-model.trim="form.crew_rest" placeholder="Ex: 1" class="form-input" autocomplete="off" required />
    </label>
  </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <!-- <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Body <span class="text-red-500">*</span></span>
      <textarea v-model.trim="form.body" placeholder="Type here....." class="form-input" autocomplete="off" required></textarea>
    </label> -->
    <RemarksComponent :isRequired="true" v-model.trim="form.body" :maxlength="500" :fieldLabel="'Body'"></RemarksComponent>
    <RemarksComponent v-model.trim="form.remarks" :maxlength="500" :fieldLabel="'Remarks'"></RemarksComponent>
  </div>
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
    <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Candidate List</legend>
    <table class="w-full whitespace-no-wrap" id="table">
      <thead>
      <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
        <th class="px-4 py-3 align-bottom">Rank <span class="text-red-500">*</span></th>
        <th class="px-4 py-3 align-bottom">Candidate Name <span class="text-red-500">*</span></th>
        <th class="px-4 py-3 align-bottom">Contact <span class="text-red-500">*</span></th>
        <th class="px-4 py-3 align-bottom">Email</th>
        <th class="px-4 py-3 align-bottom">Remarks</th>
        <th class="px-4 py-3 text-center align-bottom">Action</th>
      </tr>
      </thead>
      <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
      <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(crewRcrApprovalLine, index) in form.crwRecruitmentApprovalLines" :key="crewRcrApprovalLine.id">
        <td class="px-1 py-1">
          <select class="form-input" v-model.trim="form.crwRecruitmentApprovalLines[index].crw_rank_id" required>
            <option value="" disabled>Select</option>
            <option v-for="(crwRank,index) in crwRankLists" :value="crwRank.id">{{ crwRank?.name }}</option>
          </select>
        </td>
        <td class="px-1 py-1">
          <input type="text" v-model.trim="form.crwRecruitmentApprovalLines[index].candidate_name" placeholder="Candidate Name" class="form-input" autocomplete="off" required />
        </td>
        <td class="px-1 py-1">
          <input type="text" v-model.trim="form.crwRecruitmentApprovalLines[index].candidate_contact" placeholder="Contact" class="form-input" autocomplete="off" required />
        </td>
        <td class="px-1 py-1">
          <input type="email" v-model.trim="form.crwRecruitmentApprovalLines[index].candidate_email" placeholder="Email" class="form-input" autocomplete="off" />
        </td>
        <td class="px-1 py-1">
          <input type="text" v-model.trim="form.crwRecruitmentApprovalLines[index].remarks" placeholder="Remarks" class="form-input" autocomplete="off" />
        </td>
        <td class="px-1 py-1 text-center">
          <button v-if="index!==0" type="button" @click="removeItem(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
            </svg>
          </button>
          <button v-else type="button" @click="addItem()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
          </button>
        </td>
      </tr>
      </tbody>
    </table>
  </fieldset>
  <ErrorComponent :errors="errors"></ErrorComponent>
</template>
<style lang="postcss" scoped>

</style>