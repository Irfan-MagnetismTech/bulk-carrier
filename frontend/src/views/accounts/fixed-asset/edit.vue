<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';

import LoanForm from '../../../components/accounts/LoanForm.vue';
import useFixedAsset from '../../../composables/accounts/useFixedAsset';
import Title from "../../../services/title";

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const route = useRoute();
const loanId = route.params.loanId;
const { loan, showLoan, updateLoan, bgColor, errors } = useFixedAsset();
const icons = useHeroIcon();

const { setTitle } = Title();

setTitle('Fixed Asset');

const page = 'edit';

watch(loan, (value) => {
  if(value) {
    
  }
});


onMounted(() => {
  showLoan(fixedAssetId);
});

</script>
<template>
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Fixed Asset</h2>
    <default-button :title="'Fixed Asset'" :to="{ name: 'acc.fixed-assets.index' }" :icon="icons.DataBase"></default-button>
  </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800" :style="{ 'background-color': bgColor }">
        <form @submit.prevent="updateLoan(fixedAsset, fixedAssetId)">
            <!-- Booking Form -->
          <loan-form @bgColor="handleColorSelected" :page="page" v-model:form="fixedAsset" :errors="errors"></loan-form>
            <!-- Submit button -->
            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>