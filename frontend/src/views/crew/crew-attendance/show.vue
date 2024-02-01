<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useCrwAttendance from '../../../composables/crew/useCrwAttendance.js';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import env from '../../../config/env';

const icons = useHeroIcon();

const route = useRoute();
const crwAttendanceId = route.params.crwAttendanceId;
const { crwAttendance, showCrwAttendance, errors } = useCrwAttendance();

const { setTitle } = Title();

setTitle('Crew Attendance Details');

onMounted(() => {
  showCrwAttendance(crwAttendanceId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">  Crew Attendance Details</h2>
    <default-button :title="' Crew Attendance List'" :to="{ name: 'crw.crwAttendances.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
    <div class="flex md:gap-4">
      <div class="w-full">
        <table class="w-full">
          <thead>
            <tr>
              <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="2">Basic Info</td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th class="w-40"> Business Unit </th>
              <td><span :class="crwAttendance?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ crwAttendance?.business_unit }}</span></td>
            </tr>
            <tr>
              <th class="w-40"> Vessel Name </th>
              <td>{{ crwAttendance?.opsVessel?.name }}</td>
            </tr>
            <tr>
              <th class="w-40"> Total Crew </th>
              <td>{{ crwAttendance?.total_crews }}</td>
            </tr>
            <tr>
              <th class="w-40"> Year - Month </th>
              <td>{{ crwAttendance?.year_month }}</td>
            </tr>
            <tr>
              <th class="w-40"> Working Days </th>
              <td>{{ crwAttendance?.working_days }}</td>
            </tr>
            <tr>
              <th class="w-40"> Remarks </th>
              <td>{{ crwAttendance?.remarks }}</td>
            </tr> 
          </tbody>
        </table>
 
        <table class="w-full mt-2">
          <thead>
            <tr>
              <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="7"> Assigned Crew List </td>
            </tr>
            <tr>
              <th class="!text-center">#</th>
              <th class="w-40 !text-center"> Crew Name </th>
              <th class="w-40 !text-center"> Crew Contact </th>
              <th class="w-40 !text-center"> Onboard Position </th>
              <th class="w-40 !text-center"> Present Days </th>
              <th class="w-40 !text-center"> Absent Days </th>
              <th class="w-40 !text-center"> Payable Days </th>
            </tr>            
          </thead>
          <tbody>
            <tr v-for="(crwAttendanceLine, index) in crwAttendance?.crwAttendanceLines" :key="index">
              <td class="!w-5 !text-center">{{ index + 1 }}</td>
              <td class="text-left"> {{ crwAttendanceLine?.crwCrewAssignment?.crwCrew?.full_name }} </td>
              <td class="!text-center"> {{ crwAttendanceLine?.crwCrewAssignment?.crwCrew?.pre_mobile_no }} </td>
              <td class="text-left"> {{ crwAttendanceLine?.crwCrewAssignment?.position_onboard }} </td>
              <td class="!text-center"> {{ crwAttendanceLine?.present_days }} </td>
              <td class="!text-center"> {{ crwAttendanceLine?.absent_days }} </td>
              <td class="!text-center"> {{ crwAttendanceLine?.payable_days }} </td>
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

tfoot td{
  @apply text-center
}

#profileDetailTable th{
  text-align: center;
}
#profileDetailTable thead tr{
  @apply bg-gray-200
}

</style>