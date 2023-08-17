<script setup>
    import { onMounted, watch } from 'vue';
    import Heading from '../../../components/Heading.vue';
    import useVoyageExpenditureHead from "../../../composables/finance/useVoyageExpenditureHead";
    import usePortExpenditureHead from "../../../composables/finance/usePortExpenditureHead";
    import VoyageExpenseEntryForm from "../../../components/finance/voyageExpenseEntryForm.vue";
    import {useRoute} from "vue-router";
    import useVoyageExpense from "../../../composables/finance/useVoyageExpense";
    import useVoyagePairing from "../../../composables/finance/useVoyagePairing";

    const { heads, getHeads, isLoading, errors } = useVoyageExpenditureHead();
    const { portExpenditureHead, storePortHead} = usePortExpenditureHead();

    const { expense, storeVoyageExpense} = useVoyageExpense();

    const route = useRoute();
    const pairId = route.params.pairId;
    const { pairs, showVoyagePair } = useVoyagePairing();



</script>
<template>
    <!-- Heading -->
    <heading :to="{ name: 'finance.voyages.index' }" :type="'list'" :label="'Expense Entry'"></heading>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="storeVoyageExpense(expense)">
            <!-- Voyage Expenditure Head Form -->
          <voyage-expense-entry-form v-model:form="expense" :heads="heads" :errors="errors"></voyage-expense-entry-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="btn-submit-default">Save</button>
        </form>
    </div>
</template>