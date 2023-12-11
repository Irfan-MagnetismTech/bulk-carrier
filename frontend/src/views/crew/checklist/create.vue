<script setup>
import CheckListForm from '../../../components/crew/CheckListForm.vue';
import useCheckList from '../../../composables/crew/useCheckList';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
const icons = useHeroIcon();
const { checkList, storeCheckList, isLoading, errors, duplicateIndexArray } = useCheckList();
const { setTitle } = Title();

setTitle('Create Onboard Check List');
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3 " v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Create Onboard Check List</h2>
      <default-button :title="'Onboard Check List'" :to="{ name: 'crw.checklists.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800 overflow-hidden">
        <form @submit.prevent="storeCheckList(checkList)">
            <!-- Booking Form -->
            <check-list-form v-model:form="checkList" :errors="errors" :duplicateIndexArray="duplicateIndexArray"></check-list-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm text-white bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
        </form>
    </div>
</template>