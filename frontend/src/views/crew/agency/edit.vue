<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';

import AgencyForm from '../../../components/crew/AgencyForm.vue';
import useAgency from '../../../composables/crew/useAgency';
import Title from "../../../services/title";

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const route = useRoute();
const agencyId = route.params.agencyId;
const { agency, showAgency, updateAgency, errors } = useAgency();
const icons = useHeroIcon();

const { setTitle } = Title();

setTitle('Edit Agency');

onMounted(() => {
  showAgency(agencyId);
});
</script>
<template>
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Update Agency</h2>
    <default-button :title="'Agency List'" :to="{ name: 'crw.agencies.index' }" :icon="icons.DataBase"></default-button>
  </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="updateAgency(agency, agencyId)">
            <!-- Booking Form -->
          <agency-form v-model:form="agency" :errors="errors"></agency-form>
            <!-- Submit button -->
            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>