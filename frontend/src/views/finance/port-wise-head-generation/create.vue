<script setup>
    import { onMounted, watch } from 'vue';
    import Heading from '../../../components/Heading.vue';
    import useVoyageExpenditureHead from "../../../composables/finance/useVoyageExpenditureHead";
    import PortWiseHeadForm from "../../../components/finance/PortWiseHeadForm.vue";
    import usePortExpenditureHead from "../../../composables/finance/usePortExpenditureHead";
    import Title from "../../../services/title";

    const { heads, getHeads, isLoading, errors } = useVoyageExpenditureHead();
    const { portExpenditureHead, storePortHead} = usePortExpenditureHead();

    const { setTitle } = Title();

    setTitle('Assign Cost Group');

    let page = "create";

    onMounted(() => {
        getHeads();
    });
</script>
<template>
    <!-- Heading -->
    <heading :to="{ name: 'finance.port-wise-head-generation.index' }" :type="'list'" :label="'Assign Cost Group'"></heading>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="storePortHead(portExpenditureHead)">
            <!-- Voyage Expenditure Head Form -->    
          <port-wise-head-form v-model:form="portExpenditureHead" :page="page" :heads="heads" :errors="errors"></port-wise-head-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="btn-submit-default">Submit</button>
        </form>
    </div>
</template>