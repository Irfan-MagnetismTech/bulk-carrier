<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Vessel Details</h2>
    <default-button :title="'Vessel List'" :to="{ name: 'ops.vessels.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <div class="flex md:gap-4">
        <div class="w-1/2">
          <h2 class="!text-center font-bold bg-green-600 uppercase text-white py-1">Basic Information</h2>
          <table class="w-full">
            <tbody>
              <tr>
                <th class="w-32">Vessel Type</th>
                <td>{{ vessel?.vessel_type }}</td>
              </tr>
              <tr>
                <th class="w-32">Vessel Name</th>
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
                <td>{{ formatDate(vessel?.keel_laying_date) }}</td>
              </tr>
              <tr>
                <th class="w-32">Launching Date</th>
                <td>{{ formatDate(vessel?.launching_date) }}</td>
              </tr>
              <tr>
                <th class="w-32">Delivery Date</th>
                <td>{{ formatDate(vessel?.delivery_date) }}</td>
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
                <th class="w-32">Capacity</th>
                <td>{{ vessel?.capacity }}</td>
              </tr>
              <tr>
                <th class="w-32">Remarks</th>
                <td>{{ vessel?.remarks }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="w-1/2">
          <h2 class="!text-center font-bold bg-green-600 uppercase text-white py-1">Certificate Information</h2>
          <table class="w-full">
            <tr>
              <th class="w-10">SL</th>
              <th class="w-52">Certificate Name</th>
              <th class="w-52">Certificate Type</th>
              <!-- <th class="w-52">Validity Period</th> -->
            </tr>
            <tbody>
              <tr v-for="(bunker, index) in vessel.opsVesselCertificates">
                <td>{{ index+1 }}</td>
                <td>{{ vessel.opsVesselCertificates[index]?.opsMaritimeCertification?.name }}</td>
                <td>{{ vessel.opsVesselCertificates[index]?.type }}</td>
                <!-- <td>{{ vessel.opsVesselCertificates[index]?.validity }}</td> -->
              </tr>
             
            </tbody>
          </table>

          <h2 class="!text-center font-bold bg-green-600 uppercase text-white py-1 mt-1">Bunker Information</h2>
          <table class="w-full">
            <tr>
              <th class="w-10">SL</th>
              <th class="w-52">Bunker Name</th>
              <th class="w-52">Unit</th>
              <th class="w-52">Opening Balance</th>
            </tr>
            <tbody>
              <tr v-for="(bunker, index) in vessel.opsBunkers">
                <td>{{ index+1 }}</td>
                <td>{{ vessel.opsBunkers[index]?.scmMaterial?.name }}</td>
                <td>{{ vessel.opsBunkers[index]?.unit }}</td>
                <td>{{ vessel.opsBunkers[index]?.opening_balance }}</td>
              </tr>
             
            </tbody>
          </table>

          <h2 class="!text-center font-bold bg-green-600 uppercase text-white py-1 mt-1">Other Information</h2>
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
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import {formatDate} from "../../../utils/helper.js"


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