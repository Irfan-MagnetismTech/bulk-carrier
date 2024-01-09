<template>
  <div class="justify-center w-full grid grid-cols-1 md:grid-cols-3 md:gap-2">
      <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Vessel <span class="text-red-500">*</span></span>
            <v-select placeholder="Select Vessel" :options="vessels" :loading="isVesselLoading" @search="" v-model="form.ops_vessel" label="name" @update:modelValue="vesselChange" class="block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray form-input">
                <template #search="{attributes, events}">
                  <input
                      class="vs__search"
                      :required="!form.ops_vessel"
                      v-bind="attributes"
                      v-on="events"
                  />
                </template>
              </v-select>
              <input type="hidden" v-model="form.ops_vessel_id">
          <Error v-if="errors?.ops_vessel_id" :errors="errors.ops_vessel_id" />
        </label>
      
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Record Date <span class="text-red-500">*</span></span>
        <!-- <input type="date" v-model="form.record_date" placeholder="Record date" class="form-input" required/> -->
        <VueDatePicker v-model="form.record_date" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>
        <Error v-if="errors?.record_date" :errors="errors.record_date" />
      </label>
      
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Reference No <span class="text-red-500">*</span></span>
        <input type="text" v-model.trim="form.reference_no" placeholder="Reference No" class="form-input" required/>
        <Error v-if="errors?.reference_no" :errors="errors.reference_no" />
      </label>
    </div>
    
    <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400" >
      <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Critical Spare Parts <span class="text-red-500">*</span></legend>
      <table class="w-full whitespace-no-wrap" id="table" v-if="form.mntCriticalSpListLines?.length">
        <thead>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
            <th class="w-4/12 px-4 py-3 align-bottom">Spare Parts Name</th>
            <th class="w-2/12 px-4 py-3 align-bottom">Unit</th>
            <th class="w-2/12 px-4 py-3 align-bottom">Minimum ROB</th>
            <th class="w-2/12 px-4 py-3 align-bottom"> ROB <span class="text-red-500">*</span></th>
            <th class="w-2/12 px-4 py-3 align-bottom"> Remarks </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
          
          <template  v-for="(criticalVesselItem, index) in form.mntCriticalSpListLines" :key="index">
              <tr class="text-gray-700 dark-disabled:text-gray-400" v-if="criticalVesselItem?.mntCriticalItemSps?.length">
                <td colspan="4" ><strong class="text-left block "><span class="text-base">{{ criticalVesselItem?.mntCriticalItem?.item_name }}</span> <span class="pl-1" v-show="criticalVesselItem?.specification">({{ criticalVesselItem?.specification }})</span></strong> </td>
              </tr>
              <tr class="text-gray-700 dark-disabled:text-gray-400"  v-for="(mntCriticalItemSp, mntCriticalItemSpIndex) in criticalVesselItem?.mntCriticalItemSps" :key="mntCriticalItemSpIndex">
                <td>{{ mntCriticalItemSp?.sp_name }}</td>
                <td>{{ mntCriticalItemSp?.unit }}</td>
                <td>{{ mntCriticalItemSp?.min_rob }}</td>
                <td><input type="number" v-model="mntCriticalItemSp.rob" placeholder="ROB" class="form-input" required/></td>
                <td><input type="text" v-model.trim="mntCriticalItemSp.remarks" placeholder="Remarks" class="form-input"/></td>
                
              </tr>
          </template>
        </tbody>
      </table>
      <div v-else class="py-10  text-center rounded-md">
          <p class="text-md font-bold">{{ form.ops_vessel_id ? 'No Critical Spare Part Found' : 'Please Select Vessel' }}</p>
        </div>
      <div>

      </div>
    </fieldset>
    <LoaderComponent :isLoading = isCriticalVesselItemLoading ></LoaderComponent>
    <ErrorComponent :errors="errors"></ErrorComponent>
    
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
import LoaderComponent from "../../utils/LoaderComponent.vue";
import ErrorComponent from "../../utils/ErrorComponent.vue";
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
const dateFormat = ref(Store.getters.getVueDatePickerTextInputFormat.date)

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

const { vessels, getVesselsWithoutPaginate, isLoading: isVesselLoading } = useVessel();
const { criticalFunctions, getCriticalFunctionsWithoutPagination } = useCriticalFunction();
const { criticalFunctionWiseItemCategories, getCriticalFunctionWiseItemCategories, isLoading } = useCriticalItemCategory();
const { criticalItemCategoryWiseItems, getCriticalItemCategoryWiseItems } = useCriticalItem();
const { criticalVesselItems, getVesselWiseCriticalVesselItems, isLoading: isCriticalVesselItemLoading } = useCriticalVesselItem();

// watch(() => props.form.ops_vessel, (newValue, oldValue) => {
//   props.form.ops_vessel_id = newValue?.id;
//   if( props.form.ops_vessel_id)
//   getVesselWiseCriticalVesselItems( props.form.ops_vessel_id); /****** */
// });

function vesselChange() {
  props.form.ops_vessel_id = props.form.ops_vessel?.id;
  criticalVesselItems.value = [];

  if (props.form.ops_vessel_id)
    getVesselWiseCriticalVesselItems(props.form.ops_vessel_id);
}


watch(() => props.form.business_unit, (newValue, oldValue) => {
  businessUnit.value = newValue;

  if (props.page != 'edit') {
    props.form.ops_vessel = null;
    vesselChange();
  }
});
watch(() => criticalVesselItems.value, (value) => {
  props.form.mntCriticalSpListLines = value;
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
  @apply text-gray-700 dark-disabled:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark-disabled:disabled:bg-gray-900;
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
  
  --dp-border-color: #4b5563;
  --dp-border-color-hover: #4b5563;
  --dp-icon-color: #4b5563;
}
</style>