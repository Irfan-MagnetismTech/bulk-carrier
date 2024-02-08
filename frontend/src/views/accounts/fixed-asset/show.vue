<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useFixedAsset from '../../../composables/accounts/useFixedAsset';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import env from '../../../config/env';
import { formatDate } from "../../../utils/helper.js";

const icons = useHeroIcon();

const route = useRoute();
const fixedAssetId = route.params.fixedAssetId;
const { fixedAsset, showFixedAsset, errors } = useFixedAsset();

const { setTitle } = Title();

setTitle('Fixed Asset Details');

onMounted(() => {
  showFixedAsset(fixedAssetId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">  Fixed Asset Details  # {{fixedAssetId}} </h2>
    <default-button :title="'Fixed Asset List'" :to="{ name: 'acc.fixed-assets.index' }" :icon="icons.DataBase"></default-button>
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
            <td><span :class="fixedAsset?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ fixedAsset?.business_unit }}</span></td>
          </tr>
          <tr>
            <th class="w-40">Cost Center Name</th>
            <td>{{ fixedAsset?.costCenter?.name ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Mrr No</th>
            <td>{{ fixedAsset?.scmMrr?.ref_no ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Asset Name</th>
            <td>{{ fixedAsset?.scmMaterial?.name ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Asset Category</th>
            <td>{{ fixedAsset?.fixedAssetCategory?.account_name ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Asset Tag</th>
            <td>{{ fixedAsset?.asset_tag ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Brand</th>
            <td>{{ fixedAsset?.brand ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Model</th>
            <td>{{ fixedAsset?.model ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Serial</th>
            <td>{{ fixedAsset?.serial ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Useful Life (Year)</th>
            <td>{{ fixedAsset?.useful_life ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Depreciation Rate (%)</th>
            <td>{{ fixedAsset?.useful_life ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Acquisition Date</th>
            <td>{{ formatDate(fixedAsset?.acquisition_date) ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Location</th>
            <td>{{ fixedAsset?.location ?? '---' }}</td>
          </tr>
          </tbody>
        </table>
        <table class="w-full mt-2">
          <thead>
          <tr>
            <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="5">Fixed Asset Cost List </td>
          </tr>
          <tr>
            <th class="w-40 !text-center">Particular</th>
            <th class="w-40 !text-center">Amount</th>
            <th class="w-40 !text-center">Remarks</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(fixedAssetCost, index) in fixedAsset?.fixedAssetCosts" :key="index">
            <td class="text-left"> {{ fixedAssetCost?.particular }} </td>
            <td class="!text-center"> {{ fixedAssetCost?.amount }} </td>
            <td class="text-left"> {{ fixedAssetCost?.remarks }} </td>
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