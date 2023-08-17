<script setup>
import {onMounted, watch} from 'vue';
import { useRoute } from 'vue-router';
import usePortExpenditureHead from "../../../composables/finance/usePortExpenditureHead";
import PortWiseHeadForm from "../../../components/finance/PortWiseHeadForm.vue";
import useVoyageExpenditureHead from "../../../composables/finance/useVoyageExpenditureHead";

const route = useRoute();
const headId = route.params.headId;
const { portExpenditureHead, showPortHead, storePortHead, isLoading, errors } = usePortExpenditureHead();
const { heads, getHeads } = useVoyageExpenditureHead();

watch(portExpenditureHead, (value) => {
  if(value?.port){
    portExpenditureHead.value.port_name = value?.port_details ?? '';
  }

}, { deep: true });

let page = "update";

onMounted(() => {
  showPortHead(headId);
  getHeads();
});
</script>
<template>
    <div class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2">
        <span>Update Port Wise Head:</span>
        <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700">#{{ headId }}</span>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="storePortHead(portExpenditureHead)">
            <!-- Booking Form -->
          <port-wise-head-form v-model:form="portExpenditureHead" :page="page" :heads="heads" :errors="errors"></port-wise-head-form>
            <!-- Submit button -->
            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>