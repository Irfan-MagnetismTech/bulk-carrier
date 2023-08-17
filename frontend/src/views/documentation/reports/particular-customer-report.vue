
<script setup>
import {onMounted, watch, ref} from 'vue';
import { useRoute } from 'vue-router';
import Error from "../../../components/Error.vue";

import useParticularCustomerReport from '../../../composables/documentation/useParticularCustomerReport';
import useCustomer from "../../../composables/commercial/useCustomer";
import useService from "../../../composables/commercial/useService";
import useVessel from "../../../composables/dataencoding/useVessel";
import useVoyage from "../../../composables/operation/useVoyage";
import Title from "../../../services/title";

const route = useRoute();

const { customers, getCustomerWithoutPaginate } = useCustomer();
const { services, bounds, getServices, serviceGroupByCode } = useService();
const { vessels, vesselName, getVesselName } = useVessel();
const { voyages, voyageName, getVoyageName } = useVoyage();
const { formParams, customerLoadInfo, particularCustomerReport, errors, isLoading } = useParticularCustomerReport();

function getServiceBound(e){
  serviceGroupByCode(e.target.value);
}

const { setTitle } = Title();

setTitle('Particular Customer Report');

onMounted(() => {
  getCustomerWithoutPaginate();
  getServices();
  getVesselName();
  getVoyageName();
  //particularCustomerReport();
});


</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Particular Customer Report</h2>
    </div>
    <!-- Table -->
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form class="flex items-end justify-between gap-4" @submit.prevent="particularCustomerReport(formParams)">
            <label class="block w-full text-sm">
                <span class="text-gray-700 dark:text-gray-300">Customer Code <span class="text-red-500">*</span></span>
              <v-select :options="customers" class="form-input" v-model="formParams.customer_id" placeholder="Choose Option" :reduce="customer => customer.id" label="customer_code" required></v-select>
              <Error v-if="errors?.customer_id" :errors="errors?.customer_id" />
            </label>

            <label class="block w-full text-sm">
                <span class="text-gray-700 dark:text-gray-300">Service <span class="text-red-500">*</span></span>
              <select v-model="formParams.service_code" @change="getServiceBound($event)" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" required>
                <option value="">Choose Option</option>
                <option v-for="(service,index) in services" :value="service?.code" :key="index">{{ service?.name }} - ({{ service?.code }})</option>
              </select>
              <Error v-if="errors?.service_code" :errors="errors?.service_code" />
            </label>

            <label class="block w-full text-sm">
                <span class="text-gray-700 dark:text-gray-300">Bound <span class="text-red-500">*</span></span>
              <select v-model="formParams.bound" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" required>
                <option value="">Choose Option</option>
                <option v-for="(bound,index) in bounds" :value="bound" :key="index">{{ bound.toUpperCase() }}</option>
              </select>
              <Error v-if="errors?.bound" :errors="errors?.bound" />
            </label>

            <label class="block w-full text-sm">
                <span class="text-gray-700 dark:text-gray-300">Vessel Name</span>
              <v-select :options="vesselName" class="form-input" placeholder="Choose Option" v-model="formParams.vessel_name" label="name" required></v-select>
              <Error v-if="errors?.vessel_name" :errors="errors?.vessel_name" />
            </label>

            <label class="block w-full text-sm">
                <span class="text-gray-700 dark:text-gray-300">Voyage</span>
              <v-select :options="voyageName" class="form-input" placeholder="Choose Option" v-model="formParams.voyage" :reduce="voyageName => voyageName.voyage" label="voyage" required></v-select>
              <Error v-if="errors?.voyage" :errors="errors?.voyage" />
            </label>

            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Search</button>
        </form>
    </div>
    <div class="w-full border rounded-lg shadow-xs dark:border-0">
        <div class="w-full">
            <table class="w-full whitespace-no-wrap" v-if="customerLoadInfo?.voyages">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                        <th class="text-center" rowspan="2">Vessel / Voyage</th>
                        <template v-for="(sector, index) in customerLoadInfo?.sectors" :key="index">
                            <th class="text-center" rowspan="2">ETD {{ sector }}</th>
                        </template>

                        <th class="text-center" rowspan="2">
                            Allocation
                            <br />(In Tues)
                        </th>
                        <th class="text-center" colspan="3">Actual Loading</th>
                    </tr>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                        <th class="text-center">Boxes</th>
                        <th class="text-center">
                            Total
                            <br />Tues
                        </th>
                        <th class="text-center">
                            Total
                            <br />M/Ton
                        </th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr v-for="(voyage, key) in customerLoadInfo?.voyages ?? []" :key="key" class="text-gray-700 dark:text-gray-400">
                        <td>{{ voyage?.vessel?.name }} - {{ voyage?.voyage }}</td>

                        <template v-for="(sector, index) in customerLoadInfo?.sectors" :key="index">
                            <td class="text-center">
                                {{ voyage?.voyage_schedules.find(schedule => schedule?.port_code === sector)?.etd_date?.substring(0, 10) }}
                            </td>
                        </template>
                        <td class="text-right">{{voyage?.voyage_customer?.assigned_contract?.allocation?.tues}}</td>
                        <td class="text-right">{{ voyage?.voyage_customer?.totalContainer }}</td>
                        <td class="text-right">{{ voyage?.voyage_customer?.totalTues }}</td>
                        <td class="text-right">{{ voyage?.voyage_customer?.totalWtMt }}</td>
                    </tr>
                    <!--v-if-->
                </tbody>
                <tfoot>
                    <tr class="font-bold text-gray-600 dark:text-gray-400 bg-green-50">
                        <td colspan="2" class="text-right">Total</td>
                        <td class="text-right"> {{customerLoadInfo?.intotalAllocation}} </td>
                        <td class="text-right">{{ customerLoadInfo?.intotalBoxes }}</td>
                        <td class="text-right">{{ customerLoadInfo?.intotalTues }}</td>
                        <td class="text-right">{{ customerLoadInfo?.intotalMt }}</td>
                    </tr>
                    <tr class="font-bold text-gray-600 bg-green-100 dark:text-gray-400">
                        <td colspan="2" class="text-right">Average</td>
                        <td class="text-right"> {{customerLoadInfo?.avgAllocation}} </td>
                        <td class="text-right">{{ customerLoadInfo?.avgBoxes }}</td>
                        <td class="text-right">{{ customerLoadInfo?.avgTues }}</td>
                        <td class="text-right">{{ customerLoadInfo?.avgMt }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</template>
<style lang="postcss" scoped>
th {
    @apply p-1 border-r;
}

.form-input{
  height: 2.4rem !important;
  font-size: 13px !important;
}

td {
    @apply px-3 py-1 text-xs border-r;
}

thead tr,th{
    background-color: #b2caee;
}
table th,tr,td{
  border: 1px solid darkgray;
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