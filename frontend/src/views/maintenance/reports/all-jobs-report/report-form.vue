<script setup>
import {onMounted, watch, watchEffect, ref} from "vue";
import { useRoute } from 'vue-router';
import Title from "../../../../services/title";
import useHeroIcon from "../../../../assets/heroIcon";
import DefaultButton from '../../../../components/buttons/DefaultButton.vue';
import Error from "../../../../components/Error.vue";
import Editor from '@tinymce/tinymce-vue';

// import useShipDepartment from "../../../composables/maintenance/useShipDepartment";

import BusinessUnitInput from "../../../../components/input/BusinessUnitInput.vue";
// import useCriticalFunction from "../../../composables/maintenance/useCriticalFunction";
// import useCriticalItemCategory from "../../../composables/maintenance/useCriticalItemCategory";
// import useCriticalItem from "../../../composables/maintenance/useCriticalItem";
// import useVessel from "../../../composables/operations/useVessel";

import useAllJobsReport from "../../../../composables/maintenance/useAllJobsReport";
import useVessel from "../../../../composables/operations/useVessel";
import useShipDepartment from "../../../../composables/maintenance/useShipDepartment";
import Report from "./Report.vue";

const route = useRoute();
const icons = useHeroIcon();
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

const { formParams, allJobs, allJobsReport, downloadAllJobsReport, isLoading, errors } = useAllJobsReport();
const { vessels, getVesselsWithoutPaginate, isLoading:isVesselLoading  } = useVessel();
const { shipDepartments, getShipDepartmentsWithoutPagination, isLoading:isShipDepartmentLoading } = useShipDepartment();












// import JobForm from '../../../components/maintenance/job/JobForm.vue';
// import useJob from '../../../composables/maintenance/useJob';
// const { job, storeJob, isLoading, errors } = useJob();
// const page = 'create';

const { setTitle } = Title();
setTitle('Create Job');

function vesselChange() {
    formParams.value.ops_vessel_id = formParams.value.ops_vessel?.id;

    formParams.value.mnt_ship_department = "";
    formParams.value.mnt_ship_department_id = null;
    if (formParams.value.ops_vessel_id && businessUnit.value && businessUnit.value != 'ALL')
        getShipDepartmentsWithoutPagination(businessUnit.value);
} 

function shipDepartmentChange() {
  formParams.value.mnt_ship_department_id = formParams.value.mnt_ship_department?.id;
} 



watch(() => formParams.value.business_unit, (newValue, oldValue) => {
  businessUnit.value = newValue;
});

onMounted(() => {
  watchEffect(() => {
      if(businessUnit.value && businessUnit.value != 'ALL'){
        getVesselsWithoutPaginate(businessUnit.value);
      }
  });
});
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-3 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">All Jobs Report</h2>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="allJobsReport(formParams)">
            <!-- Form -->

            <!-- <job-form :page="page" v-model:form="job" :errors="errors"></job-form> -->
            <div class="justify-center w-full grid grid-cols-1 md:grid-cols-3 md:gap-2">
                <business-unit-input :page="page" v-model="formParams.business_unit"></business-unit-input>
                <label class="block w-full mt-2 text-sm">
                    <span class="text-gray-700 dark-disabled:text-gray-300">Vessel <span class="text-red-500">*</span></span>
                    <v-select placeholder="Select Vessel" :loading="isVesselLoading"  :options="vessels" @search="" 
                            v-model="formParams.ops_vessel" label="name" @update:modelValue="vesselChange"  class="block form-input">
                        <template #search="{attributes, events}">
                            <input
                                class="vs__search"
                                :required="!formParams.ops_vessel"
                                v-bind="attributes"
                                v-on="events"
                            />
                        </template>
                    </v-select>
                    <input type="hidden" v-model="formParams.ops_vessel_id">
                    <Error v-if="errors?.ops_vessel_id" :errors="errors.ops_vessel_id" />
                </label>
                <label class="block w-full mt-2 text-sm">
                    <span class="text-gray-700 dark-disabled:text-gray-300">Ship Department <span class="text-red-500">*</span></span>
                    <v-select placeholder="Select Ship Department" :loading="isShipDepartmentLoading"  
                        :options="shipDepartments" @search="" v-model="formParams.mnt_ship_department" label="name" @update:modelValue="shipDepartmentChange"  class="block form-input">
                        <template #search="{attributes, events}">
                            <input
                                class="vs__search"
                                :required="!formParams.mnt_ship_department"
                                v-bind="attributes"
                                v-on="events"
                            />
                        </template>
                    </v-select>
                    <input type="hidden" v-model="formParams.mnt_ship_department_id">
                    <Error v-if="errors?.mnt_ship_department_id" :errors="errors.mnt_ship_department_id" />
                </label>
                
            </div>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm text-white bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Search</button>
        </form>
    </div>
    <div v-if="allJobs?.length" class="">
        <!-- <Report :allJobs="allJobs"></Report> -->
        <div class="w-full my-2 overflow-hidden border shadow-xs dark:border-0 px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
            <!-- <h1 class="py-1 font-bold text-center text-white bg-green-500 text-capitalize"> All Jobs </h1> -->
            <div class="w-full">
                
                <template v-for="(itemGroup, index) in allJobs" :key="index">
                    <div class="my-2">
                        <h3 class="mb-1" >{{ itemGroup.name  }}</h3>
                        <table class="w-full whitespace-no-wrap">
                            <thead>
                                <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                                    <td class="p-2"> Item Code </td>
                                    <td class="p-2"> Item </td>
                                    <td class="p-2"> Item Run Hrs. </td>
                                    <td class="p-2"> Description </td>
                                    <td class="p-2"> Cycle </td>
                                    <td class="p-2"> Next Due Hrs. </td>
                                    <td class="p-2"> Last Done </td>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                                <template v-if="itemGroup?.mntItems?.length" v-for="(item, itemIndex) in itemGroup.mntItems" :key="itemIndex">
                                    <template v-if="item?.mntJobs?.length"  v-for="(job, jobIndex) in item.mntJobs" :key="jobIndex">
                                        <tr v-if="job?.mntJobLines?.length"  v-for="(jobLine, jobLineIndex) in job.mntJobLines" :key="jobLineIndex">
                                            <td v-if="jobLineIndex == 0" :rowspan="job.mntJobLines.length??1">{{ item.item_code }}</td>
                                            <td v-if="jobLineIndex == 0" :rowspan="job.mntJobLines.length??1">{{ item.name }}</td>
                                            <td v-if="jobLineIndex == 0" :rowspan="job.mntJobLines.length??1">{{ job.present_run_hour }}</td>
                                            <td>{{ jobLine.job_description }}</td>
                                            <td>{{ jobLine.cycle }}</td>
                                            <td>{{ jobLine.next_due }}</td>
                                            <td>{{ jobLine.last_done }}</td>
                                        </tr>
                                        <tr v-else >
                                            <td>{{ item.item_code }}</td>
                                            <td>{{ item.name }}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </template>
                                    <tr v-else >
                                            <td>{{ item.item_code }}</td>
                                            <td>{{ item.name }}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    <!-- <td>{{ item.mntJobs.mntJobLines }}</td> -->
                                </template>
                                <tr v-else >
                                            <td>&nbsp;</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                            </tbody>
                        </table>
                    </div>
                </template>
                <!-- <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                            <td> Item Code </td>
                            <td> Item </td>
                            <td> Item Run Hrs. </td>
                            <td> Description </td>
                            <td> Cycle </td>
                            <td> Next Due Hrs. </td>
                            <td> Last Done </td>
                        </tr>
                    </thead>

                        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                            <tr>
                                <td> 20' </td>
                                <td> {{ voyageInfo?.gp_20_ldn_rate }} </td>
                                <td> {{ voyageInfo?.gp_20_ldn_quantity }} </td>
                                <td> {{ voyageInfo?.gp_20_ldn_amount }} </td>
                            </tr>
                        </tbody>
                        
                    </table> -->
                </div>
            </div>
    </div>
</template>