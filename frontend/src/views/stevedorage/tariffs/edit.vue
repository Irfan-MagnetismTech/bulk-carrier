
<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';

import TariffForm from '../../../components/stevedorage/TariffForm.vue';
import useTariff from '../../../composables/stevedorage/useTariff';
import Title from "../../../services/title";

const route = useRoute();
const tariffId = route.params.tariffId;
const { tariff, showTariff, updateTariff, errors } = useTariff();

const { setTitle } = Title();

setTitle('Edit Tariff');
const page = ref('update');

watch(() => tariff.value, (value) => {
  //find items from tariff_charges array where operation_type is 'loading'
  let loadingTS = value.tariff_charges.filter(item => item.operation_type === 'Loading' && item.load_status === 'TS');
  let loadingLC = value.tariff_charges.filter(item => item.operation_type === 'Loading' && item.load_status === 'LC');
  let dischargeTS = value.tariff_charges.filter(item => item.operation_type === 'Discharge' && item.load_status === 'TS');
  let dischargeLC = value.tariff_charges.filter(item => item.operation_type === 'Discharge' && item.load_status === 'LC');

  tariff.value.tariff_charges = [...loadingTS, ...loadingLC, ...dischargeTS, ...dischargeLC];




  //add serial no to tariff.value.tariff_heads array
  let tempIndex = 0;

  value.tariff_heads.forEach((item, index) => {
    item.serial_no = index + 1;
  });
  for (let i = 0; i < 4; i++) {
    for(let j = 0; j < value.tariff_heads.length; j++) {
      tariff.value.tariff_charges[tempIndex].serial_no = j + 1;
      tempIndex++;
    }
  }
  //alert(value.tariff_heads.length);
});

onMounted(() => {
  showTariff(tariffId);
});
</script>

<template>
    <div class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2">
        <span>Update Tariff:</span>
        <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700">#{{ tariffId }}</span>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="updateTariff(tariff, tariffId)">
            <!-- Booking Form -->
          <tariff-form v-model:form="tariff" v-model:page="page" :errors="errors"></tariff-form>
            <!-- Submit button -->
            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update Tariff</button>
        </form>
    </div>
</template>
