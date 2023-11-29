<template>
   <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Delivery Redelivery Details</h2>
    <default-button :title="'Delivery Redelivery Index'" :to="{ name: 'ops.delivery-redelivery.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div id="basic-info">
      <h4 class="text-md font-semibold">Basic Info</h4>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          
          <label class="block w-full mt-2 text-sm">
            <span class="show-block">
              {{ deliveryRedelivery.business_unit }}
            </span>
          </label>
          <label class="block w-full mt-2 text-sm"></label>
          <label class="block w-full mt-2 text-sm"></label>
          <label class="block w-full mt-2 text-sm"></label>
        </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Note Type</span>
              <span class="show-block">
                {{ deliveryRedelivery.note_type }}
              </span>
          </label>


          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Effective Date </span>
              <span class="show-block">
                <nobr>{{ deliveryRedelivery?.effective_date ? moment(deliveryRedelivery?.effective_date).format('DD-MM-YYYY') : null }}</nobr>
              </span>

            </label>

          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Exchange Rate</span>
              <span class="show-block">
                {{ deliveryRedelivery.exchange_rate }}
              </span>
          </label>

          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Currency</span>
              <span class="show-block">
                {{ deliveryRedelivery.currency }}
              </span>
        </label>
          
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Vessel </span>
              <span class="show-block">
                {{ deliveryRedelivery.opsVessel?.name }}
              </span>
            </label>
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Vessel Code</span>
              <span class="show-block">
                {{ deliveryRedelivery.opsVessel?.short_code }}
              </span>
          </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Vessel Owner Name</span>
              <span class="show-block">
                {{ deliveryRedelivery.opsVessel?.owner_name }}
              </span>
          </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Vessel Capacity</span>
            <span class="show-block">
                {{ deliveryRedelivery.opsVessel?.capacity }}
              </span>
        </label>
        
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Select Charterer </span>
              <span class="show-block">
                {{ deliveryRedelivery.opsChartererProfile?.name }}
              </span>

          </label>
        
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Charterer Owner Code</span>
              <span class="show-block">
                {{ deliveryRedelivery.opsChartererProfile?.owner_code }}
              </span>
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Charterer Email</span>
              <span class="show-block">
                {{ deliveryRedelivery.opsChartererProfile?.email }}
              </span>
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Contact No.</span>
            <span class="show-block">
                {{ deliveryRedelivery.opsChartererProfile?.contact_no }}
              </span>
        </label>
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">

        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Remarks</span>
          <span class="show-block">
                {{ deliveryRedelivery.remarks }}
              </span>
        </label>
        <label class="block w-full mt-2 text-sm"></label>


      </div>
    </div>
    <div v-if="deliveryRedelivery.opsBunkers?.length" id="bunker-info" class="my-5">
      <h4 class="text-md font-semibold uppercase mb-2">Bunker Information</h4>
        <table class="w-full whitespace-no-wrap" >
          <thead v-once>
            <tr class="w-full">
              <th>SL</th>
              <th class="w-72">Bunker Name</th>
              <th>Unit</th>
              <th>Quantity</th>
              <th>Rate</th>
              <th>Amount <small>(USD)</small></th>
              <th>Amount <small>(BDT)</small></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(certificate, index) in deliveryRedelivery.opsBunkers">
              <td>
                {{ index+1 }}
              </td>
              <td>
                <span class="show-block !justify-center" v-if="deliveryRedelivery.opsBunkers[index]?.name">{{ deliveryRedelivery.opsBunkers[index]?.name }}</span>
              </td>
              <td>
                <span class="show-block !justify-center" v-if="deliveryRedelivery.opsBunkers[index]?.unit">{{ deliveryRedelivery.opsBunkers[index]?.unit }}</span>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <span class="show-block">
                    {{ numberFormat(deliveryRedelivery.opsBunkers[index].quantity) }}
                  </span>
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <span class="show-block">
                    {{ numberFormat(deliveryRedelivery.opsBunkers[index].rate) }}
                  </span>
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <span class="show-block">
                    {{ numberFormat(deliveryRedelivery.opsBunkers[index].amount_usd) }}
                  </span>
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <span class="show-block">
                    {{ numberFormat(deliveryRedelivery.opsBunkers[index].amount_bdt) }}
                  </span>
                </label>
              </td>
            </tr>
          </tbody>
        </table>
    </div>
</template>
<script setup>
import { ref, watchEffect, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useDeliveryRedelivery from '../../../composables/operations/useDeliveryRedelivery';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import useHelper from "../../../composables/useHelper";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import moment from 'moment';

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