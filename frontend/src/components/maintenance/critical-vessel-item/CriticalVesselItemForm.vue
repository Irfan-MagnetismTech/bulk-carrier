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
          <span class="text-gray-700 dark:text-gray-300">Critical Function <span class="text-red-500">*</span></span>
          <v-select placeholder="Select Critical Function" :loading="isLoading"  :options="criticalFunctions" @search="" v-model="form.mnt_critical_function_name" label="function_name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            <template #search="{attributes, events}">
            <input
                class="vs__search"
                :required="!form.mnt_critical_function_name"
                v-bind="attributes"
                v-on="events"
            />
          </template>
          </v-select>
          <input type="hidden" v-model="form.mnt_critical_function_id">
        <Error v-if="errors?.mnt_critical_function_id" :errors="errors.mnt_critical_function_id" />
      </label>



        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Catrgory Name <span class="text-red-500">*</span></span>
            <v-select placeholder="Select Critical Item Category" :options="form.mntCriticalItemCategories" @search="" v-model="form.mnt_critical_item_cat_name" label="category_name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            <template #search="{attributes, events}">
            <input
                class="vs__search"
                :required="!form.mnt_critical_item_cat_name"
                v-bind="attributes"
                v-on="events"
            />
          </template>
          </v-select>
          <input type="hidden" v-model="form.mnt_critical_item_cat_id">
          <Error v-if="errors?.mnt_critical_item_cat_id" :errors="errors.mnt_critical_item_cat_id" />
        </label>
        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Item Name <span class="text-red-500">*</span></span>
            <v-select placeholder="Select Critical Item" :options="form.criticalItemCategoryWiseItem" @search="" v-model="form.mnt_critical_item_name" label="item_name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            <template #search="{attributes, events}">
            <input
                class="vs__search"
                :required="!form.mnt_critical_item_name"
                v-bind="attributes"
                v-on="events"
            />
          </template>
          </v-select>
          <input type="hidden" v-model="form.mnt_critical_item_id">
          <Error v-if="errors?.mnt_critical_item_id" :errors="errors.mnt_critical_item_id" />
        </label>

      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Specification</span>
        <input type="text" v-model="form.specification" placeholder="Specification" class="form-input"/>
        <Error v-if="errors?.specification" :errors="errors.specification" />
      </label>

      
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Notes</span>
        <input type="text" v-model="form.notes" placeholder="Notes" class="form-input"/>
        <Error v-if="errors?.notes" :errors="errors.notes" />
      </label>


    </div>
    
</template>
<script setup>
import Error from "../../Error.vue";
import Editor from '@tinymce/tinymce-vue';

import useShipDepartment from "../../../composables/maintenance/useShipDepartment";
import {onMounted, watch, watchEffect, ref} from "vue";
import BusinessUnitInput from "../../input/BusinessUnitInput.vue";
import useCriticalFunction from "../../../composables/maintenance/useCriticalFunction";
import useCriticalItemCategory from "../../../composables/maintenance/useCriticalItemCategory";
import useVessel from "../../../composables/operations/useVessel";
import useCriticalItem from "../../../composables/maintenance/useCriticalVesselItem";
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

watch(() => props.form.mnt_critical_function_name, (newValue, oldValue) => {
  props.form.mnt_critical_function_id = newValue?.id;

  if(oldValue !== ''){
    props.form.mnt_critical_item_cat_name = null;
    criticalFunctionWiseItemCategories.value = [];
  }
  if(props.form.mnt_critical_function_id)
    getCriticalFunctionWiseItemCategories(props.form.mnt_critical_function_id);
});

watch(() => props.form.mnt_critical_item_cat_name, (newValue, oldValue) => {
  if (oldValue !== '') {
    props.form.mnt_critical_item_cat_id = newValue?.id; 
    criticalItemCategoryWiseItem.value = [];
  }
  if(props.form.mnt_critical_item_cat_id)
  getCriticalItemCategoriesWiseItems(props.form.mnt_critical_item_cat_id);
});

const { vessels, getVesselsWithoutPaginate } = useVessel();
const { criticalFunctions, getCriticalFunctionsWithoutPagination } = useCriticalFunction();
const { criticalFunctionWiseItemCategories, getCriticalFunctionWiseItemCategories, isLoading } = useCriticalItemCategory();
const { criticalItemCategoryWiseItem, getCriticalItemCategoriesWiseItems } = useCriticalItem();

watch(() => criticalFunctionWiseItemCategories.value, (val) => {
  props.form.mntCriticalItemCategories = val;
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