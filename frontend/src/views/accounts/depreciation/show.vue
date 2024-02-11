<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useDepreciation from '../../../composables/accounts/useDepreciation';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import env from '../../../config/env';
import { formatMonthYear, formatDate } from "../../../utils/helper.js";

const icons = useHeroIcon();

const route = useRoute();
const depreciationId = route.params.depreciationId;
const { depreciation, showDepreciation, errors } = useDepreciation();

const { setTitle } = Title();

setTitle('Depreciation Details');

onMounted(() => {
  showDepreciation(depreciationId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Depreciation Details </h2>
    <default-button :title="'Depreciation List'" :to="{ name: 'acc.depreciations.index' }" :icon="icons.DataBase"></default-button>
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
            <th class="w-40"> Business Unit </th>
            <td><span :class="depreciation?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ depreciation?.business_unit }}</span></td>
          </tr>
          <tr>
            <th class="w-40">Cost Center Name</th>
            <td>{{ depreciation?.costCenter?.name ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Year</th>
            <td>{{ formatMonthYear(depreciation?.month_year) ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Applied Date</th>
            <td>{{ formatDate(depreciation?.applied_date) ?? '---' }}</td>
          </tr>
          </tbody>
        </table>
        <table class="w-full mt-2">
          <thead>
          <tr>
            <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="7">Depreciation Lines </td>
          </tr>
          <tr>
            <th class="w-40 !text-center">#</th>
            <th class="w-40 !text-center">Asset Name</th>
            <th class="w-40 !text-center">Asset Tag</th>
            <th class="w-40 !text-center">Useful Life (Year)</th>
            <th class="w-40 !text-center">Depreciation Rate (%)</th>
            <th class="w-40 !text-center">Acquisition Cost</th>
            <th class="w-40 !text-center">Amount</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(depreciationLine, index) in depreciation?.accDepreciationLines" :key="index">
            <td class="!text-center"> {{ index + 1 }} </td>
            <td class="text-left"> {{ depreciationLine?.accFixedAsset?.scmMaterial?.name }} </td>
            <td class="text-left"> {{ depreciationLine?.accFixedAsset?.asset_tag }} </td>
            <td class="!text-center"> {{ depreciationLine?.accFixedAsset?.useful_life }} </td>
            <td class="!text-right"> {{ depreciationLine?.accFixedAsset?.depreciation_rate }} </td>
            <td class="!text-right"> {{ depreciationLine?.accFixedAsset?.acquisition_cost }} </td>
            <td class="!text-right"> {{ depreciationLine?.amount }} </td>
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

#profileDetailTable th{
  text-align: center;
}
#profileDetailTable thead tr{
  @apply bg-gray-200
}

</style>