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
                        <td><span :class="vesselBunker?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ vesselBunker?.business_unit }}</span></td>
                    </tr>
                    <tr>
                        <th class="w-40">Title</th>
                        <td>{{ vesselBunker.title }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Effective From </th>
                        <td><span>
                          <nobr>{{ vesselBunker?.effective_from ? moment(vesselBunker?.effective_from).format('DD-MM-YYYY') : null }}</nobr>
                          </span>
                        </td>
                    </tr>
                    <tr>
                        <th class="w-40">Effective To </th>
                        <td><span>
                          <nobr>{{ vesselBunker?.effective_till ? moment(vesselBunker?.effective_till).format('DD-MM-YYYY') : null }}</nobr>
                          </span>
                        </td>
                    </tr>
                    <tr>
                        <th class="w-40">Vessel</th>
                        <td>{{ vesselBunker.opsVoyage?.voyage_sequence }}</td>
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
                </tbody>
            </table>
        </div>
    </div>
    <div class="flex md:gap-4 mt-1 md:mt-2" v-if="vesselBunker.opsVesselBunkerEntries?.length">
        <div class="w-full">
          <table class="w-full">
            <thead>
                <tr>
                    <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="7">Expesne Head Information</td>
                </tr>
            </thead>
            <thead v-once>
              <tr class="w-full">
                <th class="w-40">Expesne Head</th>
                <th>Quantity </th>
                <th>Rate</th>
                <th>Amount <small>(USD)</small></th>
                <th>Amount <small>(BDT)</small></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(budget, index) in vesselBunker.opsVesselBunkerEntries">
                <td>
                  <span v-if="vesselBunker.opsVesselBunkerEntries[index]?.opsExpenseHead?.name">{{ vesselBunker.opsVesselBunkerEntries[index]?.opsExpenseHead?.name }}</span>
                </td>
                <td>
                  {{ vesselBunker.opsVesselBunkerEntries[index]?.quantity }}
                </td>
                <td>
                  {{ vesselBunker.opsVesselBunkerEntries[index]?.rate }}
                </td>
                <td>
                  {{ numberFormat(vesselBunker.opsVesselBunkerEntries[index]?.amount_usd) }}
                </td>
                <td>
                  {{ numberFormat(vesselBunker.opsVesselBunkerEntries[index]?.amount_bdt) }}
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