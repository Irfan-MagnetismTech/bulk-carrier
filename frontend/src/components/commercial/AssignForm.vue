<template>
  <!-- Cosignee information -->
  <fieldset class="px-4 pb-4 mt-3 border rounded bord2er-gray-700 dark:border-gray-400">
    <legend class="text-gray-700 dark:text-gray-300">Voyage Information</legend>
    <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Voyage No</span>
        <input type="text" disabled :value="voyage.voyage" class="block disabled:bg-gray-200 disabled:cursor-not-allowed w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
      </label>

      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Sailing Date</span>
        <input type="text" disabled readonly :value="voyage.sailing_date" class="block disabled:bg-gray-200 disabled:cursor-not-allowed w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
      </label>

      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Arrival Date</span>
        <input type="text" disabled readonly :value="voyage.arrival_date" class="block disabled:bg-gray-200 disabled:cursor-not-allowed w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
      </label>
    </div>
    <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Vessel</span>
        <input type="text" disabled readonly :value="voyage.vessel.name" class="block disabled:bg-gray-200 disabled:cursor-not-allowed w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
      </label>

      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Departure Date</span>
        <input type="text" disabled readonly :value="voyage.departure_date" class="block disabled:bg-gray-200 disabled:cursor-not-allowed w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
      </label>

      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Berthing Date</span>
        <input type="text" disabled readonly :value="voyage.berthing_date" class="block disabled:bg-gray-200 disabled:cursor-not-allowed w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
      </label>
    </div>
  </fieldset>

  <!-- MLO List -->
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300">Customer List</legend>
    <table class="w-full whitespace-no-wrap" id="table">
      <thead v-once>
        <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
          <th class="px-4 py-3 align-bottom">Customer Code</th>
          <th class="px-4 py-3 align-bottom">Customer Name</th>
          <th class="px-4 py-3 align-bottom">Contract</th>
          <th class="px-4 py-3 text-center align-bottom">Contract Date</th>
        </tr>
      </thead>

      <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
        <tr class="text-center text-gray-700 dark:text-gray-400" v-for="(mlo, key) in form">
          <td class="px-4 py-3 text-sm">
            <input type="text" readonly :value="mlo.mlo" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
            <input type="hidden" v-model="form[key].voy_cus_id" />
          </td>
          <td class="px-4 py-3 text-sm">
            <input type="text" readonly :value="mlo.customer_name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
            <input type="hidden" v-model="form[key].customer_id" />
          </td>
          <td class="px-4 py-3 text-sm">
            <select v-model="form[key].contract_id" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
              <option value="">Choose Option</option>
              <option v-for="(active_contract,index) in mlo.customer_active_contracts" :value="active_contract.id" :key="index">{{ mlo.mlo }} - ({{ active_contract.id }})</option>
            </select>
          </td>
          <td class="px-4 py-3 text-sm">
            <input type="text" :value="form[key].customer_active_contracts.find((el) => el.id === form[key].contract_id)?.effective_date" disabled class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:bg-gray-200 disabled:cursor-not-allowed form-input" />
          </td>
        </tr>
        <tr v-if="form.length === 0" class="text-center text-gray-700 dark:text-gray-400">
          <td colspan="4" class="px-4 py-3 text-sm">
            <span>No Record Found</span>
          </td>
        </tr>
      </tbody>
    </table>
  </fieldset>
</template>
<script setup>

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  voyage: {
    required: false,
    default: {}
  }
});

</script>

<style lang="postcss" scoped>
#table,
#table th,
#table td {
  @apply border border-collapse border-gray-400 text-center text-gray-700 px-1;
}
</style>