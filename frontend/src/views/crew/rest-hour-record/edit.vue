<script setup>
import {computed, onMounted, watch} from 'vue';
import { useRoute } from 'vue-router';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useRestHourRecord from '../../../composables/crew/useRestHourRecord';
import { formatDate } from '../../../utils/helper';

const icons = useHeroIcon();

const route = useRoute();
const restHourRecordId = route.params.restHourRecordId;
const { restHourRecord, showRestHourRecord, updateRestHourRecord, isLoading, errors } = useRestHourRecord();
const { setTitle } = Title();

setTitle('Edit Rest Hour Record');

let startHour = 0;
let endHour = 0;

function formatIndex(index) {
  if(index === 1){
    return '01';
  }
  index = Math.floor(index/2);
  if(index===0){
    startHour = String(index).padStart(2, '0');
    endHour = String(index + 1).padStart(2, '0');
  } else {
    startHour = endHour;
    endHour = String(index + 1).padStart(2, '0');
  }

  return `${startHour}`;
}

const timeRangeHeaders = computed(() => {
  const headers = [];
  for (let i = 0; i < 24; i++) {
    const startHour = i.toString().padStart(2, '0');
    const endHour = (i + 1).toString().padStart(2, '0');
    headers.push({ startHour, endHour });
  }
  return headers;
});

function toggleHourlyRecord(index,hourRecordIndex) {
  restHourRecord.value.crwRestHourEntryLines[index].hourly_records[hourRecordIndex].hour = hourRecordIndex;
  restHourRecord.value.crwRestHourEntryLines[index].hourly_records[hourRecordIndex].type = restHourRecord.value.location_type;
  restHourRecord.value.crwRestHourEntryLines[index].hourly_records[hourRecordIndex].is_selected = !restHourRecord.value.crwRestHourEntryLines[index].hourly_records[hourRecordIndex].is_selected;

  if(!restHourRecord.value.crwRestHourEntryLines[index].hourly_records[hourRecordIndex].is_selected){
    restHourRecord.value.crwRestHourEntryLines[index].hourly_records[hourRecordIndex].type = '';
    restHourRecord.value.crwRestHourEntryLines[index].hourly_records[hourRecordIndex].hour = '';
  }
}

function updateRestHour(){
  updateRestHourRecord(restHourRecord.value,restHourRecordId);
}

onMounted(() => {
  showRestHourRecord(restHourRecordId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Rest Hour Record</h2>
    <default-button :title="'Rest Hour Record List'" :to="{ name: 'crw.rest-hour-records.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-2 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
    <div class="flex md:gap-4">
      <div class="w-full">
        <h2 class="bg-green-600 text-white text-md font-semibold uppercase mb-2 text-center py-2">Rest Hour Record Information</h2>
        <table class="w-full">
          <tbody>
          <tr>
            <th class="w-40">Business Unit</th>
            <td><span :class="restHourRecord?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ restHourRecord?.business_unit }}</span></td>
          </tr>
          <tr>
            <th class="w-40">Vessel Name</th>
            <td class="!px-1">{{ restHourRecord?.opsVessel?.name }}</td>
          </tr>
          <tr>
            <th class="w-40">Record Date</th>
            <td class="!px-1">{{ formatDate(restHourRecord?.record_date) }}</td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800 overflow-x-auto">
    <div class="flex md:gap-4">
      <div class="w-full">
        <div class="grid lg:grid-cols-1 gap-2 md:grid-cols-1">
          <label class="block w-2/6 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Location <span class="text-red-500">*</span></span>
            <select class="form-input" v-model.trim="restHourRecord.location_type" required>
              <option value="X">Periods of Work at Sea (X)</option>
              <option value="P">Periods of Work in Port (P)</option>
              <option value="S">Proposed Periods of Work (S)</option>
            </select>
          </label>
        </div>
        <table class="w-full mt-2 table2">
          <thead>
          <tr>
            <th rowspan="2">#</th>
            <th rowspan="2">Seafarer</th>
            <th rowspan="2">Service Start</th>
            <th rowspan="2">Comment</th>
            <th colspan="48">Hourly Records</th>
          </tr>
          <tr>
            <th class="time-range-header" v-for="(header, index) in timeRangeHeaders" :key="index" colspan="2">
              <nobr><span class="">{{ header.startHour }} - {{ header.endHour }}</span></nobr>
            </th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(entryLine,index) in restHourRecord?.crwRestHourEntryLines" :key="index">
            <td class="text-center">{{ index + 1 }}</td>
            <td>{{ entryLine?.crwCrew?.full_name }}</td>
            <td class="text-center">{{ formatDate(entryLine?.crwCrewAssignment?.joining_date) }}</td>
            <td class="!p-1">
              <input class="!w-auto h-6 text-sm" type="text" v-model="entryLine.comments">
            </td>
            <td class="cursor-pointer" :class="{ 'active_hour': record?.is_selected,
                     'inactive_hour': !record?.is_selected }" v-for="(record,hourRecordIndex) in entryLine?.hourly_records"
                @click="toggleHourlyRecord(index,hourRecordIndex)"
                :key="hourRecordIndex">
              <div class="w-6 h-5 rest_hour_div text-center">
                {{ record?.type }}
              </div>
            </td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
    <button type="submit" :disabled="isLoading" @click="updateRestHour" class="flex items-center justify-between px-4 py-2 mt-4 text-sm text-white bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Submit</button>
  </div>
</template>
<style lang="postcss" scoped>
th, td, tr {
  @apply text-left border-gray-500
}

#profileDetailTable th{
  text-align: center;
}
#profileDetailTable thead tr{
  @apply bg-gray-200
}

th.text-center, td.text-center, tr.text-center {
  @apply text-center border-gray-500
}

.table2 tr,th{
  white-space: nowrap;
}

tbody tr,td{
  padding: 0rem;
}

.table2 th{
  @apply text-center bg-gray-300;
}
.active_hour{
  background-color: #a5dc86;
}
.inactive_hour{
  background-color: #c7c6c6;
}

::-webkit-scrollbar:horizontal {
  height: 0.3rem !important;
}

::-webkit-scrollbar-thumb:horizontal{
  background-color: rgba(126, 58, 242);
  border-radius: 2rem !important;
  width: 2rem !important;
}

::-webkit-scrollbar-track:horizontal{
  background: rgb(148, 144, 155)!important;
  border-radius: 2rem !important;
}

.start-time {
  float: left;
}

.dash {
  margin: 0; /* Adjust margin for spacing */
}

.end-time {
  float: right;
}

</style>