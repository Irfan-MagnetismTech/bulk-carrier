<template>
    <business-unit-input v-if="form.form_type === 'create'" v-model="form.business_unit"></business-unit-input>
    <div v-else-if="defaultBusinessUnit === 'ALL'">
      <input  type="text" :value="form.business_unit" placeholder="Business Unit" class="form-input" readonly />
    </div>

    <div class="justify-center w-full grid grid-cols-1 md:grid-cols-3 md:gap-2 ">
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Vessel Name <span class="text-red-500">*</span></span>
            <div v-if="form.form_type === 'create'">
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
            </div>
            <input v-else type="text" :value="form?.opsVessel?.name" placeholder="Vessel Name" class="form-input" readonly />
            
          <Error v-if="errors?.ops_vessel_id" :errors="errors.ops_vessel_id" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Department <span class="text-red-500">*</span></span>
            <div v-if="form.form_type === 'create'">
              <v-select placeholder="Select Department" :options="shipDepartments" @search=""     v-model="form.mnt_ship_department_name" label="name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                <template #search="{attributes, events}">
                  <input
                      class="vs__search"
                      :required="!form.mnt_ship_department_name"
                      v-bind="attributes"
                      v-on="events"
                  />
                </template>
              </v-select>
              <input type="hidden" v-model="form.mnt_ship_department_id">
            </div>
            <input v-else type="text" :value="form?.mntItem?.mntItemGroup?.mntShipDepartment?.name" placeholder="Ship Department Name" class="form-input" readonly />
          <Error v-if="errors?.mnt_ship_department_id" :errors="errors.mnt_ship_department_id" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Item Group <span class="text-red-500">*</span></span>
            <div v-if="form.form_type === 'create'">
              <v-select placeholder="Select Item Group" :options="shipDepartmentWiseItemGroups" @search="" v-model="form.mnt_item_group_name" label="name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                <template #search="{attributes, events}">
                  <input
                      class="vs__search"
                      :required="!form.mnt_item_group_name"
                      v-bind="attributes"
                      v-on="events"
                  />
                </template>
              </v-select>
              <input type="hidden" v-model="form.mnt_item_group_id">
            </div>
            <input v-else type="text" :value="form?.mntItem?.mntItemGroup?.name" placeholder="Item Group Name" class="form-input" readonly />
          <Error v-if="errors?.mnt_item_group_id" :errors="errors.mnt_item_group_id" />
        </label>
        <div class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Item Name <span class="text-red-500">*</span></span>
            <div v-if="form.form_type === 'create'">
              <v-select placeholder="Select Item" :options="form.itemGroupWiseHourlyItems" multiple @search="" v-model="form.mnt_item_name" label="name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                <template #search="{attributes, events}">
                  <input
                      class="vs__search"
                      :required="!form.mnt_item_name"
                      v-bind="attributes"
                      v-on="events"
                  />
                </template>
              </v-select>
              <input type="hidden" v-model="form.mnt_item_id">
            </div>
            <input v-else type="text" :value="form?.mntItem?.name" placeholder="Item Name" class="form-input" readonly />
          <Error v-if="errors?.mnt_item_id" :errors="errors.mnt_item_id" />
          </div>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Previous Run Hour </span>
            <input type="text" v-model="form.previous_run_hour" placeholder="Previous Run Hour" class="form-input" readonly />
          <Error v-if="errors?.previous_run_hour" :errors="errors.previous_run_hour" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Running Hour (Since Last Update) <span v-show="!(form.form_type === 'edit' && form.previous_run_hour == 0 && form.running_hour == 0)" class="text-red-500">*</span></span>
            <input type="text" v-model="form.running_hour" placeholder="Running Hour" class="form-input" :required="!(form.form_type === 'edit' && form.previous_run_hour == 0 && form.running_hour == 0)" :readonly="form.form_type === 'edit' && form.previous_run_hour == 0 && form.running_hour == 0" />
          <Error v-if="errors?.running_hour" :errors="errors.running_hour" />
        </label>

        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Present Run Hour <span v-show="(form.form_type === 'edit' && form.previous_run_hour == 0 && form.running_hour == 0)" class="text-red-500">*</span></span>
            <input type="text" v-model="computedPresentRunHour" placeholder="Present Run Hour" class="form-input" :required="(form.form_type === 'edit' && form.previous_run_hour == 0 && form.running_hour == 0)" :readonly="!(form.form_type === 'edit' && form.previous_run_hour == 0 && form.running_hour == 0)" />
        </label>

        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Updated On <span class="text-red-500">*</span></span>
            <input type="date" v-model="form.updated_on" placeholder="Updated on" class="form-input" required  />
          <Error v-if="errors?.updated_on" :errors="errors.updated_on" />
        </label>
        
    </div>
    
</template>
<script setup>
import Error from "../../Error.vue";
import Editor from '@tinymce/tinymce-vue';

import useRunHour from "../../../composables/maintenance/useRunHour";
import {onMounted, watch, watchEffect, ref, computed} from "vue";
import useShipDepartment from "../../../composables/maintenance/useShipDepartment";
import useItemGroup from "../../../composables/maintenance/useItemGroup";
import useItem from "../../../composables/maintenance/useItem";
import BusinessUnitInput from "../../input/BusinessUnitInput.vue";
import useVessel from "../../../composables/operations/useVessel";

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});

const { shipDepartments, getShipDepartmentsWithoutPagination } = useShipDepartment();
const { vessels, getVesselsWithoutPaginate } = useVessel();
const { shipDepartmentWiseItemGroups, getShipDepartmentWiseItemGroups } = useItemGroup();
const { itemGroupWiseHourlyItems, getItemGroupWiseHourlyItems } = useItem();
const { presentRunHour, getItemPresentRunHour } = useRunHour();
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
const defaultBusinessUnit = ref(Store.getters.getCurrentUser.business_unit);

const computedPresentRunHour = computed({
      get: () => {
        if (props.form.form_type === 'edit' && props.form.previous_run_hour == 0 && props.form.running_hour == 0) {
          return props.form.present_run_hour;
        } else {
          return parseInt(props.form.previous_run_hour) + parseInt(props.form.running_hour) || '';
        }
      },
      set: (value) => {
        props.form.present_run_hour = value;
      }
    });

watch(() => props.form.ops_vessel_name, (value) => {
  props.form.ops_vessel_id = value?.id;
});



watch(() => props.form.mnt_ship_department_name, (value) => {
  props.form.mnt_ship_department_id = value?.id;

  props.form.mnt_item_group_name = null;
  props.form.mnt_item_group_id = null;
  getShipDepartmentWiseItemGroups(props.form.mnt_ship_department_id);
  // fetchShipDepartmentWiseItemGroups();
});


watch(() => props.form.mnt_item_group_name, (value) => {
  props.form.mnt_item_group_id = value?.id;

  props.form.mnt_item_name = [];
  props.form.mnt_item_id = [];
  getItemGroupWiseHourlyItems(props.form.mnt_item_group_id);
  // fetchItemGroupWiseHourlyItems();
});

watch(() => props.form.mnt_item_name, (value) => {
  value = value ? value.find(val => val.id === 'all') ? [value.find(val => val.id === 'all')] : value : null;
  props.form.mnt_item_name = value;
  // props.form.previous_run_hour = value ? (value.length == 1 ? value[0].present_run_hour : '') : '';
  if(props.form.form_type === 'create' && value?.length == 1 && value[0]?.id !== 'all'){
      getItemPresentRunHour(props.form.ops_vessel_id, value[0].id);
  }
  else if(props.form.form_type !== 'edit'){
    props.form.previous_run_hour = '';
    presentRunHour.value = '';
  }

  props.form.mnt_item_id = value ? value.map(val=>val.id) : null;
});

watch(()=> presentRunHour.value, (value) => {
  props.form.previous_run_hour = (value ? value.previous_run_hour : 0);
});

watch(() => itemGroupWiseHourlyItems.value, (value) => {
  props.form.itemGroupWiseHourlyItems  = [{id: 'all', item_code: '', name: 'All', present_run_hour: ''}, ...value];
});

watch(() => props.form.business_unit, (newValue, oldValue) => {
  businessUnit.value = newValue;
  if(newValue !== oldValue && oldValue != ''){
    props.form.mnt_ship_department_name = null;
    props.form.ops_vessel_name = null;
  }
});

onMounted(() => {
  watchEffect(() => {
      getShipDepartmentsWithoutPagination(businessUnit.value);
      getVesselsWithoutPaginate(businessUnit.value);
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

.vs--searchable .vs__dropdown-toggle{
  height: auto !important;
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