<template>
    <div class="justify-center w-full grid grid-cols-1 md:grid-cols-3 md:gap-2">
        <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Vessel <span class="text-red-500">*</span></span>
              <v-select placeholder="Select Vessel" :loading="isVesselLoading"  :options="vessels" @search="" v-model="form.ops_vessel" label="name" @update:modelValue="vesselChange"  class="block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray form-input">
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
            <span class="text-gray-700 dark-disabled:text-gray-300">Survey Item <span class="text-red-500">*</span></span>
            <v-select placeholder="Select Survey Item" :loading="isSurveyItemLoading"  :options="surveyItems" @search="" v-model="form.mnt_survey_item" label="item_name" @update:modelValue="surveyItemChange"  class="block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray form-input">
              <template #search="{attributes, events}">
              <input
                  class="vs__search"
                  :required="!form.mnt_survey_item"
                  v-bind="attributes"
                  v-on="events"
              />
            </template>
            </v-select>
            <input type="hidden" v-model="form.survey_item_id">
          <Error v-if="errors?.survey_item_id" :errors="errors.survey_item_id" />
        </label>
  
        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Survey Type <span class="text-red-500">*</span></span>
            <v-select placeholder="Select Survey Type" :loading="isSurveyTypeLoading"  :options="surveyTypes" @search="" v-model="form.mnt_survey_type" label="survey_type_name" @update:modelValue="surveyTypeChange"  class="block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray form-input">
              <template #search="{attributes, events}">
              <input
                  class="vs__search"
                  :required="!form.mnt_survey_type"
                  v-bind="attributes"
                  v-on="events"
              />
            </template>
            </v-select>
            <input type="hidden" v-model="form.survey_type_id">
          <Error v-if="errors?.survey_type_id" :errors="errors.survey_type_id" />
        </label>
  
        <!-- <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Short Code <span class="text-red-500">*</span></span>
          <input type="text" v-model.trim="form.short_code" placeholder="Short Code" class="form-input" required/>
          <Error v-if="errors?.short_code" :errors="errors.short_code" />
        </label> -->
  
        <!-- <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Survey Name <span class="text-red-500">*</span></span>
          <input type="text" v-model.trim="form.survey_name" placeholder="Survey Name" class="form-input" required/>
          <Error v-if="errors?.survey_name" :errors="errors.survey_name" />
        </label> -->
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Survey <span class="text-red-500">*</span></span>
            <v-select placeholder="Select Survey" :loading="isSurveyLoading"  :options="form.mnt_surveys" @search="" v-model="form.mnt_survey" label="survey_name" @update:modelValue="surveyChange"  class="block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray form-input">
              <template #search="{attributes, events}">
              <input
                  class="vs__search"
                  :required="!form.mnt_survey"
                  v-bind="attributes"
                  v-on="events"
              />
            </template>
            </v-select>
            <input type="hidden" v-model="form.mnt_survey_id">
          <Error v-if="errors?.mnt_survey_id" :errors="errors.mnt_survey_id" />
        </label>
        
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Range Date (From) <span class="text-red-500">*</span></span>
          <!-- <input type="date" v-model="form.range_date_from" placeholder="Range Date (From)" class="form-input" @input="setRangeDateTo"  required/> -->
          <VueDatePicker v-model="form.range_date_from" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }" @update:model-value="setRangeDateTo"></VueDatePicker>
          <Error v-if="errors?.range_date_from" :errors="errors.range_date_from" />
        </label>
  
        
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Range Date (To) <span class="text-red-500">*</span></span>
          <!-- <input type="date" v-model="form.range_date_to" placeholder="Range Date (To)" class="form-input" required/> -->
          <VueDatePicker v-model="form.range_date_to" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>
          <Error v-if="errors?.range_date_to" :errors="errors.range_date_to" />
        </label>
  
        
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Assigned Date <span class="text-red-500">*</span></span>
          <!-- <input type="date" v-model="form.assigned_date" placeholder="Assigned date" @input="setDueDate"  class="form-input" required/> -->
          <VueDatePicker v-model="form.assigned_date" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }" @update:model-value="setDueDate"></VueDatePicker>
          <Error v-if="errors?.assigned_date" :errors="errors.assigned_date" />
        </label>
  
        
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Due Date <span class="text-red-500">*</span></span>
          <!-- <input type="date" v-model="form.due_date" placeholder="Due date" class="form-input" required/> -->
          <VueDatePicker v-model="form.due_date" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>
          <Error v-if="errors?.due_date" :errors="errors.due_date" />
        </label>
  
        <!-- <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Status</span>
          <input type="text" v-model="form.status" placeholder="Status" class="form-input vms-readonly-input" readonly/>
          <Error v-if="errors?.status" :errors="errors.status" />
        </label> -->
  
      </div>
      
      <ErrorComponent :errors="errors"></ErrorComponent>
  </template>
  <script setup>
  import Error from "../../Error.vue";
  import Editor from '@tinymce/tinymce-vue';
  
  import {onMounted, watch, watchEffect, ref} from "vue";
  import BusinessUnitInput from "../../input/BusinessUnitInput.vue";
  import useVessel from "../../../composables/operations/useVessel";
  import useSurveyItem from "../../../composables/maintenance/useSurveyItem";
  import useSurveyType from "../../../composables/maintenance/useSurveyType";
  import useSurvey from "../../../composables/maintenance/useSurvey";
  import ErrorComponent from "../../utils/ErrorComponent.vue";
  import moment from 'moment';
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
  
  const { vessels, getVesselsWithoutPaginate, isLoading:isVesselLoading } = useVessel();
  const { surveyItems, getSurveyItemsWithoutPagination, isLoading:isSurveyItemLoading } = useSurveyItem();
  const { surveyTypes, getSurveyTypesWithoutPagination, isLoading:isSurveyTypeLoading } = useSurveyType();
  const { surveys, getSurveysWithoutPagination, isLoading:isSurveyLoading } = useSurvey();
  
  
  
  function vesselChange() {
    props.form.ops_vessel_id = props.form.ops_vessel?.id;
  } 
  
  function surveyItemChange() {
      props.form.mnt_survey_item_id = props.form.mnt_survey_item?.id;

      surveyTypeChange();
  }
  
  function surveyTypeChange() {
    props.form.mnt_survey_type_id = props.form.mnt_survey_type?.id;

    props.form.mnt_survey = null;
    props.form.mnt_survey_id = null;
    surveys.value = [];
    if(props.form.mnt_survey_item_id && props.form.mnt_survey_type_id)
      getSurveysWithoutPagination( props.form.mnt_survey_item_id, props.form.mnt_survey_type_id );
    setRangeDateTo();
    setDueDate();
  }

  watch(() => surveys.value, value => {
    props.form.mnt_surveys = value;
  })

  function surveyChange() {
    props.form.mnt_survey_id = props.form.mnt_survey?.id;
  }
  
  function setRangeDateTo() {
    props.form.range_date_to = "";
    if(props.form.range_date_from)
      props.form.range_date_to = moment(props.form.range_date_from).add(props.form?.mnt_survey_type?.window_period ?? 0, 'months').format('YYYY-MM-DD');
  }
  
  function setDueDate() {
    props.form.due_date = "";
    if(props.form.assigned_date)
      props.form.due_date = moment(props.form.assigned_date).add(props.form?.mnt_survey_type?.due_period ?? 0, 'months').format('YYYY-MM-DD');
  }
  
  
  
  
  watch(() => props.form.business_unit, (newValue, oldValue) => {
    businessUnit.value = newValue;

    props.form.ops_vessel = null;
    vesselChange();
  });
  
  
  
  
  
  onMounted(() => {
    getSurveyItemsWithoutPagination();
    getSurveyTypesWithoutPagination();
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