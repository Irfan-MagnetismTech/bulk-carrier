<template>
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
      <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update WIP Work Requisition</h2>
      
      <default-button :title="'Work Requisition List'" :to="{ name: 'mnt.wip-work-requisitions.index' }" :icon="icons.DataBase"></default-button>
    </div>
      <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
          <form @submit.prevent="updateWipWorkRequisition(wipWorkRequisition, wipWorkRequisitionId)">
              <!-- Booking Form -->
            <wip-work-requisition-form :page="page" v-model:form="wipWorkRequisition" :errors="errors"></wip-work-requisition-form>
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
import useWipWorkRequisition from '../../../composables/maintenance/useWipWorkRequisition';
import WipWorkRequisitionForm from '../../../components/maintenance/wip-work-requisition/WipWorkRequisitionForm.vue';
  
  const route = useRoute();
  const wipWorkRequisitionId = route.params.wipWorkRequisitionId;
  const { wipWorkRequisition, showWipWorkRequisition, updateWipWorkRequisition, updateWipWorkRequisitionLine, errors } = useWipWorkRequisition();
  const icons = useHeroIcon();
  
  const { setTitle } = Title();
  const page = 'edit';
  
  setTitle('Edit WIP Work Requisition');

  watch(wipWorkRequisition, (value) => {
    wipWorkRequisition.value.ops_vessel_name = value?.opsVessel;

    wipWorkRequisition.value.mnt_ship_department_name = value?.mntWorkRequisitionItem?.MntItem?.MntItemGroup?.MntShipDepartment;

    wipWorkRequisition.value.mnt_item_groups = value?.mntWorkRequisitionItem?.MntItem?.MntItemGroup?.mntShipDepartment?.mntItemGroups;
    wipWorkRequisition.value.mnt_item_group_name = value?.mntWorkRequisitionItem?.MntItem?.MntItemGroup;

    wipWorkRequisition.value.mnt_items = value?.mntWorkRequisitionItem?.MntItem?.MntItemGroup?.mntItems;
    wipWorkRequisition.value.mnt_item_name = value?.mntWorkRequisitionItem?.MntItem;
    wipWorkRequisition.value.mnt_item_id = value?.mntWorkRequisitionItem?.MntItem?.id;

    wipWorkRequisition.value.mntWorkRequisitionLines = value?.mntWorkRequisitionLines;
    // wipWorkRequisition.value.mntWorkRequisitionMaterials = value?.mntWorkRequisitionMaterials?.length ? value?.mntWorkRequisitionMaterials : [
    //         {
    //             material_name_and_code: '',
    //             specification: '',
    //             unit: '',
    //             quantity: 0,
    //             remarks: '',
    //         }
    //     ];

    // workRequisition.value.form_type = 'edit';
  });
  
  onMounted(() => {
      showWipWorkRequisition(wipWorkRequisitionId);
  });
  </script>