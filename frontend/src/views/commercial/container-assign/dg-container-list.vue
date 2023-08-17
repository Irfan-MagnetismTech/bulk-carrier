<script setup>
import { onMounted } from '@vue/runtime-core';
import useDgGroupAssign from "../../../composables/commercial/useDgGroupAssign";
import useVoyage from "../../../composables/operation/useVoyage";
import Title from "../../../services/title";
import {watch} from "vue";

const { voyages, voyage:voyageDetail, showVoyage, voyageName, getVoyageName } = useVoyage();
const { voyage, formParams, dgContainerParams, isLoading, getDgContainerList, assignDgContainerGroup } = useDgGroupAssign();

const groupOptions = ['group-1', 'group-2', 'group-3', 'common'];

function fetchOptions(search, loading) {
  getVoyageName(search);
}

function assignDgContainer(dgContainerParams) {

  let data = {
    dg_containers: dgContainerParams,
    voyage_id: formParams.value.voyage_id,
  };
  assignDgContainerGroup(data);
}

const { setTitle } = Title();

setTitle('DG Assign');

watch(voyage,function (val) {
  //set dgcontainerParams null array
  showVoyage(formParams.value.voyage_id);

  dgContainerParams.value.length = 0;
  val?.map(function(container, containerIndex) {
      let dataObj = {
        dg_group: container?.group ?? '',
        container_id: container?.id,
        container: container?.container,
        iso: container?.iso,
        stow: container?.stow,
        imco: container?.imco,
        un: container?.un,
        pol_code: container?.pol_code,
        pod_code: container?.pod_code,
        customer_code: container?.customer?.customer_code,
        slot_partner: container?.slot_partner,
        mlo: container?.mlo,
      };
      dgContainerParams.value.push(dataObj);
  });
});

onMounted(() => {
  //getVoyageName();
});
</script>

<template>
  <!-- Heading -->
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">DG Container List</h2>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
    <form class="flex items-end justify-between gap-4" @submit.prevent="getDgContainerList(formParams)">
      <label class="block w-full text-sm">
        <span class="text-gray-700 dark:text-gray-300">Voyage <span class="text-red-500">*</span></span>
        <v-select :options="voyageName" placeholder="--Choose an option--" @search="fetchOptions" v-model="formParams.voyage_id" label="voyage" :reduce="voyageName => voyageName.id" required></v-select>
      </label>

      <!-- Submit button -->
      <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Search</button>
    </form>
  </div>
  <div>
    <form class="flex items-end justify-between gap-4" @submit.prevent="assignDgContainer(dgContainerParams)">
    <div class="w-full overflow-hidden">
      <div class="w-full overflow-x-auto">
        <table class="w-full mb-8 whitespace-no-wrap">
          <thead v-once>
            <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
              <th class="p-1">SL</th>
              <th class="p-1">Cont Ref</th>
              <th class="p-1">ISO</th>
              <th class="p-1">Stoage</th>
              <th class="p-1">IMCO</th>
              <th class="p-1">UN</th>
              <th class="p-1">POL</th>
              <th class="p-1">POD</th>
              <th class="p-1">S/O</th>
              <th class="p-1">MLO</th>
              <th class="p-1">Assign Group</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr class="text-center text-gray-700 dark:text-gray-400" v-for="(container, index) in dgContainerParams" :key="index">
            <td class="p-1">{{ index + 1 }}</td>
            <td class="p-1 text-left">{{ container?.container }} ({{ voyageDetail?.invoices?.length }})</td>
            <td class="p-1 text-center">{{ container?.iso }}</td>
            <td class="p-1 text-center">{{ container?.stow }}</td>
            <td class="p-1 text-center">{{ container?.imco }}</td>
            <td class="p-1 text-center">{{ container?.un }}</td>
            <td class="p-1 text-center">{{ container?.pol_code }}</td>
            <td class="p-1 text-center">{{ container?.pod_code }}</td>
            <td class="p-1 text-center"> {{ container?.slot_partner }} </td>
            <td class="p-1 text-center">{{ container?.mlo }}</td>
            <td class="p-1 text-center">
              <select :class="{'custom_text_opacity' : voyageDetail?.invoices?.length }" v-model="dgContainerParams[index].dg_group" class="mt-1 text-xs capitalize rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" required>
                <option v-for="(group,index) in groupOptions" v-bind:key="index" :selected= "container.group === group" :value="group">{{ group }}</option>
              </select>
            </td>
          </tr>
          </tbody>
          <tfoot v-if="!voyage?.length" class="bg-white dark:bg-gray-800">
          <tr v-if="isLoading">
            <td colspan="11">Loading...</td>
          </tr>
          <tr v-else-if="!voyage?.length">
            <td colspan="11">No container list found.</td>
          </tr>
          </tfoot>
        </table>
      </div>
      <button type="submit" v-if="dgContainerParams?.length" :disabled="isLoading" :class="{'custom_text_opacity' : voyageDetail?.invoices?.length }" class="w-full px-4 py-2 mb-5 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Submit</button>
    </div>
    </form>
  </div>
</template>
<style lang="postcss" scoped>
@tailwind components;

@layer components {
  .tab {
    @apply p-2.5 text-xs;
  }
  thead tr {
    @apply font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800;
  }
  th {
    @apply tab text-center;
  }
  tbody {
    @apply bg-white divide-y dark:divide-gray-700 dark:bg-gray-800;
  }
  tbody tr {
    @apply text-gray-700 dark:text-gray-400;
  }
  tbody tr td {
    @apply tab text-center;
  }
  tfoot td {
    @apply tab text-center;
  }
}

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
table, th,td{
  @apply border border-collapse;
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