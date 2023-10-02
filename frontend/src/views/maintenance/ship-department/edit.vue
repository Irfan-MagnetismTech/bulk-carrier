<template>
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
      <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Update Ship Department</h2>
      <router-link :to="{ name: 'maintenance.ship-department.index' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
        Ship Department List
      </router-link>
    </div>
      <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
          <form @submit.prevent="updateShipDepartment(shipDepartment, shipDepartmentId)">
              <!-- Booking Form -->
            <ship-department-form v-model:form="shipDepartment" :errors="errors"></ship-department-form>
              <!-- Submit button -->
              <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update Ship Department</button>
          </form>
      </div>
  </template>
  <script setup>
  import {onMounted, ref, watch} from 'vue';
  import { useRoute } from 'vue-router';
  
  import ShipDepartmentForm from '../../../components/maintenance/ship-department/ShipDepartmentForm.vue';
  import Title from "../../../services/title";
import useShipDepartment from '../../../composables/maintenance/useShipDepartment';
  
  const route = useRoute();
  const shipDepartmentId = route.params.shipDepartmentId;
  const { shipDepartment, showShipDepartment, updateShipDepartment, errors } = useShipDepartment();
  
  const { setTitle } = Title();
  
  setTitle('Edit Ship Department');
  
  onMounted(() => {
      showShipDepartment(shipDepartmentId);
  });
  </script>