<script setup>
    import { ref, watch, onMounted,watchEffect,computed } from 'vue';
    import Error from "../../Error.vue";
    import useMaterial from "../../../composables/supply-chain/useMaterial.js";
    import useService from '../../../composables/supply-chain/useService';
    import useWarehouse from "../../../composables/supply-chain/useWarehouse.js";
    import BusinessUnitInput from "../../input/BusinessUnitInput.vue";
    import usePurchaseRequisition from '../../../composables/supply-chain/usePurchaseRequisition';
    import useWorkRequisition from '../../../composables/supply-chain/useWorkRequisition';
    import useVendor from '../../../composables/supply-chain/useVendor';
    import ErrorComponent from "../../utils/ErrorComponent.vue";
    import cloneDeep from 'lodash/cloneDeep';
    import useBusinessInfo from '../../../composables/useBusinessInfo';
    // import usePurchaseOrder from '../../../composables/supply-chain/usePurchaseOrder';
    import useWorkOrder from '../../../composables/supply-chain/useWorkOrder';
    import RemarksComponent from '../../utils/RemarksComponent.vue';
    import useMaterialCs from '../../../composables/supply-chain/useMaterialCs';
    import useWorkCs from '../../../composables/supply-chain/useWorkCs';
    const { material, materials, getMaterials,searchMaterial } = useMaterial();
    const { service, services, getServices, searchService, isLoading:isServiceLoading } = useService();
    const { warehouses,warehouse,getWarehouses,searchWarehouse ,isLoading:isWarehouseLoading} = useWarehouse();
    const { vendors, searchVendor,isLoading: vendorLoader } = useVendor();
    const { currencies, getCurrencies } = useBusinessInfo();
    // const { getMaterialList, prMaterialList, isLoading ,getLineData} = usePurchaseOrder();
    const { getServiceList, wrServiceList, getLineData, isLoading:isWorkOrderLoading } = useWorkOrder();
    // const { searchPurchaseRequisition, filteredPurchaseRequisitions } = usePurchaseRequisition();
    const { searchWorkRequisition, filteredWorkRequisitions } = useWorkRequisition();
        
    const { filteredMaterialCs, searchCs,getCsWiseVendorList,csWiseVendorList} = useMaterialCs();
    const { filteredWorkCs, searchWcs, getWcsWiseVendorList, wcsWiseVendorList, isLoading:isWorkCsLoading } = useWorkCs();

    const props= defineProps({
      form: { type: Object, required: true },
      errors: { type: [Object, Array], required: false },
      formType: { type: String, required : false },
      // materialObject: { type: Object, required: false },
      serviceObject: { type: Object, required: false },
      termsObject: { type: Object, required: false },
      page: { required: false, default: {} },
      // poLineObject: { type: Object, required: false },
      woLineObject: { type: Object, required: false },
      // materialList: { type: [Object, Array], required: false },
      serviceList: { type: [Object, Array], required: false },
    });

    const csVendors = ref([]);

    
    function addMaterial(index) {
      const clonedObj = cloneDeep(props.materialObject);
      props.form.scmPoLines[index].scmPoItems.push(clonedObj);
    }

    function removeMaterial(index,itemIndex){
      props.form.scmPoLines[index].scmPoItems.splice(itemIndex, 1);
    }

    
    function addService(index) {
      const clonedObj = cloneDeep(props.serviceObject);
      props.form.scmWoLines[index].scmWoItems.push(clonedObj);
    }

    function removeService(index,itemIndex){
      props.form.scmWoLines[index].scmWoItems.splice(itemIndex, 1);
    }



    function addBlock() {
      let lineLength = props.form.scmWoLines.length;
      const clonedObj = cloneDeep(props.woLineObject);
      props.form.scmWoLines.push(clonedObj);
      props.serviceList.push([]);
    }

    function removeBlock(index) {
      props.form.scmWoLines.splice(index, 1);
    }

    function addTerms() {
          const clonedTermObj = cloneDeep(props.termsObject);
          props.form.scmWoTerms.push(clonedTermObj);
    }

    function removeTerms(index){
      props.form.scmWoTerms.splice(index, 1);
    }

    function changePr(index) {
        props.materialList[index] = [];
        props.form.scmPoLines[index].scm_pr_id = props.form.scmPoLines[index].scmPr?.id ?? null;
        getMaterialList(props.form.scmPoLines[index].scmPr.id).then((res) => {
          props.materialList[index] = res;
        });
        if (props.form.scm_cs_id != null) {
          getLineData(props.form.scmPoLines[index].scm_pr_id,props.form.scm_cs_id).then((res) => {
            props.form.scmPoLines[index].scmPoItems = res;
          });
        } else { 
          getLineData(props.form.scmPoLines[index].scm_pr_id).then((res) => {
          props.form.scmPoLines[index].scmPoItems = res;
        });
        }
        
    }

    
    // function changeWr(index) {
    //     props.serviceList[index] = [];
    //     props.form.scmWoLines[index].scm_wr_id = props.form.scmWoLines[index].scmWr?.id ?? null;
    //     if(props.page == 'create')
    //     {
    //       getServiceList(props.form.scmWoLines[index].scmWr?.id).then((res) => {
    //         props.serviceList[index] = res;
    //       });
    //     }
    //     else{
    //         getServiceList(props.form.scmWoLines[index].scmWr?.id, props.form.id).then((res) => {
    //         props.serviceList[index] = res;
    //       });
    //     }
    //     if (props.form.scm_wcs_id != null) {
    //       getLineData(props.form.scmWoLines[index].scm_wr_id,props.form.scm_wcs_id).then((res) => {
    //         props.form.scmWoLines[index].scmWoItems = res;
    //       });
    //     } else { 
    //       getLineData(props.form.scmWoLines[index].scm_wr_id).then((res) => {
    //       props.form.scmWoLines[index].scmWoItems = res;
    //     });
    //     }
        
    // }

    function changeWr(index) {
        props.serviceList[index] = [];
        props.form.scmWoLines[index].scm_wr_id = props.form.scmWoLines[index].scmWr?.id ?? null;
        
        if (props.formType != 'edit') {
          getLineData(props.form.scmWoLines[index].scm_wr_id, props.form.scm_wcs_id).then((res) => {
            props.form.scmWoLines[index].scmWoItems = res;
          });
          getServiceList(props.form.scmWoLines[index].scmWr?.id,props.form.scm_wcs_id).then((res) => {
          props.serviceList[index] = res;
          });
        } else { 
          getLineData(props.form.scmWoLines[index].scm_wr_id, props.form.scm_wcs_id, props.form.id).then((res) => {
            props.form.scmWoLines[index].scmWoItems = res;
          });
          getServiceList(props.form.scmWoLines[index].scmWr?.id, props.form.scm_wcs_id, props.form.id).then((res) => {
          props.serviceList[index] = res;
          });
        }
        
    }



    function changeWarehouse() {
      props.form.scm_warehouse_id = props.form.scmWarehouse?.id ?? null;
      props.form.acc_cost_center_id = props.form.scmWarehouse?.cost_center_id ?? null;
      props.form.scmVendor = null;
      props.form.scm_vendor_id = null;
      getPr();
      getCs();
    }

    
    function warehouseChange() {
      props.form.scm_warehouse_id = props.form.scmWarehouse?.id;
      props.form.acc_cost_center_id = props.form.scmWarehouse?.cost_center_id;
      props.form.scmVendor = null;
      props.form.scm_vendor_id = null;
      getWr();
      getWcs();
    }



    const purchase_center = ['Local', 'Foreign', 'Plant'];

    const customDataTableirf = ref(null);
    const dynamicMinHeight = ref(0);



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
    //     if (props.form.scmPoLines) {
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

    function fetchVendor(search) {
      searchVendor(search);
    }

    function fetchWarehouse(search) {
    searchWarehouse(search, props.form.business_unit);
    }
    function setMaterialOtherData(line, index) {
    // const selectedMaterial = prMaterialList.value.find(material => material.id === line.scmMaterial.id);
    //         if (selectedMaterial) {
    //           if ( line.scm_material_id !== selectedMaterial.id
    //           ) {
    //             props.form.scmPoLines[index].scm_material_id = selectedMaterial.id;
    //             props.form.scmPoLines[index].unit = selectedMaterial.unit;
    //             props.form.scmPoLines[index].brand = selectedMaterial.brand;
    //             props.form.scmPoLines[index].model = selectedMaterial.model;
    //             props.form.scmPoLines[index].max_quantity = selectedMaterial.max_quantity;
    //           }
    //         }
      }

    watch(() => props?.form?.scmPoLines, (newVal, oldVal) => {
      let total = 0.0;
      newVal?.forEach((lines, index) => {
        if(lines?.scmPoItems?.length != 0){
          lines.scmPoItems.forEach((line, itemIndex) => {
          props.form.scmPoLines[index].scmPoItems[itemIndex].total_price = parseFloat(((line?.rate ?? 0) * (line?.quantity ?? 0)).toFixed(2));
          total += parseFloat(props.form.scmPoLines[index].scmPoItems[itemIndex].total_price);
          });
        }
      });
      
      props.form.sub_total = parseFloat(total.toFixed(2));
      calculateNetAmount();
    }, { deep: true });


    
    watch(() => props?.form?.scmWoLines, (newVal, oldVal) => {
      let total = 0.0;
      newVal?.forEach((lines, index) => {
        if(lines?.scmWoItems?.length != 0){
          lines.scmWoItems?.forEach((line, itemIndex) => {
          props.form.scmWoLines[index].scmWoItems[itemIndex].total_price = parseFloat(((line?.rate ?? 0) * (line?.quantity ?? 0)).toFixed(2));
          total += parseFloat(props.form.scmWoLines[index].scmWoItems[itemIndex].total_price);
          });
        }
      });
      
      props.form.sub_total = parseFloat(total.toFixed(2));
      calculateNetAmount();
    }, { deep: true });




    function changePurchaseCenter() { 
      props.form.scmVendor = null;
      props.form.scm_vendor_id = null;
      getCs();
      getPr();

    }

    
    function purchaseCenterChange() { 
      props.form.scmVendor = null;
      props.form.scm_vendor_id = null;
      getWcs();
      getWr();
    }

    function changeCs() {
      props.form.cs_no = props.form?.scmCs?.ref_no ?? null;
      props.form.scm_cs_id = props.form?.scmCs?.id ?? null;
      props.form.scm_vendor_id = null;
      props.form.scmVendor = null;
      csWiseVendorList.value = [];
      getPr();
      if(props.form.scm_cs_id){
        getCsWiseVendorList(props.form.scmCs.id);
      }
    }

    
    function wcsChange() {
      props.form.wcs_no = props.form?.scmWcs?.ref_no ?? null;
      props.form.scm_wcs_id = props.form?.scmWcs?.id ?? null;
      props.form.scm_vendor_id = null;
      props.form.scmVendor = null;
      wcsWiseVendorList.value = [];
      getWr();
      if(props.form.scm_wcs_id){
        getWcsWiseVendorList(props.form.scmWcs.id);
      }
    }



    
    function calculateNetAmount(){
      // props.form.total_amount = parseFloat((props.form.sub_total - props.form.discount).toFixed(2));
      // props.form.net_amount = parseFloat((props.form.total_amount + parseFloat(props.form.vat)).toFixed(2));

      props.form.total_amount = parseFloat((props.form.sub_total).toFixed(2));
      props.form.net_amount = props.form.total_amount;
    }

    function changeMaterial(index,itemIndex) {
      props.form.scmPoLines[index].scmPoItems[itemIndex].scm_material_id = props.form.scmPoLines[index].scmPoItems[itemIndex].scmMaterial.id;
      props.form.scmPoLines[index].scmPoItems[itemIndex].unit = props.form.scmPoLines[index].scmPoItems[itemIndex].scmMaterial.unit;
      props.form.scmPoLines[index].scmPoItems[itemIndex].brand = props.form.scmPoLines[index].scmPoItems[itemIndex].scmMaterial.brand;
      props.form.scmPoLines[index].scmPoItems[itemIndex].model = props.form.scmPoLines[index].scmPoItems[itemIndex].scmMaterial.model;
      props.form.scmPoLines[index].scmPoItems[itemIndex].max_quantity = props.form.scmPoLines[index].scmPoItems[itemIndex].scmMaterial.max_quantity;
      props.form.scmPoLines[index].scmPoItems[itemIndex].quantity = props.form.scmPoLines[index].scmPoItems[itemIndex].scmMaterial.quantity;
      props.form.scmPoLines[index].scmPoItems[itemIndex].pr_composite_key = props.form.scmPoLines[index].scmPoItems[itemIndex].scmMaterial.pr_composite_key;
      
    }

    
    function changeService(index,itemIndex) {
      props.form.scmWoLines[index].scmWoItems[itemIndex].scm_service_id = props.form.scmWoLines[index].scmWoItems[itemIndex].scmService?.id;
      // props.form.scmWoLines[index].scmWoItems[itemIndex].unit = props.form.scmWoLines[index].scmWoItems[itemIndex].scmService.unit;
      // props.form.scmWoLines[index].scmWoItems[itemIndex].brand = props.form.scmWoLines[index].scmWoItems[itemIndex].scmService.brand;
      // props.form.scmWoLines[index].scmWoItems[itemIndex].model = props.form.scmWoLines[index].scmWoItems[itemIndex].scmService.model;
      props.form.scmWoLines[index].scmWoItems[itemIndex].max_quantity = props.form.scmWoLines[index].scmWoItems[itemIndex].scmService.max_quantity;
      props.form.scmWoLines[index].scmWoItems[itemIndex].quantity = props.form.scmWoLines[index].scmWoItems[itemIndex].scmService.quantity;
      props.form.scmWoLines[index].scmWoItems[itemIndex].wr_composite_key = props.form.scmWoLines[index].scmWoItems[itemIndex].scmService.wr_composite_key;
      
    }


 
    watch(() => props?.form?.discount, (newVal, oldVal) => {
      calculateNetAmount();
    });
    watch(() => props?.form?.vat, (newVal, oldVal) => {
      calculateNetAmount();
    });
    watch(() => props?.form?.scmCs, (newVal, oldVal) => {
      if(newVal){
        props.form.scm_cs_id = newVal.id;
        props.form.cs_no = newVal.ref_no;
      }
    }); 
    onMounted(() => {
      getCurrencies();
      fetchVendor('');
      watchEffect(() => {
        fetchWarehouse('');
      });
    
      if(props.formType == 'edit'){
        const editDatas = watch(()=> [props.form.business_unit,props.form.scm_warehouse_id,props.form.purchase_center,props.form.scm_wcs_id,props.form.scmWoLines], (newVal, oldVal) => {
        searchWcs(props.form.business_unit, props.form.scm_warehouse_id, props.form.purchase_center, null);
        if(props.form.scm_wcs_id){
          getWcsWiseVendorList(props.form.scm_wcs_id);
        }
        searchWorkRequisition(props.form.business_unit, props.form.scm_warehouse_id, props.form.purchase_center, props.form.scm_wcs_id,null);
        
        props.serviceList.splice(0,props.serviceList.length);
        props.serviceList.push([]);
        props.form.scmWoLines.forEach((line, index) => {
          props.serviceList[index] = [];
          if (line.scmWr) {
            // getServiceList(line.scmWr.id, props.form.id).then((res) => {
            //   props.serviceList[index] = res;
            // });
            getServiceList(line.scmWr.id,props.form.scm_wcs_id,props.form.id).then((res) => {
              props.serviceList[index] = res;
            }); 
          }
        });
        console.log('asd');
        editDatas();
      });

      }
     
    });

    // watch(() => props?.form?.scmVendor, (newVal, oldVal) => {
    //   console.log('scm vendor watch called');
    //   if(newVal){
    //     props.form.scm_vendor_id = newVal.id;
    //   }
    // });

    function scmVendorChange(){
      props.form.scm_vendor_id = props.form.scmVendor?.id;
    }

    function getPr(){
      props.materialList.splice(0,props.materialList.length)
      props.materialList.push([]);
      filteredPurchaseRequisitions.value = [];
      props.form.scmPoLines = [];
       props.form.scmPoLines.push(cloneDeep(props.poLineObject));
      props.form.scmPoLines[0].scmPoItems = [];
      props.form.scmPoLines[0].scmPoItems.push(cloneDeep(props.materialObject));
      searchPurchaseRequisition(props.form.business_unit, props.form.scm_warehouse_id, props.form.purchase_center, props.form.scm_cs_id,null);
    } 

    function getCs(){
      props.form.scmCs = null;
      props.form.scm_cs_id = null;
      props.form.cs_no = null;
      csWiseVendorList.value = [];
      searchCs(props.form.business_unit, props.form.scm_warehouse_id, props.form.purchase_center, null);
    }

    
    function getWr(){
      props.serviceList.splice(0,props.serviceList.length)
      props.serviceList.push([]);
      // filteredPurchaseRequisitions.value = [];
      filteredWorkRequisitions.value = [];
      props.form.scmWoLines = [];
      props.form.scmWoLines.push(cloneDeep(props.woLineObject));
      props.form.scmWoLines[0].scmWoItems = [];
      props.form.scmWoLines[0].scmWoItems.push(cloneDeep(props.serviceObject));
      searchWorkRequisition(props.form.business_unit, props.form.scm_warehouse_id, props.form.purchase_center, props.form.scm_wcs_id,null);
    } 

    function getWcs(){
      props.form.scmWcs = null;
      props.form.scm_wcs_id = null;
      props.form.wcs_no = null;
      wcsWiseVendorList.value = [];
      searchWcs(props.form.business_unit, props.form.scm_warehouse_id, props.form.purchase_center, null);
    }


    onMounted(() => {
      watch(() => props.form.business_unit, (newValue, oldValue) => {
      if(newValue !== oldValue && oldValue != '' && oldValue != null){
        console.log(oldValue);
        props.form.scmWarehouse = null;
        props.form.scmVendor = null;
        props.form.scm_vendor_id = null;
        props.form.scmPoTerms = [];
        props.form.scmPoTerms.push(cloneDeep(props.termsObject));
        getCs();
        getPr();
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
     
        
   
</script>
<template>

  <!-- Basic information -->
  <!-- <div class="flex flex-col justify-center w-1/4 md:flex-row md:gap-2">
    <input type="text" readonly v-model="form.business_unit" required class="form-input vms-readonly-input" name="business_unit" :id="'business_unit'" />
  </div> -->
  <div class="justify-center w-full grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 md:gap-2">
    <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
    <label class="label-group col-start-1" v-if="page == 'edit'">
      <span class="label-item-title">Wo Ref</span>
      <input type="text" readonly v-model="form.ref_no" required class="form-input vms-readonly-input" name="ref_no" :id="'ref_no'" />
    </label>
    <label class="label-group col-start-1">
        <span class="label-item-title">Purchase Center <span class="text-red-500">*</span></span>
        <v-select :options="purchase_center" placeholder="--Choose an option--" v-model="form.purchase_center" label="Product Source Type" class="block w-full mt-1 text-xs rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray form-input" @update:modelValue="purchaseCenterChange">
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
      <v-select :options="warehouses" placeholder="--Choose an option--" :loading="isWorkOrderLoading" v-model="form.scmWarehouse" label="name" class="block form-input" @update:modelValue="warehouseChange">
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
      <span class="label-item-title">CS No </span>
      <v-select :options="filteredWorkCs" placeholder="--Choose an option--" label="ref_no"  v-model="form.scmWcs" class="block w-full mt-1 text-xs rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray form-input" @update:modelValue="wcsChange">
        <template #search="{attributes, events}">
          <input
            class="vs__search"
            v-bind="attributes"
            v-on="events"
          />  
        </template>        
      </v-select>
    </label>
    <label class="label-group">
          <span class="label-item-title">WO Date<span class="text-red-500">*</span></span>
          <!-- <input type="date" v-model="form.date" required class="form-input" name="date" :id="'date'" /> -->
          <VueDatePicker v-model="form.date" class="form-input" required auto-apply :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" ></VueDatePicker>
    </label>    
    <label class="label-group" v-if="form.cs_no != null">
      <span class="label-item-title">Vendor Name<span class="text-red-500">*</span></span>
      <v-select :options="csWiseVendorList" placeholder="--Choose an option--" :loading="vendorLoader" v-model="form.scmVendor" label="name" class="block form-input" @update:modelValue="scmVendorChange">
        <template #search="{attributes, events}">
          <input
            class="vs__search"
            :required="!form.scmVendor"
            v-bind="attributes"
            v-on="events"
          />
        </template>
      </v-select>
    </label>
    <label class="label-group" v-else>
      <span class="label-item-title">Vendor Name <span class="text-red-500">*</span></span>
      <v-select :options="vendors" placeholder="--Choose an option--" :loading="vendorLoader" v-model="form.scmVendor" label="name" class="block form-input" @update:modelValue="scmVendorChange">
        <template #search="{attributes, events}">
          <input
            class="vs__search"
            :required="!form.scmVendor"
            v-bind="attributes"
            v-on="events"
          />
        </template>
      </v-select>
    </label>

    <label class="label-group">
      <span class="label-item-title">Currency <span class="text-red-500">*</span></span>
      <v-select :options="currencies" placeholder="--Choose an option--" v-model="form.currency" label="name" class="block form-input">
        <template #search="{attributes, events}">
            <input
                class="vs__search"
                :required="!form.currency"
                v-bind="attributes"
                v-on="events"
            />
        </template>
      </v-select>
    </label>
    <!-- <label class="label-group" v-if="form.currency == 'USD'">
        <span class="label-item-title">Convertion Rate( Foreign To BDT )<span class="text-red-500">*</span></span>
        <input type="text" v-model="form.foreign_to_usd" required class="form-input" name="approved_date" :id="'foreign_to_usd'" />
    </label> -->
    
    <label class="label-group" v-if="(form.currency != 'USD' && form.currency != 'BDT' && form.currency != '')">
        <span class="label-item-title">Convertion Rate( Foreign To USD ) <span class="text-red-500">*</span></span>
        <input type="number" step="0.01" v-model="form.foreign_to_usd" required class="form-input" name="approved_date" :id="'foreign_to_usd'" />
    </label>
    <label class="label-group" v-if="form.currency != 'BDT' && form.currency != ''">
        <span class="label-item-title">Convertion Rate( USD To BDT ) <span class="text-red-500">*</span></span>
        <input type="number" step="0.01" v-model="form.usd_to_bdt" required class="form-input" name="approved_date" :id="'usd_to_bdt'" />
    </label>
    <RemarksComponent class="col-span-1 md:col-span-3 lg:col-span-4" v-model="form.remarks" :maxlength="300" :fieldLabel="'Remarks'"></RemarksComponent>
  </div>

  <div class="grid grid-cols-1" v-if="form?.scmWoLines?.length">
    <fieldset class="form-fieldset">
      <legend class="form-legend">Work Order Information <span class="text-red-500">*</span></legend>
      <div v-for="(scmWoLine, index) in form.scmWoLines" :key="index"  class="w-full mx-auto p-2 border rounded-md border-gray-400 mb-5 shadow-md">

      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-1/4 mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">WR No. <span class="text-red-500">*</span></span>
          <v-select :options="filteredWorkRequisitions" placeholder="--Choose an option--" v-model="scmWoLine.scmWr" label="ref_no" class="block form-input"
          @update:modelValue="changeWr(index)"
          >
            <template #search="{attributes, events}">
                <input
                    class="vs__search"
                    :required="!scmWoLine.scmWr"
                    v-bind="attributes"
                    v-on="events"
                    />
            </template>
          </v-select>
        </label>
        <label class="block w-1/4 mt-2 text-sm">
            <span class="text-gray-700">WR Date </span>
            <!-- <input type="text" class="form-input vms-readonly-input" readonly :value="form.scmPoLines[index]?.scmPr?.raised_date"/> -->
            <VueDatePicker v-model="scmWoLine.scmWr.raised_date" class="form-input" required auto-apply readonly :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" ></VueDatePicker>
        </label>
      </div>

      <div class="relative my-3">
        <div class="dt-responsive table-responsive">
          <table class="w-full whitespace-no-wrap" >
            <tbody>
              <tr class="w-full">
                  <th class="py-3 align-center !w-4/12">Service Details <br/> <span class="!text-[8px]"></span></th>
                  <!-- <th class="py-3 align-center !w-2/12">Required Date <span class="text-red-500">*</span></th> -->
                  <th class="py-3 align-center !w-2/12">Other Info</th>
                  <!-- <th class="py-3 align-center !w-1/12">Tolarence</th> -->
                  <th class="py-3 align-center !w-3/12">Order Details</th>
                  <th class="py-3 align-center !w-2/12">Total Price</th>
                  <th class="!w-1/12">
                    <button type="button" @click="addService(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                      </svg>
                    </button>
                  </th>
                </tr>
              <template v-for="(scmWoItem, itemIndex) in form.scmWoLines[index].scmWoItems" :key="itemIndex">
                <tr class="table_tr">
                  <td class="">
                    <table class="w-full">
                      <tr>
                        <th class="w-1/4">Service - Code <span class="text-red-500">*</span></th>
                        <td class="w-3/4">
                          <v-select :options="serviceList[index]" placeholder="--Choose an option--" :loading="isLoading" v-model="scmWoItem.scmService" label="service_name_and_code" class="block form-input w-full" :menu-props="{ minWidth: '250px', minHeight: '400px' }" @update:modelValue="changeService(index,itemIndex)">
                            <template #search="{attributes, events}">
                                <input
                                    class="vs__search"
                                    :required="!scmWoItem.scmService"
                                    v-bind="attributes"
                                    v-on="events"
                                    />
                            </template>
                        </v-select>
                        </td>
                        
                      </tr>
                      
                      <tr>
                        <th class="w-1/4">Required Date <span class="text-red-500">*</span></th>
                        <td class="w-3/4">
                          <VueDatePicker v-model="scmWoItem.required_date" class="form-input" auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd"  required></VueDatePicker>
                        </td>
                        
                      </tr>

                      <!-- <tr>
                        <td>Unit</td>
                        <td>
                          <input type="text" readonly v-model="form.scmPoLines[index].scmPoItems[itemIndex].unit" class="vms-readonly-input form-input">
                        </td>
                      </tr>
                      <tr>
                        <td>Brand</td>
                        <td>
                          <input type="text" v-model="form.scmPoLines[index].scmPoItems[itemIndex].brand" class="form-input">
                        </td>
                      </tr>
                      <tr>
                        <td>Model</td>
                        <td>
                            <input type="text" v-model="form.scmPoLines[index].scmPoItems[itemIndex].model" class="form-input">
                        </td>
                      </tr> -->
                    </table>
                  </td>
                  <!-- <td>
                    <VueDatePicker v-model="scmWoItem.required_date" class="form-input" auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd"  required></VueDatePicker>
                  </td> -->
                  <td>
                    <table>
                      <tr>
                        <td>WR Qty</td>
                        <td>
                          <input type="text" readonly v-model="scmWoItem.wr_quantity" min=1 class="form-input text-right vms-readonly-input">
                        </td>
                      </tr>
                      <tr>
                        <td>Remaining Qty</td>
                        <td>
                          <input type="text" readonly v-model="scmWoItem.max_quantity" min=1 class="form-input text-right vms-readonly-input">
                        </td>
                      </tr>
                    </table>
                  </td>
                  <!-- <td>
                    <input type="number" v-model="form.scmPoLines[index].scmPoItems[itemIndex].tolarence_level" min=1 class="form-input">  
                  </td> -->
                  <td>
                    <table>
                      <tr>
                        <td>Qty</td>
                        <td>
                          
                          <input type="number" required v-model="scmWoItem.quantity" min=1 step="0.01" class="form-input text-right" :max="scmWoItem.max_quantity" :class="{'border-2': scmWoItem.quantity > scmWoItem.max_quantity,'border-red-500 bg-red-100': scmWoItem.quantity > scmWoItem.max_quantity}">
                        </td>
                      </tr>
                      <tr>
                        <td>Rate <span class="text-red-500">*</span></td>
                        <td>
                          <input type="number" required v-model="scmWoItem.rate" step="0.01" min=1 class="form-input text-right">
                        </td>
                      </tr>
                    </table>
                  </td>
                  <td>
                    <input type="text" readonly v-model="scmWoItem.total_price" class="form-input vms-readonly-input text-right">
                  </td>
                  <td class="px-1 py-1 text-center">
                    <button type="button" @click="removeService(index,itemIndex)" class="remove_button">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                      </svg>
                    </button>
                  </td>
                </tr>
      
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
    </fieldset>
  </div>
  
  <div class="grid grid-cols-1" v-if="form?.scmWoLines?.length">
    <fieldset class="form-fieldset">
      <legend class="form-legend"> Total </legend>
      <div class="w-full mx-auto p-2 border rounded-md border-gray-400 mb-5 shadow-md">

        <table class="w-full" id="table">
          <tbody class="table_body">
          <tr>
            <td class="!w-9/12 text-right">Sub Total</td>
            <td class="text-right !w-3/12">
              <input type="text" readonly class="vms-readonly-input form-input text-right" v-model="form.sub_total">
            </td>
          </tr>
          <!-- <tr>
            <td class="!w-9/12 text-right">Less: Discount</td>
            <td class="text-right !w-3/12">
              <input type="text" class="form-input" v-model="form.discount">
            </td>
          </tr> -->
          <tr>
            <td class="!w-9/12 text-right">Total Amount</td>
            <td class="text-right !w-3/12">
              <input type="text" readonly class="vms-readonly-input form-input text-right" v-model="form.total_amount">
            </td>
          </tr>
          <!-- <tr>
            <td class="!w-9/12 text-right">Add: VAT</td>
            <td class="text-right !w-3/12">
              <input type="text" class="form-input" v-model="form.vat">
            </td>
          </tr> -->
          <tr>
            <td class="!w-9/12 text-right">Net Amount</td>
            <td class="text-right !w-3/12">
              <input type="text" readonly class="vms-readonly-input form-input text-right" v-model="form.net_amount">
            </td>
          </tr>
          </tbody>
        </table>
      </div>
    </fieldset>
  </div>
  
  <div class="grid grid-cols-1">

    <fieldset class="form-fieldset">
      <legend class="form-legend">Terms And Conditions </legend>
      <table class="w-full whitespace-no-wrap" id="table">
        <tbody class="table_body">
          <tr class="table_tr" v-for="(scmWoTerm, index) in form.scmWoTerms" :key="index">
            <td>
              <input type="text" v-model="scmWoTerm.description" class="form-input">
            </td>
            
            <td class="px-1 py-1 text-center">
              <button v-if="index!=0" type="button" @click="removeTerms(index)" class="remove_button">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg>
              </button>
              <button v-else type="button" @click="addTerms()" class="add_button">
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
        @apply block mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray;
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
</style>