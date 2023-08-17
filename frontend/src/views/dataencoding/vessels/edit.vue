
<script setup>
import { onMounted, watchEffect } from 'vue';
import { useRoute } from 'vue-router';
import Title from '../../../services/title';
import useVessel from '../../../composables/dataencoding/useVessel';
import Heading from '../../../components/Heading.vue';
import VesselForm from '../../../components/dataencoding/VesselForm.vue';

const route = useRoute();
const vesselId = route.params.vesselId;
const { vessel, isLoading, showVessel, updateVessel, errors } = useVessel();
const { setTitle } = Title();

setTitle('Edit vessel');

onMounted(() => {
    showVessel(vesselId);
});
</script>
<template>
    <!-- Heading -->
    <heading label="Update Vessel" type="list" :to="{ name: 'dataencoding.vessels.index' }"></heading>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="updateVessel(vessel, vesselId)">
            <!-- Vessel Form -->
            <VesselForm v-model:form="vessel" :errors="errors"></VesselForm>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="btn-submit-default">Update Vessel</button>
        </form>
    </div>
</template>