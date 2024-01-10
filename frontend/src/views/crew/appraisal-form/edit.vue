<template>
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
      <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Appraisal Form</h2>
      <!-- <router-link :to="{ name: 'mnt.items.index' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
        Item List
      </router-link> -->
      <default-button :title="'Appraisal Form List'" :to="{ name: 'crw.appraisal-forms.index' }" :icon="icons.DataBase"></default-button>
    </div>
      <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
          <form @submit.prevent="updateAppraisalForm(appraisalForm, appraisalFormId)">
              <!-- Booking Form -->
            <appraisal-form :page="page" v-model:form="appraisalForm" :appraisalFormLineObject="appraisalFormLineObject" :errors="errors"></appraisal-form>
              <!-- Submit button -->
              <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
          </form>
      </div>
  </template>
  <script setup>
  import {onMounted, ref, watch} from 'vue';
  import { useRoute } from 'vue-router';
  
  import AppraisalForm from '../../../components/crew/AppraisalForm.vue';
  import Title from "../../../services/title";
  import useAppraisalForm from '../../../composables/crew/useAppraisalForm';
  import useHeroIcon from "../../../assets/heroIcon";
  import DefaultButton from '../../../components/buttons/DefaultButton.vue';
  
  const route = useRoute();
  const appraisalFormId = route.params.appraisalFormId;
  const { appraisalForm, appraisalFormLineObject, showAppraisalForm, updateAppraisalForm, errors } = useAppraisalForm();
  const icons = useHeroIcon();
  
  const { setTitle } = Title();
  const page = 'edit';
  
  setTitle('Edit Appraisal Form');
  
  // watch(item, (value) => {
  //   item.value.mnt_ship_department_name = value?.mntItemGroup?.mntShipDepartment;
  //   item.value.mnt_item_groups = value?.mntItemGroup?.mntShipDepartment?.mntItemGroups;
  //   item.value.mnt_item_group_name = value?.mntItemGroup;
  //   item.value.form_type = 'edit';
  // });

  onMounted(() => {
      showAppraisalForm(appraisalFormId);
  });
  </script>