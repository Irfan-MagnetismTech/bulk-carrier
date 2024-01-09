<template>
    <div class="justify-center w-full grid grid-cols-1 md:grid-cols-3 md:gap-2">
        <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
        <label class="block w-full mt-2 text-sm col-start-1">
            <span class="text-gray-700 dark-disabled:text-gray-300">Crew <span class="text-red-500">*</span></span>
            <v-select placeholder="Select Crew" :loading="isCrewLoading"  :options="crews" @search="" v-model="form.crw_crew_profile" label="full_name" @update:modelValue="crewProfileChange"  class="block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray form-input">
                <template #search="{attributes, events}">
                  <input
                      class="vs__search"
                      :required="!form.crw_crew_profile"
                      v-bind="attributes"
                      v-on="events"
                  />
                </template>
            </v-select>
            <input type="hidden" v-model="form.crw_crew_id">
            <Error v-if="errors?.crw_crew_id" :errors="errors.crw_crew_id" />
        </label>
        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Contact No</span>
            <input type="text" :value="form.crw_crew_profile?.pre_mobile_no" placeholder="Contact No" class="form-input vms-readonly-input" readonly />
            <Error v-if="errors?.contact_no" :errors="errors.contact_no" />
        </label>

        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Age</span>
            <input type="text" :value="form.age = calculateAge(form.crw_crew_profile?.date_of_birth, null)" placeholder="Age" class="form-input vms-readonly-input" readonly />
            <Error v-if="errors?.age" :errors="errors.age" />
        </label>

        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Passport No</span>
            <input type="text" :value="form.crw_crew_profile?.passport_no" placeholder="Passport No" class="form-input vms-readonly-input" readonly />
            <Error v-if="errors?.passport_no" :errors="errors.passport_no" />
        </label>

        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Seaman's Book No</span>
            <input type="text" :value="form.seaman_book_no" placeholder="Seaman's Book No" class="form-input vms-readonly-input" readonly />
            <Error v-if="errors?.seaman_book_no" :errors="errors.seaman_book_no" />
        </label>

        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Nationality</span>
            <input type="text" :value="form.crw_crew_profile?.nationality" placeholder="Nationality" class="form-input vms-readonly-input" readonly />
            <Error v-if="errors?.nationality" :errors="errors.nationality" />
        </label>

        <label class="block w-full mt-2 text-sm col-start-1">
            <span class="text-gray-700 dark-disabled:text-gray-300">Completed Assignment Code <span class="text-red-500">*</span></span>
            <v-select placeholder="Select Completed Assignment" :loading="isAppraisalUndoneAssignmentLoading"  :options="form.appraisalUndoneAssignments" @search="" v-model="form.crw_crew_assignment" label="assignment_code" @update:modelValue="crwCrewAssignmentChange"  class="block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray form-input">
                <template #search="{attributes, events}">
                  <input
                      class="vs__search"
                      :required="!form.crw_crew_assignment"
                      v-bind="attributes"
                      v-on="events"
                  />
                </template>
            </v-select>
            <input type="hidden" v-model="form.crw_crew_assignment_id">
            <Error v-if="errors?.crw_crew_assignment_id" :errors="errors.crw_crew_assignment_id" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Vessel</span>
            <input type="text" :value="form.crw_crew_assignment?.opsVessel?.name" placeholder="Vessel" class="form-input vms-readonly-input" readonly />
            <!-- <Error v-if="errors?.nationality" :errors="errors.nationality" /> -->
        </label>
        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Rank</span>
            <input type="text" :value="form.crw_crew_assignment?.position_onboard" placeholder="Rank" class="form-input vms-readonly-input" readonly />
            <!-- <Error v-if="errors?.nationality" :errors="errors.nationality" /> -->
        </label>

        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Service From</span>
            <input type="text" :value="formatDate(form.crw_crew_assignment?.joining_date)" placeholder="Service From" class="form-input vms-readonly-input" readonly />
            <!-- <Error v-if="errors?.nationality" :errors="errors.nationality" /> -->
        </label>

        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Service To</span>
            <input type="text" :value="formatDate(form.crw_crew_assignment?.completion_date)" placeholder="Service To" class="form-input vms-readonly-input" readonly />
            <!-- <Error v-if="errors?.nationality" :errors="errors.nationality" /> -->
        </label>

        <label class="block w-full mt-2 text-sm col-start-1">
            <span class="text-gray-700 dark-disabled:text-gray-300">Appraisal Form <span class="text-red-500">*</span></span>
            <v-select placeholder="Select Appraisal Form" :loading="isAppraisalFormLoading"  :options="appraisalForms" @search="" v-model="form.appraisal_form" label="form_name" @update:modelValue="appraisalFormChange"  class="block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray form-input">
                <template #search="{attributes, events}">
                  <input
                      class="vs__search"
                      :required="!form.appraisal_form"
                      v-bind="attributes"
                      v-on="events"
                  />
                </template>
            </v-select>
            <input type="hidden" v-model="form.appraisal_form_id">
            <Error v-if="errors?.appraisal_form_id" :errors="errors.appraisal_form_id" />
        </label>

        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Total Marks</span>
            <input type="text" v-model="form.total_marks" placeholder="Total Marks" class="form-input vms-readonly-input" readonly />
            <Error v-if="errors?.total_marks" :errors="errors.total_marks" />
        </label>
        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Appraisal Date</span>
            <VueDatePicker v-model="form.appraisal_date" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>
            <Error v-if="errors?.appraisal_date" :errors="errors.appraisal_date" />
        </label>

      </div>
      
      <div class="grid grid-cols-1 mt-2">
        <div class="">
            <!-- <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Aspects</legend> -->
            <template class="px-2 py-2 mt-3 border border-gray-300 rounded dark-disabled:border-gray-400" v-for="(appraisalFormLine, index) in  
              form?.appraisalRecordLines" :key="index">
                <!-- <div class="flex items-center gap-3 mb-2">
                  <div class="w-1/6 text-sm">
                    <span class="text-gray-700 dark-disabled:text-gray-300">Section No</span>
                    <input type="text" :value="appraisalFormLine.section_no"  placeholder="Section No" class="form-input section-name-input vms-readonly-input"  readonly />
                  </div>
                  <div class="w-3/6 block  text-sm relative">
                      <span class="text-gray-700 dark-disabled:text-gray-300">Section Name <span class="text-red-500">*</span></span>
                      <div class="relative">
                        <input type="text" v-model.trim="appraisalFormLine.section_name" placeholder="Section Name" class="form-input section-name-input" required />
                        <span v-show="appraisalFormLine.isSectionDuplicate" class="text-yellow-600 pl-1 absolute top-2 right-1" title="Duplicate Section" v-html="icons.ExclamationTriangle"></span>
                        <Error v-if="errors?.section_name" :errors="errors.section_name" />
                      </div>
                  </div>
                  
                  <div class="w-2/6 block  text-sm relative">
                      <span class="text-gray-700 dark-disabled:text-gray-300">Section Type <span class="text-red-500">*</span></span>
                      <select v-model.trim="appraisalFormLine.is_tabular" class="form-input" required>
                        <option value="" disabled selected>Select Section Type</option>
                        <option value="1" >Tabular</option>
                        <option value="0">Non Tabular</option>
                      </select>
                  </div>



                </div> -->
                <!-- {{ appraisalFormLine.is_tabular }} -->
                <table class="w-full whitespace-no-wrap mt-2" id="table" v-if="appraisalFormLine.is_tabular">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-center text-gray-500  bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
                            <th colspan="4" class="px-4 py-2 text-center">{{ appraisalFormLine.section_no }}. {{ appraisalFormLine.section_name }}</th>    
                        </tr>
                        
                        <tr class="text-xs font-semibold tracking-wide text-center text-gray-500  bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
                            <th class="w-2/5 px-4 py-2 text-center">Aspect</th>
                            <!-- <th class="w-2/5 px-4 py-2 text-center">Description</th> -->
                            <th class="w-2/5 px-4 py-2 text-center">Comments</th>
                            <th class="w-1/5 px-4 py-2 text-center">Marks <span class="text-red-500">*</span></th>
                        </tr>

                    </thead>
                    <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
                      <template> 
                        {{ appraisalFormLine.appraisalRecordLineItems = (page == 'create' ? appraisalFormLine?.appraisalFormLineItems : appraisalFormLine.appraisalRecordLineItems) }} 
                      </template>

                        <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(appraisalFormLineItem, appraisalFormLineItemIndex) in appraisalFormLine?.appraisalRecordLineItems" :key="appraisalFormLineItemIndex">
                            <td class="px-1 py-1">
                              <!-- <div class="relative">
                                <input type="text" class="form-input"  v-model.trim="appraisalFormLineItem.aspect" placeholder="Aspect" required />
                                <span v-show="appraisalFormLineItem.isAspectDuplicate" class="text-yellow-600 pl-1 absolute top-2 right-1" title="Duplicate Aspect" v-html="icons.ExclamationTriangle"></span>
                              </div> -->
                              <div>
                                <strong>{{ appraisalFormLineItem.aspect }}:</strong><br>
                                {{ appraisalFormLineItem.description }}
                              </div>
                            </td>
                            <!-- <td class="px-1 py-1"><input type="text" class="form-input"  v-model.trim="appraisalFormLineItem.description" placeholder="Description" /></td> -->
                            <td>
                              <input type="text" class="form-input"  v-model.trim="appraisalFormLineItem.comment" placeholder="Comment"  />
                            </td>
                            <td class="px-1 py-1">
                              <template> {{ appraisalFormLineItem.answer = appraisalFormLineItem.answer ?? '' }} </template>
                              <div v-if="appraisalFormLineItem.answer_type == 'Boolean'" class="">
                                <select v-model.trim="appraisalFormLineItem.answer" class="form-input" required>
                                  <option value="" disabled selected>Select</option>
                                  <option value="1">Yes</option>
                                  <option value="0">No</option>
                                </select>
                              </div>
                              <div v-else-if="appraisalFormLineItem.answer_type == 'Number' || appraisalFormLineItem.answer_type == 'Grade'" >
                                <select v-model.trim="appraisalFormLineItem.answer" class="form-input" required>
                                  <option value="" disabled selected>Select</option>
                                  <option value="1">{{ appraisalFormLineItem.answer_type == 'Grade' ? 'Poor' : 1 }}</option>
                                  <option value="2">{{ appraisalFormLineItem.answer_type == 'Grade' ? 'Fair' : 2 }}</option>
                                  <option value="3">{{ appraisalFormLineItem.answer_type == 'Grade' ? 'Good' : 3 }}</option>
                                  <option value="4">{{ appraisalFormLineItem.answer_type == 'Grade' ? 'Very Good' : 4 }}</option>
                                  <option value="5">{{ appraisalFormLineItem.answer_type == 'Grade' ? 'Excellent' : 5 }}</option>
                                </select>
                              </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table class="w-full whitespace-no-wrap  mt-2" id="table" v-else>
                  <thead>
                        <tr class="text-xs font-semibold tracking-wide text-center text-gray-500  bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
                            <th class="px-4 py-2 text-center">{{ appraisalFormLine.section_no }}. {{ appraisalFormLine.section_name }}</th>    
                        </tr>
                  </thead>
                  <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800 ">
                        <tr class="text-xs font-semibold tracking-wide text-center text-gray-500  bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800 ">
                            <td class="px-4 py-2 ">
                              <RemarksComponent v-model.trim="appraisalFormLine.comment" :maxlength="500" :fieldLabel="'Comment'"></RemarksComponent>
                              <!-- {{ appraisalFormLine.section_no }}. {{ appraisalFormLine.section_name }} -->
                            </td>    
                        </tr>
                        <!-- <tr class="text-gray-700 dark-disabled:text-gray-400 border-none" v-for="(appraisalFormLineItem, appraisalFormLineItemIndex) in appraisalFormLine?.appraisalFormLineItems" :key="appraisalFormLineItemIndex">
                          <td class="border-none ">
                            {{ appraisalFormLineItemIndex+1 }}. {{ appraisalFormLineItem.aspect }}
                          </td>
                          <td class="border-none ">
                            <template> {{ appraisalFormLineItem.answer = appraisalFormLineItem.answer ?? '' }} </template>
                              <div v-if="appraisalFormLineItem.answer_type == 'Boolean'" class="">
                                <select v-model.trim="appraisalFormLineItem.answer" class="form-input" required>
                                  <option value="" disabled selected>Select</option>
                                  <option value="1">Yes</option>
                                  <option value="0">No</option>
                                </select>
                              </div>
                              <div v-else-if="appraisalFormLineItem.answer_type == 'Number' || appraisalFormLineItem.answer_type == 'Grade'" >
                                <select v-model.trim="appraisalFormLineItem.answer" class="form-input" required>
                                  <option value="" disabled selected>Select</option>
                                  <option value="1">{{ appraisalFormLineItem.answer_type == 'Grade' ? 'Poor' : 1 }}</option>
                                  <option value="2">{{ appraisalFormLineItem.answer_type == 'Grade' ? 'Fair' : 2 }}</option>
                                  <option value="3">{{ appraisalFormLineItem.answer_type == 'Grade' ? 'Good' : 3 }}</option>
                                  <option value="4">{{ appraisalFormLineItem.answer_type == 'Grade' ? 'Very Good' : 4 }}</option>
                                  <option value="5">{{ appraisalFormLineItem.answer_type == 'Grade' ? 'Excellent' : 5 }}</option>
                                </select>
                              </div>
                              
                              <div class="flex gap-2 justify-around flex-wrap" v-else >
                                <input type="text" class="form-input"  v-model.trim="appraisalFormLineItem.comment" placeholder=""  />



                              </div>

                          </td>
                            
                        </tr> -->
                    </tbody>

                </table>
                
            </template>
           
        </div>
      </div>

      <ErrorComponent :errors="errors"></ErrorComponent>   
  </template>
  <script setup>
  // import Error from "../../Error.vue";
  import Error from "../Error.vue";
  import Editor from '@tinymce/tinymce-vue';
  
  // import useShipDepartment from "../../../composables/maintenance/useShipDepartment";
  import {onMounted, watch, watchEffect, ref} from "vue";
  // import useItemGroup from "../../../composables/maintenance/useItemGroup";
  // import useItem from "../../../composables/maintenance/useItem";
  // import BusinessUnitInput from "../../input/BusinessUnitInput.vue";
  import BusinessUnitInput from "../input/BusinessUnitInput.vue";
  // import ErrorComponent from "../../utils/ErrorComponent.vue";
  import ErrorComponent from "../utils/ErrorComponent.vue";
  // import useHeroIcon from "../../../assets/heroIcon";
  import useHeroIcon from "../../assets/heroIcon";
  import RemarksComponent from "../utils/RemarksComponent.vue";
  import cloneDeep from 'lodash/cloneDeep';
  import useCrewCommonApiRequest from "../../composables/crew/useCrewCommonApiRequest";
import useAppraisalForm from "../../composables/crew/useAppraisalForm";
import { formatDate } from "../../utils/helper";
  
  
  // const { getItemCodeByGroupId } = useItem();
  // const { shipDepartmentWiseItemGroups, getShipDepartmentWiseItemGroups, isItemGroupLoading } = useItemGroup();
  const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
  const dateFormat = ref(Store.getters.getVueDatePickerTextInputFormat.date);

  const icons = useHeroIcon();
  
  const props = defineProps({
    form: {
      required: false,
      default: {}
    },
    // appraisalFormLineObject: { type: Object, required: false },
    page: {
      required: false,
      default: {}
    },
    errors: { type: [Object, Array], required: false },
  });
  

  const { crews, appraisalUndoneAssignments, getCrews, getAppraisalUndoneAssignments, crwRankLists, getCrewRankLists, isLoading:isCrewLoading, isAppraisalUndoneAssignmentLoading } = useCrewCommonApiRequest();
  const { appraisalForms, appraisalForm, getAppraisalFormsWithoutPaginate, showAppraisalForm, isLoading:isAppraisalFormLoading } = useAppraisalForm();
 

  const crewProfileChange = () => {
    props.form.crw_crew_id = props.form.crw_crew_profile?.id;
    
    if(props.form.crw_crew_id){
        getAppraisalUndoneAssignments(props.form.crw_crew_id);
    }
  }

  const crwCrewAssignmentChange = () => {
    props.form.crw_crew_assignment_id = props.form.crw_crew_assignment?.id;
  }

  const appraisalFormChange = () => {
      props.form.appraisal_form_id = props.form.appraisal_form?.id;
      appraisalForm.value = null;
      if (props.form.appraisal_form_id) {
        showAppraisalForm(props.form.appraisal_form_id);
      }
  }

  watch(() => appraisalForm.value, (value) => {
    props.form.appraisalRecordLines = [];
    if(value)
      props.form.appraisalRecordLines = value?.appraisalFormLines;
  });

  
  watch(() => appraisalUndoneAssignments.value, (value) => {
    props.form.appraisalUndoneAssignments = [];
    if(value)
      props.form.appraisalUndoneAssignments = value;
  });



  function calculateAge(dateOfBirth, currentDate) {
        if (!dateOfBirth) return '';
        const dob = new Date(dateOfBirth);
        const now = currentDate ? new Date(currentDate) : new Date();
        
        let age = now.getFullYear() - dob.getFullYear();

        if (now.getMonth() < dob.getMonth() || (now.getMonth() === dob.getMonth() && now.getDate() < dob.getDate())) {
            age--;
        }
        
        return age;
    }
  

  
//   function addRow() {
//     props.form.description.push({ key: '', value: '' });
//   }
//   function removeRow(index) {
//     props.form.description.splice(index, 1);
//   }

//   function addAppraisalFormLine() {
//     props.form.appraisalFormLines.push(cloneDeep(props.appraisalFormLineObject))
//   }

//   function removeAppraisalFormLine(index) {
//     if(props.form.appraisalFormLines.length > 1)
//       props.form.appraisalFormLines.splice(index, 1)
//   }

//   function addAppraisalFormLineItem(parentIndex) {
//     props.form.appraisalFormLines[parentIndex].appraisalFormLineItems.push({ aspect: '', description: '', answer_type: ''});
//   }

//   function removeAppraisalFormLineItem(parentIndex, childIndex) {
//     props.form.appraisalFormLines[parentIndex].appraisalFormLineItems.splice(childIndex, 1);
//   }

//   const alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

//   function printToLetter(number) {
//       let result = "";
      
//       while (number > 0) {
//           const remainder = (number - 1) % alphabet.length;
//           result = alphabet.charAt(remainder) + result;
//           number = Math.floor((number - 1) / alphabet.length);
//       }
      
//       return result || alphabet[0];
//   }
  
  
  
  // const { shipDepartments, getShipDepartmentsWithoutPagination, isShipDepartmentLoading } = useShipDepartment();
  
  watch(() => props.form.business_unit, (newValue, oldValue) => {
    businessUnit.value = newValue;
    
  });
  
  
  onMounted(() => {
    watchEffect(() => {
      if(businessUnit.value && businessUnit.value != 'ALL'){
        getCrews(businessUnit.value);
        getAppraisalFormsWithoutPaginate(businessUnit.value);
      }
        
    });
  });
  
  
  
  </script>
  
  <style lang="postcss" scoped>
  #table, #table th, #table td{
    @apply border border-collapse border-gray-400 text-left text-gray-700 px-1
  }

  
  #table.text-center, #table th.text-center, #table td.text-center{
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
  .section-name-input{
    margin-top: 0 !important;
  }

  .table-borderless tbody tr td,
  .table-borderless tbody tr th{
      border: none;
  }

  td label{
    font-size: 12px;
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