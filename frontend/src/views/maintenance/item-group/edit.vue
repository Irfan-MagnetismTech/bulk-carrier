<template>
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
      <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Item Group</h2>
      <!-- <router-link :to="{ name: 'mnt.item-groups.index' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
        Item Group List
      </router-link> -->
      <default-button :title="'Item Group List'" :to="{ name: 'mnt.item-groups.index' }" :icon="icons.DataBase"></default-button>
    </div>
      <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
          <form @submit.prevent="updateItemGroup(itemGroup, itemGroupId)">
              <!-- Booking Form -->
            <item-group-form :page="page" v-model:form="itemGroup" :errors="errors"></item-group-form>
              <!-- Submit button -->
              <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
          </form>
      </div>
  </template>
  <script setup>
  import {onMounted, ref, watch} from 'vue';
  import { useRoute } from 'vue-router';
  
  import ItemGroupForm from '../../../components/maintenance/item-group/ItemGroupForm.vue'; 
  import Title from "../../../services/title";
  import useItemGroup from '../../../composables/maintenance/useItemGroup';
  import useHeroIcon from "../../../assets/heroIcon";
  import DefaultButton from '../../../components/buttons/DefaultButton.vue';
  
  const route = useRoute();
  const itemGroupId = route.params.itemGroupId;
  const { itemGroup, showItemGroup, updateItemGroup, errors } = useItemGroup();
  const icons = useHeroIcon();
  
  const { setTitle } = Title();
  const page = 'edit';
  
  setTitle('Edit Item Group');

  watch(itemGroup, (value) => {
    itemGroup.value.mnt_ship_department_name = value?.mntShipDepartment;
  });
  
  onMounted(() => {
      showItemGroup(itemGroupId);
  });
  </script>