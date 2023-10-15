<script setup>
import Error from "../Error.vue";

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});

function addItem() {
  let obj = {
    crw_rank_id: '',
    required_manpower: '',
    remarks: '',
  };
  props.form.crwCrewRequisitionLines.push(obj);
}

function removeItem(index){
  props.form.crwCrewRequisitionLines.splice(index, 1);
}

</script>

<template>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Vessel Name <span class="text-red-500">*</span></span>
        <select class="form-input" v-model="form.ops_vessel_id" required>
          <option value="" disabled>select</option>
          <option value="Master">Bashundhara Empress</option>
          <option value="Sukani">Rania - 06</option>
        </select>
        <Error v-if="errors?.effective_date" :errors="errors.effective_date" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Applied Date <span class="text-red-500">*</span></span>
        <input type="date" v-model="form.applied_date" class="form-input" autocomplete="off" required />
        <Error v-if="errors?.applied_date" :errors="errors.applied_date" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Total Crew <span class="text-red-500">*</span></span>
        <input type="number" v-model="form.total_required_manpower" placeholder="Ex: 14" class="form-input" autocomplete="off" />
        <Error v-if="errors?.total_required_manpower" :errors="errors.remarks" />
      </label>
    </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Remarks</span>
      <input type="text" v-model="form.remarks" placeholder="Remarks" class="form-input" autocomplete="off" />
      <Error v-if="errors?.remarks" :errors="errors.remarks" />
    </label>
  </div>
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300">Item List <span class="text-red-500">*</span></legend>
    <table class="w-full whitespace-no-wrap" id="table">
      <thead>
      <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th class="px-4 py-3 align-bottom">Rank <span class="text-red-500">*</span></th>
        <th class="px-4 py-3 align-bottom">Required Manpower <span class="text-red-500">*</span></th>
        <th class="px-4 py-3 align-bottom">Remarks</th>
        <th class="px-4 py-3 text-center align-bottom">Action</th>
      </tr>
      </thead>

      <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
      <tr class="text-gray-700 dark:text-gray-400" v-for="(requiredCrewLine, index) in form.crwCrewRequisitionLines" :key="requiredCrewLine.id">
        <td class="px-1 py-1">
          <select class="form-input" v-model="form.crwCrewRequisitionLines[index].crw_rank_id" required>
            <option value="" disabled>select</option>
            <option value="Master">Master</option>
            <option value="Sukani">Sukani</option>
          </select>
          <!--          <v-select v-model="form.crwVesselRequiredCrewLines[index].crw_rank_id" :id="'pod' + index" name="pod" required value="id" :options="portName" label="code_name" placeholder="Enter Port Code or Name" class="mt-1 placeholder-gray-600 w-full">-->
<!--            <template #search="{attributes, events}">-->
<!--              <input class="vs__search" :required="!form.crwVesselRequiredCrewLines[index].crw_rank_id" v-bind="attributes" v-on="events"/>-->
<!--            </template>-->
<!--          </v-select>-->
        </td>
        <td class="px-1 py-1">
          <input type="text" v-model="form.crwCrewRequisitionLines[index].required_manpower" placeholder="Ex: 2" class="form-input" autocomplete="off" />
        </td>
        <td class="px-1 py-1">
          <input type="text" v-model="form.crwCrewRequisitionLines[index].remarks" placeholder="Remarks" class="form-input" autocomplete="off" />
        </td>
        <td class="px-1 py-1 text-center">
          <button v-if="index!==0" type="button" @click="removeItem(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
            </svg>
          </button>
          <button v-else type="button" @click="addItem()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
          </button>
        </td>
      </tr>
      </tbody>
    </table>
  </fieldset>
</template>
<style lang="postcss" scoped>
#table, #table th, #table td{
  @apply border border-collapse border-gray-400 text-center text-gray-700 px-1
}

.input-group {
  @apply flex flex-col justify-center w-full h-full md:flex-row md:gap-2;
}

.label-group {
  @apply block w-full mt-2 text-sm;
}
.label-item-title {
  @apply text-gray-700 dark:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
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