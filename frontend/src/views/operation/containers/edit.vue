<script setup>
import { onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import useVoyageContainer from '../../../composables/operation/useVoyageContainer';
import usePort from "../../../composables/usePort";
import useCommodity from "../../../composables/dataencoding/useCommodity";


const route = useRoute();
const { voyageContainer, showVoyageContainer, updateVoyageContainer, isLoading, error } = useVoyageContainer();
const { commodities, getcommodities } = useCommodity();

const containerId = route.params.containerId;

const { ports, getPortsByNameOrCode } = usePort();

function fetchOptions(search, loading) {
  getPortsByNameOrCode(search);
}

onMounted(() => {
 showVoyageContainer(containerId);
 getcommodities();
});
</script>
<template>
  <!-- Heading -->
  <form @submit.prevent="updateVoyageContainer(voyageContainer,containerId)">
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Update Voyage Container</h2>
  </div>
  <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Terminal</span>
      <input type="text" v-model="voyageContainer.terminal" placeholder="Terminal" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
    </label>
    
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Trade Status</span>
      <select v-model="voyageContainer.shipping_type" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        <option value="" selected disabled>--Choose--</option>
        <option value="IPC">IPC</option>
        <option value="MTC">MTC</option>
      </select>
    </label>
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Container</span>
      <input type="text" v-model="voyageContainer.container" required placeholder="Container" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
    </label>
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Principal Name</span>
      <input type="text" v-model="voyageContainer.principal" placeholder="Principal Name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
    </label>
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Coming From</span>
      <input type="text" v-model="voyageContainer.coming_from" placeholder="Coming From" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
    </label>
  </div>
  <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">SLOT Owner</span>
      <input type="text" v-model="voyageContainer.owner" placeholder="Slot Owner" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
    </label>
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">SLOT Owner 2L</span>
      <input type="text" v-model="voyageContainer.owner_2l" placeholder="Slot Owner" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
    </label>
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">SLOT Partner</span>
      <input type="text" v-model="voyageContainer.slot_partner" placeholder="Slot Partner" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
    </label>
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">MLO</span>
      <input type="text" v-model="voyageContainer.mlo" required placeholder="MLO" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
    </label>
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">MLO 2L</span>
      <input type="text" v-model="voyageContainer.mlo_2l" placeholder="MLO" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
    </label>
  </div>
  <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">MLO Agent</span>
      <input type="text" v-model="voyageContainer.local_agent" required placeholder="MLO" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
    </label>
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Force Load</span>
      <select v-model="voyageContainer.is_force_loaded" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        <option value="" selected disabled>--Choose--</option>
        <option value="1">YES</option>
        <option value="0">NO</option>
      </select>
    </label>
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Status</span>
      <select v-model="voyageContainer.status" required class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        <option value="" selected disabled>--Choose--</option>
        <option value="FCL">FCL</option>
        <option value="EMPTY">EMPTY</option>
      </select>
    </label>
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">ISO</span>
      <input type="text" v-model="voyageContainer.iso" required placeholder="ISO" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
    </label>
    <!-- <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">TEUS</span>
      <input type="number" step=".01" v-model="voyageContainer.tues" required placeholder="TEUS" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
    </label> -->
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Gross WT (KG)</span>
      <input type="number" step=".01" v-model="voyageContainer.gross_wt" required placeholder="Gross Wt" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
    </label>
    
  </div>
  <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">POR Code</span>
      <v-select v-model="voyageContainer.por_code" @search="fetchOptions" value="code" :options="ports" label="code_name" :reduce="ports => ports.code" placeholder="Enter Port Code or Name" class="mt-1 placeholder-gray-600 w-full"></v-select>
    </label>
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">CPOR Code</span>
      <v-select v-model="voyageContainer.cpor_code" @search="fetchOptions" value="code" :options="ports" label="code_name" :reduce="ports => ports.code" placeholder="Enter Port Code or Name" class="mt-1 placeholder-gray-600 w-full"></v-select>
    </label>
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">POL Code</span>
      <v-select v-model="voyageContainer.pol_code" @search="fetchOptions" value="code" :options="ports" label="code_name" :reduce="ports => ports.code" placeholder="Enter Port Code or Name" class="mt-1 placeholder-gray-600 w-full"></v-select>
    </label>
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">POD Code</span>
      <v-select v-model="voyageContainer.pod_code" @search="fetchOptions" value="code" :options="ports" label="code_name" :reduce="ports => ports.code" placeholder="Enter Port Code or Name" class="mt-1 placeholder-gray-600 w-full"></v-select>
    </label>
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">FPOD</span>
      <v-select v-model="voyageContainer.fpod_code" @search="fetchOptions" value="code" :options="ports" label="code_name" :reduce="ports => ports.code" placeholder="Enter Port Code or Name" class="mt-1 placeholder-gray-600 w-full"></v-select>
    </label>
    
    <!-- <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Category E T</span>
      <input type="text" v-model="voyageContainer.category_e_t" placeholder="Category E T" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
    </label> -->
  </div>
  <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
    <!-- <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Commodity</span>
      <v-select v-model="voyageContainer.commodity_id" value="id" :options="commodities" label="description" :reduce="commodities => commodities.id" placeholder="Commodity" class="mt-1 placeholder-gray-600 w-full"></v-select>
    </label> -->
    
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Stowage</span>
      <input type="text" v-model="voyageContainer.stow" placeholder="Stowage" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
    </label>
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">IMCO Class</span>
      <input type="text" v-model="voyageContainer.imco" placeholder="IMCO Class" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
    </label>
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">UN</span>
      <input type="text" v-model="voyageContainer.un" placeholder="UN" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
    </label>
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">DG Note</span>
      <input type="text" v-model="voyageContainer.dg_note" placeholder="DG Note" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
    </label>
  </div>
  <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Temp</span>
      <input type="text" v-model="voyageContainer.temp" placeholder="Temp" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
    </label>
    <!-- <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Temp Unit</span>
      <select v-model="voyageContainer.temp_unit" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        <option value="" selected disabled>--Choose--</option>
        <option value="CEL">CEL</option>
        <option value="FAH">FAH</option>
      </select>
    </label> -->
    <!-- <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">OOG Overweight</span>
      <input type="text" v-model="voyageContainer.oog_overweight" placeholder="OOG Overweight" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
    </label>
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">OOG Overwidth Left</span>
      <input type="text" v-model="voyageContainer.oog_overwidth_left" placeholder="OOG Overwidth Left" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
    </label>
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">OOG Overwidth Right</span>
      <input type="text" v-model="voyageContainer.oog_overwidth_right" placeholder="OOG Overwidth Right" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
    </label>
  </div>
  <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">OOG Overwidth Front</span>
      <input type="text" v-model="voyageContainer.oog_overwidth_front" placeholder="OOG Overwidth Front" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
    </label>
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">OOG Overwidth Back</span>
      <input type="text" v-model="voyageContainer.oog_overwidth_back" placeholder="OOG Overwidth Back" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
    </label> -->
    <!-- <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">OOG</span>
      <select v-model="voyageContainer.is_force_loaded" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        <option value="" selected disabled>--Choose--</option>
        <option value="1">YES</option>
        <option value="0">NO</option>
      </select>
    </label> -->
    
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Killed Teus</span>
      <input type="number" step=".01" v-model="voyageContainer.killed_tues" placeholder="Killed Teus" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
    </label>
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">OOG Note</span>
      <input type="text" v-model="voyageContainer.oog_note" placeholder="OOG Note" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
    </label>
    <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Remarks</span>
      <input type="text" v-model="voyageContainer.tdr_remarks" placeholder="Remarks" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
    </label>
    <!-- <label class="block w-full mt-3 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Seal</span>
      <input type="text" v-model="voyageContainer.seal" placeholder="Seal" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
    </label> -->
  </div>
  <!-- <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">VAT/NONVAT</span>
        <select v-model="voyageContainer.vat_non_vat" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          <option value="" selected disabled>--Choose--</option>
          <option value="VAT">VAT</option>
          <option value="NONVAT">NONVAT</option>
        </select>
    </label>
    <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Is Hold</span>
        <select v-model="voyageContainer.is_hold" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          <option value="" selected disabled>--Choose--</option>
          <option value="0">No</option>
          <option value="1">Yes</option>
        </select>
    </label>
    <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Hold Date</span>
        <input type="date" v-model="voyageContainer.hold_date" placeholder="Hold date" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
    </label>
    <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Release Date</span>
        <input type="date" v-model="voyageContainer.release_date" placeholder="Release date" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
    </label>
  </div> -->
  <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 mb-10 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update Container</button>
</form>
</template>

<style lang="postcss" scoped>
.input-group {
    @apply flex flex-col items-center justify-center w-full md:flex-row md:gap-2;
}
.label-group {
    @apply block w-full mt-3 text-sm;
}
.label-item-title {
    @apply text-gray-700 dark:text-gray-300;
}
.label-item-input {
    @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
}
.form-input {
    @apply block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
}
.vs__selected{
  display: none !important;
}
.required-style{
    @apply text-red-400 font-semibold
}
>>> {
    --vs-controls-color: #374151;
    --vs-border-color: #4b5563;

    --vs-dropdown-bg: #282c34;
    --vs-dropdown-color: #eeeeee;
    --vs-dropdown-option-color: #eeeeee;

    --vs-selected-bg: #664cc3;
    --vs-selected-color: #374151;

    --vs-search-input-color: #4b5563;

    --vs-dropdown-option--active-bg: #664cc3;
    --vs-dropdown-option--active-color: #eeeeee;
}
</style>