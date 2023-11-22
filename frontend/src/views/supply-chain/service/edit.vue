<script setup>
import ServiceForm from '../../../components/supply-chain/service/ServiceForm.vue';
import {ref} from "vue";
import { onMounted } from '@vue/runtime-core';
import Title from "../../../services/title";
import useService from "../../../composables/supply-chain/useService.js";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();
import { useRoute } from 'vue-router';
import { useStore } from "vuex";
const store = useStore();

const { service, showService, updateService,isLoading,errors } = useService();
const { setTitle } = Title();
const route = useRoute();
const serviceId = route.params.serviceId;


setTitle('Edit Service');

onMounted(() => {
    showService(serviceId);
    
});

</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 ">Edit Service</h2>
        <default-button :title="'Service List'" :to="{ name: 'scm.service.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md ">
        <form @submit.prevent="updateService(service, serviceId)">
            <service-form v-model:form="service" :errors="errors"></service-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600  hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>
