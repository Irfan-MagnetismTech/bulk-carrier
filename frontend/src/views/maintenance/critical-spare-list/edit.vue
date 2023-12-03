<template>
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
      <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Critical Spare List</h2>
     
      <default-button :title="'Critical Item List'" :to="{ name: 'mnt.critical-spare-lists.index' }" :icon="icons.DataBase"></default-button>
    </div>
      <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800 relative">
          <form @submit.prevent="updateCriticalSpareList(criticalSpareList, criticalSpareListId)"> 
              <!-- Booking Form -->
            <critical-spare-list-form :page="page" v-model:form="criticalSpareList" :errors="errors"></critical-spare-list-form>
              <!-- Submit button -->
              <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
          </form>
      </div>
  </template>
  <script setup>
  import {onMounted, ref, watch} from 'vue';
  import { useRoute } from 'vue-router';
  
  import CriticalSpareListForm from '../../../components/maintenance/critical-spare-list/CriticalSpareListForm.vue'; 
  import Title from "../../../services/title";
  import useCriticalSpareList from '../../../composables/maintenance/useCriticalSpareList';
  import useHeroIcon from "../../../assets/heroIcon";
  import DefaultButton from '../../../components/buttons/DefaultButton.vue';
  
  const route = useRoute();
  const criticalSpareListId = route.params.criticalSpareListId;
  const { criticalSpareList, showCriticalSpareList, updateCriticalSpareList, errors } = useCriticalSpareList();
  const icons = useHeroIcon();
  
  const { setTitle } = Title();
  const page = 'edit';
  
  setTitle('Edit Critical Spare List');

  watch(criticalSpareList, (value) => {
    criticalSpareList.value.ops_vessel = value?.opsVessel;
  });
  
  onMounted(() => {
      showCriticalSpareList(criticalSpareListId);
  });
  </script>