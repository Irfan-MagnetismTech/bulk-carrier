<template>
   <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Delivery Redelivery Details</h2>
    <default-button :title="'Delivery Redelivery Index'" :to="{ name: 'ops.delivery-redelivery.index' }" :icon="icons.DataBase"></default-button>
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
                        <td><span :class="deliveryRedelivery?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ deliveryRedelivery?.business_unit }}</span></td>
                    </tr>
                    <tr>
                        <th class="w-40">Note Type</th>
                        <td>{{ deliveryRedelivery.note_type }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Effective Date</th>
                        <td><span>
                          <nobr>{{ formatDate(deliveryRedelivery.effective_date) }}</nobr>
                          </span>
                        </td>
                    </tr>
                    <tr>
                        <th class="w-40">Currency</th>
                        <td>{{ deliveryRedelivery.currency }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Exchange Rate <small>[To USD]</small></th>
                        <td>{{ deliveryRedelivery.exchange_rate_usd }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Exchange Rate <small>[To BDT]</small></th>
                        <td>{{ deliveryRedelivery.exchange_rate_bdt }}</td>
                    </tr>
                    
                    <tr>
                        <th class="w-40">Vessel</th>
                        <td>{{ deliveryRedelivery.opsVessel?.name }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Vessel Code</th>
                        <td>{{ deliveryRedelivery?.opsVessel?.short_code }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Vessel Owner Name</th>
                        <td>{{ deliveryRedelivery?.opsVessel?.owner_name}}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Vessel Capacity</th>
                        <td>{{ deliveryRedelivery?.opsVessel?.capacity }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Charterer Name</th>
                        <td>{{ deliveryRedelivery.opsChartererProfile?.name }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Charterer Owner Code</th>
                        <td>{{ deliveryRedelivery.opsChartererProfile?.owner_code }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Charterer Email</th>
                        <td>{{ deliveryRedelivery.opsChartererProfile?.email }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Contact No.</th>
                        <td>{{ deliveryRedelivery.opsChartererProfile?.contact_no }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Remarks</th>
                        <td>{{ deliveryRedelivery.remarks }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="flex md:gap-4 mt-1 md:mt-2" v-if="deliveryRedelivery.opsBunkers?.length">
        <div class="w-full">
          <table class="w-full">
            <thead>
                <tr>
                    <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="8">Bunker Information</td>
                </tr>
            </thead>
            <thead v-once>
              <tr class="w-full">
                <th>SL</th>
                <th class="w-72">Bunker Name</th>
                <th>Unit</th>
                <th>Quantity</th>
                <th>Rate</th>
                <th>Amount</th>
                <th>Amount <small>(USD)</small></th>
                <th>Amount <small>(BDT)</small></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(certificate, index) in deliveryRedelivery.opsBunkers" :key="index">
                <td>
                  {{ index+1 }}
                </td>
                <td>
                  <span v-if="deliveryRedelivery.opsBunkers[index]?.name">{{ deliveryRedelivery.opsBunkers[index]?.name }}</span>
                </td>
                <td>
                  <span v-if="deliveryRedelivery.opsBunkers[index]?.unit">{{ deliveryRedelivery.opsBunkers[index]?.unit }}</span>
                </td>
                <td>
                <span>
                    {{ numberFormat(deliveryRedelivery.opsBunkers[index].quantity) }}
                  </span>
              </td>
              <td>
                <span>
                  {{ numberFormat(deliveryRedelivery.opsBunkers[index].rate) }}
                </span>
              </td>
              <td>
                <span>
                  {{ numberFormat(deliveryRedelivery.opsBunkers[index].amount) }}
                </span>
              </td>
              <td>
                <span>
                  {{ numberFormat(deliveryRedelivery.opsBunkers[index].amount_usd) }}
                </span>
              </td>
              <td>
                <span>
                  {{ numberFormat(deliveryRedelivery.opsBunkers[index].amount_bdt) }}
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
    
  #bunkerDataTable th{
    text-align: center;
  }
  #bunkerDataTable thead tr{
    @apply bg-gray-200
  }
  
</style>
<script setup>
import { ref, watchEffect, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useDeliveryRedelivery from '../../../composables/operations/useDeliveryRedelivery';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import useHelper from "../../../composables/useHelper";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import { formatDate } from '../../../utils/helper';

const icons = useHeroIcon();

const route = useRoute();
const deliveryRedeliveryId = route.params.deliveryRedeliveryId;
const { deliveryRedelivery, showDeliveryRedelivery, errors } = useDeliveryRedelivery();
const { numberFormat } = useHelper();

const { setTitle } = Title();

setTitle('Delivery Redelivery Details');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;


onMounted(() => {
  watchEffect(() => {
    showDeliveryRedelivery(deliveryRedeliveryId)
      .then(() => {
        const customDataTable = document.getElementById("customDataTable");

        if (customDataTable) {
          tableScrollWidth.value = customDataTable.scrollWidth;
        }
      })
      .catch((error) => {
        console.error("Error fetching users:", error);
      });
  });
});
</script>