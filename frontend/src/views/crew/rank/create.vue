<script setup>
import RankForm from '../../../components/crew/RankForm.vue';
import useRank from '../../../composables/crew/useRank';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
const icons = useHeroIcon();
const { rank, storeRank, isLoading, errors } = useRank();
const { setTitle } = Title();

setTitle('Create Rank');
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3 " v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Create New Rank</h2>
      <default-button :title="'Rank List'" :to="{ name: 'crw.ranks.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800 overflow-hidden">
        <form @submit.prevent="storeRank(rank)">
            <!-- Booking Form -->
            <rank-form v-model:form="rank" :errors="errors"></rank-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm text-white bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
        </form>
    </div>
</template>