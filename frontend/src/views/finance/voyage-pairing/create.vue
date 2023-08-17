<script setup>
    import { onMounted, watch } from 'vue';
    import Heading from '../../../components/Heading.vue';
    import voyagePairingForm from "../../../components/finance/voyagePairingForm.vue";
    import {useRoute} from "vue-router";
    import useVoyage from "../../../composables/operation/useVoyage";
    import useVoyagePairing from "../../../composables/finance/useVoyagePairing";

    const route = useRoute();
    const voyageId = route.params.voyageId;
    const { voyage, voyageName, showVoyage } = useVoyage();
    const { pairs, errors, storeVoyagePair } = useVoyagePairing();


</script>
<template>
    <!-- Heading -->
    <heading :to="{ name: 'finance.voyage-pairing.index' }" :type="'list'" :label="'Voyage Pairing'"></heading>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="storeVoyagePair(pairs)">
            <!-- Voyage Expenditure Head Form -->
            <voyage-pairing-form v-model:form="pairs" :voyage="voyage" :voyageName="voyageName" :heads="heads" :errors="errors"></voyage-pairing-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="btn-submit-default">Save</button>
        </form>
    </div>
</template>