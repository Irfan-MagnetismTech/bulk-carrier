<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import moment from 'moment';
import useCriticalItem from '../../../composables/maintenance/useCriticalItem';

const icons = useHeroIcon();

const route = useRoute();
const criticalItemId = route.params.criticalItemId;
const { criticalItem, showCriticalItem, errors } = useCriticalItem();

const { setTitle } = Title();

setTitle('Critical Item Details');

onMounted(() => {
  showCriticalItem(criticalItemId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Critical Item Details</h2>
    <default-button :title="'Critical Item List'" :to="{ name: 'mnt.critical-items.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <div class="flex md:gap-4">
        <div class="w-full">
          <h2 class="bg-green-600 text-white text-md font-semibold uppercase mb-2 text-center py-2">Critical Item Information</h2>
          <table class="w-full">
            <!-- <thead>
            <tr>
              <td class="!text-center bg-gray-200 font-bold" colspan="2">Personal Info</td>
            </tr>
            </thead> -->
            <tbody>
              <tr>
                <th class="w-40">Critical Function</th>
                <td>{{ criticalItem?.mntCriticalItemCat?.mntCriticalFunction?.function_name }}</td>
              </tr>
              
              <tr>
                <th class="w-40">Category Name</th>
                <td>{{ criticalItem?.mntCriticalItemCat?.category_name }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Item Name</th>
                <td>{{ criticalItem.item_name }}</td>
              </tr>

              
              <!-- <tr>
                <th class="w-40">Specification</th>
                <td>{{ criticalItem.specification }}</td>
              </tr> -->

              
              <tr>
                <th class="w-40">Notes</th>
                <td>{{ criticalItem.notes }}</td>
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