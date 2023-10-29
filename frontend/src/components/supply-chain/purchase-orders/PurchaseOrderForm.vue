<script setup>
    import { ref, watch, onMounted,watchEffect,computed } from 'vue';
    import Error from "../../Error.vue";
    import useMaterial from "../../../composables/supply-chain/useMaterial.js";
    import useWarehouse from "../../../composables/supply-chain/useWarehouse.js";
    import BusinessUnitInput from "../../input/BusinessUnitInput.vue";
    import usePurchaseRequisition from '../../../composables/supply-chain/usePurchaseRequisition';
    import useVendor from '../../../composables/supply-chain/useVendor';

    const { material, materials, getMaterials,searchMaterial } = useMaterial();
    const { warehouses,warehouse,getWarehouses,searchWarehouse } = useWarehouse();
    const { vendors,searchVendor } = useVendor();

    const { purchaseRequisitions, searchWarehouseWisePurchaseRequisition } = usePurchaseRequisition();

    const props = defineProps({
      form: { type: Object, required: true },
      errors: { type: [Object, Array], required: false },
      formType: { type: String, required : false },
      materialObject: { type: Object, required: false },
      termsObject: { type: Object, required: false },
      csVendors: { type: Object, required: false },
      page: {
      required: false,
      default: {}
    },

    });

    function addMaterial() {
      props.form.scmPoLines.push(props.materialObject);
    }

    function removeMaterial(index){
      props.form.scmPoLines.splice(index, 1);
}

    function addTerms() {
          props.form.scmPoTerms.push(props.termsObject);
        }

    function removeTerms(index){
      props.form.scmPoTerms.splice(index, 1);
    }

    // function setMaterialOtherData(index){
    //   let material = materials.value.find((material) => material.id === props.form.materials[index].material_id);
    //   props.form.materials[index].unit = material.unit;
    //   props.form.materials[index].material_category_id = material.category.id;
    //   props.form.materials[index].material_category_name = material.category.name;
    // }


    watch(() => props?.form?.status, (newVal, oldVal) => {
      props?.form?.status == props?.form?.status;
    })


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


    function fetchWarehouse(search, loading) {
    loading(true);
    console.log(props.form.business_unit);
    searchWarehouse(search, loading,props.form.business_unit);
  }

  watch(() => props.form.scmWarehouse, (value) => {
        props.form.scm_warehouse_id = value?.id;
    });

    function fetchPurchaseRequisition(search, loading) {
    loading(true);
    searchWarehouseWisePurchaseRequisition(props.form.scm_warehouse_id,search, loading);
    }

    watch(() => props.form.scmPurchaseRequisition, (value) => {
          props.form.scm_pr_id = value?.id;
      });

    function setMaterialOtherData(datas,index){
      console.log(datas);
      props.form.scmPoLines[index].unit = datas.unit;
      props.form.scmPoLines[index].scm_material_id = datas.id;
    }

    function fetchMaterials(search, loading) {
    loading(true);
    searchMaterial(search, loading)
  }
  watch(() => props.form.business_unit, (newValue, oldValue) => {
    if(newValue !== oldValue && oldValue != ''){
      props.form.scm_warehouse_id = '';
      props.form.scmWarehouse = null;
    }
  });

//watch props.form.materials find amount from unit_price and quantity with parseFloat and toFixed 2
//watch props.form.materials find total_amount from unit_price and quantity with parseFloat and toFixed 2
//watch props.form.materials find sub_total from total_amount with parseFloat and toFixed 2
//watch props.form.materials find discount from sub_total with parseFloat and toFixed 2
//watch props.form.materials find total_amount from sub_total and discount with parseFloat and toFixed 2
//watch props.form.materials find vat from total_amount with parseFloat and toFixed 2
//watch props.form.materials find net_amount from total_amount and vat with parseFloat and toFixed 2
watch(() => props?.form?.scmPoLines, (newVal, oldVal) => {
      let total = 0.0;
      newVal?.forEach((material, index) => {
        props.form.scmPoLines[index].total_amount = parseFloat((material?.rate * material?.quantity).toFixed(2));
        total += parseFloat(props.form.scmPoLines[index].total_amount);
      });
  props.form.sub_total = parseFloat(total.toFixed(2));
      calculateNetAmount();
}, { deep: true });
    
  function calculateNetAmount(){
    props.form.total_amount = parseFloat((props.form.sub_total - props.form.discount).toFixed(2));
    props.form.net_amount = parseFloat((props.form.total_amount + parseFloat(props.form.vat)).toFixed(2));
}
 
  watch(() => props?.form?.discount, (newVal, oldVal) => {
    calculateNetAmount();
  });
  watch(() => props?.form?.vat, (newVal, oldVal) => {
    calculateNetAmount();
  });

</script>
<template>

  
<!-- const purchaseOrder = ref( {
        ref_no: '',
        scmWarehouse: '',
        scm_warehouse_name: '',
        scm_warehouse_id: '',
        po_date: '',
        pr_no: null,
        scm_pr_id: null,
        scmPr: null,
        pr_date: '',
        cs_no: '',
        scm_cs_id: '',
        scmCs: null,
        scmVendor: null,
        scm_vendor_id: null,
        vendor_name: null,
        currency: 0.0,
        convertion_rate: '',
        remarks: '',
        sub_total: 0.0,
        discount: 0.0,
        total_amount: 0.0,
        vat: 0.0,
        net_amount: 0.0,
        attachment: '',
        business_unit: '',
        scmPoLines: [
                        {
                            scmMaterial: '',
                            scm_material_id: '',
                            unit: '',
                            brand: '',
                            model: '',
                            required_date: '',
                            quantity: 0.0,
                            rate: 0.0,
                            total_price: 0.0,
                        }
                    ],
        scmPoTerms: [
                        {
                            details: ''
                        }
                    ],  
        });
    const materialObject = {
        scmMaterial: '',
        scm_material_id: '',
        unit: '',
        brand: '',
        model: '',
        required_date: '',
        quantity: 0.0,
        rate: 0.0,
        total_price: 0.0,
    }

    const termsObject =  {
        details: ''
    } -->

  <!-- Basic information -->
  <div class="flex flex-col justify-center w-1/4 md:flex-row md:gap-2">
    <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
  </div>
  <div class="input-group !w-1/4">
      <label class="label-group">
          <span class="label-item-title">Po Ref<span class="text-red-500">*</span></span>
          <input type="text" readonly v-model="form.ref_no" required class="form-input vms-readonly-input" name="ref_no" :id="'ref_no'" />
          <Error v-if="errors?.ref_no" :errors="errors.ref_no"  />
      </label>
    </div>
  <div class="input-group">
      <label class="label-group">
        <span class="label-item-title">Warehouse <span class="text-red-500">*</span></span>
        <input type="text" readonly v-model="form.scmWarehouse.name" required class="form-input vms-readonly-input" name="scm_warehouse_id" :id="'scm_warehouse_id'" />
        <Error v-if="errors?.scm_warehouse_id" :errors="errors.scm_warehouse_id" />
      </label>
      <label class="label-group">
          <span class="label-item-title">PO Date<span class="text-red-500">*</span></span>
          <input type="date" v-model="form.po_date" required class="form-input" name="po_date" :id="'po_date'" />
          <Error v-if="errors?.po_date" :errors="errors.po_date"  />
      </label>
      <label class="label-group">
        <span class="label-item-title">PR No <span class="text-red-500">*</span></span>
          <v-select :options="purchaseRequisitions" placeholder="--Choose an option--" @search="fetchPurchaseRequisition"  v-model="form.scmPurchaseRequisition" label="ref_no" class="block form-input">
          <template #search="{attributes, events}">
              <input
                  class="vs__search"
                  :required="!form.scmPurchaseRequisition"
                  v-bind="attributes"
                  v-on="events"
              />
          </template>
          </v-select>
          <Error v-if="errors?.unit" :errors="errors.unit" />
      </label>
      <label class="label-group">
          <span class="label-item-title">PR Date<span class="text-red-500">*</span></span>
          <input type="date" v-model="form.pr_date" required readonly class="form-input" name="pr_date" :id="'pr_date'" />
          <Error v-if="errors?.pr_date" :errors="errors.pr_date"  />
      </label>
      
  </div>
  <div class="input-group">    
    <label class="label-group">
        <span class="label-item-title">CS No</span>
       <!-- <input type="text" v-model="form.scmCs.ref_no" readonly required class="form-input" name="cs_ref" :id="'cs_ref'" /> -->
        <input type="text" v-model="form.scmCs" readonly required class="form-input" name="cs_ref" :id="'cs_ref'" /> 
        <Error v-if="errors?.scm_cs_id" :errors="errors.scm_cs_id" />
    </label>
    <label class="label-group" v-if="form.cs_no != ''">
          <span class="label-item-title">Vendor Name<span class="text-red-500">*</span></span>
          <select class="form-input" v-model="form.scm_vendor_id">
            <option value="" disabled>select</option>
            <option v-for="(csVendor,index) in csVendors" :value="csVendor.id">{{ csVendor?.name }}</option>
          </select>
          <Error v-if="errors?.scm_vendor_id" :errors="errors.scm_vendor_id"  />
      </label>
      <label class="label-group" v-else>
          <span class="label-item-title">Vendor Name<span class="text-red-500">*</span></span>
          <v-select :options="vendors" placeholder="--Choose an option--" @search="fetchVendor"  v-model="form.scmVendor" label="ref_no" class="block form-input">
          <template #search="{attributes, events}">
              <input
                  class="vs__search"
                  :required="!form.scmVendor"
                  v-bind="attributes"
                  v-on="events"
              />
          </template>
          </v-select>
          <Error v-if="errors?.approved_date" :errors="errors.approved_date"  />
      </label>
      
      <label class="label-group">
        <span class="label-item-title">Currency</span>
        <input type="text" v-model="form.currency" required class="form-input" name="currency" :id="'currency'" />
        <Error v-if="errors?.currency" :errors="errors.currency"/>
    </label>
      <label class="label-group">
          <span class="label-item-title">Convertion Rate( Foreign To BDT )<span class="text-red-500">*</span></span>
          <input type="text" v-model="form.convertion_rate" required class="form-input" name="approved_date" :id="'convertion_rate'" />
          <Error v-if="errors?.convertion_rate" :errors="errors.convertion_rate"  />
      </label>
  </div>

  <div class="input-group !w-3/4">
    <label class="label-group">
          <span class="label-item-title">Remarks <span class="text-red-500">*</span></span>
          <textarea v-model="form.remarks" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></textarea>
          <Error v-if="errors?.remarks" :errors="errors.remarks" />
    </label>
  </div>

  <div id="customDataTable">
    <div  class="table-responsive">
      <fieldset class="form-fieldset">
        <legend class="form-legend">Materials <span class="text-red-500">*</span></legend>
        <table class="w-full whitespace-no-wrap" id="table">
          <thead>
          <tr class="table_head_tr">
            <th class="py-3 align-center min-w-[200px] md:min-w-[250px] lg:min-w-[300px]">Material Name <br/> <span class="!text-[8px]">Material - Code</span></th>
            <th class="py-3 align-center">Unit</th>
            <th class="py-3 align-center">Brand</th>
            <th class="py-3 align-center">Model</th>
            <th class="py-3 align-center">Required Date</th>
            <th class="py-3 align-center">Qty</th>
            <th class="py-3 align-center">Rate</th>
            <th class="py-3 align-center">Total Price</th>
            <th class="py-3 text-center align-center">Action</th>
          </tr>
          </thead>

          <tbody class="table_body">
          <tr class="table_tr" v-for="(scmPoLine, index) in form.scmPoLines" :key="index">
            <td class="">
              <v-select :options="materials" placeholder="--Choose an option--" @search="fetchMaterials" v-model="form.scmPoLines[index].scmMaterial" label="material_name_and_code" class="block form-input" @change="setMaterialOtherData(form.scmPoLines[index].scmMaterial,index)" :menu-props="{ minWidth: '250px', minHeight: '400px' }">
                <template #search="{attributes, events}">
                    <input
                        class="vs__search"
                        :required="!form.scmPoLines[index].scmMaterial"
                        v-bind="attributes"
                        v-on="events"
                        />
                </template>
            </v-select>
            </td>
            <td>
              <input type="text" readonly v-model="form.scmPoLines[index].unit" class="vms-readonly-input form-input">
            </td>
            <td>
              <input type="text" v-model="form.scmPoLines[index].brand" class="form-input">
            </td>
            <td>
              <input type="text" v-model="form.scmPoLines[index].model" class="form-input">
            </td>
            <td>
              <input type="text" v-model="form.scmPoLines[index].required_date" class="form-input">
            </td>
            <td>
              <input type="text" v-model="form.scmPoLines[index].quantity" class="form-input">
            </td>
            <td>
              <input type="text" v-model="form.scmPoLines[index].rate" class="form-input">
            </td>
            <td>
              <input type="text" v-model="form.scmPoLines[index].total_amount" class="form-input">
            </td>
            <td class="px-1 py-1 text-center">
              <button v-if="index!=0" type="button" @click="removeMaterial(index)" class="remove_button">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg>
              </button>
              <button v-else type="button" @click="addMaterial()" class="add_button">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
              </button>
            </td>
          </tr>
          <tr>
            <td colspan="7" class="text-right">Sub Total</td>
            <td class="text-right">
              <input type="text" readonly class="vms-readonly-input form-input" v-model="form.sub_total">
            </td>
          </tr>
          <tr>
            <td colspan="7" class="text-right">Less: Discount</td>
            <td class="text-right">
              <input type="text" class="form-input" v-model="form.discount">
            </td>
          </tr>
          
          <tr>
            <td colspan="7" class="text-right">Total Amount</td>
            <td class="text-right">
              <input type="text" readonly class="vms-readonly-input form-input" v-model="form.total_amount">
            </td>
          </tr>
          <tr>
            <td colspan="7" class="text-right">Add: VAT</td>
            <td class="text-right">
              <input type="text" class="form-input" v-model="form.vat">
            </td>
          </tr>
          
          <tr>
            <td colspan="7" class="text-right">Net Amount</td>
            <td class="text-right">
              <input type="text" readonly class="vms-readonly-input form-input" v-model="form.net_amount">
            </td>
          </tr>
          </tbody>
        </table>
      </fieldset>
    </div>
  </div>

  <div id="customDataTable">
    <div  class="table-responsive max-w-screen">
      <fieldset class="form-fieldset">
        <legend class="form-legend">Terms And Conditions <span class="text-red-500">*</span></legend>
        <table class="w-full whitespace-no-wrap" id="table">
         <tbody class="table_body">
          <tr class="table_tr" v-for="(scmPoTerm, index) in form.scmPoTerms" :key="index">
            <td>
              <input type="text" v-model="form.scmPoTerms[index].details" class="form-input">
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
  </div>

</template>









<style lang="postcss" scoped>
    .input-group {
        @apply flex flex-col justify-center w-full md:flex-row md:gap-2;
    }
    .label-group {
        @apply block w-full mt-3 text-sm;
    }
    .label-item-title {
        @apply text-gray-700 dark:text-gray-300 text-sm;
    }
    .label-item-input {
        @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
    }
    .form-input {
        @apply block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
    }
    .form-fieldset {
      @apply px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400;
    }
    .form-legend {
      @apply px-2 text-gray-700 dark:text-gray-300;
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
      @apply text-gray-700 dark:text-gray-400;
    }
    .table_head_tr {
      @apply text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800;
    }
    .table_body {
      @apply bg-white divide-y dark:divide-gray-700 dark:bg-gray-800;
    }
    .remove_button {
      @apply px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple;
    }
    .add_button {
      @apply px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple;
    }
</style>