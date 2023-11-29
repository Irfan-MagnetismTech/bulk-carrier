<template>
  <div class="justify-center w-full grid grid-cols-1 md:grid-cols-3 md:gap-2">
      <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Vessel <span class="text-red-500">*</span></span>
            <v-select placeholder="Select Vessel" :options="vessels" @search="" v-model="form.ops_vessel_name" label="name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                <template #search="{attributes, events}">
                  <input
                      class="vs__search"
                      :required="!form.ops_vessel_name"
                      v-bind="attributes"
                      v-on="events"
                  />
                </template>
              </v-select>
              <input type="hidden" v-model="form.ops_vessel_id">
          <Error v-if="errors?.ops_vessel_id" :errors="errors.ops_vessel_id" />
        </label>
      
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Record Date <span class="text-red-500">*</span></span>
        <input type="date" v-model="form.record_date" placeholder="Record date" class="form-input" required/>
        <Error v-if="errors?.record_date" :errors="errors.record_date" />
      </label>
      
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Reference No <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.reference_no" placeholder="Reference No" class="form-input" required/>
        <Error v-if="errors?.reference_no" :errors="errors.reference_no" />
      </label>
    </div>
    <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400" >
      <legend class="px-2 text-gray-700 dark:text-gray-300">Spare Parts <span class="text-red-500">*</span></legend>
      <table class="w-full whitespace-no-wrap" id="table">
        <thead>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th class="w-6/12 px-4 py-3 align-bottom">Spare Parts Name<span class="text-red-500">*</span></th>
            <th class="w-2/12 px-4 py-3 align-bottom">Unit <span class="text-red-500">*</span></th>
            <th class="w-2/12 px-4 py-3 align-bottom">Minimum Rob <span class="text-red-500">*</span></th>
            <th class="w-2/12 px-4 py-3 align-bottom"> Rob <span class="text-red-500">*</span></th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr class="text-gray-700 dark:text-gray-400" v-for="(criticalVesselItem, index) in criticalVesselItems" :key="index">
            <td class="px-1 py-1"><input type="text" class="form-input" required readonly  :value="criticalVesselItem.item_name" placeholder="Spare Parts Name"  /></td>
            <td class="px-1 py-1"><input type="text" class="form-input" required readonly  :value="criticalVesselItem.item_name" placeholder="Spare Parts Name"  /></td>
            <!-- /************ntc*************** */ -->
          </tr>
        </tbody>
      </table>
    </fieldset>
    
</template>
<script setup>
import Error from "../../Error.vue";
import Editor from '@tinymce/tinymce-vue';

import useShipDepartment from "../../../composables/maintenance/useShipDepartment";
import {onMounted, watch, watchEffect, ref} from "vue";
import BusinessUnitInput from "../../input/BusinessUnitInput.vue";
import useCriticalFunction from "../../../composables/maintenance/useCriticalFunction";
import useCriticalItemCategory from "../../../composables/maintenance/useCriticalItemCategory";
import useCriticalItem from "../../../composables/maintenance/useCriticalItem";
import useCriticalVesselItem from "../../../composables/maintenance/useCriticalVesselItem";
import useVessel from "../../../composables/operations/useVessel";
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  page: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});

const { vessels, getVesselsWithoutPaginate } = useVessel();
const { criticalFunctions, getCriticalFunctionsWithoutPagination } = useCriticalFunction();
const { criticalFunctionWiseItemCategories, getCriticalFunctionWiseItemCategories, isLoading } = useCriticalItemCategory();
const { criticalItemCategoryWiseItems, getCriticalItemCategoryWiseItems } = useCriticalItem();
const { criticalVesselItems, getVesselWiseCriticalVesselItems } = useCriticalVesselItem();

watch(() => props.form.ops_vessel_name, (newValue, oldValue) => {
  props.form.ops_vessel_id = newValue?.id;
  if( props.form.ops_vessel_id)
  getVesselWiseCriticalVesselItems( props.form.ops_vessel_id); /****** */
});


watch(() => props.form.business_unit, (newValue, oldValue) => {
  businessUnit.value = newValue;
});





onMounted(() => {
  getCriticalFunctionsWithoutPagination();
  watchEffect(() => {
      if(businessUnit.value && businessUnit.value != 'ALL'){
        getVesselsWithoutPaginate(businessUnit.value);
      }
  });
});

</script>

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