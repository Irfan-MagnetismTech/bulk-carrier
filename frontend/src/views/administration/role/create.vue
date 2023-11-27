<template>
  <!-- Heading -->
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Create New Role</h2>
    <router-link :to="{ name: 'administration.user.roles.index' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
      </svg>
    </router-link>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
    <form @submit.prevent="storeRole(role)">
      <!-- Booking Form -->
      <role-form v-model:form="role" :page="page" :errors="errors"></role-form>
      <!-- Submit button -->
      <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create Role</button>
    </form>
  </div>
</template>
<script setup>
import RoleForm from '../../../components/authorization/RoleForm.vue';
import useRole from '../../../composables/administration/useRole';
import {ref,onMounted,watch} from "vue";
import Title from "../../../services/title";
import useAdministrationCommonApiRequest from "../../../composables/administration/useAdministrationCommonApiRequest";
const { role, storeRole, isLoading, errors } = useRole();
const { permissions, getPermissionList } = useAdministrationCommonApiRequest();
const page = ref('create');

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { setTitle } = Title();

setTitle('Create Role');

watch(() => permissions.value, (value) => {
  if(Object.entries(value).length !== 0) {
    role.value.permissions = value;
  }
});

onMounted(() => {
  getPermissionList();
});
</script>