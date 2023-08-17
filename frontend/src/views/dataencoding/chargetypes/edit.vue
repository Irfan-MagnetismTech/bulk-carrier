
<script setup>
import { onMounted, watchEffect } from 'vue';
import { useRoute } from 'vue-router';
import Title from '../../../services/title';
import useChargeType from '../../../composables/dataencoding/useChargeType';
import Heading from '../../../components/Heading.vue';
import ChargeTypeForm from '../../../components/dataencoding/ChargeTypeForm.vue';

const route = useRoute();
const chargeTypeId = route.params.chargeTypeId;
const { chargeType, isLoading, showChargeType, updateChargeType, errors } = useChargeType();
const { setTitle } = Title();

setTitle('Edit Revenue Head');

onMounted(() => {
    showChargeType(chargeTypeId);
});
</script>
<template>
    <!-- Heading -->
    <heading label="Edit Revenue Head" type="list" :to="{ name: 'dataencoding.chargetypes.index' }"></heading>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="updateChargeType(chargeType, chargeTypeId)">
            <!-- Vessel Form -->
            <ChargeTypeForm v-model:form="chargeType" :errors="errors"></ChargeTypeForm>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="btn-submit-default">Update Charge Type</button>
        </form>
    </div>
</template>