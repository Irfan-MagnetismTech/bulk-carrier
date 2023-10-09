<script setup>
import UserForm from '../../../components/authorization/UserForm.vue';
import useUser from '../../../composables/administration/useUser';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
const icons = useHeroIcon();
const { user, storeUser, isLoading, errors } = useUser();
const { setTitle } = Title();

setTitle('Create User');
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3 " v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Create New User</h2>
      <default-button :title="'User List'" :to="{ name: 'administration.users.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800 overflow-hidden">
        <form @submit.prevent="storeUser(user)">
            <!-- Booking Form -->
            <user-form v-model:form="user" :errors="errors"></user-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm text-white bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create User</button>
        </form>
    </div>
</template>