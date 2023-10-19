<script setup>
import Error from "../Error.vue";
import useCrewCommonApiRequest from "../../composables/crew/useCrewCommonApiRequest";
import useAgencyContract from "../../composables/crew/useAgencyContract";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import {onMounted, ref, watchEffect} from "vue";
import Store from "../../store";

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
const { crwRankLists, getCrewRankLists } = useCrewCommonApiRequest();

onMounted(() => {
  props.form.business_unit = businessUnit.value;
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
        <span class="text-gray-700 dark:text-gray-300">Applied Date <span class="text-red-500">*</span></span>
        <input type="date" v-model="form.applied_date" class="form-input" autocomplete="off" required />
        <Error v-if="errors?.applied_date" :errors="errors.applied_date" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Page Title <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.page_title" placeholder="Page Title" class="form-input" autocomplete="off" required />
        <Error v-if="errors?.page_title" :errors="errors.page_title" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Subject <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.subject" placeholder="Subject" class="form-input" autocomplete="off" required />
        <Error v-if="errors?.subject" :errors="errors.subject" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Total Approved <span class="text-red-500">*</span></span>
        <input type="number" v-model="form.total_approved" placeholder="Ex: 10" class="form-input" autocomplete="off" required />
        <Error v-if="errors?.total_approved" :errors="errors.total_approved" />
      </label>
    </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Agreed To Join <span class="text-red-500">*</span></span>
      <input type="number" v-model="form.crew_agreed_to_join" placeholder="Ex: 5" class="form-input" autocomplete="off" required />
      <Error v-if="errors?.crew_agreed_to_join" :errors="errors.crew_agreed_to_join" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Total Selected <span class="text-red-500">*</span></span>
      <input type="number" v-model="form.crew_selected" placeholder="Ex: 4" class="form-input" autocomplete="off" required />
      <Error v-if="errors?.crew_selected" :errors="errors.crew_selected" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Total Panel <span class="text-red-500">*</span></span>
      <input type="number" v-model="form.crew_panel" placeholder="Ex: 4" class="form-input" autocomplete="off" required />
      <Error v-if="errors?.crew_panel" :errors="errors.crew_panel" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Total Rest <span class="text-red-500">*</span></span>
      <input type="number" v-model="form.crew_rest" placeholder="Ex: 1" class="form-input" autocomplete="off" required />
      <Error v-if="errors?.crew_rest" :errors="errors.crew_rest" />
    </label>
  </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Body <span class="text-red-500">*</span></span>
      <textarea v-model="form.body" placeholder="Type here....." class="form-input" autocomplete="off" required></textarea>
      <Error v-if="errors?.body" :errors="errors.body" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Remarks</span>
      <textarea type="text" v-model="form.remarks" placeholder="Type here...." class="form-input" autocomplete="off"></textarea>
      <Error v-if="errors?.remarks" :errors="errors.remarks" />
    </label>
  </div>
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300">Candidate List</legend>
    <table class="w-full whitespace-no-wrap" id="table">
      <thead>
      <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th class="px-4 py-3 align-bottom">Rank <span class="text-red-500">*</span></th>
        <th class="px-4 py-3 align-bottom">Candidate Name <span class="text-red-500">*</span></th>
        <th class="px-4 py-3 align-bottom">Contact <span class="text-red-500">*</span></th>
        <th class="px-4 py-3 align-bottom">Email</th>
        <th class="px-4 py-3 align-bottom">Remarks</th>
        <th class="px-4 py-3 text-center align-bottom">Action</th>
      </tr>
      </thead>

      <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
      <tr class="text-gray-700 dark:text-gray-400" v-for="(crewRcrApprovalLine, index) in form.crwRecruitmentApprovalLines" :key="crewRcrApprovalLine.id">
        <td class="px-1 py-1">
          <select class="form-input" v-model="form.crwRecruitmentApprovalLines[index].crw_rank_id" required>
            <option value="" disabled>select</option>
            <option v-for="(crwRank,index) in crwRankLists" :value="crwRank.id">{{ crwRank?.name }}</option>
          </select>
        </td>
        <td class="px-1 py-1">
          <input type="text" v-model="form.crwRecruitmentApprovalLines[index].candidate_name" placeholder="Crew name" class="form-input" autocomplete="off" required />
        </td>
        <td class="px-1 py-1">
          <input type="text" v-model="form.crwRecruitmentApprovalLines[index].candidate_contact" placeholder="Contact" class="form-input" autocomplete="off" required />
        </td>
        <td class="px-1 py-1">
          <input type="email" v-model="form.crwRecruitmentApprovalLines[index].candidate_email" placeholder="Email" class="form-input" autocomplete="off" />
        </td>
        <td class="px-1 py-1">
          <input type="text" v-model="form.crwRecruitmentApprovalLines[index].remarks" placeholder="Remarks" class="form-input" autocomplete="off" />
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
</template>
<style lang="postcss" scoped>
#table, #table th, #table td{
  @apply border border-collapse border-gray-400 text-center text-gray-700 px-1
}

.input-group {
  @apply flex flex-col justify-center w-full h-full md:flex-row md:gap-2;
}

.label-group {
  @apply block w-full mt-2 text-sm;
}
.label-item-title {
  @apply text-gray-700 dark:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
}

>>> {
  --vs-controls-color: #374151;
  --vs-border-color: #4b5563;

  --vs-dropdown-bg: #282c34;
  --vs-dropdown-color: #eeeeee;
  --vs-dropdown-option-color: #eeeeee;

  --vs-selected-bg: #664cc3;
  --vs-selected-color: #374151;

  --vs-search-input-color: #4b5563;

  --vs-dropdown-option--active-bg: #664cc3;
  --vs-dropdown-option--active-color: #eeeeee;
}
</style>