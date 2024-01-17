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
const { restHourRecords, getRestHourRecords } = useRestHourRecord();

const icons = useHeroIcon();
const dateFormat = ref(Store.getters.getVueDatePickerTextInputFormat.date);
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
const route = useRoute();

const { setTitle } = Title();

setTitle('Appraisal Record Details');

const currentDate = ref(new Date());

// Computed property to get days in the current month with day names
const daysInMonth = computed(() => {
  const year = currentDate.value.getFullYear();
  const month = currentDate.value.getMonth() + 1; // Month is zero-based

  // Calculate the total number of days in the month
  const lastDay = new Date(year, month, 0).getDate();

  // Generate an array with objects containing day number and day name
  return Array.from({ length: lastDay }, (_, index) => {
    const dayNumber = index + 1;
    const date = new Date(year, month - 1, dayNumber); // Month is zero-based
    const dayName = date.toLocaleString('en-US', { weekday: 'short' });

    return {
      dayNumber,
      dayName,
    };
  });
});

// Computed property to get time range headers
const timeRangeHeaders = computed(() => {
  const headers = [];
  for (let i = 0; i < 24; i++) {
    const startHour = i.toString().padStart(2, '0');
    const endHour = (i + 1).toString().padStart(2, '0');
    headers.push({ startHour, endHour });
  }
  return headers;
});

const searchParams = ref({
  acc_cost_center_id: '',
  crw_crew_id: '',
  month_year: '',
});

function vesselChanged(){
  getVesselAssignedCrews(searchParams.value.acc_cost_center_id);
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
  <form @submit.prevent="getRestHourRecords(searchParams)">
    <div class="w-full flex items-center justify-between mb-2 my-2 select-none">
      <fieldset class="w-full grid grid-cols-4 gap-1 px-2 pb-3 border border-gray-700 rounded dark-disabled:border-gray-400">
        <legend class="px-2 text-gray-700 uppercase dark-disabled:text-gray-300">Rest Hour Record</legend>
        <div>
          <label for="" class="text-xs" style="margin-left: .01rem">Cost Center <span class="text-red-500">*</span></label>
          <v-select :options="vessels" placeholder="--Choose an option--" :loading="isLoading" v-model.trim="searchParams.acc_cost_center_id" @update:modelValue="vesselChanged" label="name" :reduce="vessels=>vessels.id" class="block w-full rounded form-input">
            <template #search="{attributes, events}">
              <input class="vs__search w-full" style="width: 50%" :required="!searchParams.acc_cost_center_id" v-bind="attributes" v-on="events"/>
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
          <VueDatePicker v-model.trim="searchParams.month_year" month-picker class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="mm/yyyy" format="MMMM/yyyy" model-type="MM-yyyy" :text-input="{ format: dateFormat }"></VueDatePicker>
        </div>
        <div>
          <label for="">&nbsp;</label>
          <button type="submit" :disabled="isLoading" class="w-full flex items-center justify-center px-2 mt-1 py-2 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Submit</button>
        </div>
      </fieldset>
    </div>
  </form>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800 overflow-x-auto">
      <div class="w-full overflow-hidden">
        <div class="w-full overflow-x-auto">
          <table class="w-full whitespace-no-wrap mt-2" id="table1">
            <thead>
            <tr>
              <th colspan="50" class="bg-green-600 text-white !text-sm font-semibold uppercase mb-2 text-center py-1">RECORD OF HOURS OF WORK / REST</th>
            </tr>
            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
              <th class="px-4 py-3 align-bottom no-wrap">Day</th>
              <th class="px-4 py-3 align-bottom no-wrap">Date</th>
              <th class="time-range-header" v-for="(header, index) in timeRangeHeaders" :key="index" colspan="2">
                <nobr><span class="start-time">{{ header.startHour }} - {{ header.endHour }}</span></nobr>
              </th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
            <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(day, index) in daysInMonth" :key="index">
              <td class="px-1 py-1 square-cell">
                {{ day.dayName }}
              </td>
              <td class="px-1 py-1 square-cell">
                {{ day.dayNumber }}
              </td>
              <template v-for="(i,index) in 24" :key="index">
                <td class="px-1 py-1 square-cell inactive_hour"></td>
                <td class="px-1 py-1 active_hour square-cell">P</td>
              </template>
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

  #profileDetailTable th{
    text-align: center;
  }
  #profileDetailTable thead tr{
    @apply bg-gray-200
  }

  th.text-center, td.text-center, tr.text-center {
    @apply text-center border-gray-500
  }
  .active_hour{
    background-color: #a5dc86;
  }

  .inactive_hour{
    background-color: #dad8d8;
  }

  .square-cell {
    width: 20px; /* Set the width of the cell */
    text-align: center; /* Center the content in the cell */
  }

  .time-range-header {
    text-align: center;
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