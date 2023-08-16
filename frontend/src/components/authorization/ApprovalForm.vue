<script setup>
import {computed, ref, watch, onMounted} from "vue";
import NProgress from "nprogress";
import Error from "../Error.vue";

import useApprovalManagement from "../../composables/authorization/useApprovalManagement";

const { approvals, approvalSubjectUser, getApprovalSubjectUser } = useApprovalManagement();

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
  page: {
    required: false,
    default: {},
  }
});

function addApprovalBody() {
  let obj =  {
    approver_id: '',
    approval_order: '',
    approver_role: '',
  };
  props.form.approval_bodies.push(obj);
}

function removeApprovalBody(index){
  props.form.approval_bodies.splice(index, 1);
}


onMounted(() => {
  getApprovalSubjectUser();
});

</script>

<template>
    <!-- Basic information -->
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-3 text-sm md:w-2/6">
            <span class="text-gray-700 dark:text-gray-300">Subject<span class="text-red-500">*</span></span>
            <select v-model="form.model" required class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
              <option value="">Select Option</option>
              <option :value="subject" v-for="(subject,index) in approvalSubjectUser?.subjects">{{subject}}</option>
            </select>
          <Error v-if="errors?.code" :errors="errors.code" />
        </label>
        <label class="block w-full mt-3 text-sm md:w-4/6">
        </label>
    </div>
    <!-- Basic information -->

    <!-- South Sectors -->
    <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
        <legend class="px-2 text-gray-700 dark:text-gray-300">Approval Bodies <span class="text-red-500">*</span></legend>
        <table class="w-full whitespace-no-wrap" id="table">
            <thead>
                <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                    <th class="px-4 py-3 align-bottom">Step</th>
                    <th class="px-4 py-3 align-bottom">Assign Approver</th>
                    <th class="px-4 py-3 align-bottom">Approver Role</th>
                    <th class="px-4 py-3 text-center align-bottom">Action</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                <tr class="text-gray-700 dark:text-gray-400" v-for="(approvalBody, index) in form.approval_bodies" :key="index">
                    <td class="px-1 py-1">
                        <table class="w-full">
                            <tr>
                                <td class="w-full" style="border: 0px">
                                    <input type="number" v-model="form.approval_bodies[index].approval_order" placeholder="ex: 1" required class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="px-1 py-1" style="width: 40%">
                        <select v-model="form.approval_bodies[index].approver_id" name="approver_id" required class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                            <option value="">Select Option</option>
                          <option :value="user?.id" v-for="(user,index) in approvalSubjectUser?.users">{{user?.name}}</option>
                        </select>
                    </td>
                  <td class="px-1 py-1">
                    <select v-model="form.approval_bodies[index].approver_role" name="approver_role" required class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                      <option value="">Select Option</option>
                      <option :value="user" v-for="(user,index) in approvalSubjectUser?.roles">{{user}}</option>
                    </select>
                  </td>
                    <td class="px-1 py-1 text-center">
                        <button v-if="index!=0" type="button" @click="removeApprovalBody(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                            </svg>
                        </button>
                      <button v-else type="button" @click="addApprovalBody()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
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
  @apply block w-full mt-3 text-sm;
}
.label-item-title {
  @apply text-gray-700 dark:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
}
.form-input {
  @apply block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
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