<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Vessel Details</h2>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
      <div class="flex md:gap-4">
        <div class="w-1/2">
          <h2 class="bg-indigo-400 text-white text-md font-semibold uppercase mb-2 text-center py-2">Basic Information</h2>
          <table class="w-full">
            <tbody>
              <tr>
                <th class="w-32">Vessel Type</th>
                <td>{{ vessel?.vessel_type }}</td>
              </tr>
              <tr>
                <th class="w-32">Name</th>
                <td>{{ vessel?.name }}</td>
              </tr>
              <tr>
                <th class="w-32">Short Code</th>
                <td>{{ vessel?.short_code }}</td>
              </tr>
              <tr>
                <th class="w-32">Call Sign</th>
                <td>{{ vessel?.call_sign }}</td>
              </tr>
              <tr>
                <th class="w-32">Owner Name</th>
                <td>{{ vessel?.owner_name }}</td>
              </tr>
              <tr>
                <th class="w-32">Manager/Director</th>
                <td>{{ vessel?.manager }}</td>
              </tr>
              <tr>
                <th class="w-32">Classification</th>
                <td>{{ vessel?.classification }}</td>
              </tr>
              <tr>
                <th class="w-32">Flag</th>
                <td>{{ vessel?.flag }}</td>
              </tr>
              <tr>
                <th class="w-32">Port of Registry</th>
                <td>{{ vessel?.port_of_registry }}</td>
              </tr>
              <tr>
                <th class="w-32">GRT</th>
                <td>{{ vessel?.grt }}</td>
              </tr>
              <tr>
                <th class="w-32">NRT</th>
                <td>{{ vessel?.nrt }}</td>
              </tr>
              <tr>
                <th class="w-32">DWT</th>
                <td>{{ vessel?.dwt }}</td>
              </tr>
              <tr>
                <th class="w-32">IMO Number</th>
                <td>{{ vessel?.imo }}</td>
              </tr>
              <tr>
                <th class="w-32">MMSI</th>
                <td>{{ vessel?.mmsi }}</td>
              </tr>
              <tr>
                <th class="w-32">Official Number</th>
                <td>{{ vessel?.official_number }}</td>
              </tr>
              <tr>
                <th class="w-32">Keel Laying Date</th>
                <td>{{ vessel?.keel_laying_date }}</td>
              </tr>
              <tr>
                <th class="w-32">Launching Date</th>
                <td>{{ vessel?.launching_date }}</td>
              </tr>
              <tr>
                <th class="w-32">Delivery Date</th>
                <td>{{ vessel?.delivery_date }}</td>
              </tr>
              <tr>
                <th class="w-32">Length Overall</th>
                <td>{{ vessel?.overall_length }}</td>
              </tr>
              <tr>
                <th class="w-32">Width Overall</th>
                <td>{{ vessel?.overall_width }}</td>
              </tr>
              <tr>
                <th class="w-32">Yearl Built</th>
                <td>{{ vessel?.year_built }}</td>
              </tr>
              <tr>
                <th class="w-32">Cargo Hold</th>
                <td>{{ vessel?.total_cargo_hold }}</td>
              </tr>
              <tr>
                <th class="w-32">Remarks</th>
                <td>{{ vessel?.remarks }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="w-1/2">
          <div class="h-72">

          </div>
          <h2 class="bg-indigo-400 text-white text-md font-semibold uppercase mb-2 text-center py-2">Other Information</h2>
          <table class="w-full">
            <tbody>
              <tr>
                <th class="w-52">Current Charterer Owner</th>
                <td></td>
              </tr>
              <tr>
                <th class="w-52">All Charterer History</th>
                <td></td>
              </tr>
              <tr>
                <th class="w-52">Total Voyage</th>
                <td></td>
              </tr>
              <tr>
                <th class="w-52">Total Inventory</th>
                <td></td>
              </tr>
              <tr>
                <th class="w-52">Total Revenue</th>
                <td></td>
              </tr>
              <tr>
                <th class="w-52">Total Expenditure</th>
                <td></td>
              </tr>
              <tr>
                <th class="w-52">Total Billable Amount</th>
                <td></td>
              </tr>
              <tr>
                <th class="w-52">Total Due Amount</th>
                <td></td>
              </tr>
              <tr>
                <th class="w-52">Total Over Due Amount</th>
                <td></td>
              </tr>
              <tr>
                <th class="w-52">Total Collection</th>
                <td></td>
              </tr>
              <tr>
                <th class="w-52">Profit/Loss</th>
                <td></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
  </div>
</template>
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useVessel from '../../../composables/operations/useVessel';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";

const icons = useHeroIcon();

const route = useRoute();
const vesselId = route.params.vesselId;
const { vessel, showVessel, errors } = useVessel();

const { setTitle } = Title();

setTitle('Vessel Details');

onMounted(() => {
  showVessel(vesselId);
});
</script>
<style lang="postcss" scoped>
  th, td {
    @apply text-left
  }
</style>