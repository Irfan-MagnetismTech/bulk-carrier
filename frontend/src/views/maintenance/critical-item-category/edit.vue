<template>
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
      <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Update Critical Item Category</h2>
     
      <default-button :title="'Critical Item Category List'" :to="{ name: 'mnt.critical-item-categories.index' }" :icon="icons.DataBase"></default-button>
    </div>
      <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
          <form @submit.prevent="updateCriticalItemCategory(criticalItemCategory, criticalItemCategoryId)"> 
              <!-- Booking Form -->
            <critical-item-category-form :page="page" v-model:form="criticalItemCategory" :errors="errors"></critical-item-category-form>
              <!-- Submit button -->
              <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
          </form>
      </div>
  </template>
  <script setup>
  import {onMounted, ref, watch} from 'vue';
  import { useRoute } from 'vue-router';
  
  import CriticalItemCategoryForm from '../../../components/maintenance/critical-item-category/CriticalItemCategoryForm.vue'; 
  import Title from "../../../services/title";
  import useCriticalItemCategory from '../../../composables/maintenance/useCriticalItemCategory';
  import useHeroIcon from "../../../assets/heroIcon";
  import DefaultButton from '../../../components/buttons/DefaultButton.vue';
  
  const route = useRoute();
  const criticalItemCategoryId = route.params.criticalItemCategoryId;
  const { criticalItemCategory, showCriticalItemCategory, updateCriticalItemCategory, errors } = useCriticalItemCategory();
  const icons = useHeroIcon();
  
  const { setTitle } = Title();
  const page = 'edit';
  
  setTitle('Edit Critical Item Category');

  watch(criticalItemCategory, (value) => {
    criticalItemCategory.value.mnt_critical_function_name = value?.mntCriticalFunction;
  });
  
  onMounted(() => {
      showCriticalItemCategory(criticalItemCategoryId);
  });
  </script>