<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Bunker Bill</h2>
    <default-button :title="'Purchase Bill List'" :to="{ name: 'ops.bunker-bills.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800 relative">
      <form @submit.prevent="updateBunkerBill(bunkerBill, bunkerBillId)">
          <!-- Port Form -->
          <bunker-bill-form v-model:form="bunkerBill" :errors="errors" :formType="formType"></bunker-bill-form>
          <!-- Submit button -->
          <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
      </form>
  </div>
</template>
<script setup>
import { onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import BunkerBillForm from '../../../components/operations/BunkerBillForm.vue';
import useBunkerBill from '../../../composables/operations/useBunkerBill';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";

const icons = useHeroIcon();

const route = useRoute();
const bunkerBillId = route.params.bunkerBillId;
const { bunkerBill, bunkerBillBunkerObject, showBunkerBill, updateBunkerBill, errors } = useBunkerBill();

const { setTitle } = Title();

setTitle('Edit Bunker Bill');

const formType = 'edit';
  watch(bunkerBill, (value) => {
    bunkerBill.value.scmVendor = value?.scmVendor;
  });

onMounted(() => {
  showBunkerBill(bunkerBillId);
});
</script>