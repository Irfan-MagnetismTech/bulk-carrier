<template>
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
      <h2 class="text-2xl font-semibold text-gray-700 ">Update Work Requisition</h2>
      
      <default-button :title="'Work Requisition List'" :to="{ name: 'mnt.work-requisitions.index' }" :icon="icons.DataBase"></default-button>
    </div>
      <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md ">
          <form @submit.prevent="updateWorkRequisition(workRequisition, workRequisitionId)">
              <!-- Booking Form -->
            <work-requisition-form :page="page" v-model:form="workRequisition" :errors="errors"></work-requisition-form>
              <!-- Submit button -->
              <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update Work Requisition</button>
          </form>
      </div>
  </template>
  <script setup>
  import {onMounted, ref, watch} from 'vue';
  import { useRoute } from 'vue-router';
  
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import useWorkRequisition from '../../../composables/maintenance/useWorkRequisition';
import WorkRequisitionForm from '../../../components/maintenance/work-requisition/WorkRequisitionForm.vue';
  
  const route = useRoute();
  const workRequisitionId = route.params.workRequisitionId;
  const { workRequisition, showWorkRequisition, updateWorkRequisition, errors } = useWorkRequisition();
  const icons = useHeroIcon();
  
  const { setTitle } = Title();
  const page = 'edit';
  
  setTitle('Edit Work Requisition');

  watch(workRequisition, (value) => {
    workRequisition.value.ops_vessel_name = value?.opsVessel;

    workRequisition.value.mnt_ship_department_name = value?.mntWorkRequisitionItem?.MntItem?.MntItemGroup?.MntShipDepartment;

    workRequisition.value.mnt_item_groups = value?.mntWorkRequisitionItem?.MntItem?.MntItemGroup?.mntShipDepartment?.mntItemGroups;
    workRequisition.value.mnt_item_group_name = value?.mntWorkRequisitionItem?.MntItem?.MntItemGroup;

    workRequisition.value.mnt_items = value?.mntWorkRequisitionItem?.MntItem?.MntItemGroup?.mntItems;
    workRequisition.value.mnt_item_name = value?.mntWorkRequisitionItem?.MntItem;

    workRequisition.value.added_job_lines = value?.mntWorkRequisitionLines;

    // workRequisition.value.form_type = 'edit';
  });
  
  onMounted(() => {
      showWorkRequisition(workRequisitionId);
  });
  </script>