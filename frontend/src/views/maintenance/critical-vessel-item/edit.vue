<template>
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
      <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Update Critical Vessel Item</h2>
     
      <default-button :title="'Critical Vessel Item List'" :to="{ name: 'mnt.critical-vessel-items.index' }" :icon="icons.DataBase"></default-button>
    </div>
      <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
          <form @submit.prevent="updateCriticalVesselItem(criticalVesselItem, criticalVesselItemId)"> 
              <!-- Booking Form -->
            <critical-vessel-item-form :page="page" v-model:form="criticalVesselItem" :errors="errors"></critical-vessel-item-form>
              <!-- Submit button -->
              <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update Critical Vessel Item</button>
          </form>
      </div>
  </template>
  <script setup>
  import {onMounted, ref, watch} from 'vue';
  import { useRoute } from 'vue-router';
  
  import CriticalVesselItemForm from '../../../components/maintenance/critical-vessel-item/CriticalVesselItemForm.vue'; 
  import Title from "../../../services/title";
  import useCriticalVesselItem from '../../../composables/maintenance/useCriticalVesselItem';
  import useHeroIcon from "../../../assets/heroIcon";
  import DefaultButton from '../../../components/buttons/DefaultButton.vue';
  
  const route = useRoute();
  const criticalVesselItemId = route.params.criticalVesselItemId;
  const { criticalVesselItem, showCriticalVesselItem, updateCriticalVesselItem, errors } = useCriticalVesselItem();
  const icons = useHeroIcon();
  
  const { setTitle } = Title();
  const page = 'edit';
  
  setTitle('Edit Critical Vessel Item');

  watch(criticalVesselItem, (value) => {
    criticalVesselItem.value.ops_vessel_name = value?.opsVessel;
    criticalVesselItem.value.mnt_critical_function_name = value?.mntCriticalItem?.mntCriticalItemCat?.mntCriticalFunction;
    criticalVesselItem.value.mnt_critical_item_cat_name = value?.mntCriticalItem?.mntCriticalItemCat;
    criticalVesselItem.value.mnt_critical_item_name = value?.mntCriticalItem;
  });
  
  onMounted(() => {
      showCriticalVesselItem(criticalVesselItemId);
  });
  </script>