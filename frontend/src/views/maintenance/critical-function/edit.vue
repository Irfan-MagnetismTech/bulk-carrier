<template>
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
      <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Update Critical Function</h2>
     
      <default-button :title="'Critical Function List'" :to="{ name: 'mnt.critical-functions.index' }" :icon="icons.DataBase"></default-button>
    </div>
      <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
          <form @submit.prevent="updateCriticalFunction(criticalFunction, criticalFunctionId)"> 
              <!-- Booking Form -->
            <critical-function-form :page="page" v-model:form="criticalFunction" :errors="errors"></critical-function-form>
              <!-- Submit button -->
              <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update Critical Function</button>
          </form>
      </div>
  </template>
  <script setup>
  import {onMounted, ref, watch} from 'vue';
  import { useRoute } from 'vue-router';
  
  import CriticalFunctionForm from '../../../components/maintenance/critical-function/CriticalFunctionForm.vue'; 
  import Title from "../../../services/title";
  import useCriticalFunction from '../../../composables/maintenance/useCriticalFunction';
  import useHeroIcon from "../../../assets/heroIcon";
  import DefaultButton from '../../../components/buttons/DefaultButton.vue';
  
  const route = useRoute();
  const criticalFunctionId = route.params.criticalFunctionId;
  const { criticalFunction, showCriticalFunction, updateCriticalFunction, errors } = useCriticalFunction();
  const icons = useHeroIcon();
  
  const { setTitle } = Title();
  const page = 'edit';
  
  setTitle('Edit Critical Function');

  
  
  onMounted(() => {
      showCriticalFunction(criticalFunctionId);
  });
  </script>