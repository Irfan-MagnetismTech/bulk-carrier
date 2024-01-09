<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useRunHour from '../../../composables/maintenance/useRunHour';
import moment from 'moment';

const icons = useHeroIcon();

const route = useRoute();
const runHourId = route.params.runHourId;
const { runHour, showRunHour, errors } = useRunHour();

const { setTitle } = Title();

setTitle('Running Hour Details');

onMounted(() => {
  showRunHour(runHourId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Running Hour Details</h2>
    <default-button :title="'Running Hour List'" :to="{ name: 'mnt.run-hours.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <div class="flex md:gap-4">
        <div class="w-full">
          <h2 class="bg-green-600 text-white text-md font-semibold uppercase mb-2 text-center py-2">Running Hour Information</h2>
          <table class="w-full">
            <!-- <thead>
            <tr>
              <td class="!text-center bg-gray-200 font-bold" colspan="2">Personal Info</td>
            </tr>
            </thead> -->
            <tbody>
              <tr>
                <th class="w-80">Business Unit</th>
                <td><span :class="runHour?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ runHour?.business_unit }}</span></td>
              </tr>
              <tr>
                <th class="w-80">Vessel</th>
                <td>{{ runHour?.opsVessel?.name }}</td>
              </tr>
              
              <tr>
                <th class="w-80">Ship Department</th>
                <td>{{ runHour?.mntItem?.mntItemGroup?.mntShipDepartment?.name }}</td>
              </tr>

              
              <tr>
                <th class="w-80">Item Group</th>
                <td>{{ runHour?.mntItem?.mntItemGroup?.name }}</td>
              </tr>

              
              <tr>
                <th class="w-80">Item</th>
                <td>{{ runHour?.mntItem?.name }}</td>
              </tr>

              
              <tr>
                <th class="w-80">Previous Running Hour</th>
                <td>{{ runHour?.previous_run_hour }} Hour</td>
              </tr>

              
              <tr>
                <th class="w-80">Running Hour (Since Last Update)</th>
                <td>{{ runHour?.running_hour }} Hour</td>
              </tr>

              
              <tr>
                <th class="w-80">Present Running Hour</th>
                <td>{{ runHour?.present_run_hour }} Hour</td>
              </tr>

              
              <tr>
                <th class="w-80">Updated On</th>
                <td>{{ moment(runHour?.updated_on).format('DD/MM/YYYY') }}</td>
              </tr>
















              
              <!-- <tr>
                <th class="w-80">Ship Department</th>
                <td>{{ job?.mntItem?.mntItemGroup?.mntShipDepartment?.name }}</td>
              </tr>
              <tr>
                <th class="w-80">Item Group</th>
                <td>{{ job?.mntItem?.mntItemGroup?.name }}</td>
              </tr>


              
              <tr>
                <th class="w-80">Item Code</th>
                <td>{{ job?.mntItem?.item_code }}</td>
              </tr>
              
              <tr>
                <th class="w-80">Item Name</th>
                <td>{{ job?.mntItem?.name }}</td>
              </tr>

              
              <tr>
                <th class="w-80">Present Running Hour</th>
                <td>{{ job?.present_run_hour }} Hours</td>
              </tr> -->
                            
              
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

</style>