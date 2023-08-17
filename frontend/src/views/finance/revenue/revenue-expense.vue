<script setup>
    import { onMounted, watch } from 'vue';
    import Heading from '../../../components/Heading.vue';
    import useVoyageExpenditureHead from "../../../composables/finance/useVoyageExpenditureHead";
    import usePortExpenditureHead from "../../../composables/finance/usePortExpenditureHead";
    import ReportForm from "../../../components/finance/RevenueVsExpenseReportForm.vue";
    import {useRoute} from "vue-router";
    import useVoyageExpense from "../../../composables/finance/useVoyageExpense";
    import useVoyagePairing from "../../../composables/finance/useVoyagePairing";

    const { heads, getHeads, isLoading, errors } = useVoyageExpenditureHead();
    const { portExpenditureHead, storePortHead} = usePortExpenditureHead();
    const route = useRoute();
    const pairId = route.params.pairId;
    const { pairs, showVoyagePair } = useVoyagePairing();

</script>
<template>
    <!-- Heading -->
    <heading :to="{ name: 'finance.revenue-and-expense' }" :type="'list'" :label="'Profitability Report'"></heading>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <!-- Voyage Expenditure Head Form -->

            <report-form v-model:form="expense" :heads="heads" :errors="errors"></report-form>

            <!-- Submit button -->
    </div>
</template>