<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useAdvanceAdjustment from '../../../composables/accounts/useAdvanceAdjustment.js';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import env from '../../../config/env';
import { formatMonthYear, formatDate } from "../../../utils/helper.js";

const icons = useHeroIcon();

const route = useRoute();
const advanceAdjustmentId = route.params.advanceAdjustmentId;
const { advanceAdjustment, showAdvanceAdjustment, errors } = useAdvanceAdjustment();

const { setTitle } = Title();

setTitle('Advance Adjustment Details');

onMounted(() => {
  showAdvanceAdjustment(advanceAdjustmentId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Advance Adjustment Details</h2>
    <default-button :title="'Advance Adjustment List'" :to="{ name: 'acc.advance-adjustments.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
    <div class="flex md:gap-4">
      <div class="w-full">
<!--        <h2 class="bg-green-600 text-white text-md font-semibold uppercase mb-2 text-center py-2">Advance Adjustment Information # {{advanceAdjustmentId}}</h2>-->
        <table class="w-full">
          <thead>
          <tr>
            <td class="!text-center bg-gray-200 font-bold bg-green-700 text-white" colspan="2">Basic Info</td>
          </tr>
          </thead>
          <tbody>
          <tr>
            <th class="w-40">Business Unit</th>
            <td><span :class="advanceAdjustment?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ advanceAdjustment?.business_unit }}</span></td>
          </tr>
          <tr>
            <th class="w-40">Cost Center Name</th>
            <td>{{ advanceAdjustment?.costCenter?.name }}</td>
          </tr>
          <tr>
            <th class="w-40">Adjustment Date</th>
            <td>{{ formatDate(advanceAdjustment?.adjustment_date) }}</td>
          </tr>
          <tr>
            <th class="w-40">Cash Requisition No.</th>
            <td>{{ advanceAdjustment?.accCashRequisition?.id }}</td>
          </tr>
          <tr>
            <th class="w-40">Cash Requisition Amount</th>
            <td>{{ advanceAdjustment?.accCashRequisition?.total_amount }}</td>
          </tr>
          <tr>
            <th class="w-40">Adjustment Amount</th>
            <td>{{ advanceAdjustment?.adjustment_amount }}</td>
          </tr>
          </tbody>
        </table>
        <table class="w-full mt-1" id="profileDetailTable">
          <thead>
          <tr>
            <td class="!text-center bg-gray-200 font-bold bg-green-700 text-white" colspan="8">Particular List</td>
          </tr>
          <tr>
            <th>#</th>
            <th>Particular</th>
            <th>Remarks</th>
            <th>Amount</th>
            <th>Attachment</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(adjustmentData,index) in advanceAdjustment?.accAdvanceAdjustmentLines" :key="index">
            <td class="!text-center">{{ index + 1 }}</td>
            <td>{{ adjustmentData?.particular }}</td>
            <td>{{ adjustmentData?.remarks }}</td>
            <td class="!text-right">{{ adjustmentData?.amount }}</td>
            <td>
              <template v-if="adjustmentData?.attachment !== 'null'">
                <a class="custom_link" :href="env.BASE_API_URL + '/' + adjustmentData?.attachment" target="_blank">Click to view</a>
              </template>
              <template v-else>
                <span>Not Available</span>
              </template>
            </td>
          </tr>
          </tbody>
          <tfoot v-if="!advanceAdjustment?.accAdvanceAdjustmentLines?.length">
          <tr>
            <td colspan="3">No data found.</td>
          </tr>
          </tfoot>
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