<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Vessel Expense Head Details</h2>
    <default-button :title="'Expense Head List'" :to="{ name: 'ops.vessel-expense-heads.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Business Unit </span>

            <span class="show-block">
              {{ vesselExpenseHead.business_unit }}
            </span>
          </label>
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Vessel </span>
            
              <span class="show-block">
                {{ vesselExpenseHead?.opsVessel?.name }}
              </span>
            </label> 
  </div>
  <!-- South Sectors -->
  <div class="relative" v-if="vesselExpenseHead?.heads?.length > 0">


        <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
          <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Cost Group and Cost Heads </legend>
          <div class="mt-2">
            <div :id="'cost_' + index" :class="index%2===0 ? 'bg-gray-100' : 'bg-yellow-100'" style="position: relative" class="px-2 py-2 border sm:rounded-lg mb-1" v-for="(costGroup, index) in vesselExpenseHead.heads" :key="index">

              <label class="flex items-center mb-2 text-gray-600 dark-disabled:text-gray-400">
                <input disabled type="checkbox" v-model="vesselExpenseHead.heads[index].is_checked" class="text-purple-600 form-radio focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray">
                <span class="ml-2 font-bold">{{ costGroup.name }}</span>
              </label>

              <template v-for="(subhead,subIndex) in vesselExpenseHead.heads[index]?.opsSubHeads" :key="subIndex">
                <label class="flex ml-6 items-center mb-2 text-gray-600 dark-disabled:text-gray-400">
                  <input disabled type="checkbox" v-model="vesselExpenseHead.heads[index].opsSubHeads[subIndex].is_checked" class="text-purple-600 form-radio focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray">
                  <span class="ml-2 font-bold">{{ subhead?.name }}</span>
                </label>
              </template>
              
            </div>
          </div>
        </fieldset>

  </div>
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
const { vesselExpenseHead, showVesselExpenseHead, errors } = useVesselExpenseHead();

const { setTitle } = Title();

setTitle('Vessel Expense Head Details');

onMounted(() => {
  showVesselExpenseHead(vesselExpenseHeadId);
});

</script>