
<script setup>
import { onMounted, watchEffect } from 'vue';
import Title from '../../../services/title';
import useChargeType from '../../../composables/dataencoding/useChargeType';
import Heading from '../../../components/Heading.vue';
import ChargeTypeForm from '../../../components/dataencoding/ChargeTypeForm.vue';

const { chargeType, isLoading, storeChargeType, errors } = useChargeType();
const { setTitle } = Title();

setTitle('Create Revenue Head');
</script>
<template>
    <!-- Heading -->
    <heading label="Create Revenue Head" type="list" :to="{ name: 'dataencoding.chargetypes.index' }"></heading>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="storeChargeType(chargeType)">
            <!-- Vessel Form -->
            <ChargeTypeForm v-model:form="chargeType" :errors="errors"></ChargeTypeForm>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="btn-submit-default">Create Charge Type</button>
        </form>
    </div>
</template>