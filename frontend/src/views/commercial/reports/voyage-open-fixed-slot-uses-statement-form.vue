<script setup>
import {onMounted, watch, ref} from 'vue';
import { useRoute } from 'vue-router';

import useCustomer from "../../../composables/commercial/useCustomer";
import useVoyageSlotUsesStatementReport from '../../../composables/commercial/useVoyageSlotUsesStatementReport';
import useVoyage from "../../../composables/operation/useVoyage";
import VoyageOpenSlotUsesStatement from "./voyage-open-slot-uses-statement.vue";
import VoyageSlotUsesStatement from "./voyage-slot-uses-statement.vue";

const route = useRoute();

const voyageId = route.params.voyageId ?? null;
const customerId = route.params.customerId ?? null;
const sector = route.params.sector ?? null;
const rate_type = route.params.rateType ?? null;

const { customers, getCustomers, getCustomerWithoutPaginate } = useCustomer();
const { voyages, voyageCustomers, voyageName, getVoyageName, getVoyageCustomer, rateType, getRateTypeOfVoyageCustomer } = useVoyage();

const { formParams, voyageInfo, voyageSlotUsesStatementReport, downloadVoyageSlotUseStatement, isLoading } = useVoyageSlotUsesStatementReport();

function fetchOptions(search) {
  getVoyageName(search);
}

watch(formParams, (value) => {
  getVoyageCustomer(value.voyage_id);
  if(value.voyage_id != null && value.customer_id != null) {
    let data = {
      voyage_id: value.voyage_id,
      customer_id: value.customer_id,
    };
    getRateTypeOfVoyageCustomer(data);
  }
}, {deep: true});

watch(rateType, (value) => {
  if (value) {
    if(value.rate_type === 'Fixed') {
      formParams.value.sector = null;
    }
    formParams.value.rate_type = value.rate_type;
  }
});

onMounted(() => {
    getCustomerWithoutPaginate();
    if(voyageId) {
      let data = {
        voyage_id: voyageId,
        customer_id: customerId,
        sector: sector,
        rate_type: rate_type,
      };
      //voyageSlotUsesStatementReport(data);
    }
});

</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
          Voyage Slot Usages Statement          
        </h2>
    </div>
    <!-- Table -->
    <div class="px-4 py-3 mb-2 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form class="flex items-end justify-between gap-4" @submit.prevent="voyageSlotUsesStatementReport(formParams)">
            <label class="block w-full text-sm">
                <span class="text-gray-700 dark:text-gray-300">Voyage No <span class="text-red-500">*</span></span>
              <v-select :options="voyageName" placeholder="--Choose an option--" @search="fetchOptions" v-model="formParams.voyage_id" label="voyage" :reduce="voyageName => voyageName.id" required>
                <template #search="{attributes, events}">
                  <input class="vs__search" :required="!formParams.voyage_id" v-bind="attributes" v-on="events"/>
                </template>
              </v-select>
            </label>

            <label class="block w-full text-sm">
                <span class="text-gray-700 dark:text-gray-300">Customer Code <span class="text-red-500">*</span></span>
                <v-select :options="voyageCustomers" placeholder="--Choose an option--" v-model="formParams.customer_id" :reduce="voyageCustomers => voyageCustomers.customer_id" label="customer_code" required>
                  <input class="vs__search" :required="!formParams.customer_id" v-bind="attributes" v-on="events"/>
                </v-select>
            </label>
          <label class="block w-full text-sm" v-if="rateType?.rate_type == 'Open'">
            <span class="text-gray-700 dark:text-gray-300">Port</span>
            <v-select :options="rateType?.sectors" placeholder="--Choose an option--" v-model="formParams.sector" required></v-select>
            <!--            <select v-model="formParams.sector" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">-->
<!--              <option value="">Select Port</option>-->
<!--              <option v-for="(sector,index) in rateType?.sectors">{{ sector }}</option>-->
<!--            </select>-->
          </label>

          <input type="hidden" v-model="formParams.rate_type">

            <!-- Submit button -->
            <button type="submit" :disabled="isLoading"
                class="flex items-center justify-between px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Search</button>
        </form>
    </div>

    <div v-if="Object.keys(voyageInfo).length != 0" class="flex items-end mt-2 justify-between gap-4">
      <h1 class="w-full">
        <!-- {{voyageInfo}} -->
        A/C:
        <strong>{{ voyageInfo?.customerCode }} - {{ voyageInfo?.customerName }}</strong>
      </h1>
      <h1 class="w-full">
        VVD :
        <strong>{{ voyageInfo?.vesselName }} - {{ voyageInfo?.voyageNo }}</strong>
      </h1>
      <h1 class="w-full">
        Contract Type :
        <strong class="uppercase">{{ voyageInfo?.rateType }}</strong>
      </h1>
      <div title="Print" @click="downloadVoyageSlotUseStatement(formParams)" class="w-14 px-4 cursor-pointer py-2 text-sm text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
        </svg>
      </div>
    </div>

    <template v-if="Object.keys(voyageInfo).length != 0">
      <template v-if="rateType?.rate_type == 'Fixed' || rate_type == 'Fixed'">
        <voyage-slot-uses-statement v-model:voyageInfo="voyageInfo"></voyage-slot-uses-statement>
      </template>
      <template v-if="rateType?.rate_type == 'Open' || rate_type == 'Open'">
        <voyage-open-slot-uses-statement v-model:voyageInfo="voyageInfo"></voyage-open-slot-uses-statement>
      </template>
    </template>
    <template v-else>
      <div class="px-2 py-3 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <h1 class="w-full text-center vms-no-data-found">Sorry! No data found</h1>
      </div>
    </template>

</template>
<style lang="postcss" scoped>
@tailwind components;

@layer components {
    th {
        @apply p-10 border-r text-center;
    }

    tbody td,
    thead td,
    thead th {
        @apply px-1 py-1 text-xs border-r text-center;
    }
}
>>> {
  --vs-controls-color: #374151;
  --vs-border-color: #4b5563;

  --vs-dropdown-bg: #282c34;
  --vs-dropdown-color: #eeeeee;
  --vs-dropdown-option-color: #eeeeee;

  --vs-selected-bg: #664cc3;
  --vs-selected-color: #374151;

  --vs-search-input-color: #4b5563;

  --vs-dropdown-option--active-bg: #664cc3;
  --vs-dropdown-option--active-color: #eeeeee;
}
</style>