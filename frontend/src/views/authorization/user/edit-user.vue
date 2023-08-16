<template>
    <div class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2">
        <span>Update User:</span>
        <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700">#{{ userId }}</span>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="updateUser(user, userId)">
            <!-- Booking Form -->
          <user-form v-model:form="user" :branches="branches" :errors="errors"></user-form>
            <!-- Submit button -->
            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update User</button>
        </form>
    </div>
</template>
<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';

import UserForm from '../../../components/authorization/UserForm.vue';
import useUser from '../../../composables/authorization/useUser';
import Title from "../../../services/title";
import useBranch from "../../../composables/configuration/useBranch";

const route = useRoute();
const userId = route.params.userId;
const { user, showUser, updateUser, errors } = useUser();

const { setTitle } = Title();

setTitle('Edit User');

const { branches, getBranches } = useBranch();

onMounted(() => {
    getBranches();
    showUser(userId);
});
</script>