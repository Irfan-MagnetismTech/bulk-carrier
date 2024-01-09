<template>
    <div class="justify-center w-full grid grid-cols-1 md:grid-cols-3 md:gap-2">
        <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
        <label class="block w-full mt-2 text-sm col-start-1">
            <span class="text-gray-700 dark-disabled:text-gray-300">Form No <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.form_no" placeholder="Form No" class="form-input" required />
            <Error v-if="errors?.form_no" :errors="errors.form_no" />
        </label>
        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Form Name <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.form_name" placeholder="Form Name" class="form-input" required />
            <Error v-if="errors?.form_name" :errors="errors.form_name" />
        </label>

        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Version <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.version" placeholder="Version" class="form-input" required />
            <Error v-if="errors?.version" :errors="errors.version" />
        </label>

        <RemarksComponent class="md:col-span-3" v-model.trim="form.description" :maxlength="500" :fieldLabel="'Description'"></RemarksComponent>

      </div>

      <div class="grid grid-cols-1">
        <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
            <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Aspects</legend>
            <fieldset class="px-2 py-2 mt-3 border border-gray-300 rounded dark-disabled:border-gray-400" v-for="(appraisalFormLine, index) in form?.appraisalFormLines" :key="index">
                <div class="flex items-center gap-3 mb-2">
                  <div class="w-1/6 text-sm">
                    <span class="text-gray-700 dark-disabled:text-gray-300">Section No</span>
                    <input type="text" :value="appraisalFormLine.section_no = printToLetter(index+1)"  placeholder="Section No" class="form-input section-name-input vms-readonly-input"  readonly />
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


                  <!-- <div>
                    <button type="button" class="bg-green-600 text-white px-3 py-2 rounded-md" v-if="index == 0" @click="addAppraisalFormLine"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                      </svg>
                    </button>
                    <button type="button" class="bg-red-600 text-white px-3 py-2 rounded-md" v-else @click="removeAppraisalFormLine(index)" ><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                      </svg></button>
                    
                  </div> -->

                </div>
                <table class="w-full whitespace-no-wrap" id="table">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-center text-gray-500  bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
                        <th class="w-1/12 px-4 py-2 align-center max-w-[67px]">Item No <span class="text-red-500" v-show="appraisalFormLine?.appraisalFormLineItems?.length">*</span></th>
                        <th class="w-4/12 px-4 py-2 align-center">Aspect <span class="text-red-500" v-show="appraisalFormLine?.appraisalFormLineItems?.length">*</span></th>
                        <th class="w-4/12 px-4 py-2 align-center">Description</th>
                        <th class="w-2/12 px-4 py-2 align-center">Answer Type <span class="text-red-500" v-show="appraisalFormLine?.appraisalFormLineItems?.length">*</span></th>
                        <th class="w-1/12 px-4 py-2 align-center text-center max-w-[55px]">
                          <button type="button" class="bg-green-600 text-white px-3 py-2 rounded-md"  @click="addAppraisalFormLineItem(index)"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                      </svg></button>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
                    <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(appraisalFormLineItem, appraisalFormLineItemIndex) in appraisalFormLine?.appraisalFormLineItems" :key="appraisalFormLineItemIndex">
                      <td class="px-1 py-1">
                        <input type="text" :value="appraisalFormLineItem.item_no = appraisalFormLineItemIndex+1"  placeholder="Item No" class="form-input section-name-input vms-readonly-input"  readonly />
                      </td>
                        <td class="px-1 py-1">
                          <div class="relative">
                            <input type="text" class="form-input"  v-model.trim="appraisalFormLineItem.aspect" placeholder="Aspect" required />
                            <span v-show="appraisalFormLineItem.isAspectDuplicate" class="text-yellow-600 pl-1 absolute top-2 right-1" title="Duplicate Aspect" v-html="icons.ExclamationTriangle"></span>
                          </div>
                        </td>
                        <td class="px-1 py-1"><input type="text" class="form-input"  v-model.trim="appraisalFormLineItem.description" placeholder="Description" /></td>
                        <td class="px-1 py-1">
                          <select v-model.trim="appraisalFormLineItem.answer_type" class="form-input" required>
                            <option value="" disabled selected>Select</option>
                            <option value="Number">Number</option>
                            <option value="Boolean">Boolean</option>
                            <option value="Grade">Grade</option>
                            <option value="Other">Other</option>
                          </select>
                        </td>
                        <td class="px-1 py-1"> <button type="button" class="bg-red-600 text-white px-3 py-2 rounded-md" @click="removeAppraisalFormLineItem(index, appraisalFormLineItemIndex)" ><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                      </svg></button></td>
                    </tr>
                </tbody>
            </table>
                <div>
                  <button type="button" v-if="form?.appraisalFormLines?.length > 1" @click="removeAppraisalFormLine(index)" class="px-3 py-2 mt-2 mx-auto  text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple flex w-32 justify-center text-center">
                    Remove
                </button> 
                </div>
            </fieldset>
            <button type="button" @click="addAppraisalFormLine" class="px-3 py-2 mx-auto mt-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple flex w-32 justify-center text-center">
                    Add More
                </button>
        </fieldset>
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
  
  // const { getItemCodeByGroupId } = useItem();
  // const { shipDepartmentWiseItemGroups, getShipDepartmentWiseItemGroups, isItemGroupLoading } = useItemGroup();
  const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
  const icons = useHeroIcon();
  
  const props = defineProps({
    form: {
      required: false,
      default: {}
    },
    appraisalFormLineObject: { type: Object, required: false },
    page: {
      required: false,
      default: {}
    },
    errors: { type: [Object, Array], required: false },
  });
  
 
  
  
  function addRow() {
    props.form.description.push({ key: '', value: '' });
  }
  function removeRow(index) {
    props.form.description.splice(index, 1);
  }

  function addAppraisalFormLine() {
    props.form.appraisalFormLines.push(cloneDeep(props.appraisalFormLineObject))
  }

  function removeAppraisalFormLine(index) {
    if(props.form.appraisalFormLines.length > 1)
      props.form.appraisalFormLines.splice(index, 1)
  }

  function addAppraisalFormLineItem(parentIndex) {
    props.form.appraisalFormLines[parentIndex].appraisalFormLineItems.push({ aspect: '', description: '', answer_type: '', item_no: '', item_composite: ''});
  }

  function removeAppraisalFormLineItem(parentIndex, childIndex) {
    props.form.appraisalFormLines[parentIndex].appraisalFormLineItems.splice(childIndex, 1);
  }

  const alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

  function printToLetter(number) {
      let result = "";
      
      while (number > 0) {
          const remainder = (number - 1) % alphabet.length;
          result = alphabet.charAt(remainder) + result;
          number = Math.floor((number - 1) / alphabet.length);
      }
      
      return result || alphabet[0];
  }
  
  
  
  // const { shipDepartments, getShipDepartmentsWithoutPagination, isShipDepartmentLoading } = useShipDepartment();
  
  watch(() => props.form.business_unit, (newValue, oldValue) => {
    businessUnit.value = newValue;
    
  });
  
  
  onMounted(() => {
    // watchEffect(() => {
    //   if(businessUnit.value && businessUnit.value != 'ALL'){
    //     getShipDepartmentsWithoutPagination(businessUnit.value);
    //   }
        
    // });
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
  .section-name-input{
    margin-top: 0 !important;
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