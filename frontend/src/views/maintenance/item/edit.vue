<template>
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
      <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Update Item</h2>
      <router-link :to="{ name: 'maintenance.item-group.index' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
        Item List
      </router-link>
    </div>
      <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
          <form @submit.prevent="updateItem(item, itemId)">
              <!-- Booking Form -->
            <item-form v-model:form="item" :errors="errors"></item-form>
              <!-- Submit button -->
              <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update Item</button>
          </form>
      </div>
  </template>
  <script setup>
  import {onMounted, ref, watch} from 'vue';
  import { useRoute } from 'vue-router';
  
  import ItemGroupForm from '../../../components/maintenance/item-group/ItemGroupForm.vue'; 
  import ItemForm from '../../../components/maintenance/item/ItemForm.vue';
  import Title from "../../../services/title";
  import useItem from '../../../composables/maintenance/useItem';
  
  const route = useRoute();
  const itemId = route.params.itemId;
  const { item, showItem, updateItem, errors } = useItem();
  
  const { setTitle } = Title();
  
  setTitle('Edit Item');
  
  onMounted(() => {
      showItem(itemId);
  });
  </script>