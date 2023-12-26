<script setup>
import { onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import moment from 'moment';
import useCriticalSpareList from '../../../composables/maintenance/useCriticalSpareList';
import { formatDate } from '../../../utils/helper';

const icons = useHeroIcon();

const route = useRoute();
const criticalSpareListId = route.params.criticalSpareListId;
const { criticalSpareList, showCriticalSpareList, errors } = useCriticalSpareList();

const { setTitle } = Title();
watch(criticalSpareList, (value) => {
    const { opsVessel, mntCriticalVesselItems } = value || {};

    criticalSpareList.value.ops_vessel = opsVessel;
    criticalSpareList.value.mntCriticalSpListLines = mntCriticalVesselItems;

    criticalSpareList.value?.mntCriticalSpListLines.forEach((mntCriticalSpListLine) => {
      mntCriticalSpListLine.mntCriticalItemSps = mntCriticalSpListLine.mntCriticalSpListLines;
      delete mntCriticalSpListLine.mntCriticalSpListLines;
    });

    if ('mntCriticalVesselItems' in value) {
      delete value.mntCriticalVesselItems;
    }
  });

setTitle('Critical Spare List Details');

onMounted(() => {
  showCriticalSpareList(criticalSpareListId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Critical Spare List Details</h2>
    <default-button :title="'Critical Spare List'" :to="{ name: 'mnt.critical-spare-lists.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <div class="flex md:gap-4">
        <div class="w-full">
          <h2 class="bg-green-600 text-white text-md font-semibold uppercase mb-2 text-center py-2">Critical Spare List Information</h2>
          <table class="w-full">
            <!-- <thead>
            <tr>
              <td class="!text-center bg-gray-200 font-bold" colspan="2">Personal Info</td>
            </tr>
            </thead> -->
            <tbody>
              <tr>
                <th class="w-40">Business Unit</th>
                <td>{{ criticalSpareList?.business_unit }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Vessel</th>
                <td>{{ criticalSpareList?.opsVessel?.name }}</td>
              </tr>



              
              <tr>
                <th class="w-40">Record Date</th>
                <td>{{ formatDate(criticalSpareList?.record_date) }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Reference No</th>
                <td>{{ criticalSpareList?.reference_no }}</td>
              </tr>

              <tr>
                <!-- {{ criticalSpareList }} -->
                <th class="w-40">Critical Spare Parts</th>
                <td>
                  <div class="grid grid-cols-1 overflow-x-auto">
                    <table class="w-full" v-if="criticalSpareList?.mntCriticalSpListLines?.length">
                      <thead>
                        <th class="text-center"> Spare Parts Name </th>
                        <th class="text-center"> Minimum Rob </th>
                        <th class="text-center"> Rob </th>
                        <th class="text-center"> Remarks </th>
                      </thead>
                      <tbody>
                        <template  v-for="(criticalVesselItem, index) in criticalSpareList?.mntCriticalSpListLines" :key="index">
                          <tr  v-if="criticalVesselItem?.mntCriticalItemSps?.length">
                            <td colspan="4" ><strong class="text-left block "><span class="">{{ criticalVesselItem?.mntCriticalItem?.item_name }}</span> <span class="pl-1" v-show="criticalVesselItem?.mntCriticalItem?.specification">({{ criticalVesselItem?.mntCriticalItem?.specification }})</span></strong> </td>
                          </tr>
                          <tr   v-for="(mntCriticalItemSp, mntCriticalItemSpIndex) in criticalVesselItem?.mntCriticalItemSps" :key="mntCriticalItemSpIndex">
                            <td>{{ mntCriticalItemSp?.sp_name }}</td>
                            <td class="text-center"><nobr>{{ mntCriticalItemSp?.min_rob }} {{ mntCriticalItemSp?.unit }}</nobr></td>
                            <td class="text-center"><nobr>{{ mntCriticalItemSp?.rob }} {{ mntCriticalItemSp?.unit }}</nobr></td>
                            <td>{{ mntCriticalItemSp?.remarks }}</td>
                            
                          </tr>
                      </template>
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