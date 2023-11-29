<template>
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
      <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Critical Item</h2>
     
      <default-button :title="'Critical Item List'" :to="{ name: 'mnt.critical-items.index' }" :icon="icons.DataBase"></default-button>
    </div>
      <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
          <form @submit.prevent="updateCriticalItem(criticalItem, criticalItemId)"> 
              <!-- Booking Form -->
            <critical-item-form :page="page" v-model:form="criticalItem" :errors="errors"></critical-item-form>
              <!-- Submit button -->
              <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
          </form>
      </div>
  </template>
  <script setup>
  import {onMounted, ref, watch} from 'vue';
  import { useRoute } from 'vue-router';
  
  import CriticalItemForm from '../../../components/maintenance/critical-item/CriticalItemForm.vue'; 
  import Title from "../../../services/title";
  import useCriticalItem from '../../../composables/maintenance/useCriticalItem';
  import useHeroIcon from "../../../assets/heroIcon";
  import DefaultButton from '../../../components/buttons/DefaultButton.vue';
  
  const route = useRoute();
  const criticalItemId = route.params.criticalItemId;
  const { criticalItem, showCriticalItem, updateCriticalItem, errors } = useCriticalItem();
  const icons = useHeroIcon();
  
  const { setTitle } = Title();
  const page = 'edit';
  
  setTitle('Edit Critical Item');

  watch(criticalItem, (value) => {
    criticalItem.value.mnt_critical_function_name = value?.mntCriticalItemCat?.mntCriticalFunction;

    criticalItem.value.mnt_critical_item_cat_name = value?.mntCriticalItemCat;
    criticalItem.value.mntItemCategories = value?.mntCriticalItemCat?.mntCriticalFunction?.mntCriticalItemCats;
  });
  
  onMounted(() => {
      showCriticalItem(criticalItemId);
  });
  </script>