<script setup>
    import { onMounted, ref, watch } from 'vue';
    import Heading from '../../../components/Heading.vue';
    import budgetDetail from "../../../components/finance/budgetDetails.vue";
    import {useRoute} from "vue-router";
    import useVoyageBudget from "../../../composables/finance/useVoyageBudget";
    import useVoyage from "../../../composables/operation/useVoyage";
    import useVoyagePairing from "../../../composables/finance/useVoyagePairing";
    import moment from 'moment';

    const { budgets, budgetDetails, searchBudget } = useVoyageBudget();
    const route = useRoute();
    const { pairs, errors, showVoyagePair, updateVoyagePair } = useVoyagePairing();
    const { voyage, showVoyage } = useVoyage();
    
    const pairId = route.params.pairId;

    const budgetId = ref(0);

    const vesselId = ref(0);

    onMounted(() => {
        console.log(pairId)
        showVoyagePair(pairId);
    });

    
    watch(() => pairs, (value) => {
        if(value?.value?.voyage_budgets_id != null) {
            budgetId.value = value?.value?.voyage_budgets_id
            vesselId.value = value?.value?.first_voyage?.vessel_id
        } else {
            budgetId.value = 0
            vesselId.value = value?.value?.first_voyage?.vessel_id
        }
    }, {deep: true})
    

</script>
<template>
    <!-- Heading -->
    <heading :to="{ name: 'finance.voyage-pairing.index' }" :type="'list'" :label="'Budget Entry'"></heading>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <div>
                <h3 class="block">Voyage Info</h3>
                <table>
                    <tbody>
                        <tr>
                            <td>
                                {{ pairs?.combined_name }}
                                [ <strong>{{ pairs?.first_voyage?.voyage }}</strong> and  <strong>{{ pairs?.second_voyage?.voyage }}</strong>]
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Financial Closing: {{ moment(pairs?.financial_closing_date).format('DD/MM/YYYY') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>


            

            <!-- Voyage Expenditure Head Form -->
          <budget-detail :budgetId="budgetId" :vesselId="vesselId" v-model:form="budgetDetails" :heads="heads" :errors="errors"></budget-detail>
    </div>
</template>