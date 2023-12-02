<template>

  <!-- Basic information -->
  <div class="flex flex-col justify-center w-1/4 md:flex-row md:gap-2">
    <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
  </div>
  <div class="input-group !w-1/4">
      <label class="label-group">
          <span class="label-item-title">MI Ref<span class="text-red-500">*</span></span>
          <input type="text" readonly v-model="form.ref_no" required class="form-input vms-readonly-input" name="ref_no" :id="'ref_no'" />
          <Error v-if="errors?.ref_no" :errors="errors.ref_no"  />
      </label>
  </div>
  <div class="input-group">
    <label class="label-group">
        <span class="label-item-title">MR No <span class="text-red-500">*</span></span>
          <v-select :options="filteredMovementRequisitions" :key="mmrKey" placeholder="-- Search Here --" @option:selected="setMovementRequisitionData(form.scmMmr)" v-model="form.scmMmr" label="ref_no" class="block form-input">
          <template #search="{attributes, events}">
              <input
                  class="vs__search"
                  :required="!form.scmMmr"
                  v-bind="attributes"
                  v-on="events"
              />
          </template>
          </v-select>
         <Error v-if="errors?.unit" :errors="errors.unit" />
      </label>
      <label class="label-group">
        <span class="label-item-title">From Warehouse <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.from_warehouse_name" required class="form-input" name="from_warehouse_name" :id="'delivery_date'" />
         <Error v-if="errors?.from_warehouse_name" :errors="errors.from_warehouse_name" />
      </label>
      <label class="label-group">
        <span class="label-item-title">To Warehouse <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.to_warehouse_name" required class="form-input" name="to_warehouse_name" :id="'to_warehouse_name'" />
          <Error v-if="errors?.to_warehouse_name" :errors="errors.to_warehouse_name" />
      </label>
      <label class="label-group">
        <span class="label-item-title">MO No<span class="text-red-500">*</span></span>
        <!-- <input type="text" v-model="form.mo_no" required class="form-input" name="mo_no" :id="'mo_no'" /> -->
        <v-select :options="filteredMovementOuts" placeholder="-- Search Here --" @option:selected="setMoData(form.scmMo)" v-model="form.scmMo" label="ref_no" class="block form-input">
          <template #search="{attributes, events}">
              <input
                  class="vs__search"
                  :required="!form.scmMo"
                  v-bind="attributes"
                  v-on="events"
              />
          </template>
          </v-select>
          <Error v-if="errors?.mo_no" :errors="errors.mo_no" />
      </label>
  </div>
  <div class="input-group !w-1/4">
    <label class="label-group">
          <span class="label-item-title">Transfer Date<span class="text-red-500">*</span></span>
          <input type="date" v-model="form.date" required class="form-input" name="date" :id="'date'" />
          <Error v-if="errors?.date" :errors="errors.date"  />
      </label>
  </div>

    <div id="">
      <div class="table-responsive min-w-screen">
        <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
          <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Materials <span class="text-red-500">*</span></legend>
          <table class="whitespace-no-wrap">
            <thead>
            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
              <th class="py-3 align-center">Material Name </th>
              <th class="py-3 align-center">Unit</th>
              <th class="py-3 align-center">MR Quantity</th>
              <th class="py-3 align-center">MO Quantity</th>
              <th class="py-3 align-center">Qty</th>
              <th class="py-3 align-center">Remarks</th>
              <th class="py-3 text-center align-center">Action</th>
            </tr>
            </thead>

            <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
            <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(scmMoLine, index) in form.scmMiLines" :key="index">
              <td class="!w-72">
                <v-select :options="materials" placeholder="--Choose an option--" @search="fetchMaterials" v-model="form.scmMiLines[index].scmMaterial" label="material_name_and_code" class="block form-input" @change="setMaterialOtherData(form.scmMiLines[index].scmMaterial,index)">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.scmMiLines[index].scmMaterial"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="text" readonly v-model="form.scmMiLines[index].unit" class="vms-readonly-input form-input">
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="text" v-model="form.scmMiLines[index].mmr_quantity" class="form-input">
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="text" v-model="form.scmMiLines[index].mo_quantity" class="form-input">
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="text" v-model="form.scmMiLines[index].quantity" class="form-input">
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="text" v-model="form.scmMiLines[index].available_stock" class="form-input">
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
  <hr class="w-7"/>
<div class="mt-5" v-if="form.scmMiShortage.scmMiShortageLines.length">
    <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
    <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Shortage <span class="text-red-500">*</span></legend>
    
    <div class="input-group !w-1/2">
        <label class="label-group">
          <span class="label-item-title">Shortage Type<span class="text-red-500">*</span></span>
          <input type="text" v-model="form.scmMiShortage.shortage_type" required class="form-input" name="to_warehouse_name" :id="'to_warehouse_name'" />
            <Error v-if="errors?.to_warehouse_name" :errors="errors.to_warehouse_name" />
        </label>
        <label class="label-group">
          <span class="label-item-title">Assigned To<span class="text-red-500">*</span></span>
          <!-- <input type="text" v-model="form.scmMiShortage.scmWarehouse" required class="form-input" name="mo_no" :id="'mo_no'" /> -->
           <v-select :options="warehouses" placeholder="-- Search Here --" @option:selected="setWarehouseData(form.scmMiShortage.scmWarehouse)" v-model="form.scmMiShortage.scmWarehouse" label="name" class="block form-input">
          <template #search="{attributes, events}">
              <input
                  class="vs__search"
                  :required="!form.scmMiShortage.scmWarehouse"
                  v-bind="attributes"
                  v-on="events"
              />
          </template>
          </v-select>


        </label>
    </div>
    <div class="mt-5">
      <div class="table-responsive min-w-screen">
      <table class="whitespace-no-wrap">
            <thead>
            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
              <th class="py-3 align-center">Material Name </th>
              <th class="py-3 align-center">Unit</th>
              <th class="py-3 align-center">Qty</th>
              <th class="py-3 align-center">Remarks</th>
            </tr>
            </thead>

            <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
            <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(scmMiShortageLine, index) in form.scmMiShortage.scmMiShortageLines" :key="index">
              <td class="!w-72">
                <!-- <v-select :options="materials" placeholder="--Choose an option--" @search="fetchMaterials" v-model="form.scmMiShortage.scmMiShortageLines[index].scmMaterial" label="material_name_and_code" class="block form-input" @change="setMaterialOtherData(form.scmMiShortage.scmMiShortageLines[index].scmMaterial,index)">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.scmMiShortage.scmMiShortageLines[index].scmMaterial"
                          :readonly = "form.scmMiShortage.scmMiShortageLines[index].scmMaterial"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select> -->
                <input type="text" readonly :value="form.scmMiShortage.scmMiShortageLines[index].scmMaterial.name" class="vms-readonly-input form-input">
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="text" readonly v-model="form.scmMiShortage.scmMiShortageLines[index].unit" class="vms-readonly-input form-input">
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="text" readonly v-model="form.scmMiShortage.scmMiShortageLines[index].quantity" class="vms-readonly-input form-input">
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="text" v-model="form.scmMiShortage.scmMiShortageLines[index].available_stock" class="form-input">
                </label>
                
              </td>
            </tr>
            </tbody>
          </table>
    </div>
    </div>
    </fieldset>
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
    import useMovementRequisition from '../../../composables/supply-chain/useMovementRequisition';
    import useMovementIn from '../../../composables/supply-chain/useMovementIn';
    import useMovementOut from '../../../composables/supply-chain/useMovementOut';
    
    const { material, materials, getMaterials,searchMaterial } = useMaterial();
    const { warehouses, warehouse, getWarehouses, searchWarehouse } = useWarehouse();
    const { getFromAndToWarehouseWiseCurrentStock, stockData } = useStockLedger();

    const { filteredMovementRequisitions, searchMovementRequisition } = useMovementRequisition();
    const { filteredMoLines, getMoWiseMiLines } = useMovementIn();
    const { getMmrWiseMo, filteredMovementOuts } = useMovementOut();
  
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

    const mmrKey = ref(0);


    function addMaterial() {
      const clonedObj = cloneDeep(props.materialObject);
      props.form.scmMiLines.push(clonedObj);
    }

    function removeMaterial(index){
      props.form.scmMiLines.splice(index, 1);
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

//   function fetchMovementRequisitions(search, loading) {
//       if (search.length > 0) {
//         loading(true);
//         props.form.scmMiShortage.scmMiShortageLines= [];
//         props.form.scmMiLines = [];
//         searchMovementRequisition(search, loading, props.form.business_unit);
//       }
// }

    function fetchWarehouse(search) {
        searchWarehouse(search, props.form.business_unit);
    }

    function setWarehouseData() {
      if (props.form.scmMiShortage.scmWarehouse) {
        props.form.scmMiShortage.scm_warehouse_id = props.form.scmMiShortage.scmWarehouse.id;
        props.form.scmMiShortage.acc_cost_center_id = props.form.scmMiShortage.scmWarehouse.acc_cost_center_id;
      }
    }

    function fetchMovementRequisitions(search, loading = false) {
            searchMovementRequisition(search,props.form.business_unit);
        }

    function setMovementRequisitionData(mmr) {
      if (mmr) {
       
        getMmrWiseMo(props.form.business_unit, mmr.id);
        props.form.scm_mmr_id = mmr?.id;
        props.form.fromWarehouse = mmr.fromWarehouse;
        props.form.toWarehouse = mmr.toWarehouse;
      }
}

    function setMoData(mo) {
      if (mo) {
        props.form.scm_mo_id = mo?.id;
        getMoWiseMiLines(mo.id);
      }
    }

    watch(() => filteredMoLines.value, (newVal, oldVal) => {
      props.form.scmMiLines = newVal;
    });


    const refreshData = () => {
      // Use Vue Router to go to the current route, triggering a re-render
      router.go(-1);
    };


  // watch(() => props.form.scmMmr, (value) => {
  //       props.form.scm_mmr_id = value?.id;
  //       // props.form.from_warehouse_id = value?.from_warehouse_id;
  //       props.form.from_warehouse_name = value?.fromWarehouse?.name;
  //       // props.form.to_warehouse_id = value?.to_warehouse_id;
  //       props.form.to_warehouse_name = value?.toWarehouse?.name;
  //       // props.form.from_cost_center_id = value?.from_cost_center_id;
  //       // props.form.to_cost_center_id = value?.to_cost_center_id;

        
  //   });


    watch(() => props.form.fromWarehouse, (value) => {
      props.form.from_warehouse_id = value?.id;
      props.form.from_cost_center_id = value?.acc_cost_center_id;
      props.form.from_warehouse_name = value?.name;
    });

  watch(() => props.form.toWarehouse, (value) => {
    props.form.to_warehouse_id = value?.id;
    props.form.to_cost_center_id = value?.acc_cost_center_id;
    props.form.to_warehouse_name = value?.name;
  });
    
 
function setMaterialOtherData(datas, index) {
      props.form.scmMiLines[index].unit = datas.unit;
      props.form.scmMiLines[index].scm_material_id = datas.id;
      // getFromAndToWarehouseWiseCurrentStock(props.form.from_warehouse_id, props.form.to_warehouse_id, datas.id, index);
}



watch(() => props.form.scmMiLines, (newLines) => {
  props.form.scmMiShortage.scmMiShortageLines = [];
  newLines.forEach((line, index) => {
    // const previousLine = previousLines.value[index];
    if (Number(line.quantity) < Number(line.mo_quantity)) {
      props.form.scmMiShortage.scmMiShortageLines.push({
        scm_material_id: line.scm_material_id,
        scmMaterial: line.scmMaterial,
        unit: line.unit,
        quantity: line.mo_quantity - line.quantity,
      
      });
    }
    if (line.scmMaterial) {
      const selectedMaterial = materials.value.find(material => material.id === line.scmMaterial.id);
      if (selectedMaterial) {
        if ( line.scm_material_id !== selectedMaterial.id
        ) {
          props.form.scmMiLines[index].unit = selectedMaterial.unit;
          props.form.scmMiLines[index].scm_material_id = selectedMaterial.id;
        }
      }
    }
  });
  // previousLines.value = cloneDeep(newLines);
}, { deep: true });


function fetchMaterials(search, loading) {
  if (search.length > 0) {
    loading(true);
    searchMaterial(search, loading)
  }
  }


  watch(() => props.form.business_unit, (newValue, oldValue) => {
   if(newValue !== oldValue && oldValue != ''){
     props.form.scmFromWarehouse = null;
     props.form.scmToWarehouse = null;
     props.form.from_warehouse_id = '';
     props.form.from_cost_center_id = '';
     props.form.to_warehouse_id = '';
     props.form.to_cost_center_id = '';
     props.form.scmMmr = null;
     props.form.scmMiLines = [];
     props.form.scm_mmr_id = '';
    }
    fetchMovementRequisitions('');
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