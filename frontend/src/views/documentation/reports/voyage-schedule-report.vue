
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import moment from 'moment';
import useVoyageScheduleReport from '../../../composables/documentation/useVoyageScheduleReport';
import useService from "../../../composables/commercial/useService";
import useVessel from "../../../composables/dataencoding/useVessel";
import Title from "../../../services/title";

const route = useRoute();
const { voyageScheduleInfo, voyageScheduleReport, formParams, isLoading } = useVoyageScheduleReport();
const { services, bounds, getServices, serviceGroupByCode } = useService();
const { vessels, vesselName, getVesselName } = useVessel();

const { setTitle } = Title();

setTitle('Voyage Schedule Report');

onMounted(() => {
    //voyageScheduleReport();
  getServices();
  getVesselName();
});


</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row">
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Voyage Schedule Report</h2>
    </div>
    <!-- Table -->
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form class="flex items-end justify-between gap-4" @submit.prevent="voyageScheduleReport(formParams)">
            <label class="block w-full text-sm">
                <span class="text-gray-700 dark:text-gray-300">Service <span class="custom_required_field">*</span></span>
              <select v-model="formParams.service_code" style="height: 38px" required class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                <option value="">--Choose an Option--</option>
                <option v-for="(service,index) in services" :value="service?.code" :key="index">{{ service?.code }}</option>
              </select>
            </label>

            <label class="block w-full text-sm">
                <span class="text-gray-700 dark:text-gray-300">Vessel Name</span>
              <v-select :options="vesselName" placeholder="--Choose an option--" v-model="formParams.vessel_name" label="name" required></v-select>
            </label>

            <label class="block w-full text-sm">
                <span class="text-gray-700 dark:text-gray-300">Voyage</span>
                <input type="text" v-model="formParams.voyage" placeholder="Ex: MHRA0005N"
                    class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
            </label>

            <!-- Submit button -->
            <button type="submit" :disabled="isLoading"
                class="flex items-center justify-between px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Search</button>
        </form>
    </div>
    <div class="w-full border rounded-lg shadow-xs dark:border-0" v-if="voyageScheduleInfo?.sectors">
        <div class="w-full">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                        class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                        <th rowspan="2">SL</th>
                        <th rowspan="2">Vessel</th>
                        <template v-for="(bound, index) in voyageScheduleInfo.sectors" :key="index">
                            <th rowspan="2">Voyage</th>
                            <template v-for="(sector, sectorIndex) in bound" :key="sectorIndex">
                                <th colspan="3">{{ sector }}</th>
                            </template>
                        </template>
                    </tr>
                    <tr
                        class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                        <template v-for="(bound, index) in voyageScheduleInfo.sectors" :key="index">
                            <template v-for="(sector, sectorIndex) in bound" :key="sectorIndex">
                                <th>ETA Date</th>
                                <th>ETB Date</th>
                                <th>ETD Date</th>
                            </template>
                        </template>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <template class="text-gray-700 dark:text-gray-400"
                        v-for="(roundVoyage, index, key) in voyageScheduleInfo.roundVoyages" :key="key">
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td>{{ key + 1 }}</td>
                            <td>
                                {{ roundVoyage.vesselName }} <br>
                            </td>
                            <template v-if="roundVoyage.southVoyage">
                                <td>{{ roundVoyage?.southVoyage?.voyage }}</td>
                                <td>{{ roundVoyage?.southVoyage?.voyage_schedules.find(schedule =>schedule.port_code === "BDCGP")?.eta_date.substring(0, 10)}}</td>
                                <td>{{ roundVoyage?.southVoyage?.voyage_schedules.find(schedule =>schedule.port_code === "BDCGP")?.etb_date.substring(0, 10)}}</td>
                                <td>{{ roundVoyage?.southVoyage?.voyage_schedules.find(schedule =>schedule.port_code === "BDCGP")?.etd_date.substring(0, 10)}}</td>
                            </template>
                            <template v-else>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </template>

                            <template v-for="(sector, sectorKey, sectorIndex) in voyageScheduleInfo.sectors.north" :key="sectorKey">
                                <template v-if="roundVoyage.northVoyage">
                                    <td v-if="sectorIndex == 0"> {{ roundVoyage?.northVoyage?.voyage }} </td>
                                    <template v-for="(voyageSchedule, voyageScheduleKey, voyageScheduleIndex) in roundVoyage.northVoyage.voyage_schedules" :key="voyageScheduleIndex">
                                        <template v-if="voyageSchedule.port_code == sectorKey">
                                            <td>{{voyageSchedule.eta_date ? moment(voyageSchedule.eta_date).format('DD-MM-YYYY') : "Empty"}}</td>
                                            <td>{{voyageSchedule.etb_date ? moment(voyageSchedule.etb_date).format('DD-MM-YYYY') : "Empty"}}</td>
                                            <td>{{voyageSchedule.etd_date ? moment(voyageSchedule.etd_date).format('DD-MM-YYYY') : "Empty"}}</td>
                                        </template>
                                    </template>
                                </template>
                                <template v-else>
                                    <td v-if="sectorIndex == 0"> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                </template>
                            </template>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</template>
<style lang="postcss" scoped>
th {
    @apply p-1 border-r;
}

td {
    @apply px-3 py-1 text-xs border-r;
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