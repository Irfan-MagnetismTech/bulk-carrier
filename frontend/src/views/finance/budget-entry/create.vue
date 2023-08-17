<script setup>
    import { onMounted, watch } from 'vue';
    import Heading from '../../../components/Heading.vue';
    import useVoyageExpenditureHead from "../../../composables/finance/useVoyageExpenditureHead";
    import usePortExpenditureHead from "../../../composables/finance/usePortExpenditureHead";
    import budgetEntryForm from "../../../components/finance/budgetEntryForm.vue";
    import {useRoute} from "vue-router";
    import useVoyageBudget from "../../../composables/finance/useVoyageBudget";
    import useVoyagePairing from "../../../composables/finance/useVoyagePairing";

    const { heads, getHeads, isLoading, errors } = useVoyageExpenditureHead();
    const { portExpenditureHead, storePortHead} = usePortExpenditureHead();

    const { storeBudgetEntry, budgetDetails} = useVoyageBudget();

</script>
<template>
    <!-- Heading -->
    <heading :to="{ name: 'finance.budget.index' }" :type="'list'" :label="'Budget Entry'"></heading>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="storeBudgetEntry(budgetDetails)">
            <!-- Voyage Expenditure Head Form -->
          <budget-entry-form v-model:form="budgetDetails" :heads="heads" :errors="errors"></budget-entry-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="btn-submit-default">Save</button>
        </form>
    </div>
</template>