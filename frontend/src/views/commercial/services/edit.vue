<template>
    <div class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2">
        <span>Update Service:</span>
        <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700">#{{ bookingId }}</span>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="updateService(service, serviceId)">
            <!-- Booking Form -->
          <service-form v-model:form="service" v-model:page="page" :errors="errors"></service-form>
            <!-- Submit button -->
            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update Service</button>
        </form>
    </div>
</template>
<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';

import ServiceForm from '../../../components/commercial/ServiceForm.vue';
import useService from '../../../composables/commercial/useService';
import Title from "../../../services/title";

const route = useRoute();
const serviceId = route.params.serviceId;
const { service, showService, updateService, errors } = useService();

const { setTitle } = Title();

setTitle('Edit Service');
const page = ref('update');

watch(service, (value) => {
  if(value?.sectors?.length) {
    value.sectors.forEach((sector, index) => {
      service.value.sectors[index].pol_code_name = sector?.sector_pol ?? '';
      service.value.sectors[index].pod_code_name = sector?.sector_pod ?? '';
    });
  }
});

onMounted(() => {
    showService(serviceId);
});
</script>