<script setup>
import JobReportForm from "../../../../components/maintenance/reports/job-report/JobReportForm.vue";
import useOverdueJobsReport from "../../../../composables/maintenance/useOverdueJobsReport";
import Title from "../../../../services/title";
import useHeroIcon from "../../../../assets/heroIcon";
import DefaultButton from '../../../../components/buttons/DefaultButton.vue';
import { ref, watch, onMounted, watchEffect } from "vue";
import { formatDate } from '../../../../utils/helper';

const icons = useHeroIcon();
const { formParams, overdueJobs, overdueJobsReport, downloadOverdueJobsReport, showReport, isLoading, errors } = useOverdueJobsReport();

const { setTitle } = Title();
setTitle('Overdue Jobs Report');

</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-3 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Overdue Jobs Report</h2>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="overdueJobsReport(formParams)">
            <!-- Form -->

            <job-report-form :page="page" v-model:form="formParams" :errors="errors"></job-report-form>
            
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm text-white bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Search</button>
        </form>
    </div>
    <div v-show="showReport">
        <div v-if="overdueJobs?.length" class="">
    
            <div class="w-full my-2 overflow-hidden border shadow-xs dark-disabled:border-0 px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
                <!-- Print Button -->
                <!-- <div class="flex justify-end">
                    <div title="Print" @click="downloadOverdueJobsReport(formParams)" class="w-14 px-4 cursor-pointer py-2 text-sm text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
                        </svg>
                    </div>
                </div> -->
                <!-- Print Button -->

                <!-- <h1 class="py-1 font-bold text-center text-white bg-green-500 text-capitalize"> Overdue Jobs </h1> -->
                <div class="w-full">
                    
                    <template v-for="(itemGroup, index) in overdueJobs" :key="index">
                        <div class="my-2">
                            <h3 class="mb-1 font-bold mt-2" >{{ itemGroup.name  }}</h3>
                            <table id="table" class="w-full whitespace-no-wrap">
                                <thead>
                                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 border-b dark-disabled:border-gray-700 bg-gray-300 dark-disabled:text-gray-200 dark-disabled:bg-gray-700">
                                        <th class="w-1/12"> Item Code </th>
                                        <th class="w-2/12"> Item </th>
                                        <th class="w-1/12"> Running Hours</th>
                                        <th class="w-3/12"> Job Description </th>
                                        <th class="w-1/12"> Cycle </th>
                                        <th class="w-2/12"> Next Due </th>
                                        <th class="w-2/12"> Last Done </th>
                                    </tr>
                                </thead>
                        
                                <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
                                    <template  v-for="(item, itemIndex) in itemGroup.mntItems" :key="itemIndex">
                                        <template v-for="(job, jobIndex) in item.mntJobs" :key="jobIndex">
                                            <template v-for="(jobLine, jobLineIndex) in job.mntJobLines" :key="jobLineIndex">
                                                <tr>
                                                    <td v-if="jobLineIndex == 0" :rowspan="job.mntJobLines.length ?? 1">{{ item.item_code }}</td>
                                                    <td class="text-left" v-if="jobLineIndex == 0" :rowspan="job.mntJobLines.length ?? 1">{{ item.name }}</td>
                                                    <td v-if="jobLineIndex == 0" :rowspan="job.mntJobLines.length ?? 1">{{ job.present_run_hour }} {{ job.present_run_hour!=null ? 'Hrs.' : '' }}</td>
                                                    <td class="text-left">{{ jobLine.job_description }}</td>
                                                    <td>{{ jobLine.cycle }} {{ jobLine.cycle_unit == "Hours" ? 'Hrs.' : jobLine.cycle_unit }}</td>
                                                    <!-- <td>{{ jobLine.next_due }} {{ jobLine.cycle_unit == "Hours" ? 'Hrs.' : '' }}</td> -->
                                                    <td>{{ jobLine.cycle_unit == "Hours" ? jobLine.next_due + " Hrs." : formatDate(jobLine.next_due) }}</td>
                                                    <td>{{ formatDate(jobLine.last_done) }}</td>
                                                </tr>
                                                <template>
                                                
                                            </template>
                                            </template>

                                        </template>
                                        
                                        <!-- <td>{{ item.mntJobs.mntJobLines }}</td> -->
                                    </template>
                                    
                                    
                                </tbody>
                            </table>
                        </div>
                    </template>

                </div>
            </div>
        </div>
        <template v-else>
        <div class="px-2 py-20 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
            <h1 class="w-full text-center vms-no-data-found">Sorry! No data found</h1>
        </div>
        </template>
    </div>

</template>

<style lang="postcss" scoped>
#table, #table th, #table td{
  @apply border border-collapse border-gray-600 text-center text-black px-1
}

#table, #table th.text-left, #table td.text-left{
  @apply border border-collapse border-gray-600 text-left text-black px-1
}

</style>