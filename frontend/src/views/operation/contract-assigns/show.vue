<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Contract Assign Details</h2>
    <default-button :title="'Contract Assign List'" :to="{ name: 'ops.contract-assigns.index' }" :icon="icons.DataBase"></default-button>
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
                        <td><span :class="contractAssign?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ contractAssign?.business_unit }}</span></td>
                    </tr>
                    <tr>
                        <th class="w-40">Assign Date</th>
                        <td>{{ contractAssign?.assign_date ? moment(contractAssign?.assign_date).format('DD-MM-YYYY') : null }}</td>
                        <!-- <td>{{ contractAssign?.assign_date }}</td> -->
                    </tr>
                    <tr>
                        <th class="w-40">Vessel</th>
                        <td>{{ contractAssign?.opsVessel?.name }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Voyage</th>
                        <td>{{ contractAssign?.opsVoyage?.voyage_sequence }}</td>
                    </tr>
                    <tr v-if="contractAssign?.business_unit === 'TSLL'">
                        <th class="w-40">Customer</th>
                        <td>{{ contractAssign?.opsCustomer?.name }}</td>
                    </tr>
                    <tr v-if="contractAssign?.business_unit === 'PSML'">
                        <th class="w-40">Charterer</th>
                        <td>{{ contractAssign?.opsChartererProfile?.name }}</td>
                    </tr>
                    <tr v-if="contractAssign?.business_unit === 'PSML'">
                        <th class="w-40">Charterer Contract</th>
                        <td>{{ contractAssign?.opsChartererContract?.contract_name }}</td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
    <div class="flex md:gap-4 mt-1 md:mt-2" v-if="contractAssign?.opsContractTariffs?.length">
        <div class="w-full">
          <table class="w-full">
            <thead>
                <tr>
                    <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="7">Bunker Information</td>
                </tr>
            </thead>
            <thead v-once>
              <tr class="w-full">
                <th>SL</th>
                <th class="w-72">Loading Point</th>
                <th>Unloading Point</th>
                <th>Quantity</th>
                <th>Tariff </th>
                <th>Rate</th>
                <th>Month - Total Rate</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(contractTariff, index) in contractAssign?.opsContractTariffs">
                <td>
                  {{ index+1 }}
                </td>
                <td>
                  {{ contractTariff.loading_point }}
                </td>
                <td>
                  {{ contractTariff.unloading_point }}
                </td>
                <td>
                  <span>
                    {{ numberFormat(contractTariff.quantity) }}
                  </span>
                </td>
                <td>
                  <span>
                    {{ contractTariff?.opsCargoTariff?.tariff_name }}
                  </span>
                </td>
                <td>
                  <span>
                    {{ numberFormat(contractTariff.total_rate) }}
                  </span>
                </td>
                <td>
                  <span>
                    {{ (contractTariff?.tariff_month.charAt(0).toUpperCase() + contractTariff?.tariff_month.slice(1)) +' - '+numberFormat(contractTariff.total_rate) }}
                  </span>
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
import useContractAssign from '../../../composables/operations/useContractAssign';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";
import useHelper from "../../../composables/useHelper";
import moment from 'moment';

const icons = useHeroIcon();

const route = useRoute();
const contractAssignId = route.params.contractAssignId;
const { contractAssign, showContractAssign, errors, otherObject, serviceObject } = useContractAssign();
const { numberFormat } = useHelper();

const { setTitle } = Title();

onMounted(() => {
  showContractAssign(contractAssignId);
});
</script>