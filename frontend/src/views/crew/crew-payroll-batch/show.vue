<script setup>
import {onMounted, ref} from 'vue';
import { useRoute } from 'vue-router';
import usePayrollBatch from '../../../composables/crew/usePayrollBatch.js';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import env from '../../../config/env';

const icons = useHeroIcon();

const route = useRoute();
const crewPayrollBatchId = route.params.crewPayrollBatchId;
const { payrollBatch, showPayrollBatch, errors, isLoading } = usePayrollBatch();

const { setTitle } = Title();

setTitle('Payroll Batch Details');
const openTab = ref(1);
function changeTab(tabNumber, buttonType = null){
  if(buttonType === 'back') {
    openTab.value = tabNumber;
  } else {
    openTab.value = tabNumber;
  }
}

onMounted(() => {
  showPayrollBatch(crewPayrollBatchId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Payroll Batch Details  # {{crewPayrollBatchId}} </h2>
    <default-button :title="'Payroll Batch List'" :to="{ name: 'crw.crewPayrollBatches.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
    <div class="flex md:gap-4">
      <div class="w-full">
        <table class="w-full">
          <thead>
            <tr>
              <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="2">Basic Info</td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th class="w-40"> Business Unit </th>
              <td><span :class="payrollBatch?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ payrollBatch?.business_unit }}</span></td>
            </tr>
            <tr>
              <th class="w-40"> Vessel Name </th>
              <td>{{ payrollBatch?.opsVessel?.name }}</td>
            </tr>
            <tr>
              <th class="w-40"> Attendance Month </th>
              <td>{{ payrollBatch?.crwAttendance?.month_year_name }}</td>
            </tr>
            <tr>
              <th class="w-40"> Process Date </th>
              <td>{{ payrollBatch?.process_date }}</td>
            </tr>
            <tr>
              <th class="w-40"> Currency </th>
              <td>{{ payrollBatch?.currency }}</td>
            </tr>
            <tr>
              <th class="w-40"> Working Days </th>
              <td>{{ payrollBatch?.working_days }}</td>
            </tr>
          </tbody>
        </table>
 
<!--        <table class="w-full mt-2">-->
<!--          <thead>-->
<!--            <tr>-->
<!--              <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="7"> Assigned Crew List (Attendance) </td>-->
<!--            </tr>-->
<!--            <tr class="bg-gray-200">-->
<!--              <th class="w-40 !text-center">Crew Name</th>-->
<!--              <th class="w-40 !text-center">Contact</th>-->
<!--              <th class="w-40 !text-center">Net Salary</th>-->
<!--              <th class="w-40 !text-center">Present Days</th>-->
<!--              <th class="w-40 !text-center">Absent Days</th>-->
<!--              <th class="w-40 !text-center">Payable Days</th>-->
<!--              <th class="w-40 !text-center">Payable Amount</th>-->
<!--            </tr>-->
<!--          </thead>-->
<!--          <tbody>-->
<!--            <tr v-for="(crwPayrollBatchLine, crwPayrollBatchLineIndex) in payrollBatch?.crwPayrollBatchLines" :key="crwPayrollBatchLineIndex">-->
<!--              <td class="text-left"> <nobr>{{ crwPayrollBatchLine?.crwCrew?.full_name }}</nobr> </td>-->
<!--              <td class="text-left"> {{ crwPayrollBatchLine?.crwCrew?.pre_mobile_no }} </td>-->
<!--              <td class="!text-right"> {{ crwPayrollBatchLine?.crwSalaryStructure?.net_amount }} </td>-->
<!--              <td class="!text-center"> {{ crwPayrollBatchLine?.crwAttendanceLine?.present_days }} </td>-->
<!--              <td class="!text-center"> {{ crwPayrollBatchLine?.crwAttendanceLine?.absent_days }} </td>-->
<!--              <td class="!text-center"> {{ crwPayrollBatchLine?.crwAttendanceLine?.payable_days }} </td>-->
<!--              <td class="!text-right"> {{ crwPayrollBatchLine?.payable_amount }} </td>-->
<!--            </tr>-->
<!--          </tbody>-->
<!--        </table>-->

        <template v-if="payrollBatch?.crwPayrollBatchLines?.length">
          <div class="border-b-2 border-purple-700 mt-3"></div>
          <div class="dark-disabled:border-gray-700">
            <ul class="flex flex-wrap -mb-px border-b">
              <li class="mr-2">
                <a href="#" @click="changeTab(1)" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark-disabled:text-gray-400 group" v-bind:class="{'text-purple-600 bg-white': openTab !== 1, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active-tab': openTab === 1}">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Attendance
                </a>
              </li>
              <li class="mr-2">
                <a href="#" @click="changeTab(2)" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark-disabled:text-gray-400 group" v-bind:class="{'text-purple-600 bg-white': openTab !== 2, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active-tab': openTab === 2}">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Addition
                </a>
              </li>
              <li class="mr-2">
                <a href="#" @click="changeTab(3)" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark-disabled:text-gray-400 group" v-bind:class="{'text-purple-600 bg-white': openTab !== 3, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active-tab': openTab === 3}">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Deduction
                </a>
              </li>
              <li class="mr-2">
                <a href="#" @click="changeTab(4)" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark-disabled:text-gray-400 group" v-bind:class="{'text-purple-600 bg-white': openTab !== 4, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active-tab': openTab === 4}">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Review
                </a>
              </li>
            </ul>
            <div @click="changeTab(1)" v-bind:class="{'hidden': openTab !== 1, 'block': openTab === 1}">
              <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
                <legend class="flex items-center px-2 text-gray-700 dark-disabled:text-gray-300">
                  <span class="mr-2">Assigned Crew List</span>
                </legend>
                <table class="w-full mt-2 whitespace-no-wrap" id="table">
                  <thead>
                  <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
                    <th class="px-4 py-3 align-bottom"><nobr>Crew Name</nobr></th>
                    <th class="px-4 py-3 align-bottom"><nobr>Contact</nobr></th>
                    <th class="px-4 py-3 align-bottom"><nobr>Net Salary</nobr></th>
                    <th class="px-4 py-3 align-bottom"><nobr>Present Days</nobr></th>
                    <th class="px-4 py-3 align-bottom"><nobr>Absent Days</nobr></th>
                    <th class="px-4 py-3 align-bottom"><nobr>Payable Days</nobr></th>
                    <th class="px-4 py-3 align-bottom"><nobr>Payable Amount</nobr></th>
                  </tr>
                  </thead>

                  <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
                  <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(batchLine, index) in payrollBatch.crwPayrollBatchLines" :key="batchLine.crw_crew_id">
                    <td class="px-1 py-1">
                      <input type="text" v-model.trim="payrollBatch.crwPayrollBatchLines[index].crw_full_name" placeholder="Crew name" class="form-input vms-readonly-input" autocomplete="off" readonly />
                    </td>
                    <td class="px-1 py-1">
                      <input type="text" v-model.trim="payrollBatch.crwPayrollBatchLines[index].crw_contact_no" placeholder="Crew contact no" class="form-input vms-readonly-input" autocomplete="off" readonly />
                    </td>
                    <td class="px-1 py-1">
                      <input type="text" v-model.trim="payrollBatch.crwPayrollBatchLines[index].net_salary" placeholder="Net salary" class="form-input vms-readonly-input" autocomplete="off" readonly />
                    </td>
                    <td class="px-1 py-1">
                      <input type="text" v-model.trim="payrollBatch.crwPayrollBatchLines[index].present_days" placeholder="Present days" class="form-input vms-readonly-input" autocomplete="off" readonly />
                    </td>
                    <td class="px-1 py-1">
                      <input type="text" v-model.trim="payrollBatch.crwPayrollBatchLines[index].absent_days" placeholder="Absent days" class="form-input vms-readonly-input" autocomplete="off" readonly />
                    </td>
                    <td class="px-1 py-1">
                      <input type="text" v-model.trim="payrollBatch.crwPayrollBatchLines[index].payable_days" placeholder="Payable days" class="form-input vms-readonly-input" autocomplete="off" readonly />
                    </td>
                    <td class="px-1 py-1">
                      <input type="text" v-model.trim="payrollBatch.crwPayrollBatchLines[index].payable_amount" placeholder="Payable amount" class="form-input vms-readonly-input" autocomplete="off" readonly />
                    </td>
                  </tr>
                  </tbody>
                  <tfoot v-if="!payrollBatch.crwPayrollBatchLines?.length">
                  <tr v-if="isLoading">
                    <td colspan="8">Loading</td>
                  </tr>
                  <tr v-else-if="!payrollBatch.crwPayrollBatchLines?.length">
                    <td colspan="8">No data found.</td>
                  </tr>
                  </tfoot>
                </table>
              </fieldset>
            </div>
            <div @click="changeTab(2)" v-bind:class="{'hidden': openTab !== 2, 'block': openTab === 2}">
              <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
                <legend class="flex items-center px-2 text-gray-700 dark-disabled:text-gray-300">
                  <span class="mr-2">Addition Head List</span>
                </legend>
                <table class="w-full mt-2 whitespace-no-wrap" id="table">
                  <thead>
                  <tr>
                    <th class="!text-center"><nobr>Addition Head</nobr></th>
                    <th class="!text-center"><nobr>Amount</nobr></th>
                  </tr>
                  </thead>
                  <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
                  <template v-for="(batchHead, index) in payrollBatch.crwPayrollBatchHeads">
                    <tr v-if="batchHead?.head_type === 'addition'" class="text-gray-700 dark-disabled:text-gray-400" :key="batchHead.key">
                      <td class="px-1 py-1">
                        <input type="text" v-model.trim="payrollBatch.crwPayrollBatchHeads[index].head_name" placeholder="Head name" class="form-input vms-readonly-input" autocomplete="off" readonly />
                      </td>
                      <td class="px-1 py-1">
                        <input type="number" step=".01" v-model.trim="payrollBatch.crwPayrollBatchHeads[index].amount" placeholder="Amount" class="form-input vms-readonly-input" autocomplete="off" readonly />
                      </td>
                    </tr>
                  </template>
                  </tbody>
                  <tfoot>
                  <tr v-if="!payrollBatch.crwPayrollBatchHeads?.filter(item => item.head_type === 'addition').length">
                    <td colspan="4">No data found</td>
                  </tr>
                  </tfoot>
                </table>
              </fieldset>
              <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
                <legend class="flex items-center px-2 text-gray-700 dark-disabled:text-gray-300">
                  <span class="mr-2">Assigned Crew List</span>
                </legend>
                <table class="w-full mt-2 whitespace-no-wrap" id="table">
                  <thead>
                  <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
                    <th class="px-4 py-3 align-bottom"><nobr>Crew Name</nobr></th>
                    <th class="px-4 py-3 align-bottom"><nobr>Crew Contact</nobr></th>
                    <template v-for="(batchHead, index) in payrollBatch.crwPayrollBatchHeads">
                      <th class="px-4 py-3 align-bottom" v-if="batchHead?.head_type==='addition'"><nobr>{{ batchHead?.head_name }}</nobr></th>
                    </template>
                    <th class="px-4 py-3 align-bottom"><nobr>Addition Amount</nobr></th>
                  </tr>
                  </thead>
                  <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
                  <template v-for="(batchHeadLine, payrollBatchHeadLineIndex) in payrollBatch.crwPayrollBatchHeadLines">
                    <tr v-if="batchHeadLine?.head_type==='addition'" class="text-gray-700 dark-disabled:text-gray-400" :key="batchHeadLine.crw_crew_id">
                      <td class="px-1 py-1">
                        <input type="text" v-model.trim="payrollBatch.crwPayrollBatchHeadLines[payrollBatchHeadLineIndex].crew_name" placeholder="Crew name" class="form-input vms-readonly-input" autocomplete="off" readonly />
                      </td>
                      <td class="px-1 py-1">
                        <input type="text" v-model.trim="payrollBatch.crwPayrollBatchHeadLines[payrollBatchHeadLineIndex].crw_contact_no" placeholder="Crew contact no" class="form-input vms-readonly-input" autocomplete="off" readonly />
                      </td>
                      <template v-for="(lineBatchHead, lineBatchHeadIndex) in payrollBatch.crwPayrollBatchHeadLines[payrollBatchHeadLineIndex].crew_batch_heads">
                        <td v-if="lineBatchHead?.head_type==='addition'" class="px-1 py-1">
                          <input type="number" step=".01" v-model.trim="payrollBatch.crwPayrollBatchHeadLines[payrollBatchHeadLineIndex].crew_batch_heads[lineBatchHeadIndex].amount" placeholder="Amount" class="form-input vms-readonly-input" autocomplete="off" readonly />
                        </td>
                      </template>
                      <td class="px-1 py-1">
                        <input type="number" step=".01" v-model.trim="payrollBatch.crwPayrollBatchHeadLines[payrollBatchHeadLineIndex].amount" placeholder="Amount" class="form-input vms-readonly-input" autocomplete="off" readonly />
                      </td>
                    </tr>
                  </template>
                  </tbody>
                  <tfoot>
                  <tr v-if="!payrollBatch.crwPayrollBatchHeadLines?.filter(item => item.head_type === 'addition').length">
                    <td colspan="4">No data found</td>
                  </tr>
                  </tfoot>
                </table>
              </fieldset>
            </div>
            <div @click="changeTab(3)" v-bind:class="{'hidden': openTab !== 3, 'block': openTab === 3}">
              <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
                <legend class="flex items-center px-2 text-gray-700 dark-disabled:text-gray-300">
                  <span class="mr-2">Deduction Head List</span>
                </legend>
                <table class="w-full mt-2 whitespace-no-wrap" id="table">
                  <thead>
                  <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
                    <th class="!text-center"><nobr>Deduction Head</nobr></th>
                    <th class="!text-center"><nobr>Amount</nobr></th>
                  </tr>
                  </thead>
                  <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
                  <template v-for="(batchHead, index) in payrollBatch.crwPayrollBatchHeads">
                    <tr v-if="batchHead?.head_type === 'deduction'" class="text-gray-700 dark-disabled:text-gray-400" :key="batchHead.key">
                      <td class="px-1 py-1">
                        <input type="text" v-model.trim="payrollBatch.crwPayrollBatchHeads[index].head_name" placeholder="Head name" class="form-input vms-readonly-input" autocomplete="off" readonly />
                      </td>
                      <td class="px-1 py-1">
                        <input type="number" step=".01" v-model.trim="payrollBatch.crwPayrollBatchHeads[index].amount" placeholder="Amount" class="form-input vms-readonly-input" autocomplete="off" readonly />
                      </td>
                    </tr>
                  </template>
                  </tbody>
                  <tfoot>
                  <tr v-if="!payrollBatch.crwPayrollBatchHeads?.filter(item => item.head_type === 'deduction').length">
                    <td colspan="4">No data found</td>
                  </tr>
                  </tfoot>
                </table>
              </fieldset>
              <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
                <legend class="flex items-center px-2 text-gray-700 dark-disabled:text-gray-300">
                  <span class="mr-2">Assigned Crew List</span>
                </legend>
                <table class="w-full mt-2 whitespace-no-wrap" id="table">
                  <thead>
                  <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
                    <th class="px-4 py-3 align-bottom"><nobr>Crew Name</nobr></th>
                    <th class="px-4 py-3 align-bottom"><nobr>Crew Contact</nobr></th>
                    <template v-for="(batchHead, index) in payrollBatch.crwPayrollBatchHeads">
                      <th class="px-4 py-3 align-bottom" v-if="batchHead?.head_type==='deduction'"><nobr>{{ batchHead?.head_name }}</nobr></th>
                    </template>
                    <th class="px-4 py-3 align-bottom"><nobr>Deduction Amount</nobr></th>
                  </tr>
                  </thead>
                  <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
                  <template v-for="(batchHeadLine, payrollBatchHeadLineIndex) in payrollBatch.crwPayrollBatchHeadLines">
                    <tr v-if="batchHeadLine?.head_type==='deduction'" class="text-gray-700 dark-disabled:text-gray-400" :key="batchHeadLine.crw_crew_id">
                      <td class="px-1 py-1">
                        <input type="text" v-model.trim="payrollBatch.crwPayrollBatchHeadLines[payrollBatchHeadLineIndex].crew_name" placeholder="Crew name" class="form-input vms-readonly-input" autocomplete="off" readonly />
                      </td>
                      <td class="px-1 py-1">
                        <input type="text" v-model.trim="payrollBatch.crwPayrollBatchHeadLines[payrollBatchHeadLineIndex].crw_contact_no" placeholder="Crew contact no" class="form-input vms-readonly-input" autocomplete="off" readonly />
                      </td>
                      <template v-for="(lineBatchHead, lineBatchHeadIndex) in payrollBatch.crwPayrollBatchHeadLines[payrollBatchHeadLineIndex].crew_batch_heads">
                        <td v-if="lineBatchHead?.head_type==='deduction'" class="px-1 py-1">
                          <input type="number" step=".01" v-model.trim="payrollBatch.crwPayrollBatchHeadLines[payrollBatchHeadLineIndex].crew_batch_heads[lineBatchHeadIndex].amount" placeholder="Amount" class="form-input vms-readonly-input" autocomplete="off" readonly />
                        </td>
                      </template>
                      <td class="px-1 py-1">
                        <input type="number" step=".01" v-model.trim="payrollBatch.crwPayrollBatchHeadLines[payrollBatchHeadLineIndex].amount" placeholder="Crew rank" class="form-input vms-readonly-input" autocomplete="off" readonly />
                      </td>
                    </tr>
                  </template>
                  </tbody>
                  <tfoot>
                  <tr v-if="!payrollBatch.crwPayrollBatchHeadLines?.filter(item => item.head_type === 'deduction').length">
                    <td colspan="4">No data found</td>
                  </tr>
                  </tfoot>
                </table>
              </fieldset>
            </div>
            <div @click="changeTab(4)" v-bind:class="{'hidden': openTab !== 4, 'block': openTab === 4}">
              <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
                <legend class="flex items-center px-2 text-gray-700 dark-disabled:text-gray-300">
                  <span class="mr-2">Assigned Crew List</span>
                </legend>
                <table class="w-full mt-2 whitespace-no-wrap" id="table">
                  <thead>
                  <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
                    <th class="px-4 py-3 align-bottom"><nobr>Crew Name</nobr></th>
                    <th class="px-4 py-3 align-bottom"><nobr>Crew Contact</nobr></th>
                    <th class="px-4 py-3 align-bottom"><nobr>Payable Days</nobr></th>
                    <th class="px-4 py-3 align-bottom"><nobr>Payable Amount</nobr></th>
                    <th class="px-4 py-3 align-bottom"><nobr>Addition Amount</nobr></th>
                    <th class="px-4 py-3 align-bottom"><nobr>Deduction Amount</nobr></th>
                    <th class="px-4 py-3 align-bottom"><nobr>Net Payable Amount</nobr></th>
                  </tr>
                  </thead>

                  <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
                  <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(batchLine, index) in payrollBatch.crwPayrollBatchLines" :key="batchLine.crw_crew_id">
                    <td class="px-1 py-1">
                      <input type="text" v-model.trim="payrollBatch.crwPayrollBatchLines[index].crw_full_name" placeholder="Crew name" class="form-input vms-readonly-input" autocomplete="off" readonly />
                    </td>
                    <td class="px-1 py-1">
                      <input type="text" v-model.trim="payrollBatch.crwPayrollBatchLines[index].crw_contact_no" placeholder="Crew ID" class="form-input vms-readonly-input" autocomplete="off" readonly />
                    </td>
                    <td class="px-1 py-1">
                      <input type="text" v-model.trim="payrollBatch.crwPayrollBatchLines[index].payable_days" placeholder="Payable days" class="form-input vms-readonly-input" autocomplete="off" readonly />
                    </td>
                    <td class="px-1 py-1">
                      <input type="text" v-model.trim="payrollBatch.crwPayrollBatchLines[index].payable_amount" placeholder="Payable amount" class="form-input vms-readonly-input" autocomplete="off" readonly />
                    </td>
                    <td class="px-1 py-1">
                      <input type="text" v-model.trim="payrollBatch.crwPayrollBatchLines[index].total_earnings" placeholder="Total earnings" class="form-input vms-readonly-input" autocomplete="off" readonly />
                    </td>
                    <td class="px-1 py-1">
                      <input type="text" v-model.trim="payrollBatch.crwPayrollBatchLines[index].total_deductions" placeholder="Total deductions" class="form-input vms-readonly-input" autocomplete="off" readonly />
                    </td>
                    <td class="px-1 py-1">
                      <input type="text" v-model.trim="payrollBatch.crwPayrollBatchLines[index].net_payable_amount" placeholder="Net payable amount" class="form-input vms-readonly-input" autocomplete="off" readonly />
                    </td>
                  </tr>
                  </tbody>
                  <tfoot v-if="!payrollBatch.crwPayrollBatchLines?.length">
                  <tr v-if="isLoading">
                    <td colspan="7">Loading</td>
                  </tr>
                  <tr v-else-if="!payrollBatch.crwPayrollBatchLines?.length">
                    <td colspan="7">No data found.</td>
                  </tr>
                  </tfoot>
                </table>
              </fieldset>
            </div>
          </div>
        </template>

      </div>
    </div>
  </div>
</template>
<style lang="postcss" scoped>
th, td, tr {
  @apply text-left border-gray-500
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