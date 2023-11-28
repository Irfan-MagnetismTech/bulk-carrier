<script setup>
import FixedAssetForm from '../../../components/accounts/FixedAssetForm.vue';
import useFixedAsset from '../../../composables/accounts/useFixedAsset';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import useDepreciation from "../../../composables/accounts/useDepreciation";
import DepreciationForm from "../../../components/accounts/DepreciationForm.vue";
const icons = useHeroIcon();
const { depreciation, storeDepreciation, isLoading, errors } = useDepreciation();
const { setTitle } = Title();

setTitle('Depreciation');

const page = 'create';

</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3 " v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Create Depreciation</h2>
      <default-button :title="'Depreciation List'" :to="{ name: 'acc.depreciations.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800 overflow-hidden">
        <form @submit.prevent="storeDepreciation(depreciation)">
            <!-- Booking Form -->
          <depreciation-form :page="page" v-model:form="depreciation" :errors="errors"></depreciation-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm text-white bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
        </form>
    </div>
</template>