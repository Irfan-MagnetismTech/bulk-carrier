<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useItem from '../../../composables/maintenance/useItem';

const icons = useHeroIcon();

const route = useRoute();
const itemId = route.params.itemId;
const { item, showItem, errors } = useItem();

const { setTitle } = Title();

setTitle('Item Details');

onMounted(() => {
  showItem(itemId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Item Details</h2>
    <default-button :title="'Item List'" :to="{ name: 'mnt.items.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <div class="flex md:gap-4">
        <div class="w-full">
          <h2 class="bg-green-600 text-white text-md font-semibold uppercase mb-2 text-center py-2">Item Information</h2>
          <table class="w-full">
            <!-- <thead>
            <tr>
              <td class="!text-center bg-gray-200 font-bold" colspan="2">Personal Info</td>
            </tr>
            </thead> -->
            <tbody>
              <tr>
                <th class="w-40">Business Unit</th>
                <td><span :class="item?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ item?.business_unit }}</span></td>
              </tr>
              <tr>
                <th class="w-40">Ship Department</th>
                <td>{{ item?.mntItemGroup?.mntShipDepartment?.name }}</td>
              </tr>
              
              <tr>
                <th class="w-40">Item Group</th>
                <td>{{ item?.mntItemGroup?.name }}</td>
              </tr>
              
              <tr>
                <th class="w-40">Item Code</th>
                <td>{{ item?.item_code }}</td>
              </tr>
              
              <tr>
                <th class="w-40">Item Name</th>
                <td>{{ item?.name }}</td>
              </tr>
              
              <tr>
                <th class="w-40">Description</th>
                <!-- <td>{{ item?.description }}</td> -->
                <td>
                    <table class=" w-full border-none">
                        <tbody class="border-none">
                            <tr class="border-none" v-for="(des, index) in item?.description" :key="index">
                                <td class="border-none p-0 pb-1"><strong>{{ des.key }} :</strong> {{ des.value }}</td>
                                <!-- <td class="border-none">{{ des.value }}</td> -->
                            </tr>
                        </tbody>

                    </table>
                </td>
              </tr>

              <tr>
                <th class="w-40">Running Hour Entry</th>
                <td>
                    <span :class="item?.has_run_hour == true ? 'text-green-700 bg-green-100' : 'text-red-700 bg-red-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ item?.has_run_hour == true ? 'Enabled' : 'Disabled' }}</span>
                
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

</style>