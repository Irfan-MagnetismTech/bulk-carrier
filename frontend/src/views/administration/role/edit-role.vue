<template>
    <div class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2">
        <span>Update Role:</span>
        <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700">#{{ permissionId }}</span>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="updateRole(role, roleId)">
            <!-- Booking Form -->
          <role-form v-model:form="role" :errors="errors"></role-form>
            <!-- Submit button -->
            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update Role</button>
        </form>
    </div>
</template>
<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';

import RoleForm from '../../../components/authorization/RoleForm.vue';
import useRole from '../../../composables/administration/useRole';
import Title from "../../../services/title";

const route = useRoute();
const roleId = route.params.roleId;
const { role, showRole, updateRole, errors } = useRole();

const { setTitle } = Title();

setTitle('Edit Role');

onMounted(() => {
    showRole(roleId);
});
</script>