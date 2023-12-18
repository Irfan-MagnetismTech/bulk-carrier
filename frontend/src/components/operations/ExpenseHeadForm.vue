<template>
  <!-- Basic information -->
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">

    <business-unit-input v-model="form.business_unit" :page="formType"></business-unit-input>

    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Group <span class="text-red-500">*</span></span>
      <input type="text" v-model="form.name" required placeholder="Expediture Group" class="block w-full form-input" />
    </label>
    <label class="block w-full mt-2 text-sm" title="This will make this group available in every expenditure and budget input.">
      <span class="text-gray-700  form-input">Voyage Report Visibility </span>
      <input type="checkbox" :checked="form.is_visible_in_voyage_report == 1" v-model="form.is_visible_in_voyage_report" class="ml-2 " />
    </label>
    <label class="block w-full mt-2 text-sm"></label>
  </div>
  <!-- South Sectors -->
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded ">
    <legend class="px-2 text-gray-700 ">Head <span class="text-red-500">*</span></legend>
    <table class="w-full whitespace-no-wrap" id="table">
      <thead>
      <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50  ">
        <th class="px-4 py-3 align-bottom">SL.</th>
        <th class="px-4 py-3 align-bottom">Name <span class="text-red-500">*</span></th>
        <th class="px-4 py-3 align-bottom">Usage Type <small>(PER)</small></th>
        <th class="px-4 py-3 text-center align-bottom">Action</th>
      </tr>
      </thead>

      <tbody class="bg-white divide-y ">
      <tr class="text-gray-700 " v-for="(subHead, index) in form.opsSubHeads" :key="subHead.id">
        <td class="px-1 py-1" width="10%">
          {{ index+1 }}
        </td>
        <td class="px-1 py-1">
          <input type="text" v-model="form.opsSubHeads[index].name" required :id="'sub_head_name' + index" placeholder="Sub Head" class="form-input" />
        </td>
        <td class="px-1 py-1">
          <input type="text" v-model="form.opsSubHeads[index].billing_type" :id="'cost_type' + index" placeholder="Usage Type" class="form-input" />
        </td>
        <td class="px-1 py-1 text-center">
          
          <button v-if="index==0" type="button" @click="addSubHead()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
          </button>
          <button v-else type="button" @click="removeSubHead(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
            </svg>
          </button>
        </td>
      </tr>
      </tbody>
    </table>
  </fieldset>

  <ErrorComponent :errors="errors"></ErrorComponent>
</template>
<script setup>
import Error from "../Error.vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import ErrorComponent from '../../components/utils/ErrorComponent.vue';

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
  formType: { type: String, required: false },

});

function addSubHead() {
  let obj = {
    head_id: '',
    head_id_name: '',
    name: '',
  };
  props.form.opsSubHeads.push(obj);
}

function removeSubHead(index){
    props.form.opsSubHeads.splice(index, 1);
}


</script>

<style lang="postcss" scoped>
#table, #table th, #table td{
  @apply border border-collapse border-gray-400 text-center text-gray-700 px-1
}

.input-group {
  @apply flex flex-col justify-center w-full h-full md:flex-row md:gap-2;
}

.label-group {
  @apply block w-full mt-3 text-sm;
}
.label-item-title {
  @apply text-gray-700;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded  focus:border-purple-400 focus:outline-none focus:shadow-outline-purple  disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed ;
}
.form-input {
  @apply block mt-1 text-sm rounded  focus:border-purple-400 focus:outline-none focus:shadow-outline-purple;
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