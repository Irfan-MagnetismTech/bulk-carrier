
<script setup>
import { onMounted, watchEffect } from 'vue';
import { useRoute } from 'vue-router';
import Title from '../../../services/title';
import useCurrency from '../../../composables/dataencoding/useCurrency';
import Heading from '../../../components/Heading.vue';
import CurrencyForm from '../../../components/dataencoding/CurrencyForm.vue';

const route = useRoute();
const currencyId = route.params.currencyId;
const { currency, isLoading, showCurrency, updateCurrency, errors } = useCurrency();
const { setTitle } = Title();

setTitle('Edit Currency');

onMounted(() => {
    showCurrency(currencyId);
});
</script>
<template>
    <!-- Heading -->
    <heading label="Edit Currency" type="list" :to="{ name: 'dataencoding.currency.index' }"></heading>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="updateCurrency(currency, currencyId)">
            <!-- Vessel Form -->
            <CurrencyForm v-model:form="currency"></CurrencyForm>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="btn-submit-default">Update Currency</button>
        </form>
    </div>
</template>