<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Vessel Bunker Details</h2>
    <default-button :title="'Vessel Bunker List'" :to="{ name: 'ops.vessel-bunkers.index' }" :icon="icons.DataBase"></default-button>
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
                        <th class="w-40">Business Unit</th>
                        <td><span :class="vesselBunker?.opsVessel?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ vesselBunker?.opsVessel?.business_unit }}</span></td>
                    </tr>
                    
                    <tr>
                        <th class="w-40">Vessel</th>
                        <td>{{ vesselBunker.opsVessel?.name }}</td>
                    </tr>

                    <tr>
                        <th class="w-40">Voyage</th>
                        <td>{{ vesselBunker.opsVoyage?.voyage_sequence }}</td>
                    </tr>

                    <tr>
                        <th class="w-40">Type</th>
                        <td>{{ vesselBunker.type }}</td>
                    </tr>

                    <tr v-if="vesselBunker.type == 'Reconciliation'">
                        <th class="w-40">Reconciliation Type</th>
                        <td>{{ vesselBunker.reconciliation_type }}</td>
                    </tr>

                    <tr>
                        <th class="w-40">Date</th>
                        <td>{{ formatDate(vesselBunker.date) }}</td>
                    </tr>

                    <tr>
                        <th class="w-40">From Date</th>
                        <td>{{ formatDate(vesselBunker.from_date) }}</td>
                    </tr>

                    <tr>
                        <th class="w-40">Till Date</th>
                        <td>{{ formatDate(vesselBunker.till_date) }}</td>
                    </tr>
                    <template v-if="vesselBunker.type == 'Stock In'">
                      <tr>
                          <th class="w-40">Vendor </th>
                          <td>{{ vesselBunker?.opsBunkers[0]?.scmVendor?.name }}</td>
                      </tr>
                      <tr>
                          <th class="w-40">Currency </th>
                          <td>{{ vesselBunker?.currency }}</td>
                      </tr>
                      <tr>
                          <th class="w-40">Exchange Rate (To USD) </th>
                          <td>{{ vesselBunker.exchange_rate_usd }}</td>
                      </tr>
                      <tr>
                          <th class="w-40">Exchange Rate (To BDT) </th>
                          <td>{{ vesselBunker.exchange_rate_bdt }}</td>
                      </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
    <div class="flex md:gap-4 mt-1 md:mt-2" v-if="vesselBunker.opsBunkers?.length">
        <div class="w-full">
          <table class="w-full">
            <thead>
                <tr>
                    <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="7">Expesne Head Information</td>
                </tr>
            </thead>
            <thead v-once>
              <tr class="w-full">
                <th class="w-40 !text-center">Material</th>
                <th class="!text-center">Quantity </th>
                <template v-if="vesselBunker.type == 'Stock In'">
                  <th class="!text-center">Rate</th>
                  <th class="!text-center">Amount</th>
                  <th class="!text-center">Amount <small>(USD)</small></th>
                  <th class="!text-center">Amount <small>(BDT)</small></th>
                </template>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(budget, index) in vesselBunker.opsBunkers" :key="index">
                <td>
                  {{ vesselBunker.opsBunkers[index]?.scmMaterial?.name }}
                </td>
                <td class="!text-right">
                  {{ numberFormat(vesselBunker.opsBunkers[index]?.quantity) }}
                </td>
                <template v-if="vesselBunker.type == 'Stock In'">
                  <td class="!text-right">
                    {{ numberFormat(vesselBunker.opsBunkers[index]?.rate) }}
                  </td>
                  <td class="!text-right">
                    {{ numberFormat(vesselBunker.opsBunkers[index]?.amount) }}
                  </td>
                  <td class="!text-right">
                    {{ numberFormat(vesselBunker.opsBunkers[index]?.amount_usd) }}
                  </td>
                  <td class="!text-right">
                    {{ numberFormat(vesselBunker.opsBunkers[index]?.amount_bdt) }}
                  </td>
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
  
  tfoot td{
    @apply text-center
  }
    
</style>
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import VesselBunkerForm from '../../../components/operations/VesselBunkerForm.vue';
import useVesselBunker from '../../../composables/operations/useVesselBunker';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";
import useHelper from "../../../composables/useHelper";
import moment from 'moment';
import { formatDate } from '../../../utils/helper';

const icons = useHeroIcon();

const route = useRoute();
const vesselBunkerId = route.params.vesselBunkerId;
const { vesselBunker, showVesselBunker, errors } = useVesselBunker();
const { numberFormat } = useHelper();
const { setTitle } = Title();

setTitle('Vessel Bunker Details');

onMounted(() => {
  showVesselBunker(vesselBunkerId);
});
</script>