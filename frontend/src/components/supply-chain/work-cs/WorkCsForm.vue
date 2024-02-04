<template>
    <!-- Basic information -->
    <div class="justify-center w-full grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 md:gap-2 ">
        <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
        <label class="label-group col-start-1" v-show="page === 'edit'">
            <span class="label-item-title">CS Ref</span>
            <input
              type="text"
              readonly
              v-model="form.ref_no"
              required
              class="form-input vms-readonly-input"
              name="ref_no"
              :id="'ref_no'" />
        </label>
        <label class="label-group" :class="{'col-start-1' : page === 'create'}">
          <span class="label-item-title">Warehouse <span class="text-red-500">*</span></span>
            <v-select :options="warehouses" placeholder="--Choose an option--" :loading="warehouseLoading" v-model="form.scmWarehouse" label="name" class="block form-input" @update:modelValue="warehouseChange">
            <template #search="{attributes, events}">
                <input
                    class="vs__search"
                    :required="!form.scmWarehouse"
                    v-bind="attributes"
                    v-on="events"
                />
            </template>
            </v-select>
        </label>
        <label class="label-group">
          <span class="label-item-title">Effective Date <span class="text-red-500">*</span></span>
          <VueDatePicker v-model="form.effective_date" class="form-input" required auto-apply :enable-time-picker = "false" placeholder="dd-mm-yyyy" format="dd-MM-yyyy" model-type="yyyy-MM-dd" @update:model-value="effectiveDateChange" :min-date="minEffectiveDate"></VueDatePicker>
      </label>
      <label class="label-group">
          <span class="label-item-title">Expire Date <span class="text-red-500">*</span></span>
          <VueDatePicker v-model="form.expire_date" class="form-input" required auto-apply :enable-time-picker = "false" placeholder="dd-mm-yyyy" format="dd-MM-yyyy" model-type="yyyy-MM-dd" :min-date="form.effective_date"></VueDatePicker>
      </label>

      <label class="label-group">
            <span class="label-item-title">Purchase Center <span class="text-red-500">*</span></span>
            <v-select :options="purchase_center" placeholder="--Choose an option--" v-model="form.purchase_center" label="Product Source Type" class="block w-full mt-1 text-xs rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray form-input" @update:modelValue="changePurchaseCenter">
              <template #search="{attributes, events}">
                  <input
                      class="vs__search"
                      :required="!form.purchase_center"
                      v-bind="attributes"
                      v-on="events"
                  />  
              </template>        
            </v-select>
        </label>

      <label class="label-group">
          <span class="label-item-title">Prioity <span class="text-red-500">*</span></span>
            <v-select
              :options="PRIORITY"
              placeholder="--Choose an option--"
              v-model="form.priority"
              label="name"
              class="block form-input">
              <template #search="{attributes, events}">
                  <input
                      class="vs__search"
                      :required="!form.priority"
                      v-bind="attributes"
                      v-on="events"
                      />
              </template>
            </v-select>
        </label>

        <label class="label-group">
          <span class="label-item-title">Required Days <span class="text-red-500">*</span></span>
            <input type="number" v-model="form.required_days" placeholder="Required Days" required class="form-input" min=1/>
        </label>

        <RemarksComponet class="col-span-1 md:col-span-3 lg:col-span-4" v-model="form.special_instruction" :maxlength="300" :fieldLabel="'Special Instruction'" isRequired="true"></RemarksComponet>

    </div>
    <!-- <div class="grid grid-cols-1 mt-2"> -->
      <fieldset class=" grid grid-cols-1 px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400 ">
        <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Services</legend>
        <table class="w-full whitespace-no-wrap " id="table">
          <thead>
            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500  bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
                <th class="w-3/12 px-4 align-center">WR No <span class="text-red-500">*</span></th>
                <th class="w-5/12 px-4 align-center">Service Name <span class="text-red-500">*</span></th>
                <th class="w-2/12 px-4 align-center">Quantity <span class="text-red-500">*</span></th>
                <th class="w-2/12 px-4 align-center text-center">Action</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
            <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(scmWcsService, index) in form.scmWcsServices" :key="index">
              <td class="px-1 py-1">
                <v-select :options="filteredWorkRequisitions" placeholder="--Choose an option--" :loading="isWorkRequisitionLoading" v-model="scmWcsService.scmWr" label="ref_no" class="block form-input" @update:modelValue="wrChange(index)">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!scmWcsService.scmWr"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
                </v-select>
              </td>
              <td class="px-1 py-1">
                <div class="relative">
                  <v-select :options="serviceList[index]" placeholder="--Choose an option--" :loading="iswrServiceListLoading" v-model="scmWcsService.scmService" label="service_name_and_code" class="block form-input" @update:modelValue="serviceChange(index)">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!scmWcsService.scmService"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
                </v-select>
                <span v-show="scmWcsService.isServiceDuplicate" class="text-yellow-600 pl-1 absolute top-2 right-10" title="Duplicate Service" v-html="icons.ExclamationTriangle"></span>
                </div>
              </td>
              <td class="px-1 py-1">
                <label class="block w-full mt-2 text-sm">
                   <input type="number" placeholder="Quantity" v-model="scmWcsService.quantity" class="form-input" min="1" required :max="scmWcsService?.max_quantity ?? 0"
                   >
                </label>
              </td>
              
              <!-- <td class="px-1 py-1"><input type="text" class="form-input" required v-model.trim="des.value" placeholder="Value" /></td> -->

              <td class="px-1 py-1"> 
                <button type="button" v-if="index == 0" class="bg-green-600 text-white px-3 py-2 rounded-md" @click="addWork"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg></button>
                <button type="button" v-else class="bg-red-600 text-white px-3 py-2 rounded-md" @click="removeWork(index)" ><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg></button>
              </td>
            </tr>
          </tbody>
        </table>
      </fieldset>
    <!-- </div> -->




    <!-- <div class="flex flex-col justify-center w-1/4 md:flex-row md:gap-2">
      <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
      
    </div>
    <div class="flex flex-col justify-center w-1/4 md:flex-row md:gap-2">
      <label class="label-group">
            <span class="label-item-title">CS Ref</span>
            <input
              type="text"
              readonly
              v-model="form.ref_no"
              required
              class="form-input vms-readonly-input"
              name="ref_no"
              :id="'ref_no'" />
        </label>
    </div>
    <div class="input-group">
      <label class="label-group">
          <span class="label-item-title">Date <span class="text-red-500">*</span></span>
          <VueDatePicker v-model="form.effective_date" class="form-input" required auto-apply :enable-time-picker = "false" placeholder="dd-mm-yyyy" format="dd-MM-yyyy" model-type="yyyy-MM-dd"></VueDatePicker>
      </label>
      <label class="label-group">
          <span class="label-item-title">Expire Date <span class="text-red-500">*</span></span>
          <VueDatePicker v-model="form.expire_date" class="form-input" required auto-apply :enable-time-picker = "false" placeholder="dd-mm-yyyy" format="dd-MM-yyyy" model-type="yyyy-MM-dd"></VueDatePicker>
      </label>
        <label class="label-group">
          <span class="label-item-title">Warehouse <span class="text-red-500">*</span></span>
            <v-select :options="warehouses" placeholder="--Choose an option--" :loading="warehouseLoading" v-model="form.scmWarehouse" label="name" class="block form-input" @update:modelValue="warehouseChange">
            <template #search="{attributes, events}">
                <input
                    class="vs__search"
                    :required="!form.scmWarehouse"
                    v-bind="attributes"
                    v-on="events"
                />
            </template>
            </v-select>
        </label>  
    </div>
    <div class="input-group">
        <label class="label-group">
            <span class="label-item-title">Purchase Center <span class="text-red-500">*</span></span>
            <v-select :options="purchase_center" placeholder="--Choose an option--" v-model="form.purchase_center" label="Product Source Type" class="block w-full mt-1 text-xs rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray form-input" @update:modelValue="changePurchaseCenter">
              <template #search="{attributes, events}">
                  <input
                      class="vs__search"
                      :required="!form.purchase_center"
                      v-bind="attributes"
                      v-on="events"
                  />  
              </template>        
            </v-select>
        </label>
        <label class="label-group">
          <span class="label-item-title">Prioity <span class="text-red-500">*</span></span>
            <v-select
              :options="PRIORITY"
              placeholder="--Choose an option--"
              v-model="form.priority"
              label="name"
              class="block form-input">
              <template #search="{attributes, events}">
                  <input
                      class="vs__search"
                      :required="!form.priority"
                      v-bind="attributes"
                      v-on="events"
                      />
              </template>
            </v-select>
        </label>
        <label class="label-group">
          <span class="label-item-title">Required Days <span class="text-red-500">*</span></span>
            <input type="number" v-model="form.required_days" required class="form-input" min=1/>
        </label>
    </div>
  
    <div class="input-group !w-3/4">
      <label class="label-group">
              <RemarksComponet v-model="form.special_instructions" :maxlength="300" :fieldLabel="'Special Instruction'" isRequired="true"></RemarksComponet>
      </label>
    </div>  
    <div id="customDataTable" ref="customDataTableirf" class="!min-w-screen"> 
        <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
          <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Materials <span class="text-red-500">*</span></legend>
          <div class=""> 
          <table class="!w-full">
            <thead>
            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
              <th style="" class="py-3 align-center">PR </th>
              <th style="" class="py-3 align-center">Material Name </th>
              <th style="" class="py-3 align-center">Unit</th>
              <th class="py-3 align-center">Quantity</th>
              <th class="py-3 text-center align-center">Action</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
            <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(scmCsMaterial, index) in form.scmCsMaterials" :key="index">
              <td class="!w-72">
                <v-select :options="filteredPurchaseRequisitions" placeholder="--Choose an option--" :loading="isLoading" v-model="form.scmCsMaterials[index].scmPr" label="ref_no" class="block form-input" @update:modelValue="prChange(index)">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.scmCsMaterials[index].scmPr"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
                </v-select>
              </td>
              <td class="!w-72">
                <v-select :options="materialList[index]" placeholder="--Choose an option--" :loading="isLoading" v-model="form.scmCsMaterials[index].scmMaterial" label="material_name_and_code" class="block form-input" @update:modelValue="materialChange(index)">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.scmCsMaterials[index].scmMaterial"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
                </v-select>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                   <input type="text" readonly v-model="form.scmCsMaterials[index].unit" class="vms-readonly-input form-input">
                 </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                   <input type="number" v-model="form.scmCsMaterials[index].quantity" class="form-input" min="2" required :max="form.scmCsMaterials[index].max_quantity"
                   :class="{'border-2': form.scmCsMaterials[index].quantity > form.scmCsMaterials[index].max_quantity,'border-red-500 bg-red-100': form.scmCsMaterials[index].quantity > form.scmCsMaterials[index].max_quantity}">
                </label>
              </td>
              <td class="px-1 py-1 text-center">
                <button v-if="index!=0" type="button" @click="removeMaterial(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                  </svg>
                </button>
                <button v-else type="button" @click="addMaterial()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg>
                </button>
              </td>
            </tr>
            </tbody>
          </table>
          </div>  
        </fieldset>
      </div> -->
      <ErrorComponent :errors="errors"></ErrorComponent> 
  </template>
  
  
  
  <script setup>
      import { ref, watch, onMounted,watchEffect,computed, toRefs } from 'vue';
      import Error from "../../Error.vue";
      import DropZone from "../../DropZone.vue";
      import useMaterial from "../../../composables/supply-chain/useMaterial.js";
      import useWarehouse from "../../../composables/supply-chain/useWarehouse.js";
      import BusinessUnitInput from "../../input/BusinessUnitInput.vue";
      import DropZoneV2 from '../../DropZoneV2.vue';
      import {useStore} from "vuex";
      import env from '../../../config/env';
      import cloneDeep from 'lodash/cloneDeep';
      import useStoreIssue from '../../../composables/supply-chain/useStoreIssue';
      import useStoreIssueReturn from '../../../composables/supply-chain/useStoreIssueReturn';
      import useMaterialCs from '../../../composables/supply-chain/useMaterialCs';
      import usePurchaseRequisition from '../../../composables/supply-chain/usePurchaseRequisition';
      import RemarksComponet from '../../utils/RemarksComponent.vue';
      import ErrorComponent from "../../utils/ErrorComponent.vue";
      import useWorkRequisition from '../../../composables/supply-chain/useWorkRequisition';
import useWorkCs from '../../../composables/supply-chain/useWorkCs';
import useHeroIcon from '../../../assets/heroIcon';
  
      const { material, materials, getMaterials,searchMaterial } = useMaterial();
      const { warehouses,warehouse,getWarehouses,searchWarehouse ,isLoading:warehouseLoading} = useWarehouse();
      const { filteredStoreIssues, searchStoreIssue , fetchSiWiseMaterials, siWiseMaterials} = useStoreIssue();
      const { getSiWiseSir, filteredStoreIssueReturnLines } = useStoreIssueReturn();
      const { getWrWiseServiceList, wrServiceList, isLoading:iswrServiceListLoading } = useWorkCs();
      const { searchWorkRequisition, filteredWorkRequisitions, isLoading:isWorkRequisitionLoading } = useWorkRequisition();
      const props = defineProps({
        form: { type: Object, required: true },
        errors: { type: [Object, Array], required: false },
        formType: { type: String, required : false },
        page: { required: false, default: {} },
        serviceObj: { type: Object, required: false },
        serviceList: { type: Array, required: false },
      }); 
  
      const icons = useHeroIcon();
      const PRIORITY = ['High', 'Medium', 'Low']
      const form = toRefs(props).form;
      const minEffectiveDate = ref('');
      
     
      const tableScrollWidth = ref(null);
      const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
  
  
      function addMaterial() {
        const clonedObj = cloneDeep(props.materialObj);
        props.form.scmCsMaterials.push(clonedObj);
        props.serviceList.push([]);
      }
  
      function removeMaterial(index){
        props.form.scmCsMaterials.splice(index, 1);
        props.serviceList.splice(index, 1);
      }

      
      function addWork() {
        const clonedObj = cloneDeep(props.serviceObj);
        props.form.scmWcsServices.push(clonedObj);
        props.serviceList.push([]);
      }
  
      function removeWork(index){
        props.form.scmWcsServices.splice(index, 1);
        props.serviceList.splice(index, 1);
      }

      function effectiveDateChange(){
      if (props.form.expire_date < props.form.effective_date) 
        props.form.expire_date = '';

    }


      
  // function fetchStoreIssue(search, loading = false) {
  //     // if (search.length > 0) {
  //     //   loading(true);
  //       searchStoreIssue(search, /*loading,*/ props.form.business_unit);
  //     // }
  //   }
  
  // function setStoreIssueOtherData(datas) {
  //       props.form.scm_si_id = datas.id;
  //       props.form.si_no = datas.ref_no;
  //       props.form.acc_cost_center_id = datas.acc_cost_center_id;
  //       props.form.scm_warehouse_id = datas.scm_warehouse_id;
  //       props.form.scmWarehouse = datas.scmWarehouse;
  //       props.form.scm_warehouse_name = datas.scmWarehouse.name;
  //       props.form.scm_department_id = datas.scm_department_id;
  //       getSiWiseSir(datas.id);    
  // }
  
  function materialChange(index) {
      props.form.scmCsMaterials[index].unit = props.form.scmCsMaterials[index].scmMaterial.unit;
      props.form.scmCsMaterials[index].scm_material_id = props.form.scmCsMaterials[index].scmMaterial.id; 
      props.form.scmCsMaterials[index].pr_composite_key = props.form.scmCsMaterials[index].scmMaterial.pr_composite_key;
      props.form.scmCsMaterials[index].max_quantity = props.form.scmCsMaterials[index].scmMaterial.max_quantity;
  }

  function serviceChange(index){
    props.form.scmWcsServices[index].scm_service_id = props.form.scmWcsServices[index]?.scmService?.id;
    props.form.scmWcsServices[index].wr_composite_key = props.form.scmWcsServices[index]?.scmService?.wr_composite_key;
    props.form.scmWcsServices[index].max_quantity = props.form.scmWcsServices[index]?.scmService?.max_quantity;
  }
  
  function prChange(index) {
    props.form.scmCsMaterials[index].scm_pr_id = props.form.scmCsMaterials[index]?.scmPr?.id;
    getPrWiseMaterialList(props.form.scmCsMaterials[index].scm_pr_id).then((res) => {
      props.materialList[index] = res;
    });
  }
  
  function warehouseChange() {
    props.form.scm_warehouse_id = props.form.scmWarehouse?.id;
    props.form.acc_cost_center_id = props.form.scmWarehouse?.cost_center_id;
  } 

  function wrChange(index) {
    props.form.scmWcsServices[index].scm_wr_id = props.form.scmWcsServices[index]?.scmWr?.id;
    getWrWiseServiceList(props.form.scmWcsServices[index].scm_wr_id).then((res) => {
      props.serviceList[index] = res;
    });
  }

  watch(() => props.form.scmWcsServices, (scmWcsServices) => {
    minEffectiveDate.value = '';
    scmWcsServices?.forEach(scmWcsService => {
      if ((minEffectiveDate.value < scmWcsService?.scmWr?.approved_date || minEffectiveDate.value == '') && scmWcsService?.scmWr) {
        minEffectiveDate.value = scmWcsService?.scmWr?.approved_date;
      }
    });

    if(props.form.effective_date < minEffectiveDate.value)
      props.form.effective_date = '';

}, { deep: true });

  // watch(() => props.form.scmSi, (new Val,oldVal) => {
  //       props.form.scm_si_id = newVal?.id;
  //       props.form.si_no = newVal?.ref_no;
  //       props.form.acc_cost_center_id = newVal?.acc_cost_center_id;
  //       props.form.scm_warehouse_id = newVal?.scm_warehouse_id;
  //       props.form.scmWarehouse = newVal?.scmWarehouse;
  //       props.form.scm_department_id = newVal?.scm_department_id;
  //       filteredStoreIssues.value = []; 
  //       getSiWiseSir(newVal?.id);    
  // });
  // watch(() => props.form.scmDepartment, (value) => {
  //   if (value) {
  //     props.form.scm_department_id = value?.id;
  //   }
  // });
  
  
  // watch(() => props.form.scmWarehouse, (value) => {
  //   if (value) {
  //     props.form.scm_warehouse_name = value?.scmWarehouse?.name;
  //   }
  // });
  
  
  
  // watch(() => props.form.scm_si_id, (value) => {
  //   if (value) {
  
  //   }
  // });
  
  // watchEffect filteredStoreIssueReturnLines
  // watchEffect(() => {
  //   props.form.scmSirLines = filteredStoreIssueReturnLines.value;
  // });
  //watch filteredStoreIssueReturnLines
  // watch(() => filteredStoreIssueReturnLines.value, (newVal, oldVal) => {
  //   props.form.scmSirLines = newVal;
  // });
  // function setMaterialOtherData(datas, index) {
  //       props.form.scmSirLines[index].unit = datas.unit;
  //       props.form.scmSirLines[index].scm_material_id = datas.id;
            
  // }
  
  // const previousLines = ref(cloneDeep(props.form.scmSrLines));
  
  // watch(() => props.form.scmSirLines, (newLines) => {
  //   newLines.forEach((line, index) => {
  //     // const previousLine = previousLines.value[index];
  
  //     if (line.scmMaterial) {
  //       const selectedMaterial = materials.value.find(material => material.id === line.scmMaterial.id);
  //       if (selectedMaterial) {
  //         if ( line.scm_material_id !== selectedMaterial.id
  //         ) {
  //           props.form.scmSirLines[index].unit = selectedMaterial.unit;
  //           props.form.scmSirLines[index].scm_material_id = selectedMaterial.id;
  //         }
  //       }
  //     }
  //   });
  //   // previousLines.value = cloneDeep(newLines);
  // }, { deep: true });
  
  
    //   function fetchMaterials(search, loading) {
    //   loading(true);
    //   searchMaterial(search, loading)
    // }
  
  
    //   function fetchMaterials(search, loading = false) {
    //     // loading(true);
    //     fetchSiWiseMaterials(props.form.scm_si_id);
    // }
  
    // watch(() => props.form.scmSi, (newVal, oldVal) => {
    //   fetchMaterials(newVal?.id)
    //   props.form.department_id = newVal?.department_id;
    //   props.form.scm_warehouse_id = newVal?.scm_warehouse_id;
    //   props.form.scmWarehouse = newVal?.scmWarehouse;
    // });
  
    watch(() => props.form.business_unit, (newValue, oldValue) => {
    //  if(newValue !== oldValue && oldValue != ''){
    //   props.form.scm_warehouse_id = '';
    //   props.form.acc_cost_center_id = '';
    //   props.form.scmWarehouse = null;
    //   props.form.scmSi = null;
    //   props.form.scm_si_id = null;
    //   props.form.si_no = null,
    //   props.form.scmDepartment= null,
    //   props.form.scm_department_id = null,
    //   props.form.scmSirLines = [];
    //   filteredStoreIssues.value = [];
    //  } 
      //  fetchStoreIssue('');
  });
  
  function tableWidth() {
    setTimeout(function() {
      const customDataTable = document.getElementById("customDataTable");
  
      if (customDataTable) {
          tableScrollWidth.value = customDataTable.scrollWidth;
        
        }
        
      }, 10000);
  }
  //after mount
  onMounted(() => {
    tableWidth();
    // watch(() => props?.form?.scm_pr_id, (newVal, oldVal) => {
    //   getPrWiseMaterialList(props.form.scm_pr_id);
    //   });
  });
  
  onMounted(() => {
    watchEffect(() => {
      searchWorkRequisition(props.form.business_unit, props.form.scm_warehouse_id,props.form.purchase_center, null)
      // fetchWarehouse('');
    });
    
    watchEffect(() => {
      // searchWorkRequisition(props.form.business_unit, props.form.scm_warehouse_id,props.form.purchase_center, null)
      fetchWarehouse('');
    });

    if(props.page == 'edit'){
    const watchBusinessUnit = watch(() => props.form, (newVal, oldVal) => {
      newVal.scmWcsServices.forEach((service, index) => {
        props.serviceList.push([]);
          getWrWiseServiceList(service.scm_wr_id, props.form.id).then((res) => {
            props.serviceList[index] = res;
          });
      });
      watchBusinessUnit();
    });
  }

  });
  
  
  function changePurchaseCenter() {
    // searchPurchaseRequisition(props.form.business_unit, props.form.scm_warehouse_id,props.form.purchase_center, null)
  }
  function fetchWarehouse(search) {

      searchWarehouse(search, props.form.business_unit);
    }
  const purchase_center = ['Local', 'Foreign', 'Plant'];
  
  </script>
  
  
  
  
  
  <style lang="postcss" scoped>
      .input-group {
          @apply flex flex-col justify-center w-full md:flex-row md:gap-2;
      }
      .label-group {
          @apply block w-full mt-3 text-sm;
      }
      .label-item-title {
          @apply text-gray-700 dark-disabled:text-gray-300 text-sm;
      }
      .label-item-input {
          @apply block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark-disabled:disabled:bg-gray-900;
      }
      .form-input {
          @apply block mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray;
      }
      .vs__selected{
      display: none !important;
      }
      .required-style{
          @apply text-red-400 font-semibold
      }
  
   
  
  </style>