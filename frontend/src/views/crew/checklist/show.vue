<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useCheckList from "../../../composables/crew/useCheckList";
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import env from '../../../config/env';

const icons = useHeroIcon();

const route = useRoute();
const checkListId = route.params.checkListId;
const { checkList, showCheckList, errors } = useCheckList();

const { setTitle } = Title();

setTitle('Crew Checklist Details');

onMounted(() => {
  showCheckList(checkListId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Crew Checklist Details # {{checkListId}}</h2>
    <default-button :title="'Crew Checklist'" :to="{ name: 'crw.checklists.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <div class="flex md:gap-4">
        <div class="w-full">
          <table class="w-full">
            <thead>
            <tr>
              <td class="!text-center uppercase text-white font-bold bg-green-600" colspan="2">Basic Info</td>
            </tr>
            </thead>
            <tbody>
              <tr>
                <th class="w-40 text-left">Business Unit</th>
                <td class="text-left"><span :class="checkList?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ checkList?.business_unit }}</span></td>
              </tr>
              <tr>
                <th class="w-40 text-left">Effective Date</th>
                <td class="text-left">{{ checkList?.effective_date }}</td>
              </tr>
            </tbody>
          </table>
          <table class="w-full mt-1" id="profileDetailTable">
            <thead>
            <tr>
              <td class="!text-center text-white uppercase bg-green-600 font-bold" colspan="8">Checklist Item</td>
            </tr>
            <tr>
              <th>Sl.</th>
              <th>Item Name</th>
              <th>Remarks</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(checkListData,index) in checkList?.crwCrewChecklistLines" :key="index">
              <td>{{ index + 1 }}</td>
              <td class="text-left">{{ checkListData?.item_name }}</td>
              <td>{{ checkListData?.remarks }}</td>
            </tr>
            </tbody>
            <tfoot v-if="!checkList?.crwCrewChecklistLines?.length">
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
    @apply border-gray-500
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