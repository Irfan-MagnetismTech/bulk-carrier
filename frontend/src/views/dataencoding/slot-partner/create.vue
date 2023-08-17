
<script setup>
import { onMounted, watchEffect } from 'vue';
import Title from '../../../services/title';
import useSlotPartner from '../../../composables/dataencoding/useSlotPartner';
import Heading from '../../../components/Heading.vue';
import SlotPartnerForm from '../../../components/dataencoding/SlotPartnerForm.vue';

const { partner, isLoading, storeSlotPartner, errors } = useSlotPartner();
const { setTitle } = Title();

setTitle('Create slot partner');
</script>
<template>
    <!-- Heading -->
    <heading label="Create Slot Partner" type="list" :to="{ name: 'dataencoding.slot-partner.index' }"></heading>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="storeSlotPartner(partner)">
            <!-- Vessel Form -->
            <SlotPartnerForm v-model:form="partner" :errors="errors"></SlotPartnerForm>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="btn-submit-default">Create Slot Partner</button>
        </form>
    </div>
</template>