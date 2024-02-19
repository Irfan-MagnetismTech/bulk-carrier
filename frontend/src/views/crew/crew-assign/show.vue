<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useCrewAssign from '../../../composables/crew/useCrewAssign.js';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import env from '../../../config/env';
import { formatDate } from "../../../utils/helper.js";
const icons = useHeroIcon();

const route = useRoute();
const crewAssignId = route.params.crewAssignId;
const { crewAssign, showCrewAssign, errors } = useCrewAssign();

const { setTitle } = Title();

setTitle('Crew Assign Details');

onMounted(() => {
  showCrewAssign(crewAssignId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">  Crew Assign Details</h2>
    <default-button :title="' Crew Assign List'" :to="{ name: 'crw.crewAssigns.index' }" :icon="icons.DataBase"></default-button>
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
              <td><span :class="crewAssign?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ crewAssign?.business_unit }}</span></td>
            </tr>
            <tr>
              <th class="w-40"> Vessel Name </th>
              <td>{{ crewAssign?.opsVessel?.name }}</td>
            </tr>
            <tr>
              <th class="w-40"> Crew Name </th>
              <td>{{ crewAssign?.crwCrewProfile?.full_name }}</td>
            </tr>
            <tr>
              <th class="w-40"> Current Rank </th>
              <td>{{ crewAssign?.crwCrewProfile?.crwCurrentRank?.name }}</td>
            </tr>
            <tr>
              <th class="w-40"> Crew Contact </th>
              <td>{{ crewAssign?.crwCrewProfile?.pre_mobile_no }}</td>
            </tr>

            <tr>
              <th class="w-40"> Position Onboard </th>
              <td>{{ crewAssign?.position_onboard }}</td>
            </tr>
            <tr>
              <th class="w-40"> Joining Date </th>
              <td>{{ formatDate(crewAssign?.joining_date) }}</td>
            </tr>
            <tr>
              <th class="w-40"> Joining Port Code </th>
              <td>{{ crewAssign?.joining_port_code }}</td>
            </tr>
            <tr>
              <th class="w-40"> Duration (Months) </th>
              <td>{{ crewAssign?.duration }}</td>
            </tr>
            <tr>
              <th class="w-40"> Remarks </th>
              <td>{{ crewAssign?.remarks }}</td>
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