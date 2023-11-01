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
    
    function fetchVendor(search, loading) {
    loading(true);
    searchVendor(search, loading);
  }
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


  <!-- Basic information -->
  <div class="flex flex-col justify-center w-1/4 md:flex-row md:gap-2">
    <business-unit-input :page="page" v-model="form.lc_no"></business-unit-input>
  </div>
  <div class="input-group">
      <label class="label-group">
        <span class="label-item-title">LC No <span class="text-red-500">*</span></span>
        <input type="text" readonly v-model="form.lc_no" required class="form-input vms-readonly-input" name="scm_warehouse_id" :id="'scm_warehouse_id'" />
        <Error v-if="errors?.scm_warehouse_id" :errors="errors.scm_warehouse_id" />
      </label>
      <label class="label-group">
          <span class="label-item-title">LC Date<span class="text-red-500">*</span></span>
          <input type="date" v-model="form.lc_no" required class="form-input" name="po_date" :id="'po_date'" />
          <Error v-if="errors?.po_date" :errors="errors.po_date"  />
      </label>
      <label class="label-group">
        <span class="label-item-title">LC Expire Date<span class="text-red-500">*</span></span>
          <v-select :options="purchaseRequisitions" placeholder="--Choose an option--" @search="fetchPurchaseRequisition"  v-model="form.lc_no" label="ref_no" class="block form-input">
          <template #search="{attributes, events}">
              <input
                  class="vs__search"
                  :required="!form.lc_no"
                  v-bind="attributes"
                  v-on="events"
              />
          </template>
          </v-select>
          <Error v-if="errors?.unit" :errors="errors.unit" />
      </label>
      <label class="label-group">
          <span class="label-item-title">Weight<span class="text-red-500">*</span></span>
          <input type="date" v-model="form.lc_no" required readonly class="form-input" name="pr_date" :id="'pr_date'" />
          <Error v-if="errors?.pr_date" :errors="errors.pr_date"  />
      </label>
      
  </div>
  <div class="input-group">    
    <label class="label-group">
        <span class="label-item-title">No. Of Packet</span>
       <!-- <input type="text" v-model="form.lc_no.ref_no" readonly required class="form-input" name="cs_ref" :id="'cs_ref'" /> -->
        <input type="text" v-model="form.lc_no" readonly required class="form-input" name="cs_ref" :id="'cs_ref'" /> 
        <Error v-if="errors?.scm_cs_id" :errors="errors.scm_cs_id" />
    </label>
    <label class="label-group">
          <span class="label-item-title">PO No<span class="text-red-500">*</span></span>
          <select class="form-input" v-model="form.lc_no">
            <option value="" disabled>select</option>
            <option v-for="(csVendor,index) in csVendors" :value="csVendor.id">{{ csVendor?.name }}</option>
          </select>
          <Error v-if="errors?.scm_vendor_id" :errors="errors.scm_vendor_id"  />
      </label>
      
      <label class="label-group">
        <span class="label-item-title">Assessment Value</span>
        <input type="text" v-model="form.lc_no" required class="form-input" name="currency" :id="'currency'" />
        <Error v-if="errors?.currency" :errors="errors.currency"/>
    </label>
      <label class="label-group">
          <span class="label-item-title">Issuing Bank<span class="text-red-500">*</span></span>
          <input type="text" v-model="form.lc_no" required class="form-input" name="approved_date" :id="'convertion_rate'" />
          <Error v-if="errors?.convertion_rate" :errors="errors.convertion_rate"  />
      </label>
  </div>
  <div class="input-group">    
    <label class="label-group">
        <span class="label-item-title">Issuing Bank</span>
       <!-- <input type="text" v-model="form.lc_no.ref_no" readonly required class="form-input" name="cs_ref" :id="'cs_ref'" /> -->
        <input type="text" v-model="form.lc_no" readonly required class="form-input" name="cs_ref" :id="'cs_ref'" /> 
        <Error v-if="errors?.scm_cs_id" :errors="errors.scm_cs_id" />
    </label>
    <label class="label-group">
          <span class="label-item-title">Advising Bank<span class="text-red-500">*</span></span>
          <select class="form-input" v-model="form.lc_no">
            <option value="" disabled>select</option>
            <option v-for="(csVendor,index) in csVendors" :value="csVendor.id">{{ csVendor?.name }}</option>
          </select>
          <Error v-if="errors?.scm_vendor_id" :errors="errors.scm_vendor_id"  />
      </label>
      
      <label class="label-group">
        <span class="label-item-title">Beneficiary Bank</span>
        <input type="text" v-model="form.lc_no" required class="form-input" name="currency" :id="'currency'" />
        <Error v-if="errors?.currency" :errors="errors.currency"/>
    </label>
      <label class="label-group">
          <span class="label-item-title">Issuing Bank<span class="text-red-500">*</span></span>
          <input type="text" v-model="form.lc_no" required class="form-input" name="approved_date" :id="'convertion_rate'" />
          <Error v-if="errors?.convertion_rate" :errors="errors.convertion_rate"  />
      </label>
  </div>
  <div class="input-group !w-1/2">    
    <label class="label-group">
        <span class="label-item-title">Party Name</span>
       <!-- <input type="text" v-model="form.lc_no.ref_no" readonly required class="form-input" name="cs_ref" :id="'cs_ref'" /> -->
        <input type="text" v-model="form.lc_no" readonly required class="form-input" name="cs_ref" :id="'cs_ref'" /> 
        <Error v-if="errors?.scm_cs_id" :errors="errors.scm_cs_id" />
    </label>
    <label class="label-group">
          <span class="label-item-title">Attachment<span class="text-red-500">*</span></span>
          <select class="form-input" v-model="form.lc_no">
            <option value="" disabled>select</option>
            <option v-for="(csVendor,index) in csVendors" :value="csVendor.id">{{ csVendor?.name }}</option>
          </select>
          <Error v-if="errors?.scm_vendor_id" :errors="errors.scm_vendor_id"  />
      </label>
  </div>


  <div id="customDataTable">
    <div  class="table-responsive">
      <!-- <fieldset class="form-fieldset">
        <legend class="form-legend">Materials <span class="text-red-500">*</span></legend> -->
        
        <table class="w-full whitespace-no-wrap" id="table">
          //table caption with border and text center
          <caption class="table_caption p-2 border-2 mt-7 bg-gray-400 text">Materials</caption>
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
          <tr class="table_tr" v-for="(scmPoLine, index) in form.scmLcRecordLines" :key="index">
            <td class="">
              <v-select :options="materials" placeholder="--Choose an option--" @search="fetchMaterials" v-model="form.scmLcRecordLines[index].scmMaterial" label="material_name_and_code" class="block form-input" @change="setMaterialOtherData(form.scmLcRecordLines[index].scmMaterial,index)" :menu-props="{ minWidth: '250px', minHeight: '400px' }">
                <template #search="{attributes, events}">
                    <input
                        class="vs__search"
                        :required="!form.scmLcRecordLines[index].scmMaterial"
                        v-bind="attributes"
                        v-on="events"
                        />
                </template>
            </v-select>
            </td>
            <td>
              <input type="text" readonly v-model="form.scmLcRecordLines[index].unit" class="vms-readonly-input form-input">
            </td>
            <td>
              <input type="text" v-model="form.scmLcRecordLines[index].brand" class="form-input">
            </td>
            <td>
              <input type="text" v-model="form.scmLcRecordLines[index].model" class="form-input">
            </td>
            <td>
              <input type="text" v-model="form.scmLcRecordLines[index].required_date" class="form-input">
            </td>
            <td>
              <input type="text" v-model="form.scmLcRecordLines[index].quantity" class="form-input">
            </td>
            <td>
              <input type="text" v-model="form.scmLcRecordLines[index].rate" class="form-input">
            </td>
            <td>
              <input type="text" v-model="form.scmLcRecordLines[index].total_amount" class="form-input">
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
              <input type="text" readonly class="vms-readonly-input form-input" v-model="form.lc_no">
            </td>
          </tr>
          <tr>
            <td colspan="7" class="text-right">Less: Discount</td>
            <td class="text-right">
              <input type="text" class="form-input" v-model="form.lc_no">
            </td>
          </tr>
          
          <tr>
            <td colspan="7" class="text-right">Total Amount</td>
            <td class="text-right">
              <input type="text" readonly class="vms-readonly-input form-input" v-model="form.lc_no">
            </td>
          </tr>
          <tr>
            <td colspan="7" class="text-right">Add: VAT</td>
            <td class="text-right">
              <input type="text" class="form-input" v-model="form.lc_no">
            </td>
          </tr>
          
          <tr>
            <td colspan="7" class="text-right">Net Amount</td>
            <td class="text-right">
              <input type="text" readonly class="vms-readonly-input form-input" v-model="form.lc_no">
            </td>
          </tr>
          </tbody>
        </table>
      <!-- </fieldset> -->
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