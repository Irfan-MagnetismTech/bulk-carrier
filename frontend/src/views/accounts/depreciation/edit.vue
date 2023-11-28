<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';

import DepreciationForm from '../../../components/accounts/DepreciationForm.vue';
import useDepreciation from '../../../composables/accounts/useDepreciation';
import Title from "../../../services/title";

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const route = useRoute();
const depreciationId = route.params.depreciationId;
const { depreciation, showDepreciation, updateDepreciation, bgColor, errors } = useDepreciation();
const icons = useHeroIcon();

const { setTitle } = Title();

setTitle('Depreciation');

const page = 'edit';

watch(depreciation, (value) => {
  if(value) {
    depreciation.value.acc_cost_center_name = depreciation.value.costCenter;
  }
});

onMounted(() => {
  showDepreciation(depreciationId);
});

</script>
<template>
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200"> Update Depreciation</h2>
    <default-button :title="'Depreciation'" :to="{ name: 'acc.depreciations.index' }" :icon="icons.DataBase"></default-button>
  </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800" :style="{ 'background-color': bgColor }">
        <form @submit.prevent="updateDepreciation(depreciation, depreciationId)">
            <!-- Booking Form -->
          <depreciation-form :page="page" v-model:form="depreciation" :errors="errors"></depreciation-form>
            <!-- Submit button -->
            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>