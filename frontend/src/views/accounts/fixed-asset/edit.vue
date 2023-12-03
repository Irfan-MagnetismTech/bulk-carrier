<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';

import FixedAssetForm from '../../../components/accounts/FixedAssetForm.vue';
import useFixedAsset from '../../../composables/accounts/useFixedAsset';
import Title from "../../../services/title";

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const route = useRoute();
const fixedAssetId = route.params.fixedAssetId;
const { fixedAsset, showFixedAsset, updateFixedAsset, bgColor, errors } = useFixedAsset();
const icons = useHeroIcon();

const { setTitle } = Title();

setTitle('Fixed Asset');

const page = 'edit';

watch(fixedAsset, (value) => {
  if(value) {
    fixedAsset.value.acc_cost_center_name = fixedAsset.value.costCenter;
    fixedAsset.value.scm_mrr_name = fixedAsset.value.scmMrr;
    fixedAsset.value.scm_material_name = fixedAsset.value.scmMaterial
    fixedAsset.value.acc_parent_account_name = fixedAsset.value.fixedAssetCategory;
  }
});

onMounted(() => {
  showFixedAsset(fixedAssetId);
});

</script>
<template>
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200"> Update Fixed Asset</h2>
    <default-button :title="'Fixed Asset'" :to="{ name: 'acc.fixed-assets.index' }" :icon="icons.DataBase"></default-button>
  </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800" :style="{ 'background-color': bgColor }">
        <form @submit.prevent="updateFixedAsset(fixedAsset, fixedAssetId)">
            <!-- Booking Form -->
          <fixed-asset-form @bgColor="handleColorSelected" :page="page" v-model:form="fixedAsset" :errors="errors"></fixed-asset-form>
            <!-- Submit button -->
            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>