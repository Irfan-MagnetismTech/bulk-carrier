<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';

import AgencyBillForm from '../../../components/crew/AgencyBillForm.vue';
import useAgencyBill from '../../../composables/crew/useAgencyBill';
import Title from "../../../services/title";

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const route = useRoute();
const agencyBillId = route.params.agencyBillId;
const { agencyBill, showAgencyBill, updateAgencyBill, errors } = useAgencyBill();
const icons = useHeroIcon();

const { setTitle } = Title();

setTitle('Edit Agency Bill');

onMounted(() => {
  showAgencyBill(agencyBillId);
});
</script>
<template>
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 ">Update Agency Bill</h2>
    <default-button :title="'Agency Bill List'" :to="{ name: 'crw.agencyBills.index' }" :icon="icons.DataBase"></default-button>
  </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md ">
        <form @submit.prevent="updateAgencyBill(agencyBill, agencyBillId)">
            <!-- Booking Form -->
          <agency-bill-form v-model:form="agencyBill" :errors="errors"></agency-bill-form>
            <!-- Submit button -->
            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>