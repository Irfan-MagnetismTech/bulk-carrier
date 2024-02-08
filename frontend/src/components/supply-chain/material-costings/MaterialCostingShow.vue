<template>

  <!-- Basic information -->
  <div class="flex flex-col justify-center w-1/4 md:flex-row md:gap-2">
    <span class="show-block">{{ form.business_unit }}</span>
  </div>
  <div class="input-group">
      <label class="label-group">
          <span class="label-item-title">SR Ref<span class="text-red-500">*</span></span> <span class="show-block">{{ form.ref_no }}</span>
      </label>
      <label class="label-group">
        <span class="label-item-title">Warehouse <span class="text-red-500">*</span></span>
          <span class="show-block">{{ form.scmWarehouse?.name }}</span>
      </label>
      <label class="label-group">
        <span class="label-item-title">Department <span class="text-red-500">*</span></span>
          <span class="show-block">{{ DEPARTMENTS[form.department_id] }}</span>
      </label>
      <label class="label-group">
          <span class="label-item-title">Date<span class="text-red-500">*</span></span>
          <span class="show-block">{{ form.date ? moment(form.date).format('DD-MM-YYYY') : null }}</span>
      </label>
  </div>

  <div class="input-group !w-3/4">
    <label class="label-group">
          <span class="label-item-title">Remarks </span>
          
          <span class="show-block">{{ form.remarks }}</span>
    </label>
  </div>


  <div id="">

    <div id="">
    <div class="table-responsive min-w-screen">
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
        <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Materials <span class="text-red-500">*</span></legend>
        <table class="w-full">
          <thead>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
            <th class="py-3 align-center">Material Name </th>
            <th class="py-3 align-center">Unit</th>
            <th class="py-3 align-center">Specification</th>
            <th class="py-3 align-center">Qty</th>
          </tr>
          </thead>

          <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
          <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(scmSrLine, index) in form.scmSrLines" :key="index">
            <td class="!w-72">
            <span class="show-block">{{ form.scmSrLines[index].scmMaterial?.material_name_and_code }}</span>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <span class="show-block">{{ form.scmSrLines[index].unit }}</span>
               </label>
              
            </td>
           
            <td>
              <label class="block w-full mt-2 text-sm">
                
                 <span class="show-block">{{ form.scmSrLines[index].specification }}</span> 
               </label>
              
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <span class="show-block">{{ form.scmSrLines[index].quantity }}</span>
              </label>
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
    import { ref, watch, onMounted,watchEffect,computed, watchPostEffect } from 'vue';
    import Error from "../../Error.vue";
    import DropZone from "../../DropZone.vue";
    import useMaterial from "../../../composables/supply-chain/useMaterial.js";
    import useWarehouse from "../../../composables/supply-chain/useWarehouse.js";
    import BusinessUnitInput from "../../input/BusinessUnitInput.vue";
    import ErrorComponent from "../../utils/ErrorComponent.vue";
    import DropZoneV2 from '../../DropZoneV2.vue';
    import {useStore} from "vuex";
    import env from '../../../config/env';
    import cloneDeep from 'lodash/cloneDeep';
    import moment from 'moment';
        
    const { materials, searchMaterial,isLoading: materialLoading } = useMaterial();
    const { warehouses, searchWarehouse,isLoading } = useWarehouse();
   


    const props = defineProps({
      form: { type: Object, required: true },
      errors: { type: [Object, Array], required: false },
      formType: { type: String, required : false },
      materialObject: { type: Object, required: false },
      page: {required: false, default: {} },

    });

    const DEPARTMENTS = ['N/A','Store Department', 'Engine Department', 'Provision Department'];
    function addMaterial() {
      const clonedObj = cloneDeep(props.materialObject);
      props.form.scmSrLines.push(clonedObj);
    }

    function removeMaterial(index){
      props.form.scmSrLines.splice(index, 1);
    }

    // function setMaterialOtherData(index){
    //   let material = materials.value.find((material) => material.id === props.form.materials[index].material_id);
    //   props.form.materials[index].unit = material.unit;
    //   props.form.materials[index].material_category_id = material.category.id;
    //   props.form.materials[index].material_category_name = material.category.name;
    // }


 

    const tableScrollWidth = ref(null);
    const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;



  //   function fetchWarehouse(search, loading) {
  //   loading(true);
  //   searchWarehouse(search, loading,props.form.business_unit);
  // }
    function fetchWarehouse(search, loading = false) {
    // loading(true);
    searchWarehouse(search, props.form.business_unit);
  }

  watch(() => props.form.scmWarehouse, (value) => {
        props.form.scm_warehouse_id = value?.id ?? null;
        props.form.acc_cost_center_id = value?.acc_cost_center_id ?? null;
    });

// function setMaterialOtherData(datas, index) {
//       props.form.scmSrLines[index].unit = datas.unit;
//       props.form.scmSrLines[index].scm_material_id = datas.id;
// }

// const previousLines = ref(cloneDeep(props.form.scmSrLines));

watch(() => props.form.scmSrLines, (newLines) => {

  const materialArray = [];
  newLines.forEach((line, index) => {
    // const previousLine = previousLines.value[index];
    let material_key = line.scm_material_id;
    if (materialArray.indexOf(material_key) === -1) {
      materialArray.push(material_key);
    } else {
      alert("Duplicate Material Found");
      props.form.scmSrLines.splice(index, 1);
    } 
    if (line.scmMaterial) {
      const selectedMaterial = materials.value.find(material => material.id === line.scmMaterial.id);
      if (selectedMaterial) {
        if ( line.scm_material_id !== selectedMaterial.id
        ) {
          props.form.scmSrLines[index].unit = selectedMaterial.unit;
          props.form.scmSrLines[index].scm_material_id = selectedMaterial.id;
        }
      }
    }
  });
  // previousLines.value = cloneDeep(newLines);
}, { deep: true });


  //   function fetchMaterials(search, loading) {
  //   loading(true);
  //   searchMaterial(search, loading)
  // }
    function fetchMaterials(search, loading = false) {
    // loading(true);
    searchMaterial(search)
  }


  watch(() => props.form.business_unit, (newValue, oldValue) => {
   if(newValue !== oldValue && oldValue != '' && oldValue != null){
    props.form.scmWarehouse = null;
  }
});

// function tableWidth() {
//   setTimeout(function() {
//     const customDataTable = document.getElementById("customDataTable");

//     if (customDataTable) {
//         tableScrollWidth.value = customDataTable.scrollWidth;

//       }

//     }, 10000);
// }
//after mount
// onMounted(() => {
//   tableWidth();
// });

onMounted(() => {
  console.log(isLoading);
  fetchMaterials("");
  watchPostEffect(() => {
    console.log(isLoading);
    fetchWarehouse("");
  });
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