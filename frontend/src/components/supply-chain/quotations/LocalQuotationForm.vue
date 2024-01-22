<template>
  <!-- Basic information -->
  <div class="flex flex-col justify-center w-1/4 md:flex-row md:gap-2">
    <!-- <business-unit-input :page="{edit}" v-model="form.business_unit"></business-unit-input> -->
  </div>
  <div class="flex flex-col justify-center w-1/4 md:flex-row md:gap-2">
 
    <label class="label-group">
          <span class="label-item-title">CS Ref</span>
          <input
            type="text"
            readonly
            :value="form.scmCs?.ref_no"
            required
            class="form-input vms-readonly-input"
            name="ref_no"
            :id="'ref_no'" />
      </label>
  </div>
  <div class="input-group">
    <label class="label-group">
        <span class="label-item-title">Vendor Name <span class="text-red-500">*</span></span>
          <v-select :options="vendors" placeholder="--Choose an option--" :loading="vendorLoading" v-model="form.scmVendor" label="name" class="block form-input" @update:modelValue="setVendorOtherData(form.scmVendor)">
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
        <span class="label-item-title">Vendor Contact No <span class="text-red-500">*</span></span>
        <input
          type="text"
          :value="form.scmVendor?.scmVendorContactPerson?.phone"
          class="form-input vms-readonly-input" readonly />
    </label>
    <label class="label-group">
        <span class="label-item-title">Party Type <span class="text-red-500">*</span></span>
        <input
          type="text"
          :value="form.scmVendor?.product_source_type"
          class="form-input vms-readonly-input" readonly/>
    </label>
    <label class="label-group">
        <span class="label-item-title">Sourcing History <span class="text-red-500">*</span></span>
        <select v-model="form.sourcing" class="form-input" required>
            <option value="Existing">Existing</option>
            <option value="New">New</option>
        </select>
    </label>   
  </div>
  <div class="input-group">
      <label class="label-group">
        <span class="label-item-title">Date Of RFQ <span class="text-red-500">*</span></span>
          <VueDatePicker v-model="form.date_of_rfq" class="form-input" required auto-apply :enable-time-picker = "false" placeholder="dd-mm-yyyy" format="dd-MM-yyyy" model-type="yyyy-MM-dd"></VueDatePicker>
       </label>
      <label class="label-group">
        <span class="label-item-title">Quotation Received Date <span class="text-red-500">*</span></span>
          <VueDatePicker v-model="form.quotation_received_date" class="form-input" required auto-apply :enable-time-picker = "false" placeholder="dd-mm-yyyy" format="dd-MM-yyyy" model-type="yyyy-MM-dd"></VueDatePicker>
      </label>
      <label class="label-group">
        <span class="label-item-title">Vendor Quotation No <span class="text-red-500">*</span></span>
          <input type="text" v-model="form.quotation_ref" class="form-input" name="scm_department_id" :id="'scm_department_id'" min=1/>
          <!-- <Error v-if="errors?.scm_department_id" :errors="errors.scm_department_id" /> -->
      </label>
      <label class="label-group">
        <span class="label-item-title">PI Date <span class="text-red-500">*</span></span>
         <VueDatePicker v-model="form.quotation_date" class="form-input" required auto-apply :enable-time-picker = "false" placeholder="dd-mm-yyyy" format="dd-MM-yyyy" model-type="yyyy-MM-dd"></VueDatePicker>
      </label>
  </div>

  <div class="input-group">
      <label class="label-group">
        <span class="label-item-title">Offer Validity <span class="text-red-500">*</span></span>
          <input type="date" v-model="form.quotation_validity" class="form-input" name="scm_department_id" :id="'scm_department_id'" required/>
      </label>
      <label class="label-group">
        <span class="label-item-title">Payment Method <span class="text-red-500">*</span></span>
         <select v-model="form.payment_method" class="form-input" required>
            <option value="Cash">Cash</option>
            <option value="Credit">Credit</option>
        </select>
      </label>
      <label class="label-group">
        <span class="label-item-title">Currency <span class="text-red-500">*</span></span>
          <input type="text" v-model="form.currency" class="form-input" required/>
      </label>
      <label class="label-group">
        <span class="label-item-title">Carrying Charge Bear By <span class="text-red-500">*</span></span>
          <input type="text" v-model="form.carring_cost_bear_by" class="form-input" required/>
      </label>
  </div>

  
  <div class="input-group">
      <label class="label-group">
        <span class="label-item-title">Unloading Charge Bear By <span class="text-red-500">*</span></span>
          <input type="text" v-model="form.unloading_cost_bear_by" class="form-input" required/>
      </label>
      <label class="label-group">
        <span class="label-item-title">VAT <span class="text-red-500">*</span></span>
          <input type="text" v-model="form.vat" class="form-input" required/>
      </label>
      <label class="label-group">
        <span class="label-item-title">AIT <span class="text-red-500">*</span></span>
          <input type="text" v-model="form.ait" class="form-input" required/>
      </label>
      <label class="label-group">
        <span class="label-item-title">Warranty <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.warranty" class="form-input"/>
      
      </label>
  </div>

  
  <div class="input-group">
    <label class="label-group">
        <span class="label-item-title">Delivery Time (Days) <span class="text-red-500">*</span></span>
          <input type="number" v-model="form.delivery_time" required class="form-input " name="scm_department_id" :id="'scm_department_id'" />
      </label>
    <label class="label-group">
        <span class="label-item-title">Inco-term <span class="text-red-500">*</span></span>
          <input type="text" v-model="form.delivery_term" class="form-input" required/>
      </label>
    <label class="label-group">
        <span class="label-item-title">Credit Term <span class="text-red-500">*</span></span>
          <input type="text" v-model="form.credit_term" class="form-input" required/>
      </label>
  </div>

  <div class="input-group !w-3/4">
    <label class="label-group">
            <RemarksComponet v-model="form.terms_and_condition" :maxlength="300" :fieldLabel="'Terms & Conditions'" isRequired="true"></RemarksComponet>
    </label>
  </div>  
  <div class="input-group !w-3/4">
    <label class="label-group">
            <RemarksComponet v-model="form.remarks" :maxlength="300" :fieldLabel="'Remarks'" isRequired="true"></RemarksComponet>
    </label>
  </div>  

  <div id="" class="w-full">
    <div id="">
      <div class="table-responsive min-w-screen">
        <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
          <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Materials <span class="text-red-500">*</span></legend>
          <table class="whitespace-no-wrap">
            <thead>
            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
              <th class="py-3 align-center">Material Name </th>
              <th class="py-3 align-center">PR No </th>
              <th class="py-3 align-center">Unit</th>
              <th class="py-3 align-center">Brand</th>
              <th class="py-3 align-center">Model</th>
              <th class="py-3 align-center">Warranty Period</th>
              <th class="py-3 align-center">Quantity</th>
              <!-- <th class="py-3 align-center">Offer Price</th> -->
              <th class="py-3 align-center">Price</th>
              <th class="py-3 align-center">Amount </th>
            </tr>
            </thead>

            <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
              
              <template v-for="(lines, indexa) in form.scmCsMaterialVendors" :key="indexa">
                <template v-for="(scmSrLine, index) in lines" :key="index">
                  <tr v-if="index != 0">
                    <td><nobr>{{ scmSrLine?.scmPr?.ref_no }}</nobr></td>
                  </tr>
                  <tr class="text-gray-700 dark-disabled:text-gray-400" v-else>
                    <td :rowspan="lines.length">{{ first(values(lines))?.scmMaterial?.name }}</td>
                    <td><nobr>{{ scmSrLine?.scmPr?.ref_no }}</nobr></td>
                    <td :rowspan="lines.length">{{ scmSrLine?.scmMaterial?.unit }}</td>
                    <td v-if="form.scmCsMaterialVendors[indexa][index]" :rowspan="lines.length">
                      <input type="text" v-model="form.scmCsMaterialVendors[indexa][index].brand" class="form-input" required/>
                    </td>
                    <td v-if="form.scmCsMaterialVendors[indexa][index]" :rowspan="lines.length">
                      <input type="text" v-model="form.scmCsMaterialVendors[indexa][index].model" class="form-input" required/>
                    </td>
                    <td v-if="form.scmCsMaterialVendors[indexa][index]" :rowspan="lines.length">
                      <input type="text" v-model="form.scmCsMaterialVendors[indexa][index].warranty_period" class="form-input" required/>
                    </td>
                    <td v-if="form.scmCsMaterialVendors[indexa][index]" :rowspan="lines.length">
                      <input type="text" v-model="form.scmCsMaterialVendors[indexa][index].quantity" class="form-input" required readonly/>
                    </td>
                    <!-- <td v-if="form.scmCsMaterialVendors[indexa][index]" :rowspan="lines.length">
                      <input type="number" v-model="form.scmCsMaterialVendors[indexa][index].offered_price" class="form-input"/>
                    </td> -->
                    <td v-if="form.scmCsMaterialVendors[indexa][index]" :rowspan="lines.length">
                      <input type="number" v-model="form.scmCsMaterialVendors[indexa][index].negotiated_price" class="form-input" min="1"/>
                    </td>
                    <td v-if="form.scmCsMaterialVendors[indexa][index]" :rowspan="lines.length">
                      <input type="number" v-model="form.scmCsMaterialVendors[indexa][index].negotiated_amount" class="form-input" min="1"/>
                    </td>
                  </tr>
                </template>
              </template>
            </tbody>
            <tfoot>
              <!-- <tr>
                <td colspan="8" class="text-right">
                  Total
                </td>
                <td class="text-right">
                  <input type="text" v-model="form.total_negotiated_price" class="form-input" required/>
                </td>
                <td class="text-right">
                  <input type="text" v-model="form.total_offered_price" class="form-input" required/>
                </td>
              </tr>
              <tr>
                <td colspan="8" class="text-right">
                  Freight
                </td>
                <td class="text-right">
                  <input type="text" v-model="form.freight" class="form-input" required/>
                </td>
                <td class="text-right">
                  <input type="text" :aria-valuenow="form.freight" class="form-input" required/>
                </td>
              </tr> -->
              <tr>
                <td colspan="8" class="text-right">
                  Grand Total
                </td>
                <td class="text-right">
                  <input type="text" v-model="form.grand_total_negotiated_price" class="form-input" required/>
                </td>
                <!-- <td class="text-right">
                  <input type="text" v-model="form.grand_total_offered_price" class="form-input" required/>
                </td> -->
              </tr>
            </tfoot>
          </table>
        </fieldset>
      </div>
    </div>
  </div>
</template>



<script setup>
    import { ref, watch, onMounted,watchEffect,computed, toRefs } from 'vue';
    import Error from "../../Error.vue";
    import DropZone from "../../DropZone.vue";
    import useMaterial from "../../../composables/supply-chain/useMaterial.js";
    import useWarehouse from "../../../composables/supply-chain/useWarehouse.js";
    import BusinessUnitInput from "../../input/BusinessUnitInput.vue";
    import DropZoneV2 from '../../DropZoneV2.vue';
    import {useStore} from "vuex";
    import env from '../../../config/env';
    // import cloneDeep from 'lodash/cloneDeep';
    import { merge ,cloneDeep,groupBy,first, values} from 'lodash';
    import useStoreIssue from '../../../composables/supply-chain/useStoreIssue';
    import useStoreIssueReturn from '../../../composables/supply-chain/useStoreIssueReturn';
    import useVendor from '../../../composables/supply-chain/useVendor';
    import useMaterialCs from '../../../composables/supply-chain/useMaterialCs';
    import { useRoute } from 'vue-router';
    import RemarksComponet from '../../utils/RemarksComponent.vue';
    
    const { material, materials, getMaterials,searchMaterial } = useMaterial();
    const { warehouses, warehouse, getWarehouses, searchWarehouse } = useWarehouse();
    const { searchVendor, vendors, vendor, isLoading: vendorLoading } = useVendor();
    const { materialCs, showMaterialCs, getCsData } = useMaterialCs();
    
    const props = defineProps({
      form: { type: Object, required: true },
      errors: { type: [Object, Array], required: false },
      formType: { type: String, required: false },
      lineObj: { type: Object, required: false },
      page: {
      required: false,
      default: {}
    },

    }); 

    const route = useRoute();
    const CSID = route.params.csId;

    watch(() => materialCs.value, (newVal, oldVal) => {
          props.form.scmCs = newVal;
          props.form.scm_cs_id = newVal?.id;

          if (props.formType != 'edit') {
            props.form.scmCsMaterialVendors = [];
          
            materialCs.value.scmCsMaterials.map((lines, index) => {
              lines.map((line, index) => {
                line['negotiated_price'] = null;
                line['warranty_period'] = null;
                line['brand'] = null;
                line['model'] = null;
              });
            });
            props.form.scmCsMaterialVendors = materialCs.value.scmCsMaterials;
          }
      });


    const addLine = () => {
      const line = cloneDeep(props.lineObj);  
      props.form.scmCsMaterialVendors.push(line);
    };


    const removeLine = (index) => {
      props.form.scmCsMaterialVendors.splice(index, 1);
    };

    const PRIORITY = ['High', 'Medium', 'Low']
    const form = toRefs(props).form;
    
   
    const tableScrollWidth = ref(null);
    const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;

    function changeMaterial(value,index) {
        props.form.scmCsMaterialVendors[index].unit = value.unit;
        props.form.scmCsMaterialVendors[index].scm_material_id = value.id;
    }

  function setVendorOtherData() {
        props.form.scm_vendor_id = props.form.scmVendor?.id;
      }







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
  searchVendor('');
  searchMaterial('');
  getCsData(CSID);
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