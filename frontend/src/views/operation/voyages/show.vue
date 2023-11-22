<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 ">Voyage Details</h2>
    <default-button :title="'Voyage List'" :to="{ name: 'ops.voyages.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md ">
      <div class="flex md:gap-4">
        <div class="w-1/2">
          <h2 class="bg-indigo-400 text-white text-md font-semibold uppercase mb-2 text-center py-2">Basic Information</h2>
          <table class="w-full">
            <tbody>
              <tr>
                <th class="w-32">Voyage Type</th>
                <td>{{ voyage?.voyage_type }}</td>
              </tr>
              <tr>
                <th class="w-32">Name</th>
                <td>{{ voyage?.name }}</td>
              </tr>
              <tr>
                <th class="w-32">Short Code</th>
                <td>{{ voyage?.short_code }}</td>
              </tr>
              <tr>
                <th class="w-32">Call Sign</th>
                <td>{{ voyage?.call_sign }}</td>
              </tr>
              <tr>
                <th class="w-32">Owner Name</th>
                <td>{{ voyage?.owner_name }}</td>
              </tr>
              <tr>
                <th class="w-32">Manager/Director</th>
                <td>{{ voyage?.manager }}</td>
              </tr>
              <tr>
                <th class="w-32">Classification</th>
                <td>{{ voyage?.classification }}</td>
              </tr>
              <tr>
                <th class="w-32">Flag</th>
                <td>{{ voyage?.flag }}</td>
              </tr>
              <tr>
                <th class="w-32">Port of Registry</th>
                <td>{{ voyage?.port_of_registry }}</td>
              </tr>
              <tr>
                <th class="w-32">GRT</th>
                <td>{{ voyage?.grt }}</td>
              </tr>
              <tr>
                <th class="w-32">NRT</th>
                <td>{{ voyage?.nrt }}</td>
              </tr>
              <tr>
                <th class="w-32">DWT</th>
                <td>{{ voyage?.dwt }}</td>
              </tr>
              <tr>
                <th class="w-32">IMO Number</th>
                <td>{{ voyage?.imo }}</td>
              </tr>
              <tr>
                <th class="w-32">MMSI</th>
                <td>{{ voyage?.mmsi }}</td>
              </tr>
              <tr>
                <th class="w-32">Official Number</th>
                <td>{{ voyage?.official_number }}</td>
              </tr>
              <tr>
                <th class="w-32">Keel Laying Date</th>
                <td>{{ voyage?.keel_laying_date }}</td>
              </tr>
              <tr>
                <th class="w-32">Launching Date</th>
                <td>{{ voyage?.launching_date }}</td>
              </tr>
              <tr>
                <th class="w-32">Delivery Date</th>
                <td>{{ voyage?.delivery_date }}</td>
              </tr>
              <tr>
                <th class="w-32">Length Overall</th>
                <td>{{ voyage?.overall_length }}</td>
              </tr>
              <tr>
                <th class="w-32">Width Overall</th>
                <td>{{ voyage?.overall_width }}</td>
              </tr>
              <tr>
                <th class="w-32">Yearl Built</th>
                <td>{{ voyage?.year_built }}</td>
              </tr>
              <tr>
                <th class="w-32">Cargo Hold</th>
                <td>{{ voyage?.total_cargo_hold }}</td>
              </tr>
              <tr>
                <th class="w-32">Remarks</th>
                <td>{{ voyage?.remarks }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="w-1/2">
          <div class="h-72">
            <!-- <h2 class="bg-indigo-400 text-white text-md font-semibold uppercase mb-2 text-center py-2">Other Information</h2> -->

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
import useVoyage from '../../../composables/operations/useVoyage';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";

const icons = useHeroIcon();

const route = useRoute();
const voyageId = route.params.voyageId;
const { voyage, showVoyage, errors } = useVoyage();

const { setTitle } = Title();

setTitle('Voyage Details');

onMounted(() => {
  showVoyage(voyageId);
});
</script>
<style lang="postcss" scoped>
  th, td {
    @apply text-left
  }
</style>