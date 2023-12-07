<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Approve Purchase Requistions</h2>
    <default-button :title="'Purchase Requistion List'" :to="{ name: 'ops.bunker-requisitions.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800 relative">
      <form @submit.prevent="approvedBunkerRequisition(bunkerRequisition, bunkerRequisitionId)">
          <!-- Port Form -->
          <bunker-requisition-approve-form v-model:form="bunkerRequisition" :errors="errors" :formType="formType"></bunker-requisition-approve-form>
          <!-- Submit button -->
          <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Approve</button>
      </form>
  </div>
</template>
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import BunkerRequisitionApproveForm from '../../../components/operations/BunkerRequisitionApproveForm.vue';
import useBunkerRequisition from '../../../composables/operations/useBunkerRequisition';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";

const icons = useHeroIcon();

const route = useRoute();
const bunkerRequisitionId = route.params.bunkerRequisitionId;
const { bunkerRequisition, bunkerRequisitionBunkerObject, showBunkerRequisition, approvedBunkerRequisition, errors } = useBunkerRequisition();

const { setTitle } = Title();

setTitle('Approve Purchase Requistion');

const formType = 'approve';

onMounted(() => {
  showBunkerRequisition(bunkerRequisitionId);
});
</script>