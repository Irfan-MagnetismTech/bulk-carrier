<template>

  <!-- Basic information -->
  <div class="flex flex-col justify-center w-1/4 md:flex-row md:gap-2">
    <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
  </div>
  <div class="input-group !w-1/4">
      <label class="label-group">
          <span class="label-item-title">MO Ref<span class="text-red-500">*</span></span>
          <span class="show-block">{{ form.ref_no }}</span>
      </label>
  </div>
  <div class="input-group">
    <label class="label-group">
        <span class="label-item-title">MR No <span class="text-red-500">*</span></span>
        <span class="show-block">{{ form.scmMmr.ref_no }}</span>
      </label>
      <label class="label-group">
        <span class="label-item-title">From Warehouse <span class="text-red-500">*</span></span>
        <span class="show-block">{{ form.from_warehouse_name }}</span>
      </label>
      <label class="label-group">
        <span class="label-item-title">To Warehouse <span class="text-red-500">*</span></span>
        <span class="show-block">{{ form.to_warehouse_name }}</span>
      </label>
      <label class="label-group">
          <span class="label-item-title">Transfer Date<span class="text-red-500">*</span></span>
          <span class="show-block">{{ form.date }}</span>
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
            <th class="py-3 align-center">MR Quantity</th>
            <th class="py-3 align-center">Qty</th>
          </tr>
          </thead>

          <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
          <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(scmMoLine, index) in form.scmMoLines" :key="index">
            <td class="!w-72">
            <span class="show-block">{{ form.scmMoLines[index].scmMaterial.material_name_and_code }}</span>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <span class="show-block">{{ form.scmMoLines[index].unit }}</span>
               </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <span class="show-block">{{ form.scmMoLines[index].mmr_quantity }}</span>
               </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <span class="show-block">{{ form.scmMoLines[index].quantity }}</span>
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
import useMovementOut from '../../../composables/supply-chain/useMovementOut';
    
    const { material, materials, getMaterials,searchMaterial } = useMaterial();
    const { warehouses, warehouse, getWarehouses, searchWarehouse } = useWarehouse();
    const { getFromAndToWarehouseWiseCurrentStock, stockData } = useStockLedger();

    const { filteredMovementRequisitions, searchMovementRequisition ,mmrWiseMaterials, getMmrWiseMaterials} = useMovementRequisition();
    const { getMmrWiseMoData, filteredMovementRequisitionLines } = useMovementOut();
  
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
      props.form.scmMoLines.push(clonedObj);
    }

    function removeMaterial(index){
      props.form.scmMoLines.splice(index, 1);
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

  function fetchMovementRequisitions(search, loading = false) {
        searchMovementRequisition(search,props.form.business_unit);
    }

  function setMovementRequisitionData(mmr) {
    if (mmr) {
      getMmrWiseMoData(mmr.id);
      props.form.scm_mmr_id = mmr?.id;
      props.form.fromWarehouse = mmr.fromWarehouse;
      props.form.toWarehouse = mmr.toWarehouse;
    }
}
watch(() => props.form.scmMmr, (value) => {
  getMmrWiseMaterials(value?.id);
});


    watch(() => filteredMovementRequisitionLines.value, (newVal, oldVal) => {
      props.form.scmMoLines = newVal;
    });


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
      props.form.scmMoLines[index].unit = datas.unit;
      props.form.scmMoLines[index].scm_material_id = datas.id;
      getFromAndToWarehouseWiseCurrentStock(props.form.from_warehouse_id, props.form.to_warehouse_id, datas.id, index);
      props.form.scmMoLines[index].mmr_quantity = datas.mmr_quantity;
      props.form.scmMoLines[index].quantity = datas.quantity;
    }



watch(() => props.form.scmMoLines, (newLines) => {
  newLines.forEach((line, index) => {
    // const previousLine = previousLines.value[index];

    if (line.scmMaterial) {
      const selectedMaterial = materials.value.find(material => material.id === line.scmMaterial.id);
      if (selectedMaterial) {
        if ( line.scm_material_id !== selectedMaterial.id
        ) {
          props.form.scmMoLines[index].unit = selectedMaterial.unit;
          props.form.scmMoLines[index].scm_material_id = selectedMaterial.id;
        }
      }
    }
  });
  // previousLines.value = cloneDeep(newLines);
}, { deep: true });


// function fetchMaterials(search, loading) {
//   if (search.length > 0) {
//     loading(true);
//     searchMaterial(search, loading)
//   }
//   }


  watch(() => props.form.business_unit, (newValue, oldValue) => {
   if(newValue !== oldValue && oldValue != ''){
     props.form.scmFromWarehouse = null;
     props.form.scmToWarehouse = null;
     props.form.from_warehouse_id = '';
     props.form.from_cost_center_id = '';
     props.form.to_warehouse_id = '';
     props.form.to_cost_center_id = '';
     props.form.scmMmr = null;
     props.form.scmMoLines = [];
     props.form.scm_mmr_id = '';
    }
    fetchMovementRequisitions('');
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