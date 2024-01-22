<script setup>
import {onMounted, computed, ref, watchEffect} from 'vue';
import { useRoute } from 'vue-router';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import Store from "../../../store";
import useCrewCommonApiRequest from "../../../composables/crew/useCrewCommonApiRequest";
import useVessel from "../../../composables/operations/useVessel";
import useRestHourRecord from "../../../composables/crew/useRestHourRecord";
const { getVesselAssignedCrews, vesselAssignedCrews } = useCrewCommonApiRequest();
const { vessels, searchVessels, getVesselsWithoutPaginate, isLoading } = useVessel();
const { restHourRecords, getRestHourRecords, getRestHourReport, restHourReport  } = useRestHourRecord();
import { formatMonthYear, formatDate } from "../../../utils/helper.js";

const icons = useHeroIcon();
const dateFormat = ref(Store.getters.getVueDatePickerTextInputFormat.date);
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
const route = useRoute();

const { setTitle } = Title();

setTitle('Rest Hour Report Details');

const currentDate = ref(new Date());

const searchParams = ref({
  ops_vessel_id: '',
  crw_crew_id: '',
  year_month: '',
});

const daysInMonth = computed(() => {
  const year = parseInt(searchParams.value.year_month.split('-')[0]);
  const month = parseInt(searchParams.value.year_month.split('-')[1]);
  const lastDay = new Date(year, month, 0).getDate();

  return Array.from({ length: lastDay }, (_, index) => {
    const dayNumber = index + 1;
    const date = new Date(year, month - 1, dayNumber);
    const dayName = date.toLocaleString('en-US', { weekday: 'short' });

    return {
      dayNumber,
      dayName,
    };
  });
});

const timeRangeHeaders = computed(() => {
  const headers = [];
  for (let i = 0; i < 24; i++) {
    const startHour = i.toString().padStart(2, '0');
    const endHour = (i + 1).toString().padStart(2, '0');
    headers.push({ startHour, endHour });
  }
  return headers;
});

function vesselChanged(){
  getVesselAssignedCrews(searchParams.value.ops_vessel_id);
}

onMounted(() => {
  watchEffect(() => {
    getVesselsWithoutPaginate(businessUnit.value);
  });
});
</script>

<template>
<!--  <div class="flex items-center justify-between w-full my-3" v-once>-->
<!--    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Appraisal Record Details</h2>-->
<!--    <default-button :title="'Create rest hour record'" :to="{ name: 'crw.rest-hour-records.create' }" :icon="icons.AddIcon"></default-button>-->
<!--  </div>-->
  <form @submit.prevent="getRestHourReport(searchParams)">
    <div class="w-full flex items-center justify-between mb-2 my-2 select-none">
      <fieldset class="w-full grid grid-cols-4 gap-1 px-2 pb-3 border border-gray-700 rounded dark-disabled:border-gray-400">
        <legend class="px-2 text-gray-700 uppercase dark-disabled:text-gray-300">Rest Hour Record</legend>
        <div>
          <label for="" class="text-xs" style="margin-left: .01rem">Cost Center <span class="text-red-500">*</span></label>
          <v-select :options="vessels" placeholder="--Choose an option--" :loading="isLoading" v-model.trim="searchParams.ops_vessel_id" @update:modelValue="vesselChanged" label="name" :reduce="vessels=>vessels.id" class="block w-full rounded form-input">
            <template #search="{attributes, events}">
              <input class="vs__search w-full" style="width: 50%" :required="!searchParams.ops_vessel_id" v-bind="attributes" v-on="events"/>
            </template>
          </v-select>
        </div>
        <div>
          <label for="" class="text-xs" style="margin-left: .01rem">Crew <span class="text-red-500">*</span></label>
          <select class="block w-full rounded form-input" v-model.trim="searchParams.crw_crew_id" required>
            <option value="">Select</option>
            <option :value="crew?.crw_crew_id" v-for="(crew,index) in vesselAssignedCrews">{{ crew?.crwCrew?.full_name }}</option>
          </select>
        </div>
        <div>
          <label for="" class="text-xs" style="margin-left: .01rem">Date <span class="text-red-500">*</span></label>
          <VueDatePicker v-model.trim="searchParams.year_month" month-picker class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="mm/yyyy" format="yyyy/MMMM" model-type="yyyy-MM" :text-input="{ format: dateFormat }"></VueDatePicker>
        </div>
        <div>
          <label for="">&nbsp;</label>
          <button type="submit" :disabled="isLoading" class="w-full flex items-center justify-center px-2 mt-1 py-2 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Submit</button>
        </div>
      </fieldset>
    </div>
  </form>
  <div v-if="restHourReport?.daily_records?.length" class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800 overflow-x-auto">
      <div class="w-full overflow-hidden">
        <div class="grid grid-cols-3 gap-4">
          <div class="text-sm">
            <strong>Name of Ship :</strong>
            <span class="ml-1">{{ restHourReport?.vessel?.name }}</span>
          </div>
          <div class="text-sm">
            <strong>IMO No :</strong>
            <span class="ml-1">{{ restHourReport?.vessel?.imo }}</span>
          </div>
          <div class="text-sm">
            <strong>Flag of Ship :</strong>
            <span class="ml-1">{{ restHourReport?.vessel?.flag }}</span>
          </div>
        </div>
        <div class="grid grid-cols-3 gap-4">
          <div class="text-sm">
            <strong>Seafarer :</strong>
            <span class="ml-1">{{ restHourReport?.crw_profile?.full_name }}</span>
          </div>
          <div class="text-sm">
            <strong>Position / Rank :</strong>
            <span class="ml-1">{{ restHourReport?.assignment?.position_onboard }}</span>
          </div>
          <div class="text-sm">
            <strong>Service Start :</strong>
            <span class="ml-1">{{ formatDate(restHourReport?.assignment?.joining_date) }}</span>
          </div>
        </div>
        <div class="grid grid-cols-3 gap-4">
          <div class="text-sm">
            <strong>Month and Year :</strong>
            <span class="ml-1 uppercase">{{ formatMonthYear(searchParams?.year_month) }}</span>
          </div>
          <div class="text-sm">
            <strong>Watchkeeper :</strong>
            <span class="ml-1"> ??????? </span>
          </div>
          <div class="text-sm">
            <strong>Rule :</strong>
            <span class="ml-1"> ?????? </span>
          </div>
        </div>
        <hr class="mb-1">
        <fieldset class="w-full grid grid-cols-4 gap-1 px-2 pb-1 border border-gray-700 rounded dark-disabled:border-gray-400">
          <legend class="px-1 text-sm bg-blue-600 text-white rounded uppercase dark-disabled:text-gray-300">Indexes</legend>
          <div>
            <label class="text-xs border border-gray-300 px-1 font-bold">X</label><span class="text-xs ml-1">Periods of Work at Sea</span>
          </div>
          <div>
            <label class="text-xs border border-gray-300 px-1 font-bold">P</label><span class="text-xs ml-1">Periods of Work in Port</span>
          </div>
          <div>
            <label class="text-xs border border-gray-300 px-1 font-bold">S</label><span class="text-xs ml-1">Proposed Periods of Work</span>
          </div>
        </fieldset>
        <div class="w-full overflow-x-auto">
          <table class="w-full whitespace-no-wrap mt-2" id="table1">
            <caption class="bg-green-600 text-white !text-sm font-semibold uppercase mb-2 text-center py-1">RECORD OF HOURS OF WORK / REST</caption>
            <thead>
            <tr>
              <th colspan="50" class="bg-gray-100 uppercase text-center">HOURS</th>
              <th class="align-middle breakable-text" rowspan="2">Hours of work in 24-hour period</th>
              <th class="align-middle breakable-text" rowspan="2">Hours of rest in 24-hour period</th>
              <th class="align-middle breakable-text" rowspan="2">Actual Overtime in 24-hour period</th>
              <th class="align-middle breakable-text" rowspan="2">Hours of rest as applicable in any 24-hour</th>
              <th class="align-middle breakable-text" rowspan="2">Hours of rest as applicable in any 7-days</th>
              <th class="align-middle" rowspan="2">Comments</th>
            </tr>
            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
              <th class="align-bottom no-wrap">Day</th>
              <th class="align-bottom no-wrap">Date</th>
              <th v-for="(header, index) in timeRangeHeaders" :key="index" colspan="2">
                <nobr><span class="">{{ header.startHour }} - {{ header.endHour }}</span></nobr>
              </th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
            <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(day, index) in daysInMonth" :key="index">
              <td class="">
                {{ day.dayName }}
              </td>
              <td class="" style="text-align: center">
                {{ day.dayNumber }}
              </td>
              <template v-for="(data,hourRecordIndex) in 48" :key="hourRecordIndex">
                <td :class="{ 'active_hour': restHourReport?.daily_records?.find(hourlyRecord => hourlyRecord.day === day.dayNumber)
                          && restHourReport?.daily_records?.find(hourlyRecord => hourlyRecord.day === day.dayNumber)?.crwRestHourEntryLine?.hourly_records?.find(record => record.hour === hourRecordIndex)}">
                  <div class="w-6 h-5 rest_hour_div text-center font-bold" v-if="restHourReport?.daily_records?.find(hourlyRecord => hourlyRecord.day === day.dayNumber) && restHourReport?.daily_records?.find(hourlyRecord => hourlyRecord.day === day.dayNumber)?.crwRestHourEntryLine?.hourly_records?.find(record => record.hour === hourRecordIndex)">
                    {{restHourReport?.daily_records?.find(hourlyRecord => hourlyRecord.day === day.dayNumber)?.crwRestHourEntryLine?.hourly_records?.find(record => record.hour === hourRecordIndex)?.type}}
                  </div>
                  <div class="w-6 h-5 rest_hour_div text-center" v-else></div>
                </td>
              </template>
              <td class="text-center">
                {{restHourReport?.daily_records?.find(hourlyRecord => hourlyRecord.day === day.dayNumber)?.crwRestHourEntryLine?.work_hours}}
              </td>
              <td class="text-center">
                {{restHourReport?.daily_records?.find(hourlyRecord => hourlyRecord.day === day.dayNumber)?.crwRestHourEntryLine?.rest_hours}}
              </td>
              <td class="text-center">
                {{restHourReport?.daily_records?.find(hourlyRecord => hourlyRecord.day === day.dayNumber)?.crwRestHourEntryLine?.overtime_hours}}
              </td>
              <td class="px-1 py-1 text-center">
                {{restHourReport?.daily_records?.find(hourlyRecord => hourlyRecord.day === day.dayNumber)?.crwRestHourEntryLine?.applicable_rest_hour_daily}}
              </td>
              <td class="text-center">
                {{restHourReport?.daily_records?.find(hourlyRecord => hourlyRecord.day === day.dayNumber)?.crwRestHourEntryLine?.applicable_rest_hour_weekly}}
              </td>
              <td class="text-center whitespace-nowrap min-w-24">
                {{restHourReport?.daily_records?.find(hourlyRecord => hourlyRecord.day === day.dayNumber)?.crwRestHourEntryLine?.comments}}
              </td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
  </div>
</template>
<style lang="postcss" scoped>
th, td, tr {
  @apply text-left border-gray-500
}

table th,tr,td{
  font-size: 10px;
  padding: 0;
  text-align: center;
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

.text-sm{
  font-size: 11px;
}

.table2 tr,th{
  white-space: nowrap;
}

.breakable-text {
  white-space: normal; /* or use 'pre-wrap' for preformatted text with preserved line breaks */
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

</style>