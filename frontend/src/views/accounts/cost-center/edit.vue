<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';

import CostCenterForm from '../../../components/accounts/CostCenterForm.vue';
import useCostCenter from '../../../composables/accounts/useCostCenter';
import Title from "../../../services/title";

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const route = useRoute();
const costCenterId = route.params.costCenterId;
const { costCenter, showCostCenter, updateCostCenter, errors } = useCostCenter();
const icons = useHeroIcon();

const { setTitle } = Title();

const page = 'edit';

setTitle('Edit Cost Center');

onMounted(() => {
  showCostCenter(costCenterId);
});
</script>
<template>
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 ">Update Cost Center</h2>
    <default-button :title="'Cost center List'" :to="{ name: 'acc.cost-centers.index' }" :icon="icons.DataBase"></default-button>
  </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md ">
        <form @submit.prevent="updateCostCenter(costCenter, costCenterId)">
            <!-- Booking Form -->
          <cost-center-form :page="page" v-model:form="costCenter" :errors="errors"></cost-center-form>
            <!-- Submit button -->
            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>