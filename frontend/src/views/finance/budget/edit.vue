<script setup>
    import { onMounted, watch } from 'vue';
    import Heading from '../../../components/Heading.vue';
    import budgetForm from "../../../components/finance/budgetForm.vue";
    import {useRoute} from "vue-router";
    import useVoyage from "../../../composables/operation/useVoyage";
    import useVoyageBudget from "../../../composables/finance/useVoyageBudget";
    import useVessel from '../../../composables/dataencoding/useVessel';
    import useService from '../../../composables/commercial/useService';


    const { budget, updateBudget, showBudget} = useVoyageBudget();
    const route = useRoute();   
    const { vessels, getVesselWithoutPaginate } = useVessel();
    const { services, getServices } = useService();
    const budgetId = route.params.budgetId;

    onMounted(() => {
        showBudget(budgetId);
        getVesselWithoutPaginate();
        getServices();
    });

</script>
<template>
    <!-- Heading -->
    <heading :to="{ name: 'finance.budget.index' }" :type="'list'" :label="'Update Budget'"></heading>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="updateBudget(budget, budgetId)">
            <!-- Voyage Expenditure Head Form -->
          <budget-form v-model:form="budget" :voyage="voyage" v-model:vessels="vessels" v-model:services="services" :heads="heads" :errors="errors"></budget-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="btn-submit-default ml-3">Update</button>
        </form>
    </div>
</template>