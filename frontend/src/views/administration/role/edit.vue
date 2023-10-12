<template>
  <div class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2">
    <span>Update Role:</span>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
    <form @submit.prevent="updateRole(role, roleId)">
      <!-- Booking Form -->
      <role-form v-model:form="role" :page="page" :errors="errors"></role-form>
      <!-- Submit button -->
      <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update Role</button>
    </form>
  </div>
</template>
<script setup>
import {onMounted, ref, watch, watchEffect} from 'vue';
import { useRoute } from 'vue-router';

import RoleForm from '../../../components/authorization/RoleForm.vue';
import useRole from '../../../composables/administration/useRole';
import Title from "../../../services/title";
import usePermission from "../../../composables/administration/usePermission";

const route = useRoute();
const roleId = route.params.roleId;
const { role, showRole, updateRole, errors, isLoading } = useRole();
const { permissions, getPermissions } = usePermission();

const { setTitle } = Title();
const page = ref('edit');

setTitle('Edit Role');

watch(() => permissions.value, (value) => {
  role.value.permissions = value;
  if(Object.entries(value).length !== 0) {
    Object.entries(role.value.permissions).forEach(([permissionMenuKey, permissionMenuData]) => {
      Object.entries(role.value.permissions[permissionMenuKey]).forEach(([permissionSubjectKey, permissionSubjectData]) => {
        Object.entries(role.value.permissions[permissionMenuKey][permissionSubjectKey]).forEach(([permissionIndex, permissionData]) => {
          // check permissionData.id exist in props.form.current_permissions array or not. if true, set is_checked to true
          if (role.value.current_permissions.includes(permissionData.id)) {
            role.value.permissions[permissionMenuKey][permissionSubjectKey][permissionIndex].is_checked = true;
          } else {
            role.value.permissions[permissionMenuKey][permissionSubjectKey][permissionIndex].is_checked = false;
          }
          //if all permissionData.is_checked is true, set permissionSubjectData.is_checked to true
          if (permissionData.is_checked === false) {
            role.value.permissions[permissionMenuKey][permissionSubjectKey].is_checked = false;
          } else {
            role.value.permissions[permissionMenuKey][permissionSubjectKey].is_checked = true;
          }
        });
        //if all permissionSubjectData.is_checked is true, set permissionMenuData.is_checked to true
        if (permissionSubjectData.is_checked === false) {
          role.value.permissions[permissionMenuKey].is_checked = false;
        } else {
          role.value.permissions[permissionMenuKey].is_checked = true;
        }
      });
    });
  }
});

onMounted(() => {
  watchEffect(() => {
    showRole(roleId);
    getPermissions(1, false);
  });
});
</script>