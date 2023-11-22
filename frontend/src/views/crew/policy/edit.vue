<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';

import PolicyForm from '../../../components/crew/PolicyForm.vue';
import usePolicy from '../../../composables/crew/usePolicy';
import Title from "../../../services/title";

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const route = useRoute();
const policyId = route.params.policyId;
const { policy, showPolicy, updatePolicy, errors } = usePolicy();
const icons = useHeroIcon();

const { setTitle } = Title();

setTitle('Edit Policy');

onMounted(() => {
  showPolicy(policyId);
});
</script>
<template>
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Policy</h2>
    <default-button :title="'Policy List'" :to="{ name: 'crw.policies.index' }" :icon="icons.DataBase"></default-button>
  </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="updatePolicy(policy, policyId)">
            <!-- Booking Form -->
          <policy-form v-model:form="policy" :errors="errors"></policy-form>
            <!-- Submit button -->
            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>