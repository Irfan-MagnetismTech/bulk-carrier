<template>
  <div class="justify-center w-full grid grid-cols-1 md:grid-cols-3 md:gap-2">
      <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Vessel <span class="text-red-500">*</span></span>
            <v-select placeholder="--Choose an option--" :loading="isVesselLoading"  :options="vessels" @search="" v-model="form.ops_vessel" label="name" @update:modelValue="vesselChange"  class="block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray form-input">
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
          <!-- <Error v-if="errors?.ops_vessel_id" :errors="errors.ops_vessel_id" /> -->
        </label>
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Critical Function <span class="text-red-500">*</span></span>
          <v-select placeholder="--Choose an option--" :loading="isCriticalFunctionLoading"  :options="criticalFunctions" @search="" v-model="form.mnt_critical_function" label="function_name" @update:modelValue="criticalFunctionChange"  class="block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray form-input">
            <template #search="{attributes, events}">
            <input
                class="vs__search"
                :required="!form.mnt_critical_function"
                v-bind="attributes"
                v-on="events"
            />
          </template>
          </v-select>
          <input type="hidden" v-model="form.mnt_critical_function_id">
        <!-- <Error v-if="errors?.mnt_critical_function_id" :errors="errors.mnt_critical_function_id" /> -->
      </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Category <span class="text-red-500">*</span></span>
            <v-select placeholder="--Choose an option--" :loading="isCriticalItemCategoryLoading"  :options="form.mntCriticalItemCategories" @search="" v-model="form.mnt_critical_item_cat" label="category_name" @update:modelValue="criticalItemCategoryChange"  class="block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray form-input">
            <template #search="{attributes, events}">
            <input
                class="vs__search"
                :required="!form.mnt_critical_item_cat"
                v-bind="attributes"
                v-on="events"
            />
          </template>
          </v-select>
          <input type="hidden" v-model="form.mnt_critical_item_cat_id">
          <!-- <Error v-if="errors?.mnt_critical_item_cat_id" :errors="errors.mnt_critical_item_cat_id" /> -->
        </label>
        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Item <span class="text-red-500">*</span></span>
            <v-select placeholder="--Choose an option--" :loading="isCriticalItemLoading"  :options="form.mntCriticalItems" @search="" v-model="form.mnt_critical_item" label="item_name" @update:modelValue="criticalItemChange"  class="block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray form-input">
            <template #search="{attributes, events}">
            <input
                class="vs__search"
                :required="!form.mnt_critical_item"
                v-bind="attributes"
                v-on="events"
            />
          </template>
          </v-select>
          <input type="hidden" v-model="form.mnt_critical_item_id">
          <!-- <Error v-if="errors?.mnt_critical_item_id" :errors="errors.mnt_critical_item_id" /> -->
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Specification</span>
          <input type="text" v-model.trim="form.specification" placeholder="Specification" class="form-input"/>
          <!-- <Error v-if="errors?.specification" :errors="errors.specification" /> -->
        </label>

      <!-- <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Notes</span>
        <input type="text" v-model="form.notes" placeholder="Notes" class="form-input"/>
        <Error v-if="errors?.notes" :errors="errors.notes" />
      </label> -->
      <RemarksComponent  v-model.trim="form.notes" :maxlength="500" :fieldLabel="'Notes'"></RemarksComponent>
      
      <div class="block w-full mt-2 text-sm col-start-1">
        <!-- <span class="text-gray-700 dark-disabled:text-gray-300">Notes</span> -->
        <input type="checkbox" v-model="form.is_critical" @change="isCritical" /> Critical Item
        <!-- <Error v-if="errors?.notes" :errors="errors.notes" /> -->
      </div>

    </div>
    <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400" v-if="form.is_critical && form.mntCriticalItemSps?.length">
      <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Spare Parts <span class="text-red-500">*</span></legend>
      <table class="w-full whitespace-no-wrap" id="table">
        <thead>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
            <th class="w-6/12 px-4 py-3 align-bottom">Spare Parts Name<span class="text-red-500">*</span></th>
            <th class="w-2/12 px-4 py-3 align-bottom">Minimum ROB <span class="text-red-500">*</span></th>
            <th class="w-2/12 px-4 py-3 align-bottom">Unit <span class="text-red-500">*</span></th>
            <th class="w-2/12 px-4 py-3 align-bottom text-center">Action</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
          <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(mntCriticalItemSp, index) in form.mntCriticalItemSps" :key="index">
            <td class="px-1 py-1"><input type="text" class="form-input" required  v-model.trim="mntCriticalItemSp.sp_name" placeholder="Spare Parts Name"  /></td>
            <td class="px-1 py-1"><input type="number" min="1" class="form-input" required v-model="mntCriticalItemSp.min_rob" placeholder="Minimum ROB" />
            </td>
            <td class="px-1 py-1"><input type="text" class="form-input" required  v-model.trim="mntCriticalItemSp.unit" placeholder="Unit" /></td>
            <td class="px-1 py-1"><button type="button" class="bg-green-600 text-white px-3 py-2 rounded-md" v-show="index == 0" @click="addCriticalItemSp"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg></button> <button type="button" class="bg-red-600 text-white px-3 py-2 rounded-md" v-show="index != 0" @click="removeCriticalItemSp(index)" ><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                  </svg></button></td>
          </tr>
        </tbody>
      </table>
    </fieldset>
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
import useVessel from "../../../composables/operations/useVessel";
import RemarksComponent from "../../utils/RemarksComponent.vue";
import ErrorComponent from "../../utils/ErrorComponent.vue";
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

const { vessels, getVesselsWithoutPaginate, isLoading:isVesselLoading } = useVessel();
const { criticalFunctions, getCriticalFunctionsWithoutPagination, isLoading:isCriticalFunctionLoading } = useCriticalFunction();
const { criticalFunctionWiseItemCategories, getCriticalFunctionWiseItemCategories, isLoading:isCriticalItemCategoryLoading } = useCriticalItemCategory();
const { criticalItemCategoryWiseItems, getCriticalItemCategoryWiseItems, isLoading:isCriticalItemLoading } = useCriticalItem();

// watch(() => props.form.ops_vessel, (newValue, oldValue) => {
//   props.form.ops_vessel_id = newValue?.id;
// });
function vesselChange() {
  props.form.ops_vessel_id = props.form.ops_vessel?.id;
} 

// watch(() => props.form.mnt_critical_function, (newValue, oldValue) => {
//   props.form.mnt_critical_function_id = newValue?.id;

//   if(oldValue !== ''){
//     props.form.mnt_critical_item_cat_name = null;
//     criticalFunctionWiseItemCategories.value = [];
//   }
//   if(props.form.mnt_critical_function_id)
//     getCriticalFunctionWiseItemCategories(props.form.mnt_critical_function_id);
// });

function criticalFunctionChange() {
  props.form.mnt_critical_function_id = props.form.mnt_critical_function?.id;

  props.form.mnt_critical_item_cat = null;
  props.form.mnt_critical_item_cat_id = null;
  criticalItemCategoryChange();

  criticalFunctionWiseItemCategories.value = [];

  if(props.form.mnt_critical_function_id)
    getCriticalFunctionWiseItemCategories(props.form.mnt_critical_function_id);
}

// watch(() => props.form.mnt_critical_item_cat, (newValue, oldValue) => {
//   props.form.mnt_critical_item_cat_id = newValue?.id; 

//   if(oldValue !== ''){
//     props.form.mnt_critical_item_name = null;
//     criticalItemCategoryWiseItems.value = [];
//   }
//   if(props.form.mnt_critical_item_cat_id)
//     getCriticalItemCategoryWiseItems(props.form.mnt_critical_item_cat_id);
// });

function criticalItemCategoryChange() {
  props.form.mnt_critical_item_cat_id = props.form.mnt_critical_item_cat?.id; 

  props.form.mnt_critical_item = null;
  props.form.mnt_critical_item_id = null;
  criticalItemCategoryWiseItems.value = [];
  criticalItemChange();

  if(props.form.mnt_critical_item_cat_id)
    getCriticalItemCategoryWiseItems(props.form.mnt_critical_item_cat_id);
}

// watch(() => props.form.mnt_critical_item_name, (value) => {
//   props.form.mnt_critical_item_id = value?.id; 
// });

function criticalItemChange() {
  props.form.mnt_critical_item_id = props.form.mnt_critical_item?.id;
}


watch(() => criticalFunctionWiseItemCategories.value, (val) => {
  props.form.mntCriticalItemCategories = val;
});
watch(() => criticalItemCategoryWiseItems.value, (val) => {
  props.form.mntCriticalItems = val;
});
watch(() => props.form.business_unit, (newValue, oldValue) => {
  businessUnit.value = newValue;

  if (props.page != 'edit') {
    props.form.ops_vessel = null;
    vesselChange();
  }
});

// watch(() => props.form.is_critical, (newValue, oldValue) => {
//   if (newValue != oldValue && oldValue != null) {
//     props.form.mntCriticalItemSps = newValue == true  ? [{ sp_name: '', unit: '', min_rob: 0 }] : [];
//   }
// });

function isCritical() {
  // if (props.form.mntCriticalItemSps.length == 0)
  //   addCriticalItemSp();
  // console.log(props.form.is_critical);
  props.form.mntCriticalItemSps = props.form.is_critical ? [{ sp_name: '', unit: '', min_rob: 0 }] : [];
}

function addCriticalItemSp() {
  props.form.mntCriticalItemSps.push({sp_name: '', unit: '', min_rob: 0});
}
function removeCriticalItemSp(index) {
  props.form.mntCriticalItemSps.splice(index, 1);
}


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
}
</style>