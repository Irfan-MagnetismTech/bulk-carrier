<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import moment from 'moment';
import useCriticalVesselItem from '../../../composables/maintenance/useCriticalVesselItem';

const icons = useHeroIcon();

const route = useRoute();
const criticalVesselItemId = route.params.criticalVesselItemId;
const { criticalVesselItem, showCriticalVesselItem, errors } = useCriticalVesselItem();

const { setTitle } = Title();

setTitle('Critical Vessel Item Details');

onMounted(() => {
  showCriticalVesselItem(criticalVesselItemId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Critical Vessel Item Details</h2>
    <default-button :title="'Critical Vessel Item List'" :to="{ name: 'mnt.critical-vessel-items.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <div class="flex md:gap-4">
        <div class="w-full">
          <h2 class="bg-green-600 text-white text-md font-semibold uppercase mb-2 text-center py-2">Critical Vessel Item Information</h2>
          <table class="w-full">
            <!-- <thead>
            <tr>
              <td class="!text-center bg-gray-200 font-bold" colspan="2">Personal Info</td>
            </tr>
            </thead> -->
            <tbody>
              <tr>
                <th class="w-40">Business Unit</th>
                <td>{{ criticalVesselItem?.business_unit }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Vessel</th>
                <td>{{ criticalVesselItem?.opsVessel?.name }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Critical Function</th>
                <td>{{ criticalVesselItem?.mntCriticalItem?.mntCriticalItemCat?.mntCriticalFunction?.function_name }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Category</th>
                <td>{{ criticalVesselItem?.mntCriticalItem?.mntCriticalItemCat?.category_name }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Item</th>
                <td>{{ criticalVesselItem?.mntCriticalItem?.item_name }}</td>
              </tr>

              <tr>
                <th class="w-40">Specification</th>
                <td>{{ criticalVesselItem?.specification }}</td>
              </tr>
              
              <tr>
                <th class="w-40">Notes </th>
                <td>{{ criticalVesselItem?.notes }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Critical Item</th>
                <td><input type="checkbox" v-model="criticalVesselItem.is_critical" disabled /></td>
              </tr>

              
              <tr>
                <th class="w-40">Spare Parts</th>
                <td>
                    <div class="grid grid-cols-1 overflow-x-auto">
                      <table class="w-full" v-if="criticalVesselItem?.mntCriticalItemSps?.length">
                        <thead>
                          <th class="text-center"> Spare Parts Name	 </th>
                          <th class="text-center"> Minimum ROB	 </th>
                        </thead>
                        <tbody>
                          <tr v-for="(mntCriticalItemSp, index) in criticalVesselItem.mntCriticalItemSps" :key="index">
                            <td> {{ mntCriticalItemSp.sp_name }} </td>
                            <td class="text-center"> {{ mntCriticalItemSp.min_rob }} {{ mntCriticalItemSp.unit }} </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                </td>
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

  #profileDetailTable th{
    text-align: center;
  }
  #profileDetailTable thead tr{
    @apply bg-gray-200
  }

  th.text-center, td.text-center, tr.text-center {
    @apply text-center border-gray-500
  }

</style>