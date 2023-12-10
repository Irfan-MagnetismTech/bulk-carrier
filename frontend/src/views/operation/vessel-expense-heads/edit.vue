<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Vessel Expense Head</h2>
    <default-button :title="'Expense Head List'" :to="{ name: 'ops.vessel-expense-heads.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <form @submit.prevent="updateVesselExpenseHead(vesselExpenseHead, vesselExpenseHeadId, formType)">
          <!-- Port Form -->
          <vessel-expense-head-form v-model:form="vesselExpenseHead" :errors="errors" :formType="formType"></vessel-expense-head-form>
          <!-- Submit button -->
          <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
      </form>
  </div>
</template>
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import VesselExpenseHeadForm from '../../../components/operations/VesselExpenseHeadForm.vue';
import useVesselExpenseHead from '../../../composables/operations/useVesselExpenseHead';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";

const icons = useHeroIcon();

const route = useRoute();
const vesselExpenseHeadId = route.params.vesselExpenseHeadId;
const { vesselExpenseHead, showVesselExpenseHead, updateVesselExpenseHead, errors } = useVesselExpenseHead();

const { setTitle } = Title();

setTitle('Edit Vessel Expense Head');

const formType = 'edit';

onMounted(() => {
  showVesselExpenseHead(vesselExpenseHeadId);
});
</script>