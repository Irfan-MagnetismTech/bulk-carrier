<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useAppraisalRecord from '../../../composables/crew/useAppraisalRecord';
import { formatDate } from '../../../utils/helper';

const icons = useHeroIcon();

const route = useRoute();
const appraisalRecordId = route.params.appraisalRecordId;
const { appraisalRecord, showAppraisalRecord, errors } = useAppraisalRecord();


const grade = {
  "1" : "Poor",
  "2" : "Fair",
  "3" : "Good",
  "4" : "Very Good",
  "5" : "Excellent",
}

const { setTitle } = Title();

setTitle('Appraisal Record Details');

onMounted(() => {
  showAppraisalRecord(appraisalRecordId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Appraisal Record Details</h2>
    <default-button :title="'Appraisal Record List'" :to="{ name: 'crw.appraisal-records.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <div class="flex md:gap-4">
        <div class="w-full">
          <h2 class="bg-green-600 text-white text-md font-semibold uppercase mb-2 text-center py-2">Appraisal Record Information</h2>
          <table class="w-full">
            
            <tbody>
              <tr>
                <th class="w-40">Business Unit</th>
                <td><span :class="appraisalRecord?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ appraisalRecord?.business_unit }}</span></td>
              </tr>
             <tr>
                <th class="w-40">Crew</th>
                <td>{{ appraisalRecord?.crwCrew?.full_name }}</td>
              </tr>
              
             <tr>
                <th class="w-40">Contact No</th>
                <td>{{ appraisalRecord?.crwCrew?.pre_mobile_no }}</td>
              </tr>

             <tr>
                <th class="w-40">Age</th>
                <td>{{ appraisalRecord?.age }}</td>
              </tr>
              <tr>
                <th class="w-40">Passport No</th>
                <td>{{ appraisalRecord?.crwCrew?.passport_no }}</td>
              </tr>

              
              <!-- <tr>
                <th class="w-40">Seaman's Book No</th>
                <td></td>
              </tr> -->

              
              <tr>
                <th class="w-40">Nationality</th>
                <td>{{ appraisalRecord?.crwCrew?.nationality }}</td>
              </tr>

              <tr>
                <th class="w-40">Completed Assignment Code</th>
                <td>{{ appraisalRecord?.crwCrewAssignment?.assignment_code }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Vessel</th>
                <td>{{ appraisalRecord?.crwCrewAssignment?.opsVessel?.name }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Rank</th>
                <td>{{ appraisalRecord?.crwCrewAssignment?.position_onboard }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Service From</th>
                <td>{{ formatDate(appraisalRecord?.crwCrewAssignment?.joining_date) }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Service To</th>
                <td>{{ formatDate(appraisalRecord?.crwCrewAssignment?.completion_date) }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Appraisal Form No</th>
                <td>{{ appraisalRecord?.appraisalForm?.form_no }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Appraisal Form Version</th>
                <td>{{ appraisalRecord?.appraisalForm?.version }}</td>
              </tr>



              
              <tr>
                <th class="w-40">Total Marks</th>
                <td>{{ appraisalRecord.total_marks }}</td>
              </tr>
              
              <tr>
                <th class="w-40">Obtained Marks</th>
                <td>{{ appraisalRecord.obtained_marks }}</td>
              </tr>


              
              <tr>
                <th class="w-40">Appraisal Date</th>
                <td>{{ formatDate(appraisalRecord?.appraisal_date) }}</td>
              </tr>

              <tr>
                <td colspan="2">
                  <template class="px-2 py-2 mt-3 border border-gray-300 rounded dark-disabled:border-gray-400" v-for="(appraisalFormLine, index) in  
              appraisalRecord?.appraisalRecordLines" :key="index">
                <table class="w-full whitespace-no-wrap mt-2" id="table" v-if="appraisalFormLine.is_tabular">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-center text-gray-500  bg-gray-200 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
                            <th colspan="4" class="px-4 py-2 text-center">{{ appraisalFormLine.section_no }}. {{ appraisalFormLine.section_name }}</th>    
                        </tr>
                        
                        <tr class="text-xs font-semibold tracking-wide text-center text-gray-500  bg-gray-200 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
                            <th class="w-2/5 px-4 py-2 text-center">Aspect</th>
                            <th class="w-2/5 px-4 py-2 text-center">Comments</th>
                            <th class="w-1/5 px-4 py-2 text-center">Marks</th>
                        </tr>

                    </thead>
                    <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
                      <template> 
                        {{ appraisalFormLine.appraisalRecordLineItems = (page == 'create' ? appraisalFormLine?.appraisalFormLineItems : appraisalFormLine.appraisalRecordLineItems) }} 
                      </template>

                        <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(appraisalFormLineItem, appraisalFormLineItemIndex) in appraisalFormLine?.appraisalRecordLineItems" :key="appraisalFormLineItemIndex">
                            <td class="px-1 py-1">
                              
                              <div>
                                <strong>{{ appraisalFormLineItem.aspect }}:</strong><br>
                                {{ appraisalFormLineItem.description }}
                              </div>
                            </td>
                            <td>
                              {{ appraisalFormLineItem.comment }}
                            </td>
                            <td class="px-1 py-1 text-center">
                              <div v-if="appraisalFormLineItem.answer_type == 'Boolean'" class="">
                                <!-- <select v-model.trim="appraisalFormLineItem.answer" class="form-input" required>
                                  <option value="" disabled selected>Select</option>
                                  <option value="1">Yes</option>
                                  <option value="0">No</option>
                                </select> -->
                                {{ appraisalFormLineItem.answer == 1 ? "Yes" : "No" }}
                              </div>
                              <div v-else-if="appraisalFormLineItem.answer_type == 'Number' || appraisalFormLineItem.answer_type == 'Grade'" >
                                <!-- <select v-model.trim="appraisalFormLineItem.answer" class="form-input" required>
                                  <option value="" disabled selected>Select</option>
                                  <option value="1">{{ appraisalFormLineItem.answer_type == 'Grade' ? 'Poor' : 1 }}</option>
                                  <option value="2">{{ appraisalFormLineItem.answer_type == 'Grade' ? 'Fair' : 2 }}</option>
                                  <option value="3">{{ appraisalFormLineItem.answer_type == 'Grade' ? 'Good' : 3 }}</option>
                                  <option value="4">{{ appraisalFormLineItem.answer_type == 'Grade' ? 'Very Good' : 4 }}</option>
                                  <option value="5">{{ appraisalFormLineItem.answer_type == 'Grade' ? 'Excellent' : 5 }}</option>
                                </select> -->
                                {{ appraisalFormLineItem.answer_type == 'Grade' ? grade[appraisalFormLineItem.answer] : appraisalFormLineItem.answer }}
                              </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table class="w-full whitespace-no-wrap  mt-2" id="table" v-else>
                  <thead>
                        <tr class="text-xs font-semibold tracking-wide text-center text-gray-500  bg-gray-200 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
                            <th class="px-4 py-2 text-center">{{ appraisalFormLine.section_no }}. {{ appraisalFormLine.section_name }}</th>    
                        </tr>
                  </thead>
                  <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800 ">
                        <tr class="">
                            <td class="px-4 py-2 ">
                              <div class="min-h-[50px]">
                                  {{ appraisalFormLine.comment }}
                              </div>
                              
                            </td>    
                        </tr>
                        
                    </tbody>

                </table>
                
            </template>

                </td>
              </tr>               
            </tbody>
          </table>
          
        </div>
      </div>
  </div>
</template>
<style lang="postcss" scoped>
  th, td, tr {
    @apply text-left border-gray-500
  }

  #profileDetailTable th{
    text-align: center;
  }
  #profileDetailTable thead tr{
    @apply bg-gray-200
  }

  th.text-center, td.text-center, tr.text-center {
    @apply text-center border-gray-500
  }

</style>