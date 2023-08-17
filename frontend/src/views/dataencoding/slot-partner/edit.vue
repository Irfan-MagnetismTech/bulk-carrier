
<script setup>
import { onMounted, watchEffect } from 'vue';
import { useRoute } from 'vue-router';
import Title from '../../../services/title';
import useSlotPartner from '../../../composables/dataencoding/useSlotPartner';
import Heading from '../../../components/Heading.vue';
import SlotPartnerForm from '../../../components/dataencoding/SlotPartnerForm.vue';

const route = useRoute();
const partnerId = route.params.partnerId;
const { partner, isLoading, showSlotPartner, updateSlotPartner, errors } = useSlotPartner();
const { setTitle } = Title();

setTitle('Edit Slot Partner');

onMounted(() => {
    showSlotPartner(partnerId);
});
</script>
<template>
    <!-- Heading -->
    <heading label="Edit Slot Partner" type="list" :to="{ name: 'dataencoding.slot-partner.index' }"></heading>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="updateSlotPartner(partner, partnerId)">
            <!-- Vessel Form -->
            <SlotPartnerForm v-model:form="partner"></SlotPartnerForm>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="btn-submit-default">Update Slot Partner</button>
        </form>
    </div>
</template>