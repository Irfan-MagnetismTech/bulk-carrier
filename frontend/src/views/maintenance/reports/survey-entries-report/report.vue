<script setup>
import useSurveyEntriesReport from "../../../../composables/maintenance/useSurveyEntriesReport";
import Title from "../../../../services/title";
import useHeroIcon from "../../../../assets/heroIcon";
import DefaultButton from '../../../../components/buttons/DefaultButton.vue';
import { formatDate } from "../../../../utils/helper";
import CriticalVesselFunctionReportForm from "../../../../components/maintenance/reports/critical-vessel-function-report/CriticalVesselFunctionReportForm.vue";
import SurveyReportForm from "../../../../components/maintenance/reports/survey-report/SurveyReportForm.vue";
const icons = useHeroIcon();
const { formParams, surveyEntries, surveyEntriesReport, downloadSurveyEntriesReport, isLoading, showReport, errors } = useSurveyEntriesReport();

const { setTitle } = Title();
setTitle('Survey Report');

</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-3 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Survey Report</h2>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="surveyEntriesReport(formParams)">
            <!-- Form -->

            <survey-report-form :page="page" v-model:form="formParams" :errors="errors"></survey-report-form>
            
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm text-white bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Search</button>
        </form>
    </div>
    <div v-show="showReport">
        <div v-if="surveyEntries?.length" class="">
    
            <div class="w-full my-2 overflow-hidden border shadow-xs dark-disabled:border-0 px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
                <!-- Print Button -->
                <!-- <div class="flex justify-end">
                    <div title="Print" @click="downloadSurveyEntriesReport(formParams)" class="w-14 px-4 cursor-pointer py-2 text-sm text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
                        </svg>
                    </div>
                </div> -->
                <!-- Print Button -->

                <!-- <h1 class="py-1 font-bold text-center text-white bg-green-500 text-capitalize"> Critical Items </h1> -->
                <div class="w-full">
                    
                    <template v-for="(mntSurveyItem, index) in surveyEntries" :key="index">
                        <div class="my-2">
                            <h3 class="mb-1 font-bold mt-2" >{{ mntSurveyItem.item_name  }}</h3>
                            <table id="table" class="w-full whitespace-no-wrap">
                                <thead>
                                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 border-b dark-disabled:border-gray-700 bg-gray-50 dark-disabled:text-gray-200 dark-disabled:bg-gray-700">
                                        <th class="w-3/12"> Survey Details </th>
                                        <th class="w-3/12"> Range Dates </th>
                                        <th class="w-2/12"> Status</th>
                                        <th class="w-2/12"> Assigned Date </th>
                                        <th class="w-2/12"> Due Date </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
                                    <tr  v-for="(mntSurvey, surveyIndex) in mntSurveyItem.mntSurveys" :key="surveyIndex">
                                        <!-- <tr v-for="(mntCriticalItem, itemIndex) in mntSurveyEntry.mntCriticalItems" :key="itemIndex"> -->
                                            <td class="p-1">{{ mntSurvey?.survey_name }}</td>
                                            <td class="p-1">{{ formatDate(mntSurvey?.mntSurveyEntries[0]?.range_date_from) }} - {{ formatDate(mntSurvey?.mntSurveyEntries[0]?.range_date_to)  }}</td>
                                            <td class="p-1">{{ mntSurvey?.mntSurveyEntries[0]?.status }}</td>
                                            <td class="p-1">{{ formatDate(mntSurvey?.mntSurveyEntries[0]?.assigned_date) }}</td>
                                            <td class="p-1">{{ formatDate(mntSurvey?.mntSurveyEntries[0]?.due_date) }}</td>
                                        <!-- </tr> -->
                                        
                                    </tr>
                                    
                                    
                                </tbody>
                            </table>
                        </div>
                    </template>

                </div>
            </div>
        </div>
        <div v-else-if="!isLoading" class="px-2 py-20 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
            <h1 class="w-full text-center vms-no-data-found">Sorry! No data found</h1>
        </div>
    </div>
</template>

<style lang="postcss" scoped>
#table, #table th, #table td{
  @apply border border-collapse border-gray-400 text-center text-gray-700 px-1
}

#table, #table th.text-left, #table td.text-left{
  @apply border border-collapse border-gray-400 text-left text-gray-700 px-1
}

</style>