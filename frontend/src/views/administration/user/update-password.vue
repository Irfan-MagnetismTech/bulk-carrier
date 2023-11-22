<template>
    <div class="my-6 text-2xl font-semibold text-gray-700  flex items-center gap-2">
        <span>Update User:</span>
        <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full  ">#{{ userId }}</span>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md ">
        <form @submit.prevent="updateUserPassword(user)">
            <!-- Booking Form -->
          <update-user-password-form v-model:form="user" :errors="errors"></update-user-password-form>
            <!-- Submit button -->
            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update Password</button>
        </form>
    </div>
</template>
<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';

import useUser from '../../../composables/administration/useUser';
import Title from "../../../services/title";
import UpdateUserPasswordForm from "../../../components/authorization/UpdateUserPasswordForm.vue";

const route = useRoute();
// userid get from store
const userId = Store.getters.getCurrentUser.id
const { user, showUser, updateUserPassword, errors } = useUser();

const { setTitle } = Title();

setTitle('Update User Password');

onMounted(() => {
    showUser(userId);
});
</script>