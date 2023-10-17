<script setup>
// import UserForm from '../../../components/authorization/UserForm.vue';
import ItemGroupForm from '../../../components/maintenance/item-group/ItemGroupForm.vue';
import ItemForm from '../../../components/maintenance/item/ItemForm.vue';
import useItem from '../../../composables/maintenance/useItem';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
const icons = useHeroIcon();
const { item, storeItem, isLoading, errors } = useItem();
const { setTitle } = Title();

setTitle('Create Item');
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-3 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Create New Item</h2>
        <!-- <router-link :to="{ name: 'mnt.items.index' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            Item List
        </router-link> -->
        <default-button :title="'Item List'" :to="{ name: 'mnt.items.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="storeItem(item)">
            <!-- Booking Form -->
            <item-form v-model:form="item" :errors="errors"></item-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm text-white bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create Item</button>
        </form>
    </div>
</template>