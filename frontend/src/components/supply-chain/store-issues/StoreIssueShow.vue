<template>

  <!-- Basic information -->
  <div class="flex flex-col justify-center w-1/4 md:flex-row md:gap-2">
    <!-- <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input> -->
    <input
      type="text"
      readonly
      v-model="form.business_unit"
      required
      class="form-input vms-readonly-input"
      name="business_unit"
      :id="'business_unit'" />
  </div>
  <div class="flex flex-col justify-center w-1/4 md:flex-row md:gap-2">
    <label class="label-group">
          <span class="label-item-title">SI Ref</span>
            <span class="show-block">{{ form.ref_no }}</span>
      </label>
  </div>
  <div class="input-group">
      <label class="label-group">
          <span class="label-item-title">SR Ref</span>
          <span class="show-block">{{ form.scmSr?.ref_no }}</span>
      </label>
      <label class="label-group">
        <span class="label-item-title">Warehouse </span>
        <span class="show-block">{{ form.scmWarehouse.name }}</span>
      </label>
      <label class="label-group">
        <span class="label-item-title">Issue To </span>
        <span class="show-block">{{ DEPARTMENTS[form.department_id] }}</span>
      </label>
      <label class="label-group">
          <span class="label-item-title">Date</span>
          <span class="show-block">{{ form.date ? moment(form.date).format('DD-MM-YYYY') : null }}</span>
      </label>
  </div>
  

  <div class="input-group !w-3/4">
    <label class="label-group">
      <span class="label-item-title">Remarks</span>
       <span class="show-block">{{ form.remarks }}</span>
    </label>
  </div>


  <div id="">

    <div id="">
    <div class="table-responsive min-w-screen">
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400 min-w-screen">
        <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Materials </legend>
        <table class="whitespace-no-wrap w-full">
          <thead>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
            <th class="py-3 align-center">Material Name </th>
            <th class="py-3 align-center">Unit</th>
            <th class="py-3 align-center">Sr Quantity</th>
            <th class="py-3 align-center">Current Stock</th>
            <th class="py-3 align-center">Qty</th>
          </tr>
          </thead>

          <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
          <tr
            class="text-gray-700 dark-disabled:text-gray-400"
            v-for="(scmSiLine, index) in form.scmSiLines"
            :key="index">
            <td class="!w-72">
            <span class="show-block">{{ form.scmSiLines[index].scmMaterial.name }}</span>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                   <span class="show-block">{{ form.scmSiLines[index].unit }}</span>
               </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                   <span class="show-block">{{ form.scmSiLines[index].sr_quantity }}</span>
               </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                   <span class="show-block">{{ form.scmSiLines[index].current_stock }}</span>
               </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                   <span class="show-block">{{ form.scmSiLines[index].quantity }}</span>
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
    import useStoreRequisition from '../../../composables/supply-chain/useStoreRequisition';
    import useStockLedger from '../../../composables/supply-chain/useStockLedger';
    import ErrorComponent from "../../utils/ErrorComponent.vue";
    import RemarksComponet from '../../utils/RemarksComponent.vue';
    import moment from 'moment';
    
    const { material, materials, getMaterials,searchMaterial } = useMaterial();
    const { warehouses,warehouse,getWarehouses,searchWarehouse } = useWarehouse();
    const { srWiseMaterials , fetchSrWiseMaterials } = useStoreRequisition();
    const { getMaterialWiseCurrentStock,CurrentStock} = useStockLedger();
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
    function addMaterial() {
      const clonedObj = cloneDeep(props.materialObject);
      props.form.scmSiLines.push(clonedObj);
    }

    function removeMaterial(index){
      props.form.scmSiLines.splice(index, 1);
    }

    // function setMaterialOtherData(index){
      // let material = materials.value.find((material) => material.id === props.form.materials[index].material_id);
      // props.form.materials[index].unit = material.unit;
      // props.form.materials[index].material_category_id = material.category.id;
      // props.form.materials[index].material_category_name = material.category.name;
      
    // }


 

    const tableScrollWidth = ref(null);
    const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;



    function fetchWarehouse(search, loading=false) {
    // loading(true);
    searchWarehouse(search, loading,props.form.business_unit);
  }

  watch(() => props.form.scmWarehouse, (value) => {
        props.form.scm_warehouse_id = value?.id;
        props.form.acc_cost_center_id = value?.acc_cost_center_id;
    });

function setMaterialOtherData(datas, index) {
      props.form.scmSiLines[index].unit = datas.unit;
      props.form.scmSiLines[index].scm_material_id = datas.id;
      props.form.scmSiLines[index].max_quantity = datas.max_quantity;
      getMaterialWiseCurrentStock(datas.id,props.form.scm_warehouse_id).then(() => {
       props.form.scmSiLines[index].current_stock = CurrentStock ?? 0;
    });
}


function oomudueupdate(){
  console.log('mudueupdate');
}

function unsetMaterialOtherData(index) {
      props.form.scmSiLines[index].unit = null;
      props.form.scmSiLines[index].scm_material_id = null;
       props.form.scmSiLines[index].current_stock = 0;
}

// const previousLines = ref(cloneDeep(props.form.scmSrLines));

 watch(() => props.form.scmSiLines, (newLines) => {
   const materialArray = [];
   if (newLines && newLines.length) {
    newLines.forEach((line, index) => {
        let material_key = line.scm_material_id;
        if (materialArray.indexOf(material_key) === -1) {
          materialArray.push(material_key);
        } else {
          alert("Duplicate Material Found");
          props.form.scmSiLines.splice(index, 1);
        } 
   });
  }
   
 }, { deep: true });


    function fetchMaterials(search, loading=false) {
    // loading(true);
    searchMaterial(search, loading)
  }


//watch scmSr changes
watch(() => props.form.scmSr, (newValue, oldValue) => {
      if (props.formType == 'edit') { 
        fetchSrWiseMaterials(props.form.scm_sr_id,props.form.id);
      } else {
        
       fetchSrWiseMaterials(props.form.scm_sr_id);
      }
    });


  watch(() => props.form.business_unit, (newValue, oldValue) => {

   if(newValue !== oldValue && oldValue != ''){
    props.form.scm_warehouse_id = '';
    props.form.acc_cost_center_id = '';
    props.form.scmWarehouse = null;
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