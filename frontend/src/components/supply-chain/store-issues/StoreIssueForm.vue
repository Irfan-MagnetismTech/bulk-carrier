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
  <div class="flex flex-col justify-center w-1/4 md:flex-row md:gap-2" v-if="formType == 'edit'">
    <label class="label-group">
          <span class="label-item-title">SI Ref</span>
          <input
            type="text"
            readonly
            v-model="form.ref_no"
            required
            class="form-input vms-readonly-input"
            name="ref_no"
            :id="'ref_no'" />
         <!-- <Error
            v-if="errors?.ref_no"
            :errors="errors.ref_no" /> -->
      </label>
  </div>
  <div class="input-group">
      <label class="label-group">
          <span class="label-item-title">SR Ref<span class="text-red-500">*</span></span>
          <input
            type="text"
            readonly
            :value="form.scmSr?.ref_no"
            required
            class="form-input vms-readonly-input"
            name="sr_no"
            :id="'sr_no'" />
          <!-- <Error
            v-if="errors?.sr_no"
            :errors="errors.sr_no" /> -->
      </label>
      <label class="label-group">
        <span class="label-item-title">Warehouse <span class="text-red-500">*</span></span>
          <!-- <v-select :options="warehouses" placeholder="--Choose an option--" @search="fetchWarehouse"  v-model="form.scmWarehouse" label="name" class="block form-input">
          <template #search="{attributes, events}">
              <input
                  class="vs__search"
                  :required="!form.scmWarehouse"
                  v-bind="attributes"
                  v-on="events"
              />
          </template>
          </v-select> -->
          <input
            type="text"
            readonly
            v-model="form.scmWarehouse.name"
            required
            class="form-input vms-readonly-input"
            name="scmwarehouse_name"
            :id="'scmwarehouse_name'" />
          <!-- <Error
            v-if="errors?.scmwarehouse_name"
            :errors="errors.scmwarehouse_name" /> -->
      </label>
      <label class="label-group">
        <span class="label-item-title">Issue To <span class="text-red-500">*</span></span>
          <!-- <v-select :options="warehouses" placeholder="--Choose an option--" @search="fetchWarehouse"  v-model="form.scmWarehouse" label="name" class="block form-input">
          <template #search="{attributes, events}">
              <input
                  class="vs__search"
                  :required="!form.scmWarehouse"
                  v-bind="attributes"
                  v-on="events"
              />
          </template>
          </v-select> -->
          <input type="text" readonly :value="DEPARTMENTS[form.department_id]" required class="form-input vms-readonly-input" name="scm_department_id" :id="'scm_department_id'" />
          <!-- <Error v-if="errors?.scm_department_id" :errors="errors.scm_department_id" /> -->
      </label>
      <label class="label-group">
          <span class="label-item-title">Date<span class="text-red-500">*</span></span>
          <VueDatePicker v-model="form.date" class="form-input" required auto-apply :enable-time-picker = "false" placeholder="dd-mm-yyyy" format="dd-MM-yyyy" model-type="yyyy-MM-dd"></VueDatePicker>
          <!-- <Error
            v-if="errors?.date"
            :errors="errors.date" /> -->
      </label>
  </div>
  

  <div class="input-group !w-3/4">
    <label class="label-group">
       <RemarksComponet v-model="form.remarks" :maxlength="300" :fieldLabel="'Remarks'"></RemarksComponet>
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
            <th class="py-3 align-center">Sr Quantity</th>
            <th class="py-3 align-center">Current Stock</th>
            <th class="py-3 align-center">Remaining Quantity</th>
            <th class="py-3 align-center">Qty</th>
            <th class="py-3 text-center align-center">Action</th>
          </tr>
          </thead>

          <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
          <tr
            class="text-gray-700 dark-disabled:text-gray-400"
            v-for="(scmSiLine, index) in form.scmSiLines"
            :key="index">
            <td class="!w-72">
              <v-select
                :options="srWiseMaterials"
                placeholder="--Choose an option--"
                v-model="form.scmSiLines[index].scmMaterial"
                label="material_name_and_code"
                class="block form-input"
                @update:modelValue="setMaterialOtherData(form.scmSiLines[index].scmMaterial,index)"
                >
                <template #search="{attributes, events}">
                    <input
                        class="vs__search"
                        :required="!form.scmSiLines[index].scmMaterial"
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
                   v-model="form.scmSiLines[index].unit"
                   class="vms-readonly-input form-input">
               </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                <input
                   type="text" readonly
                   v-model="form.scmSiLines[index].sr_quantity"
                   class="vms-readonly-input form-input">
               </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                <input
                   type="text" readonly
                   v-model="form.scmSiLines[index].current_stock"
                   class="vms-readonly-input form-input">
               </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                <input
                   type="text" readonly
                   v-model="form.scmSiLines[index].remaining_quantity"
                   class="vms-readonly-input form-input">
               </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <input
                   type="number"
                   v-model="form.scmSiLines[index].quantity"
                   :max="form.scmSiLines[index].max_quantity"
                   min="1"
                   class="form-input"
                   :class="{'border-2': form.scmSiLines[index].quantity > form.scmSiLines[index].max_quantity,'border-red-500 bg-red-100': form.scmSiLines[index].quantity > form.scmSiLines[index].max_quantity}"
                   >
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

  <ErrorComponent :errors="errors"></ErrorComponent>  
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
      props.form.scmSiLines[index].remaining_quantity = datas.remaining_quantity;
      props.form.scmSiLines[index].sr_quantity = datas.sr_quantity;
      props.form.scmSiLines[index].sr_composite_key = datas.sr_composite_key;
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