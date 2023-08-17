
<script setup>
import { onMounted, watchEffect } from 'vue';
import Title from '../../../services/title';
import useCurrency from '../../../composables/dataencoding/useCurrency';
import Heading from '../../../components/Heading.vue';
import CurrencyForm from '../../../components/dataencoding/CurrencyForm.vue';

const { currency, isLoading, storeCurrency, errors } = useCurrency();
const { setTitle } = Title();

setTitle('Create currency');
</script>
<template>
    <!-- Heading -->
    <heading label="Create Currency" type="list" :to="{ name: 'dataencoding.currency.index' }"></heading>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="storeCurrency(currency)">
            <!-- Vessel Form -->
            <CurrencyForm v-model:form="currency" :errors="errors"></CurrencyForm>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="btn-submit-default">Create Currency</button>
        </form>
    </div>
</template>