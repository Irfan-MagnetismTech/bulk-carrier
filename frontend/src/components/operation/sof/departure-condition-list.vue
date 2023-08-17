
<script setup>
import {watch, toRefs} from 'vue';
import Error from "../../Error.vue";
import moment from 'moment';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Editor from '@tinymce/tinymce-vue';
import useVoyageSof from "../../../composables/operation/sof/useVoyageSof";
import {useRoute} from "vue-router";

const route = useRoute();
const voyageId = route.params.voyageId;
const { voyageSof, voyageSofLists, storeVoyageSofList, showVoyageSofList, deleteVoyageSofList, isVoyageSofModalOpen, isLoading, errors } = useVoyageSof();

const props = defineProps({
  data: {
    default: () => [],
  },
  uniqueServicePorts: {
    default: () => [],
  },
  additionalInfo: {
    default: () => [],
  },
  sofTimes: {
    default: () => []
  }
});

const { data, uniqueServicePorts, voyageSofListsProp } = toRefs(props);

function openDgListModal() {
  isVoyageSofModalOpen.value = 1;
  voyageSof.value = {};
}

function updateDgList(voyageSofId) {
  showVoyageSofList(voyageSofId);
}

function closeDgListModal() {
  isVoyageSofModalOpen.value = 0;
}

watch(voyageSofListsProp, (newVal, oldVal) => {
  voyageSofLists.value = newVal;
});
</script>

<template>
  <div class="grid grid-cols-2 gap-4">
    <table class="w-full whitespace-no-wrap mt-2 border-collapse">
      <thead>
      <tr>
        <th colspan="2" class="text-center font-bold bg-red-500 text-white">
          Departure (Supply) & DISCHARGING
        </th>
      </tr>
      
      </thead>
      <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
        <tr>
          <td>FUEL OIL</td>
          <td><input type="text" 
            v-model="props.additionalInfo.supply_fuel"
            name="" id="" class="w-full bg-gray-100" /></td>
          
        </tr>
        <tr>
          <td>DIESEL OIL</td>
          <td><input type="text" 
            v-model="props.additionalInfo.supply_diesel"
            name="" id="" class="w-full bg-gray-100" /></td>
        </tr>
        <tr>
          <td>FRESH WATER</td>
          <td><input type="text" 
            v-model="props.additionalInfo.supply_fresh_water"
            name="" id="" class="w-full bg-gray-100" /></td>
        </tr>
        <tr>
          <td>SLUDGE</td>
          <td><input type="text" 
            v-model="props.additionalInfo.supply_sludge"
            name="" id="" class="w-full bg-gray-100" /></td>
        </tr>
        <tr>
          <td>GARBAGE</td>
          <td><input type="text" 
            v-model="props.additionalInfo.supply_garbage"
            name="" id="" class="w-full bg-gray-100" /></td>
        </tr>
      </tbody>
    </table>

    <table class="w-full whitespace-no-wrap mt-2 border-collapse">
      <thead>
      <tr>
        <th colspan="2" class="text-center font-bold bg-red-500 text-white">
          Additional Info
        </th>
      </tr>
      
      </thead>
      <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
        <tr>
          <td>HATCH COVER</td>
          <td><input type="text"
            v-model="props.additionalInfo.hatch_cover"
            class="w-full bg-gray-100" placeholder="Hatch Cover" /></td>
        </tr>
        <tr>
          <td>GEAR BIN HANDED</td>
          <td><input type="text" 
            v-model="props.additionalInfo.gear_bin"
            class="w-full bg-gray-100" placeholder="Gear Bin Handed" /></td>
        </tr>
        <tr>
          <td>ARRIVAL DATE/TIME</td>
          <td><input type="datetime-local" 
            v-model="props.sofTimes.arrival"
            class="w-full bg-gray-100"></td>
        </tr>
        <tr>
          <td>BERTHING DATE/TIME</td>
          <td><input type="datetime-local" 
            v-model="props.sofTimes.berthing"
            class="w-full bg-gray-100"></td>
        </tr>
        <tr>
          <td>DEPARTURE DATE/TIME</td>
          <td><input type="datetime-local" 
            v-model="props.sofTimes.departure"
            class="w-full bg-gray-100"></td>
        </tr>
        <tr>
          <td>ARRIVE NEXT PORT</td>
          <td><input type="datetime-local" 
            v-model="props.sofTimes.eta_next_port"
            class="w-full bg-gray-100"></td>
        </tr>
      </tbody>
    </table>
  </div>

</template>

<style lang="postcss" scoped>
@tailwind components;

@layer components {
  th {
    @apply px-3 py-2;
  }
  td {
    @apply px-3 py-2 text-xs border-r;
  }
  thead tr {
    @apply text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800;
  }
  tbody {
    @apply bg-white divide-y dark:divide-gray-700 dark:bg-gray-800;
  }

  td {
    @apply px-3 py-2 text-xs;
  }
  thead tr {
    @apply text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800;
  }
  tbody {
    @apply bg-white divide-y dark:divide-gray-700 dark:bg-gray-800;
  }

  table th,tr,td{
    border: 1px solid gray;
  }
  #modal{
    max-width: 50rem !important;
  }
}
</style>