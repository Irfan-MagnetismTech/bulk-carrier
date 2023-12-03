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
                @option:selected="setStoreIssueOtherData(form.scmSi)"
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
            :value="form?.scmWarehouse?.name"
            required
            class="form-input vms-readonly-input"
            name="scmwarehouse_name"
            :id="'scm_warehouse_id'" />
         
      </label>
      <label class="label-group">
        <span class="label-item-title">Issue To <span class="text-red-500">*</span></span>
          <input type="text" readonly :value="DEPARTMENTS[form.department_id]" required class="form-input vms-readonly-input" name="scm_department_id" :id="'scm_department_id'" />
          <!-- <Error v-if="errors?.scm_department_id" :errors="errors.scm_department_id" /> -->
      </label>
     
  </div>
  

  <div class="input-group !w-3/4">
    <label class="label-group">
          <span class="label-item-title">Remarks <span class="text-red-500">*</span></span>
          <textarea
            v-model="form.remarks"
            class="block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray form-input"></textarea>
          <Error
            v-if="errors?.remarks"
            :errors="errors.remarks" />
    </label>
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
    const { filteredStoreIssues, searchStoreIssue , fetchSiWiseMaterials, siWiseMaterials} = useStoreIssue();
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



function fetchStoreIssue(search, loading = false) {
    // if (search.length > 0) {
    //   loading(true);
      searchStoreIssue(search, /*loading,*/ props.form.business_unit);
    // }
  }

function setStoreIssueOtherData(datas) {
      props.form.scm_si_id = datas.id;
      props.form.si_no = datas.ref_no;
      props.form.acc_cost_center_id = datas.acc_cost_center_id;
      props.form.scm_warehouse_id = datas.scm_warehouse_id;
      props.form.scmWarehouse = datas.scmWarehouse;
      props.form.scm_warehouse_name = datas.scmWarehouse.name;
      props.form.scm_department_id = datas.scm_department_id;
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


  //   function fetchMaterials(search, loading) {
  //   loading(true);
  //   searchMaterial(search, loading)
  // }


    function fetchMaterials(search, loading = false) {
      // loading(true);
      fetchSiWiseMaterials(props.form.scm_si_id);
  }

  watch(() => props.form.scmSi, (newVal, oldVal) => {
    fetchMaterials(newVal?.id)
    props.form.department_id = newVal?.department_id;
    props.form.scm_warehouse_id = newVal?.scm_warehouse_id;
    props.form.scmWarehouse = newVal?.scmWarehouse;
  });

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
     fetchStoreIssue('');
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

const DEPARTMENTS = ['N/A','Store Department', 'Engine Department', 'Provision Department'];
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