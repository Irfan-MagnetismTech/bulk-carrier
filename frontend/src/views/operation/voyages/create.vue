<template>
    <!-- Heading -->
    <heading :to="{ name: 'operation.voyages.index' }" :type="'list'" :label="'Create Voyage'"></heading>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="storeVoyage(voyage)">
            <!-- Voyage Information Form -->
            <voyage-form v-model:form="voyage" v-model:page="page" v-model:lastVoyageId="lastVoyageId" v-model:vessels="vessels" :errors="errors" v-model:services="services"></voyage-form>
            <!-- Submit button -->
        </form>
    </div>
</template>
<script setup>
import { onMounted, watch, ref } from 'vue';
import Heading from '../../../components/Heading.vue';
import VoyageForm from '../../../components/operation/VoyageForm.vue';
import useVoyage from '../../../composables/operation/useVoyage';
import useVessel from '../../../composables/dataencoding/useVessel';
import useService from '../../../composables/commercial/useService';
import moment from "moment";
import Title from "../../../services/title";


const { voyage, storeVoyage, getLastVoyageId, lastVoyageId, isLoading, errors } = useVoyage();
const { vessels, getVesselWithoutPaginate } = useVessel();
const { services, bounds, getServices, serviceGroupById, serviceGroupByCode } = useService();

const page = ref('create');

const { setTitle } = Title();

setTitle('Create Voyage');

watch(lastVoyageId, (value) => {
    
    voyage.value.document_no = "HRL"+value;
    voyage.value.sender = "HRL";
    voyage.value.message_reference_no = "HRL"+value;
    voyage.value.time_of_preparation = moment().format('YYYY-MM-DD\THH:mm')
    voyage.value.message_compilation_time = moment().format('YYYY-MM-DD\THH:mm')
    
});

// watch(() => voyage, (value) => {
//     alert(value);
//     voyage.document_no = lastVoyageId;
//     voyage.message_reference_no = lastVoyageId;
//   }, {deep: true});

onMounted(() => {
  getVesselWithoutPaginate();
  getServices();
  getLastVoyageId();
});
</script>