
<script setup>
import { onMounted, watchEffect } from 'vue';
import Title from '../../../services/title';
import useVessel from '../../../composables/dataencoding/useVessel';
import Heading from '../../../components/Heading.vue';
import VesselForm from '../../../components/dataencoding/VesselForm.vue';

const { vessel, isLoading, storeVessel, errors } = useVessel();
const { setTitle } = Title();

setTitle('Create vessel');
</script>
<template>
    <!-- Heading -->
    <heading label="Create Vessel" type="list" :to="{ name: 'dataencoding.vessels.index' }"></heading>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="storeVessel(vessel)">
            <!-- Vessel Form -->
            <VesselForm v-model:form="vessel" :errors="errors"></VesselForm>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="btn-submit-default">Create Vessel</button>
        </form>
    </div>
</template>