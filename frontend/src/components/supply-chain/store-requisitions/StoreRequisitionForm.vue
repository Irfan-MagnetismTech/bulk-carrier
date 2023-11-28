<template>

  <!-- Basic information -->
  <div class="flex flex-col justify-center w-1/4 md:flex-row md:gap-2">
    <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
  </div>
  <div class="input-group">
      <label class="label-group">
          <span class="label-item-title">SR Ref<span class="text-red-500">*</span></span>
          <input type="text" readonly v-model="form.ref_no" required class="form-input vms-readonly-input" name="ref_no" :id="'ref_no'" />
          <!-- <Error v-if="errors?.ref_no" :errors="errors.ref_no"  /> -->
      </label>
      <label class="label-group">
        <span class="label-item-title">Warehouse <span class="text-red-500">*</span></span>
          <!-- <v-select :options="warehouses" placeholder="--Choose an option--" @search="fetchWarehouse"  v-model="form.scmWarehouse" label="name" class="block form-input"> -->
          <v-select :options="warehouses" placeholder="--Choose an option--" v-model="form.scmWarehouse" label="name" class="block form-input">
          <template #search="{attributes, events}">
              <input
                  class="vs__search"
                  :required="!form.scmWarehouse"
                  v-bind="attributes"
                  v-on="events"
              />
          </template>
          </v-select>
          <!-- <Error v-if="errors?.unit" :errors="errors.unit" /> -->
      </label>
      <label class="label-group">
        <span class="label-item-title">Department <span class="text-red-500">*</span></span>
         <v-select :options="DEPARTMENTS" placeholder="--Choose an option--" v-model="form.department_id" label="name" class="block form-input" :reduce="DEPARTMENTS => DEPARTMENTS.id">
          <template #search="{attributes, events}">
              <input
                  class="vs__search"
                  :required="!form.department_id"
                  v-bind="attributes"
                  v-on="events"
              />
          </template>
          </v-select>
          <!-- <select class="form-input" v-model.trim="form.department_id" autocomplete="off" required >
            <option value="" disabled selected>Select</option>
            <option value="1">Store Department</option>
            <option value="2">Engine Department</option>
            <option value="3">Provision Department</option>
          </select> -->
          <!-- <Error v-if="errors?.unit" :errors="errors.unit" /> -->
      </label>
      <label class="label-group">
          <span class="label-item-title">Date<span class="text-red-500">*</span></span>
          <input type="date" v-model="form.date" required class="form-input" name="date" :id="'date'" />
          <!-- <Error v-if="errors?.date" :errors="errors.date"  /> -->
      </label>
  </div>

  <div class="input-group !w-3/4">
    <label class="label-group">
          <span class="label-item-title">Remarks </span>
          <textarea v-model="form.remarks" class="block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray form-input"></textarea>
          <!-- <Error v-if="errors?.remarks" :errors="errors.remarks" /> -->
    </label>
  </div>


  <div id="">

    <div id="">
    <div class="table-responsive min-w-screen">
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
        <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Materials <span class="text-red-500">*</span></legend>
        <table class="whitespace-no-wrap">
          <thead>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
            <th class="py-3 align-center">Material Name </th>
            <th class="py-3 align-center">Unit</th>
            <th class="py-3 align-center">Specification</th>
            <th class="py-3 align-center">Qty</th>
            <th class="py-3 text-center align-center">Action</th>
          </tr>
          </thead>

          <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
          <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(scmSrLine, index) in form.scmSrLines" :key="index">
            <td class="!w-72">
              <!-- <v-select :options="materials" placeholder="--Choose an option--" @search="fetchMaterials" v-model="form.scmSrLines[index].scmMaterial" label="material_name_and_code" class="block form-input" @change="setMaterialOtherData(form.scmSrLines[index].scmMaterial,index)"> -->
              <v-select :options="materials" placeholder="--Choose an option--" v-model="form.scmSrLines[index].scmMaterial" label="material_name_and_code" class="block form-input">
                <template #search="{attributes, events}">
                    <input
                        class="vs__search"
                        :required="!form.scmSrLines[index].scmMaterial"
                        v-bind="attributes"
                        v-on="events"
                        />
                </template>
            </v-select>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <input type="text" readonly v-model="form.scmSrLines[index].unit" class="vms-readonly-input form-input">
               </label>
              
            </td>
           
            <td>
              <label class="block w-full mt-2 text-sm">
                 <input type="text" v-model="form.scmSrLines[index].specification" class="form-input">
               </label>
              
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <input type="number" v-model="form.scmSrLines[index].quantity" class="form-input" min="1" required>
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
      </fieldset>
    </div>
    </div>
  </div>
  <ErrorComponent :errors="errors"></ErrorComponent>  

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
    
    const { materials, searchMaterial } = useMaterial();
    const { warehouses, searchWarehouse } = useWarehouse();
   


    const props = defineProps({
      form: { type: Object, required: true },
      errors: { type: [Object, Array], required: false },
      formType: { type: String, required : false },
      materialObject: { type: Object, required: false },
      page: {required: false, default: {} },

    });

const DEPARTMENTS = [
  {
    'id': 1,
    'name': 'Store Department'
  },
  {
    'id': 2,
    'name': 'Engine Department'
  },
  {
    'id': 3,
    'name': 'Provision Department'
  }
]
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
  fetchMaterials("");
  watchPostEffect(() => {
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