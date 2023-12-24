<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Voyage Budget</h2>
    <default-button :title="'Expense Head List'" :to="{ name: 'ops.voyage-budgets.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <form @submit.prevent="updateVoyageBudget(voyageBudget, voyageBudgetId)">
          <!-- Port Form -->
          <voyage-budget-form v-model:form="voyageBudget" :errors="errors" :formType="formType"></voyage-budget-form>
          <!-- Submit button -->
          <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
      </form>
  </div>
</template>
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import VoyageBudgetForm from '../../../components/operations/VoyageBudgetForm.vue';
import useVoyageBudget from '../../../composables/operations/useVoyageBudget';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";

const icons = useHeroIcon();

const route = useRoute();
const voyageBudgetId = route.params.voyageBudgetId;
const { voyageBudget, showVoyageBudget, updateVoyageBudget, errors } = useVoyageBudget();

const { setTitle } = Title();

setTitle('Edit Voyage Budget');

const formType = 'edit';

onMounted(() => {
  showVoyageBudget(voyageBudgetId);
});
</script>