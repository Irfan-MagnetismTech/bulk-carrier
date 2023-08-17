<template>
    <!-- Heading -->
    <heading :to="{ name: 'operation.voyages.index' }" :type="'list'" :label="`Update Voyage: ${voyageId}`"></heading>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="updateVoyage(voyage, voyageId)">
            <!-- Voyage Information Form -->
            <voyage-form v-model:form="voyage" v-model:page="page" v-model:vessels="vessels" v-model:services="services" :errors="errors"></voyage-form>
            <!-- Submit button -->
        </form>
    </div>
</template>
<script setup>
import { onMounted, watch, ref } from 'vue';
import { useRoute } from 'vue-router';
import moment from 'moment';
import Heading from '../../../components/Heading.vue';
import VoyageForm from '../../../components/operation/VoyageForm.vue';
import useVoyage from '../../../composables/operation/useVoyage';
import useVessel from '../../../composables/dataencoding/useVessel';
import useService from '../../../composables/commercial/useService';

const route = useRoute();
const voyageId = route.params.voyageId;
const { voyage, showVoyage, updateVoyage, isLoading, errors } = useVoyage();
const { vessels, getVesselWithoutPaginate } = useVessel();
const { services, sectors, bounds, getServices, serviceGroupById, serviceGroupByCode } = useService();
const page = ref('update');

watch(voyage, (value) => {

  if(value?.origin_port){
    voyage.value.port_origin_name = value.origin_port ?? '';
  }

  if(value?.discharge_port){
    voyage.value.port_discharge_name = value.discharge_port ?? '';
  }

  if(value?.next_port){
    voyage.value.next_port_name = value?.next_port ?? '';
  }

  if(value?.voyage_schedules?.length) {
    value?.voyage_schedules.forEach((schedule, index) => {
      voyage.value.voyage_schedules[index].port_code_name = schedule?.schedule_port ?? '';
    });
  }

  if(value?.previous_voyage_id) {
    voyage.value.previous_voyage_name = value?.previous_voyage
  }

  if(value?.voyage_schedules?.length) {
      value.voyage_schedules.forEach((schedule, index) => {

        if(moment(schedule.eta_date).isValid()) {
          voyage.value.voyage_schedules[index].eta_date = moment(schedule.eta_date).format('YYYY-MM-DD\THH:mm');
        } else{
          voyage.value.voyage_schedules[index].eta_date = null;
        }

        if(moment(schedule.etb_date).isValid()) {
          voyage.value.voyage_schedules[index].etb_date = moment(schedule.etb_date).format('YYYY-MM-DD\THH:mm');
        } else{
          voyage.value.voyage_schedules[index].etb_date = null;
        }

        if(moment(schedule.etd_date).isValid()) {
          voyage.value.voyage_schedules[index].etd_date = moment(schedule.etd_date).format('YYYY-MM-DD\THH:mm');
        } else{
          voyage.value.voyage_schedules[index].etd_date = null;
        }

        if(moment(schedule.commence_discharge).isValid()) {
          voyage.value.voyage_schedules[index].commence_discharge = moment(schedule.commence_discharge).format('YYYY-MM-DD\THH:mm');
        } else{
          voyage.value.voyage_schedules[index].commence_discharge = null;
        }

        if(moment(schedule.completed_discharge).isValid()) {
          voyage.value.voyage_schedules[index].completed_discharge = moment(schedule.completed_discharge).format('YYYY-MM-DD\THH:mm');
        } else{
          voyage.value.voyage_schedules[index].completed_discharge = null;
        }

        if(moment(schedule.commence_load).isValid()) {
          voyage.value.voyage_schedules[index].commence_load = moment(schedule.commence_load).format('YYYY-MM-DD\THH:mm');
        } else{
          voyage.value.voyage_schedules[index].commence_load = null;
        }

        if(moment(schedule.completed_load).isValid()) {
          voyage.value.voyage_schedules[index].completed_load = moment(schedule.completed_load).format('YYYY-MM-DD\THH:mm');
        } else{
          voyage.value.voyage_schedules[index].completed_load = null;
        }
      });
    }
    if(value) {

        voyage.value.arrival_date = value.arrival_date ? moment(value.arrival_date).format('YYYY-MM-DD\THH:mm') : null;
        voyage.value.departure_date = value.departure_date ? moment(value.departure_date).format('YYYY-MM-DD\THH:mm') : null;
        voyage.value.berthing_date = value.berthing_date ? moment(value.berthing_date).format('YYYY-MM-DD\THH:mm') : null;
        voyage.value.import_pilot_boarding_time = value.import_pilot_boarding_time ? moment(value.import_pilot_boarding_time).format('YYYY-MM-DD\THH:mm') : null;
        voyage.value.export_pilot_boarding_time = value.export_pilot_boarding_time ? moment(value.export_pilot_boarding_time).format('YYYY-MM-DD\THH:mm') : null;
        voyage.value.expire_date = value.expire_date ? moment(value.expire_date).format('YYYY-MM-DD') : null;
        voyage.value.message_compilation_time = value.message_compilation_time ? moment(value.message_compilation_time).format('YYYY-MM-DD\THH:mm') : null;
        voyage.value.time_of_preparation = value.time_of_preparation ? moment(value.time_of_preparation).format('YYYY-MM-DD\THH:mm') : null;

    }
});

onMounted(() => {
  getVesselWithoutPaginate();
  getServices();
  showVoyage(voyageId);
});

</script>