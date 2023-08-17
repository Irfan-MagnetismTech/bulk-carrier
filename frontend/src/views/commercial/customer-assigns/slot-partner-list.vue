<script setup>
import { onMounted } from '@vue/runtime-core';
import useCustomerAssign from "../../../composables/commercial/useCustomerAssign";
import useVoyage from "../../../composables/operation/useVoyage";
import Title from "../../../services/title";
import {watch} from "vue";
import useCustomer from "../../../composables/commercial/useCustomer";
import PermissionBlockedModal from "../../../components/utils/PermissionBlockedModal.vue";

const { voyages, voyage:voyageDetail, showVoyage, voyageName, getVoyageName } = useVoyage();
const { customers, getCustomerWithoutPaginate } = useCustomer();
const { voyage, permissionBlockedModal, formParams, slotPartnerListParams, isLoading, getSlotPartnerList, assignSlotPartner } = useCustomerAssign();

const groupOptions = ['group-1', 'group-2', 'group-3', 'common'];

const message = 'Sorry! We cann\'t process the request. Invoice already generated for this voyage.';

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

setTitle('Customer Assign');

function toggleStatus() {
  permissionBlockedModal.value = false;
}

watch(voyage,function (voyageData) {

    showVoyage(formParams.value.voyage_id);

    voyage?.value?.map(function (voyageData,voyageIndex){
    voyageData?.customers.map(function (voyageCustomer,voyageCustomerIndex){
      if(voyage?.value[voyageIndex].customer_id == voyageCustomer?.id){
        //insert customer code
        voyage.value[voyageIndex].customer_code = voyageCustomer?.customer_code;
        voyage.value[voyageIndex].company_name = voyageCustomer?.company_name;
      }
    });
  });
}, {deep: true});

onMounted(() => {
  getCustomerWithoutPaginate();
});
</script>

<template>
  <!-- Heading -->
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Assign Customers </h2>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
    <form class="flex items-end justify-between gap-4" @submit.prevent="getSlotPartnerList(formParams)">
      <label class="block w-full text-sm">
        <span class="text-gray-700 dark:text-gray-300">Voyage <span class="text-red-500">*</span></span>
        <v-select :options="voyageName" placeholder="--Choose an option--" @search="fetchOptions" v-model="formParams.voyage_id" label="voyage" :reduce="voyageName => voyageName.id" required></v-select>
      </label>

      <!-- Submit button -->
      <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Search</button>
    </form>
  </div>
  <div>
    <form class="flex items-end justify-between gap-4" @submit.prevent="assignSlotPartner(voyage,voyageDetail)">
      <div class="w-full overflow-hidden">
        <div class="w-full overflow-x-auto">
          <table class="w-full mb-8 whitespace-no-wrap">
            <thead v-once>
            <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
              <th class="p-1"> Slot Partner</th>
              <th class="p-1"> Service</th>
              <th class="p-1"> Sectors </th>
              <th class="p-1"> Total Containers </th>
              <th class="p-1"> Assign Customer <span class="text-red-500">*</span></th>
              <th class="p-1"> Customer Name</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                  <tr class="text-center text-gray-700 dark:text-gray-400" v-for="(slotPartnerList, index) in voyage" :key="index">
                    <td>{{ slotPartnerList?.slot_partner }} <br>
                      <span v-if="slotPartnerList?.slot_partner_name" class="px-2 font-semibold leading-tight text-purple-700 bg-purple-100 rounded-full dark:text-white dark:bg-orange-600">
                          {{ slotPartnerList?.slot_partner_name }}
                        </span></td>
                    <td>{{ slotPartnerList?.service }}</td>
                    <td>{{ slotPartnerList?.pol_pod }}</td>
                    <td>{{ slotPartnerList?.total_containers }}</td>
                    <td>
                      <v-select :options="slotPartnerList?.customers" placeholder="--Choose an option--" :class="{'custom_text_opacity' : voyageDetail?.invoices?.length }" v-model="voyage[index].customer_id" :reduce="customers => customers.id" class="mt-1" label="customer_code">
                        <template #search="{attributes, events}">
                          <input class="vs__search" :required="!voyage[index]?.customer_id" v-bind="attributes" v-on="events"/>
                        </template>
                      </v-select>
                    </td>
                    <td>
                      <input type="text" v-model="voyage[index].company_name" readonly class="block vms-readonly-input w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                    </td>
                  </tr>
            </tbody>
            <tfoot v-if="!voyage?.length" class="bg-white dark:bg-gray-800">
            <tr v-if="isLoading">
              <td colspan="6">Loading...</td>
            </tr>
            <tr v-else-if="!voyage?.length">
              <td colspan="6">No container list found.</td>
            </tr>
            </tfoot>
          </table>
        </div>
        <button type="submit" v-if="voyage?.length" :disabled="isLoading" class="w-full px-4 py-2 mb-5 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Submit</button>
      </div>
    </form>
  </div>

  <permission-blocked-modal :message="message" v-if="permissionBlockedModal" @close-permission-blocked-modal="toggleStatus"></permission-blocked-modal>
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

  table, th,td{
    @apply border-gray-400;
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

.slot-partner-table-border-none{
  border-bottom: 1px solid white;
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