<template>

  <!-- Basic information -->
  <div class="flex flex-col justify-center w-1/4 md:flex-row md:gap-2">
    <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
  </div>
  <div class="input-group !w-1/4">
      <label class="label-group">
          <span class="label-item-title">Ref No<span class="text-red-500">*</span></span>
          <input type="text" readonly v-model="form.ref_no" required class="form-input vms-readonly-input" name="ref_no" :id="'ref_no'" />
          <Error v-if="errors?.ref_no" :errors="errors.ref_no"  />
      </label>
  </div>
  <div class="input-group">
      <label class="label-group">
          <span class="label-item-title">Date<span class="text-red-500">*</span></span>
          <input type="date" v-model="form.date" required class="form-input" name="date" :id="'date'" />
          <Error v-if="errors?.date" :errors="errors.date"  />
      </label>
      <label class="label-group">
        <span class="label-item-title">Adjustment Type <span class="text-red-500">*</span></span>
        <select v-model="form.type" required class="form-input" name="adjustment_type" :id="'adjustment_type'">
          <option value="">--Choose an option--</option>
          <option value="Addition">Addition</option>
          <option value="Deduction">Deduction</option>
        </select>
      </label>
      <label class="label-group">
        <span class="label-item-title">Warehouse <span class="text-red-500">*</span></span>
          <v-select :options="warehouses" placeholder="-- Search Here --" v-model="form.scmWarehouse" label="name" class="block form-input">
          <template #search="{attributes, events}">
              <input
                  class="vs__search"
                  :required="!form.scmWarehouse"
                  v-bind="attributes"
                  v-on="events"
              />
          </template>
          </v-select>
         <Error v-if="errors?.unit" :errors="errors.unit" />
      </label>
     
     
  </div> 
  <div class="input-group !w-3/4">
    <label class="label-group">
          <span class="label-item-title">Remarks <span class="text-red-500">*</span></span>
          <textarea v-model="form.remarks" class="block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray form-input"></textarea>
          <Error v-if="errors?.remarks" :errors="errors.remarks" />
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
            <th class="py-3 align-center">Rate</th>
            <th class="py-3 align-center">Qty</th>
            <th class="py-3 text-center align-center">Action</th>
          </tr>
          </thead>

          <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
          <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(scmMmrLine, index) in form.scmAdjustmentLines" :key="index">
            <td class="!w-72">
              <v-select :options="materials" placeholder="--Choose an option--" v-model="form.scmAdjustmentLines[index].scmMaterial" label="material_name_and_code" class="block form-input" @update:modelValue="setMaterialOtherData(form.scmAdjustmentLines[index].scmMaterial,index)">
                <template #search="{attributes, events}">
                    <input
                        class="vs__search"
                        :required="!form.scmAdjustmentLines[index].scmMaterial"
                        v-bind="attributes"
                        v-on="events"
                        />
                </template>
            </v-select>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <input type="text" readonly v-model="form.scmAdjustmentLines[index].unit" class="vms-readonly-input form-input">
               </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <input type="text" v-model="form.scmAdjustmentLines[index].rate" class="form-input">
               </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <input type="text" v-model="form.scmAdjustmentLines[index].quantity" class="form-input">
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


</template>



<script setup>
    import { ref, watch, onMounted,watchEffect,computed } from 'vue';
    import Error from "../../Error.vue";
    import DropZone from "../../DropZone.vue";
    import useMaterial from "../../../composables/supply-chain/useMaterial.js";
    import useWarehouse from "../../../composables/supply-chain/useWarehouse.js";
    import BusinessUnitInput from "../../input/BusinessUnitInput.vue";
    import DropZoneV2 from '../../DropZoneV2.vue';
    import {useStore} from "vuex";
    import env from '../../../config/env';
    import cloneDeep from 'lodash/cloneDeep';
    import useStockLedger from '../../../composables/supply-chain/useStockLedger';
    
    const { material, materials, getMaterials,searchMaterial } = useMaterial();
    const { warehouses, warehouse, getWarehouses, searchWarehouse } = useWarehouse();
    const { getFromAndToWarehouseWiseCurrentStock, stockData } = useStockLedger();


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

    const fromWarehouseKey = ref(0);
    const toWarehouseKey = ref(0);


    function addMaterial() {
      const clonedObj = cloneDeep(props.materialObject);
      props.form.scmAdjustmentLines.push(clonedObj);
    }

    function removeMaterial(index){
      props.form.scmAdjustmentLines.splice(index, 1);
    }

    


 

    const tableScrollWidth = ref(null);
    const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;



  //   function fetchWarehouse(search, loading) {
  //   loading(true);
  //   searchWarehouse(search, loading,props.form.business_unit);
  // }

  function fetchWarehouse(search) {
        // loading(true);
        searchWarehouse(search, props.form.business_unit);
    }

watch(() => props.form.scmWarehouse, (value) => {
        props.form.scm_warehouse_id = value?.id;
        props.form.acc_cost_center_id = value?.cost_center_id;
    // warehouses.value = warehouses.value.filter((warehouse) => warehouse.id !== value?.id);
    // warehouses.value = [...warehouses.value, props.form.scmToWarehouse];
    });
 

 
  // watch(() => props.form.scmWarehouse, (value) => {
  //       props.form.scm_warehouse_id = value?.id;
  //       props.form.acc_cost_center_id = value?.acc_cost_center_id;
  //   });

function setMaterialOtherData(datas, index) {
      props.form.scmAdjustmentLines[index].unit = datas.unit;
      props.form.scmAdjustmentLines[index].scm_material_id = datas.id;
}

// watch(() => stockData.value, (newValue) => {
//   console.log(newValue);
//   if (newValue && newValue.index !== undefined && props.form.scmAdjustmentLines[newValue.index]) {
//     props.form.scmAdjustmentLines[newValue.index].available_stock = newValue.from_warehouse_stock;
//     props.form.scmAdjustmentLines[newValue.index].present_stock = newValue.to_warehouse_stock;
//   } else {
//     console.error('Invalid index or stock data:', newValue);
//   }
// });

// const previousLines = ref(cloneDeep(props.form.scmSrLines));

// watch(() => props.form.scmAdjustmentLines, (newLines) => {
//   newLines.forEach((line, index) => {
//     // const previousLine = previousLines.value[index];

//     if (line.scmMaterial) {
//       const selectedMaterial = materials.value.find(material => material.id === line.scmMaterial.id);
//       if (selectedMaterial) {
//         if ( line.scm_material_id !== selectedMaterial.id
//         ) {
//           props.form.scmAdjustmentLines[index].unit = selectedMaterial.unit;
//           props.form.scmAdjustmentLines[index].scm_material_id = selectedMaterial.id;
//         }
//       }
//     }
//   });
//   // previousLines.value = cloneDeep(newLines);
// }, { deep: true });


function fetchMaterials(search) {
    // loading(true);
    searchMaterial(search)
  }


  watch(() => props.form.business_unit, (newValue, oldValue) => {
   if(newValue !== oldValue && oldValue != ''){
    props.form.scm_warehouse_id = '';
    props.form.acc_cost_center_id = '';
     props.form.scmWarehouse = null;
      
    }
    
    fetchWarehouse('');
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
  fetchMaterials('');
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