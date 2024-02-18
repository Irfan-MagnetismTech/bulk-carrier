<template>
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
      <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Appraisal Record</h2>
      <!-- <router-link :to="{ name: 'mnt.items.index' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
        Item List
      </router-link> -->
      <default-button :title="'Appraisal Record List'" :to="{ name: 'crw.appraisal-records.index' }" :icon="icons.DataBase"></default-button>
    </div>
      <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
          <form @submit.prevent="updateAppraisalRecord(appraisalRecord, appraisalRecordId)">
              <!-- Booking Form -->
            <appraisal-record-form :page="page" v-model:form="appraisalRecord" :errors="errors"></appraisal-record-form>
              <!-- Submit button -->
              <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
          </form>
      </div>
  </template>
  <script setup>
  import {onMounted, ref, watch, watchEffect} from 'vue';
  import { useRoute } from 'vue-router';
  
  import Title from "../../../services/title";
  import useHeroIcon from "../../../assets/heroIcon";
  import DefaultButton from '../../../components/buttons/DefaultButton.vue';
  import useAppraisalRecord from '../../../composables/crew/useAppraisalRecord';
  import AppraisalRecordForm from '../../../components/crew/AppraisalRecordForm.vue';
import useCrewCommonApiRequest from '../../../composables/crew/useCrewCommonApiRequest';
  
  const route = useRoute();
  const appraisalRecordId = route.params.appraisalRecordId;
  const { appraisalRecord, showAppraisalRecord, updateAppraisalRecord, errors } = useAppraisalRecord();
  const { crews, getAppraisalUndoneAssignments, appraisalUndoneAssignments } = useCrewCommonApiRequest();
  const icons = useHeroIcon();
  
  const { setTitle } = Title();
  const page = 'edit';
  
  setTitle('Edit Appraisal Record');
  
  watch(appraisalRecord, (value) => {
    appraisalRecord.value.crw_crew_profile = value?.crwCrew;
    appraisalRecord.value.appraisal_form = value?.appraisalForm;
    appraisalRecord.value.crw_crew_assignment = value?.crwCrewAssignment;
  });

  watch(() => appraisalUndoneAssignments.value, (value) => {
    appraisalRecord.value.appraisalUndoneAssignments = [];
    if(value)
    appraisalRecord.value.appraisalUndoneAssignments = value;
  });


  onMounted(() => {
      showAppraisalRecord(appraisalRecordId);

      watchEffect(() => {
          if (appraisalRecord.value.crw_crew_assignment) {
              getAppraisalUndoneAssignments(appraisalRecord.value.crw_crew_assignment.crw_crew_id, appraisalRecord.value.crw_crew_assignment.id);
        }
          
      });
  });
  </script>