<template>

  <!-- Basic information -->
  <div class="flex flex-col justify-center w-1/4 md:flex-row md:gap-2">
    <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
  </div>
  <div class="flex flex-col justify-center w-1/4 md:flex-row md:gap-2">
 
    <label class="label-group">
          <span class="label-item-title">SIR Ref<span class="text-red-500">*</span></span>
          <input
            type="text"
            readonly
            v-model="form.ref_no"
            required
            class="form-input vms-readonly-input"
            name="ref_no"
            :id="'ref_no'" />
         <Error
            v-if="errors?.ref_no"
            :errors="errors.ref_no" />
      </label>
  </div>
  <div class="input-group">
    <label class="label-group">
        <span class="label-item-title">Date<span class="text-red-500">*</span></span>
        <input
          type="date"
          v-model="form.date"
          required
          class="form-input"
          name="date"
          :id="'date'" />
        <Error
          v-if="errors?.date"
          :errors="errors.date" />
    </label>
      <label class="label-group">
          <span class="label-item-title">SI Ref<span class="text-red-500">*</span></span>
            <v-select
                :options="filteredStoreIssues"
                placeholder="--Choose an option--"
                @search="fetchStoreIssue"
                @change="setStoreIssueOtherData(form.scmSi)"
                v-model="form.scmSi"
                label="ref_no"
                class="block form-input">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.scmSi"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
          <Error
            v-if="errors?.scm_si_id"
            :errors="errors.scm_si_id" />
      </label>
      <label class="label-group">
        <span class="label-item-title">Warehouse <span class="text-red-500">*</span></span>
          <input
            type="text"
            readonly
            v-model="form.scm_warehouse_name"
            required
            class="form-input vms-readonly-input"
            name="scmwarehouse_name"
            :id="'scm_warehouse_id'" />
          <Error
            v-if="errors?.scm_warehouse_id"
            :errors="errors.scm_warehouse_id" />
      </label>
      <label class="label-group">
        <span class="label-item-title">Issue To <span class="text-red-500">*</span></span>
          <input type="text" readonly v-model="form.scm_department_id" required class="form-input vms-readonly-input" name="scm_department_id" :id="'scm_department_id'" />
          <Error v-if="errors?.scm_department_id" :errors="errors.scm_department_id" />
      </label>
     
  </div>
  

  <div class="input-group !w-3/4">
    <label class="label-group">
          <span class="label-item-title">Remarks <span class="text-red-500">*</span></span>
          <textarea
            v-model="form.remarks"
            class="block w-full mt-1 text-sm rounded   focus:border-purple-400 focus:outline-none focus:shadow-outline-purple  form-input"></textarea>
          <Error
            v-if="errors?.remarks"
            :errors="errors.remarks" />
    </label>
  </div>


  <div id="">

    <div id="">
    <div class="table-responsive min-w-screen">
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded ">
        <legend class="px-2 text-gray-700 ">Materials <span class="text-red-500">*</span></legend>
        <table class="whitespace-no-wrap">
          <thead>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50  ">
            <th class="py-3 align-center">Material Name </th>
            <th class="py-3 align-center">Unit</th>
            <th class="py-3 align-center">Qty</th>
            <th class="py-3 align-center">Note</th>
            <th class="py-3 text-center align-center">Action</th>
          </tr>
          </thead>

          <tbody class="bg-white divide-y ">
          <tr
            class="text-gray-700 "
            v-for="(scmSirLine, index) in form.scmSirLines"
            :key="index">
            <td class="!w-72">
              <v-select
                :options="materials"
                placeholder="--Choose an option--"
                @search="fetchMaterials"
                v-model="form.scmSirLines[index].scmMaterial"
                label="material_name_and_code"
                class="block form-input"
                @change="setMaterialOtherData(form.scmSirLines[index].scmMaterial,index)">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.scmSirLines[index].scmMaterial"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <input
                   type="text"
                   readonly
                   v-model="form.scmSirLines[index].unit"
                   class="vms-readonly-input form-input">
               </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <input
                   type="text"
                   v-model="form.scmSirLines[index].quantity"
                   class="form-input">
              </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <input
                   type="text"
                   v-model="form.scmSirLines[index].notes"
                   class="form-input">
              </label>
            </td>
            <td class="px-1 py-1 text-center">
             <button
                v-if="index!=0"
                type="button"
                @click="removeMaterial(index)"
                class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg>
              </button>
             <button
                v-else
                type="button"
                @click="addMaterial()"
                class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
              </button>
            </td>
          </tr>
          </tbody>
        </table>
      </fieldset>
    </div>
    </div>
  </div>


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
    
    const { material, materials, getMaterials,searchMaterial } = useMaterial();
    const { warehouses,warehouse,getWarehouses,searchWarehouse } = useWarehouse();
    const { filteredStoreIssues, searchStoreIssue } = useStoreIssue();
    const { getSiWiseSir, filteredStoreIssueReturnLines } = useStoreIssueReturn();
    
    const props = defineProps({
      form: { type: Object, required: true },
      errors: { type: [Object, Array], required: false },
      formType: { type: String, required : false },
      materialObject: { type: Object, required: false },
      page: {
      required: false,
      default: {}
    },

    }); 
    const form = toRefs(props).form;
    
    function addMaterial() {
      const clonedObj = cloneDeep(props.materialObject);
      props.form.scmSirLines.push(clonedObj);
    }

    function removeMaterial(index){
      props.form.scmSirLines.splice(index, 1);
    }

    const tableScrollWidth = ref(null);
    const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;



function fetchStoreIssue(search, loading) {
    if (search.length > 0) {
      loading(true);
      searchStoreIssue(search, loading, props.form.business_unit);
    }
  }

function setStoreIssueOtherData(datas) {
      props.form.scm_si_id = datas.id;
      props.form.si_no = datas.ref_no;
      props.form.acc_cost_center_id = datas.acc_cost_center_id;
      props.form.scm_warehouse_id = datas.scm_warehouse_id;
      props.form.scmWarehouse = datas.scmWarehouse;
      props.form.scm_department_id = datas.scm_department_id;
      filteredStoreIssues.value = []; 
      console.log(datas);
      getSiWiseSir(datas.id);    
}



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

watch(() => props.form.scmWarehouse, (value) => {
  if (value) {
    props.form.scm_warehouse_name = value?.scmWarehouse?.name;
  }
});



// watch(() => props.form.scm_si_id, (value) => {
//   if (value) {

//   }
// });

// watchEffect filteredStoreIssueReturnLines
// watchEffect(() => {
//   props.form.scmSirLines = filteredStoreIssueReturnLines.value;
// });
//watch filteredStoreIssueReturnLines
watch(() => filteredStoreIssueReturnLines.value, (newVal, oldVal) => {
  props.form.scmSirLines = newVal;
});
function setMaterialOtherData(datas, index) {
      props.form.scmSirLines[index].unit = datas.unit;
      props.form.scmSirLines[index].scm_material_id = datas.id;
}

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


    function fetchMaterials(search, loading) {
    loading(true);
    searchMaterial(search, loading)
  }


  watch(() => props.form.business_unit, (newValue, oldValue) => {
   if(newValue !== oldValue && oldValue != ''){
    props.form.scm_warehouse_id = '';
    props.form.acc_cost_center_id = '';
    props.form.scmWarehouse = null;
    props.form.scmSi = null;
    props.form.scm_si_id = null;
    props.form.si_no = null,
    props.form.scmDepartment= null,
    props.form.scm_department_id = null,
    props.form.scmSirLines = [];
    filteredStoreIssues.value = [];
  }
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
});
</script>





<style lang="postcss" scoped>
    .input-group {
        @apply flex flex-col justify-center w-full md:flex-row md:gap-2;
    }
    .label-group {
        @apply block w-full mt-3 text-sm;
    }
    .label-item-title {
        @apply text-gray-700  text-sm;
    }
    .label-item-input {
        @apply block w-full mt-1 text-sm rounded   focus:border-purple-400 focus:outline-none focus:shadow-outline-purple  disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed ;
    }
    .form-input {
        @apply block mt-1 text-sm rounded   focus:border-purple-400 focus:outline-none focus:shadow-outline-purple ;
    }
    .vs__selected{
    display: none !important;
    }
    .required-style{
        @apply text-red-400 font-semibold
    }

 

</style>