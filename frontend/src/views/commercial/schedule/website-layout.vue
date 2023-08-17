<script setup>
import { onMounted } from '@vue/runtime-core';
import Title from "../../../services/title";
import useSchedule from "../../../composables/commercial/useSchedule";
import {ref} from "vue";
const { services, getSchedule, getScheduleForWebsite, deleteSchedule, isLoading } = useSchedule();
const { setTitle } = Title();

setTitle('Schedule');

const openTab = ref(1);
const port_etd = ref('')
const toggleTabs = (tabNumber,buttonType = null) => {
  openTab.value = tabNumber;
}

onMounted(() => {
  getScheduleForWebsite();
});


</script>

<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Schedule</h2>
      <router-link :to="{ name: 'commercial.schedule.create' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
      </router-link>
    </div>
    <!-- Table -->
  <div class="w-full border-b border-gray-200 dark:border-gray-700">
    <ul class="w-full flex flex-wrap -mb-px">
      <template v-for="(head,key,index) in services" :key="index">
        <li class="w-6/12" v-bind:class="{'bg-red-600': index%2 === 0, 'bg-green-600': index%2 !== 0}">
          <a href="#" class="w-full inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 capitalize dark:text-gray-400 group" v-on:click="toggleTabs(index + 1)" v-bind:class="{'text-purple-600 bg-white': openTab !== index+1, 'text-white rounded-t-lg border-b-2 border-transparent active': openTab === index+1}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> {{ key }}
          </a>
        </li>
      </template>
    </ul>
    <template v-for="(service, serviceKey, serviceIndex) in services" :key="serviceKey">
      <div class="mt-2" v-on:click="toggleTabs(serviceIndex+1)" v-bind:class="{'hidden': openTab !== serviceIndex+1, 'block': openTab === serviceIndex+1}">
        <div class="w-full overflow-hidden">
          <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
              <thead>
                <tr>
                  <td rowspan="2"> Vessel </td>
                  <template v-for="(bound, boundIndex) in service.head" :key="boundIndex">
                    <td rowspan="2"> Voyage </td>
                    <template v-for="(ports, boundIndex) in bound" :key="boundIndex">
                      <td colspan="2"> {{ ports }} </td>
                    </template>
                  </template>
                </tr>
                <tr>
                  <template v-for="(bound, boundIndex) in service.head" :key="boundIndex">
                    <template v-for="(ports, boundIndex) in bound" :key="boundIndex">
                      <td> ETB </td>
                      <td> ETD </td>
                    </template>
                  </template>
                </tr>
              </thead>

              <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">

                <template v-for="(schedule, scheduleKey) in service.schedules" :key="scheduleKey">
                  <tr>
                    <td> <strong> {{ schedule.vessel_name }} </strong> </td>

                    <template v-for="(boundHead, boundHeadKey, boundHeadIndex) in service.head" :key="boundHeadKey">
                      <td> Voyage - 20 </td>
                      <template v-for="(portHead, portHeadKey, portHeadIndex) in boundHead" :key="portHeadKey">

                        <td>
                          <template v-for="(bound, boundKey, boundIndex) in schedule.bounds" :key="boundKey">
                            <template v-for="(port, portKey) in bound" :key="portKey">
                              <strong>
                                {{ boundHeadKey == boundKey && portHeadKey == port.port_code ? port.etb_date : null }}
                              </strong>
                            </template> 
                          </template> 
                        </td>
                        
                        <td>
                          <template v-for="(bound, boundKey, boundIndex) in schedule.bounds" :key="boundKey">
                            <template v-for="(port, portKey) in bound" :key="portKey">
                              <strong>
                                {{ boundHeadKey == boundKey && portHeadKey == port.port_code ? port.etd_date : null }}
                              </strong>
                            </template> 
                          </template> 
                        </td>

                      </template> <!-- Port Head -->
                    </template> <!-- Bound Head -->

                  </tr>
                </template> <!-- Schedule -->

              </tbody>

            </table>
          </div>
        </div>
      </div>

    </template>

  </div>

</template>

<style lang="postcss" scoped>
@tailwind components;

@layer components {
  .tab {
    @apply p-2.5 text-xs;
  }
  thead tr {
    @apply font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800 text-xs text-center bg-green-500 text-white;
  }
  th {
    @apply tab text-center;
  }
  tbody {
    @apply bg-white divide-y dark:divide-gray-700 dark:bg-gray-800;
  }
  tbody tr {
    @apply text-gray-700 dark:text-gray-400;
  }
  tbody tr td {
    @apply tab text-center;
  }
  tfoot td {
    @apply tab text-center;
  }

  table, th,td{
    @apply border border-collapse;
  }
}
</style>