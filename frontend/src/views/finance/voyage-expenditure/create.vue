<script setup>
    import { onMounted, watch } from 'vue';
    import Heading from '../../../components/Heading.vue';
    import VoyageExpenditureHeadForm from '../../../components/finance/VoyageExpendtiureHeadForm.vue';
    import useVoyageExpenditureHead from "../../../composables/finance/useVoyageExpenditureHead";
    import Title from "../../../services/title";

    const { expenditureHead, storeHead, isLoading, errors } = useVoyageExpenditureHead();

    const { setTitle } = Title();

    setTitle('Create Cost Group');
</script>
<template>
    <!-- Heading -->
    <heading :to="{ name: 'finance.voyageExpenditureHead.index' }" :type="'list'" :label="'Create Cost Group'"></heading>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="storeHead(expenditureHead)">
            <!-- Voyage Expenditure Head Form -->    
            <voyage-expenditure-head-form v-model:form="expenditureHead" :errors="errors"></voyage-expenditure-head-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="btn-submit-default">Create Expenditure Group</button>
        </form>
    </div>
</template>