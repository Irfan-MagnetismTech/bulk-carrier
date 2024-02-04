<script setup>
    import { ref, watch, onMounted,watchEffect,computed } from 'vue';
    import Error from "../../Error.vue";
    import useMaterial from "../../../composables/supply-chain/useMaterial.js";
    import useWarehouse from "../../../composables/supply-chain/useWarehouse.js";
    import BusinessUnitInput from "../../input/BusinessUnitInput.vue";
    import usePurchaseRequisition from '../../../composables/supply-chain/usePurchaseRequisition';
    import useVendor from '../../../composables/supply-chain/useVendor';
    import ErrorComponent from "../../utils/ErrorComponent.vue";
    import cloneDeep from 'lodash/cloneDeep';
    import useBusinessInfo from '../../../composables/useBusinessInfo';
    import usePurchaseOrder from '../../../composables/supply-chain/usePurchaseOrder';
    import RemarksComponet from '../../utils/RemarksComponent.vue';
    import useHeroIcon from '../../../assets/heroIcon';
    import useMaterialReceiptReport from '../../../composables/supply-chain/useMaterialReceiptReport';
    
    const icons = useHeroIcon();
    const { material, materials, getMaterials,searchMaterial } = useMaterial();
    const { warehouses,warehouse,getWarehouses,searchWarehouse ,isLoading:warehouseLoading} = useWarehouse();
    const { vendors, searchVendor,isLoading: vendorLoader } = useVendor();
    const { currencies, getCurrencies } = useBusinessInfo();
    const { getPoMaterialList,getMrrLineData } = useMaterialReceiptReport();
    const { searchPurchaseRequisition, filteredPurchaseRequisitions } = usePurchaseRequisition();
    const {getPoList,polist,isLoading:poloading,getPoWisePrList,prlist,isPrLoading} = useMaterialReceiptReport();

    const props= defineProps({
      form: { type: Object, required: true },
      errors: { type: [Object, Array], required: false },
      formType: { type: String, required : false },
      materialObject: { type: Object, required: false },
      termsObject: { type: Object, required: false },
      page: { required: false, default: {} },
      mrrLineObject: { type: Object, required: false },
      materialList: { type: [Object, Array], required: false },
      poMaterialList: { type: [Object, Array], required: false },
    });

    
    function addMaterial(index) {
      const clonedObj = cloneDeep(props.materialObject);
      props.form.scmMrrLines[index].scmMrrItems.push(clonedObj);
    }

    function removeMaterial(index,itemIndex){
      props.form.scmMrrLines[index].scmMrrItems.splice(itemIndex, 1);
    }

    function addBlock() {
      let lineLength = props.form.scmMrrLines.length;
      const clonedObj = cloneDeep(props.mrrLineObject);
      props.form.scmMrrLines.push(clonedObj);
      props.materialList.push([]);
    }

    function removeBlock(index) {
      props.form.scmMrrLines.splice(index, 1);
    }

    

    function changePr(index) {
        props.materialList[index] = [];
        props.form.scmMrrLines[index].scm_pr_id = props.form.scmMrrLines[index].scmPr?.id ?? null;
        
        if (props.formType != 'edit') {
          getLineData(props.form.scmMrrLines[index].scm_pr_id).then((res) => {
            props.form.scmMrrLines[index].scmMrrItems = res;
          });
          getMaterialList(props.form.scmMrrLines[index].scmPr.id).then((res) => {
          props.materialList[index] = res;
          });
        } else { 
          getLineData(props.form.scmMrrLines[index].scm_pr_id,props.form.id).then((res) => {
            props.form.scmMrrLines[index].scmMrrItems = res;
          });
          getMaterialList(props.form.scmMrrLines[index].scmPr.id,props.form.id).then((res) => {
          props.materialList[index] = res;
          });
        }
        
    }

    function changeWarehouse() {
      props.form.scm_warehouse_id = props.form.scmWarehouse?.id ?? null;
      props.form.acc_cost_center_id = props.form.scmWarehouse?.cost_center_id ?? null;
      getPoList(props.form.business_unit,props.form.purchase_center,props.form.scm_warehouse_id);
    }

    const purchase_center = ['Local', 'Foreign', 'Plant'];

    const customDataTableirf = ref(null);
    const dynamicMinHeight = ref(0);
    const SourceButtonWidth = ref(null);
    const TargetButtonWidth = ref(null);
    const minPoDate = ref('');

    // function setMaterialOtherData(index){
    //   let material = materials.value.find((material) => material.id === props.form.materials[index].material_id);
    //   props.form.materials[index].unit = material.unit;
    //   props.form.materials[index].material_category_id = material.category.id;
    //   props.form.materials[index].material_category_name = material.category.name;
    // }

    const tableScrollWidth = ref(null);
    const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;

    // onMounted(() => {
    //   watchEffect(() => {
    //     if (props.form.scmMrrLines) {
    //       const customDataTable = document.getElementById("customDataTable");
    //       if (customDataTable) {
    //         tableScrollWidth.value = customDataTable.scrollWidth;
    //       }
    //     }
    // }, { deep: true });

    // });// Code for global search end here
    // function fetchVendor(search, loading) {
    //   loading(true);
    //   searchVendor(search, loading);
// }

 

    function fetchWarehouse(search) {
    searchWarehouse(search, props.form.business_unit);
    }
    // function setMaterialOtherData(line, index) {
    // const selectedMaterial = prMaterialList.value.find(material => material.id === line.scmMaterial.id);
    //         if (selectedMaterial) {
    //           if ( line.scm_material_id !== selectedMaterial.id
    //           ) {
    //             props.form.scmMrrLines[index].scm_material_id = selectedMaterial.id;
    //             props.form.scmMrrLines[index].unit = selectedMaterial.unit;
    //             props.form.scmMrrLines[index].brand = selectedMaterial.brand;
    //             props.form.scmMrrLines[index].model = selectedMaterial.model;
    //             props.form.scmMrrLines[index].max_quantity = selectedMaterial.max_quantity;
    //           }
    //         }
      // }

    // watch(() => props?.form?.scmMrrLines, (newVal, oldVal) => {
    //   let total = 0.0;
    //   newVal?.forEach((lines, index) => {
    //     if(lines?.scmMrrItems?.length != 0){
    //       lines.scmMrrItems.forEach((line, itemIndex) => {
    //       props.form.scmMrrLines[index].scmMrrItems[itemIndex].total_price = parseFloat(((line?.rate ?? 0) * (line?.quantity ?? 0)).toFixed(2));
    //       total += parseFloat(props.form.scmMrrLines[index].scmMrrItems[itemIndex].total_price);
    //       });
    //     }
    //   });
    // }, { deep: true });

    function changePurchaseCenter() { 
      props.form.scmVendor = null;
      props.form.scm_vendor_id = null;
    }


    function changeMaterial(index,itemIndex) {
      props.form.scmMrrLines[index].scmMrrItems[itemIndex].scm_material_id = props.form.scmMrrLines[index].scmMrrItems[itemIndex].scmMaterial?.id ?? null;
      props.form.scmMrrLines[index].scmMrrItems[itemIndex].unit = props.form.scmMrrLines[index].scmMrrItems[itemIndex].scmMaterial?.unit ?? null;
      props.form.scmMrrLines[index].scmMrrItems[itemIndex].brand = props.form.scmMrrLines[index].scmMrrItems[itemIndex].scmMaterial?.brand ?? null;
      props.form.scmMrrLines[index].scmMrrItems[itemIndex].model = props.form.scmMrrLines[index].scmMrrItems[itemIndex].scmMaterial?.model ?? null;
      props.form.scmMrrLines[index].scmMrrItems[itemIndex].max_quantity = props.form.scmMrrLines[index].scmMrrItems[itemIndex].scmMaterial?.max_quantity ?? 0;
      props.form.scmMrrLines[index].scmMrrItems[itemIndex].quantity = props.form.scmMrrLines[index].scmMrrItems[itemIndex].scmMaterial?.quantity ?? 0;
      props.form.scmMrrLines[index].scmMrrItems[itemIndex].pr_composite_key = props.form.scmMrrLines[index].scmMrrItems[itemIndex].scmMaterial?.pr_composite_key ?? null;
      props.form.scmMrrLines[index].scmMrrItems[itemIndex].tolarence_level = props.form.scmMrrLines[index].scmMrrItems[itemIndex].scmMaterial?.tolarence_level ?? 0;
      props.form.scmMrrLines[index].scmMrrItems[itemIndex].pr_qty = props.form.scmMrrLines[index].scmMrrItems[itemIndex].scmMaterial?.pr_qty ?? 0;
      props.form.scmMrrLines[index].scmMrrItems[itemIndex].po_qty = props.form.scmMrrLines[index].scmMrrItems[itemIndex].scmMaterial?.po_qty ?? 0;
      props.form.scmMrrLines[index].scmMrrItems[itemIndex].tolarence_quantity = props.form.scmMrrLines[index].scmMrrItems[itemIndex].scmMaterial?.tolarence_quantity ?? 0;
      props.form.scmMrrLines[index].scmMrrItems[itemIndex].remaining_quantity = props.form.scmMrrLines[index].scmMrrItems[itemIndex].scmMaterial?.remaining_quantity ?? 0;
      props.form.scmMrrLines[index].scmMrrItems[itemIndex].rate = props.form.scmMrrLines[index].scmMrrItems[itemIndex].scmMaterial?.rate ?? 0;
      
      
    }
 
    onMounted(() => {
      watchEffect(() => {
        fetchWarehouse('');
      });
    
      // if(props.formType == 'edit'){
      //   const editDatas = watch(()=> [props.form.business_unit,props.form.scm_warehouse_id,props.form.purchase_center,props.form.scm_cs_id,props.form.scmMrrLines], (newVal, oldVal) => {
      //   searchPurchaseRequisition(props.form.business_unit, props.form.scm_warehouse_id, props.form.purchase_center, props.form.scm_cs_id,null);
        
      //   props.materialList.splice(0,props.materialList.length);
      //   props.materialList.push([]);
      //   props.form.scmMrrLines.forEach((line, index) => {
      //     props.materialList[index] = [];
      //     if (line.scmPr) {
      //       // getMaterialList(line.scmPr.id).then((res) => {
      //       //   props.materialList[index] = res;
      //       // });
      //       getMaterialList(line.scmPr.id,props.form.scm_cs_id,props.form.id).then((res) => {
      //       props.materialList[index] = res;
      //       });         
      //      }
      //   });
      //   editDatas();
      // });

      // }
      // const sourceWidth = SourceButtonWidth.value.offsetWidth;
      // TargetButtonWidth.value.style.width = `${sourceWidth}px`;
    });

  

    // function getPr(){
    //   props.materialList.splice(0,props.materialList.length)
    //   props.materialList.push([]);
    //   filteredPurchaseRequisitions.value = [];
    //   props.form.scmMrrLines = [];
    //    props.form.scmMrrLines.push(cloneDeep(props.mrrLineObject));
    //   props.form.scmMrrLines[0].scmMrrItems = [];
    //   props.form.scmMrrLines[0].scmMrrItems.push(cloneDeep(props.materialObject));
    //   searchPurchaseRequisition(props.form.business_unit, props.form.scm_warehouse_id, props.form.purchase_center, null);
    // } 

    onMounted(() => {
      watch(() => props.form.business_unit, (newValue, oldValue) => {
      if(newValue !== oldValue && oldValue != '' && oldValue != null){
        console.log(oldValue);
        props.form.scmWarehouse = null;
      }
    }
    
    );

  watchEffect(() => {
    fetchWarehouse('');
  });

  
});


  watch(() => props.form.scmWarehouse, (value) => {
          props.form.scm_warehouse_id = value?.id ?? null;
          props.form.acc_cost_center_id = value?.cost_center_id;
    });
     
  function changePo(){
  props.form.scmMrrLines = [];
  // props.materialList.splice(0,props.materialList.length)
  getPoWisePrList(props.form.scmPo.id);
}   
watch(() => prlist.value, (newVal, oldVal) => {
  console.log(newVal);
  if(newVal.length > 0){
    props.form.scmMrrLines = [];
    // props.materialList.splice(0,props.materialList.length);
    newVal.forEach((line, index) => {
      props.form.scmMrrLines.push({
        scmPr: line,
        scm_pr_id: line.id,
        scmMrrItems: [],
      });
      props.poMaterialList.push([]);
      getMrrLineData(props.form.scmPo.id, line.id).then((res) => {
        props.form.scmMrrLines[index].scmMrrItems = res;
        console.log(res);
      });
      getPoMaterialList(props.form.scmPo.id, line.id).then((res) => {
        props.poMaterialList[index] = res;
      });
    });
  }
});
</script>
<template>

  <!-- Basic information -->
  <!-- <div class="flex flex-col justify-center w-1/4 md:flex-row md:gap-2">
    <input type="text" readonly v-model="form.business_unit" required class="form-input vms-readonly-input" name="business_unit" :id="'business_unit'" />
  </div> -->

  <div class="flex flex-col justify-center w-1/4 md:flex-row md:gap-2">
    <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
  </div>
  <div class="input-group !w-1/4" v-if="formType == 'edit'">
      <label class="label-group">
          <span class="label-item-title">Po Ref<span class="text-red-500">*</span></span>
          <input type="text" readonly v-model="form.ref_no" required class="form-input vms-readonly-input" name="ref_no" :id="'ref_no'" />
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
        <span class="label-item-title">Warehouse <span class="text-red-500">*</span></span>
         <v-select :options="warehouses" placeholder="--Choose an option--" :loading="warehouseLoading" v-model="form.scmWarehouse" label="name" class="block form-input" @update:modelValue="changeWarehouse">
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
        <span class="label-item-title">PO Ref <span class="text-red-500">*</span></span>
         <v-select :options="polist" placeholder="--Choose an option--" :loading="poloading" v-model="form.scmPo" label="ref_no" class="block form-input" @update:modelValue="changePo">
          <template #search="{attributes, events}">
              <input
                  class="vs__search"
                  :required="!form.scmPo"
                  v-bind="attributes"
                  v-on="events"
              />
          </template>
          </v-select>
      </label>
  </div>
  <div class="input-group">
    <label class="label-group">
          <span class="label-item-title">PO Date<span class="text-red-500">*</span></span>
          <!-- <input type="date" v-model="form.date" required class="form-input" name="date" :id="'date'" /> -->
          <VueDatePicker v-model="form.date" class="form-input" required auto-apply :enable-time-picker = "false" placeholder="dd-mm-yyyy" format="dd-MM-yyyy" model-type="yyyy-MM-dd" :min-date="minPoDate" @update:modelValue="poDateChange"></VueDatePicker>
      </label>  
  </div>
  <div class="input-group !w-3/4">
    <label class="label-group">
         <RemarksComponet v-model="form.remarks" :maxlength="300" :fieldLabel="'Remarks'"></RemarksComponet>
    </label>
  </div>


  <div class="mt-3 md:mt-8" v-if="'zczczc'">
      <h4 class="text-md font-semibold uppercase mb-2 text-center border-2 rounded-md border-gray-400 py-2 mb-0">Purchase Order Information</h4>
      
      <div v-for="(scmPoLine, index) in form.scmMrrLines" :key="index"  class="w-full mx-auto p-2 border rounded-md border-gray-400 mb-5 shadow-md">

        <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <label class="block w-1/4 mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">PR No. <span class="text-red-500">*</span></span>
            <v-select :options="prlist" placeholder="--Choose an option--" v-model="form.scmMrrLines[index].scmPr" label="ref_no" class="block form-input">
              <template #search="{attributes, events}">
                  <input
                      class="vs__search"
                      :required="!form.scmMrrLines[index].scmPr"
                      v-bind="attributes"
                      v-on="events"
                      />
              </template>
            </v-select>
          </label>
          <label class="block w-1/4 mt-2 text-sm">
              <span class="text-gray-700">PR Date<span class="text-red-500">*</span></span>
              <input type="text" class="form-input vms-readonly-input" readonly :value="form.scmMrrLines[index]?.scmPr?.raised_date"/>
          </label>
        </div>

        <div class="relative my-3">
          <div class="dt-responsive table-responsive">
            <table class="w-full whitespace-no-wrap" >
              <tbody>
                <tr class="w-full">
                    <th class="py-3 align-center !w-3/12">Material Details <br/> <span class="!text-[8px]"></span></th>
                    <!-- <th class="py-3 align-center !w-2/12"><nobr>Required Date</nobr></th> -->
                    <th class="py-3 align-center !w-3/12">Other Info</th>
                    <!-- <th class="py-3 align-center !w-1/12">Tolarence <br/> (%)</th> -->
                    <th class="py-3 align-center !w-2/12">Tolarence</th>
                    <th class="py-3 align-center !w-3/12">Order Details</th>
                    <th class="!w-1/12" ref="TargetButtonWidth">
                      <button type="button" @click="addMaterial(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                      </button>
                    </th>
                  </tr>
                  <template v-if="form?.scmMrrLines[index].scmMrrItems.length">
                <template v-for="(lineItem, itemIndex) in form.scmMrrLines[index].scmMrrItems" :key="itemIndex">
                  <tr class="table_tr">
                    <td class="">
                      <table>
                        <tr>
                          <td><nobr>Material - Code</nobr></td>
                          <td>
                            <div class="relative">
                            <v-select :options="poMaterialList[index]" placeholder="--Choose an option--" :loading="isLoading" v-model="form.scmMrrLines[index].scmMrrItems[itemIndex].scmMaterial" label="material_name_and_code" class="block form-input-sm" :menu-props="{ minWidth: '250px', minHeight: '400px' }" @update:modelValue="changeMaterial(index,itemIndex)">
                              <template #search="{attributes, events}">
                                  <input
                                      class="vs__search"
                                      :required="!form.scmMrrLines[index].scmMrrItems[itemIndex].scmMaterial"
                                      v-bind="attributes"
                                      v-on="events"
                                      />
                              </template>
                          </v-select>
                          <!-- <span v-show="form.scmMrrLines[index].scmMrrItems[itemIndex].isAspectDuplicate" class="text-yellow-600 pl-1 absolute top-2 right-1" title="Duplicate Aspect" v-html="icons.ExclamationTriangle"></span> -->
                          </div>
                          </td>
                          
                        </tr>
                        <tr>
                          <td>Unit</td>
                          <td>
                            <input type="text" readonly v-model="form.scmMrrLines[index].scmMrrItems[itemIndex].unit" class="vms-readonly-input form-input-sm">
                          </td>
                        </tr>
                        <tr>
                          <td>Brand</td>
                          <td>
                            <input type="text" v-model="form.scmMrrLines[index].scmMrrItems[itemIndex].brand" class="form-input-sm vms-readonly-input" readonly>
                          </td>
                        </tr>
                        <tr>
                          <td>Model</td>
                          <td>
                              <input type="text" v-model="form.scmMrrLines[index].scmMrrItems[itemIndex].model" class="form-input-sm vms-readonly-input" readonly>
                          </td>
                        </tr>
                      </table>
                    </td>
                    <td>
                      <table>
                        <tr>
                          <td>PR Qty</td>
                          <td>
                            <input type="number" required :value="form.scmMrrLines[index].scmMrrItems[itemIndex].pr_qty" min=1 class="!text-xs form-input text-right vms-readonly-input" readonly>
                          </td>
                        </tr>
                        <tr>
                          <td>PO Qty</td>
                          <td>
                            <input type="number" required :value="form.scmMrrLines[index].scmMrrItems[itemIndex].po_qty" min=1 class="!text-xs form-input text-right vms-readonly-input" readonly>
                          </td>
                        </tr>
                        
                        <tr>
                          <td class="">Remaining Qty</td>
                          <td>
                            <input type="number" required :value="form.scmMrrLines[index].scmMrrItems[itemIndex].remaining_quantity" min=1 class="!text-xs form-input text-right vms-readonly-input" readonly>
                          </td>
                        </tr>
                      </table>
                    </td>
                    <td>
                      <tr>
                          <td>Qty</td>
                          <td>
                            <input type="number" required :value="form.scmMrrLines[index].scmMrrItems[itemIndex].tolarence_quantity" min=1 class="!text-xs form-input text-right vms-readonly-input" readonly>
                          </td>
                        </tr>
                        <tr>
                          <td>Level (%)</td>
                          <td>
                            <input type="number" required :value="form.scmMrrLines[index].scmMrrItems[itemIndex].tolarence_level" min=1 class="!text-xs form-input text-right vms-readonly-input" readonly>
                          </td>
                        </tr>
                    </td>
                    <td>
                      <table>
                        <tr>
                          <td>Qty</td>
                          <td>
                             <input type="number" required v-model="form.scmMrrLines[index].scmMrrItems[itemIndex].quantity" min=1 class="!text-xs form-input text-right" :max="form.scmMrrLines[index].scmMrrItems[itemIndex].max_quantity" :class="{'border-2': form.scmMrrLines[index].scmMrrItems[itemIndex].quantity > form.scmMrrLines[index].scmMrrItems[itemIndex].max_quantity,'border-red-500 bg-red-100': form.scmMrrLines[index].scmMrrItems[itemIndex].quantity > form.scmMrrLines[index].scmMrrItems[itemIndex].max_quantity}">
                          </td>
                        </tr>
                        <tr>
                          <td>Rate</td>
                          <td>
                            <input type="number" required v-model="form.scmMrrLines[index].scmMrrItems[itemIndex].rate" min=1 class="!text-xs form-input text-right">
                          </td>
                        </tr>
                      </table>
                    </td>
                   
                    <td class="px-1 py-1 text-center">
                      <button type="button" @click="removeMaterial(index,itemIndex)" class="remove_button">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                      </button>
                    </td>
                  </tr>
         
                </template>
              </template>
              </tbody>
            </table>
          </div>
        </div>
        <div class="flex justify-center items-center my-3">
            <button type="button" @click="addBlock()" class="px-3 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple flex w-32 justify-center text-center">
              Add More
            </button>
            <button type="button" v-if="index > 0" @click="removeBlock(index)" class="px-3 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple flex w-32 justify-center text-center ml-3">
              Remove
            </button> 
        </div>
      </div>
    </div>
    <div class="w-full mx-auto p-2 border rounded-md border-gray-400 mb-5 shadow-md">



</div>
  <!-- <div id="customDataTableMat" ref="customDataTableirf" class="!max-w-screen" :style="{ minHeight: dynamicMinHeight + 'px!important' }" >
    <div class="table-responsive">
      <fieldset class="form-fieldset">
        <legend class="form-legend">---</legend>
       
        <table class="w-full" id="table">
          <tbody class="table_body">
          <tr>
            <td class="!w-9/12 text-right">Sub Total</td>
            <td class="text-right">
              <input type="text" readonly class="vms-readonly-input form-input text-right" v-model="form.sub_total">
            </td>
          </tr>
          <tr>
            <td class="!w-9/12 text-right">Less: Discount</td>
            <td class="text-right">
              <input type="text" class="form-input" v-model="form.discount">
            </td>
          </tr>
          <tr>
            <td class="!w-9/12 text-right">Total Amount</td>
            <td class="">
              <input type="text" readonly class="vms-readonly-input form-input text-right" v-model="form.total_amount">
            </td>
          </tr>
          <tr>
            <td class="!w-9/12 text-right">Add: VAT</td>
            <td class="text-right">
              <input type="text" class="form-input text-right" v-model="form.vat">
            </td>
          </tr>
          <tr>
            <td class="!w-9/12 text-right">Net Amount</td>
            <td class="text-right">
              <input type="text" readonly class="vms-readonly-input form-input text-right" v-model="form.net_amount">
            </td>
          </tr>
          </tbody>
        </table>
      </fieldset> 
    </div>
  </div>  -->

 
  <ErrorComponent :errors="errors"></ErrorComponent>  
</template>


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
        @apply block text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray;
    }
  
    .form-input-sm {
        @apply block mt-1 text-xs rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray;
    }
    .form-fieldset {
      @apply px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400;
    }
    .form-legend {
      @apply px-2 text-gray-700 dark-disabled:text-gray-300;
    }
    .vs__selected{
    display: none !important;
    }
    .required-style{
        @apply text-red-400 font-semibold
    }

    table tr,td,th {
        @apply border border-gray-300
    }
    .table_tr {
      @apply text-gray-700 dark-disabled:text-gray-400;
    }
    .table_head_tr {
      @apply text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800;
    }
    .table_body {
      @apply bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800;
    }
    .remove_button {
      @apply px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple;
    }
    .add_button {
      @apply px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple;
    }

    #customDataTableMat::-webkit-scrollbar:horizontal {
      height: 1rem!important; 
    }
  
    #customDataTableMat::-webkit-scrollbar-thumb:horizontal{
      background-color: rgb(132, 109, 175); 
      border-radius: 12rem!important;
      width: 0.5rem!important;
      height: 0.5rem!important;
      border-radius: 12rem!important;
    }
  
    #customDataTableMat::-webkit-scrollbar-track:horizontal{
      background: rgb(148, 144, 155)!important; 
      border-radius: 12rem!important;
    }
  
    #customDataTableMat::-webkit-scrollbar-button:horizontal {
      background-color: rgb(0, 0, 0); 
      border-radius: 12rem!important;
      width: 1.3rem!important;
    }   
   
  
    >>> {
      --vs-controls-color: #374151;
      --vs-border-color: #4b5563;
    
      --vs-dropdown-bg: #282c34;
      --vs-dropdown-color: #eeeeee;
      --vs-dropdown-option-color: #eeeeee;
    
      --vs-selected-bg: #664cc3;
      --vs-selected-color: #374151;
    
      --vs-search-input-color: #4b5563;
    
      --vs-dropdown-option--active-bg: #664cc3;
      --vs-dropdown-option--active-color: #eeeeee;
      
      --dp-border-color: #4b5563;
      --dp-border-color-hover: #4b5563;
      --dp-icon-color: #4b5563;
    }
</style>