<template>
<div class="input-group">
  <label class="label-group">
        <span class="label-item-title font-bold">CS Ref<span class="text-red-500"> : </span></span>
        <span class="label-item-title"> {{ form.ref_no }}</span>
    </label>
    <label class="label-group">
        <span class="label-item-title font-bold">Warehouse<span class="text-red-500"> : </span></span>
        <span class="label-item-title"> {{ form.scmWarehouse?.name }}</span>
    </label>
    <label class="label-group">
        <span class="label-item-title font-bold">Expire Date<span class="text-red-500"> : </span></span>
        <span class="label-item-title"> {{ formatDate(form.expire_date) }}</span>
    </label>
  </div>

  <div class="input-group">
    <label class="label-group">
        <span class="label-item-title font-bold">Requirement Type<span class="text-red-500"> : </span></span>
        <span class="label-item-title"> {{ form.priority }}</span>
    </label>
    <label class="label-group">
        <span class="label-item-title font-bold">Required Days<span class="text-red-500"> : </span></span>
        <span class="label-item-title"> {{ form.required_days }}</span>
    </label>
    
    <label class="label-group">
        <span class="label-item-title font-bold">Effective Date<span class="text-red-500"> : </span></span>
        <span class="label-item-title"> {{ formatDate(form.effective_date) }}</span>
    </label>
  </div>

  <div class="input-group">
    <label class="label-group">
        <span class="label-item-title font-bold">Special Instruction<span class="text-red-500"> : </span></span>
        <span class="label-item-title"> {{ form.special_instructions }}</span>
    </label>
  </div>

<table class="min-w-full divide-y divide-gray-200 dark-disabled:divide-gray-700 mt-10">
  <caption class="border-2 bg-green-400 uppercase">Comparative Statement</caption>
  <thead class="bg-gray-50 dark-disabled:bg-gray-800">
    <tr class="text-gray-600 dark-disabled:text-gray-400 text-sm leading-normal">
      <th rowspan="2" class="px-6 py-3 text-left text-gray-600 dark-disabled:text-gray-400 uppercase tracking-wider">
        <nobr>Material Name</nobr>
      </th>
      <!-- <th rowspan="2" class="px-6 py-3 text-left text-gray-600 dark-disabled:text-gray-400 uppercase tracking-wider">
       <nobr>PR No</nobr> 
      </th> -->
      <th rowspan="2" class="px-6 py-3 text-left text-gray-600 dark-disabled:text-gray-400 uppercase tracking-wider">
        Unit
      </th> 
      <th rowspan="2" class="px-6 py-3 text-left text-gray-600 dark-disabled:text-gray-400 uppercase tracking-wider">
        Quantity
      </th>
      <th rowspan="2" class="px-6 py-3 text-left text-gray-600 dark-disabled:text-gray-400 uppercase tracking-wider">
        <nobr>Previous Selected Price</nobr>
      </th>
      <th colspan="2" scope="col" class="px-6 py-3 text-left text-gray-600 dark-disabled:text-gray-400 uppercase tracking-wider" v-for="(vendorData,index) in (formData?.scmCsVendor)" :key="index">
        <nobr>{{ vendorData[0].scmVendor?.name ?? ''}}</nobr>
      </th>  
      <th rowspan="2">
        Details
      </th>     
    </tr>
    <tr>
      <template v-for="(vendorData,index) in (formData?.scmCsVendor)" :key="index">
          <th>Rate</th>
          <th>Amount</th>
        </template>
    </tr>
  </thead>
  <tbody>
    <template v-for="(materialprData,index1) in (formData?.scmCsMaterial)" :key="index1">
      <template v-for="(materialData,name,index) in (materialprData)" :key="index">
      <tr v-if="index == 0">
        <td :rowspan="Object.keys(materialprData).length">{{ materialData[0].scmMaterial.name }}</td>
        <!-- <td>{{ materialData[0].scmPr.ref_no  }}</td> -->
        <td :rowspan="Object.keys(materialprData).length">{{ materialData[0].unit }}</td>
        <td>{{ materialData[0].sum_quantity }}</td>
        <td>{{ formData?.latestPoItem && formData?.latestPoItem.length ? formData?.latestPoItem[index1].rate : "N/A" }}</td>
        <template v-for="(materialVendorData,index11) in (formData?.scmCsMaterialVendor[index1][name])" :key="index11">
            <td>{{ materialVendorData[0].negotiated_price }}</td>
            <td>{{ materialVendorData[0].negotiated_price * materialData[0].sum_quantity}}</td>
         </template>
        <td :rowspan="Object.keys(materialprData).length">
          <a @click="showModal(index1,name)" style="display: inline-block;cursor: pointer" class="relative tooltip">
            <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
            </svg>
            <span class="tooltiptext">Details</span>
          </a>
        </td>
      </tr>
      <!-- <tr v-else>
        <td>{{ materialData[0].scmPr.ref_no  }}</td>
        <td>{{ materialData[0].quantity }}</td>
        <td></td>
        <template v-for="(materialVendorData,index11) in (formData?.scmCsMaterialVendor[index1][name])" :key="index11">
            <td>{{ materialVendorData[0].negotiated_price }}</td>
            <td>{{ materialVendorData[0].negotiated_price * materialData[0].quantity}}</td>
         </template>
      </tr> -->
    </template>
  </template>
  </tbody>
  </table>  
  <table class="min-w-full divide-y divide-gray-200 dark-disabled:divide-gray-700 mt-10">
  <caption class="border-2 bg-green-400 uppercase">Other Conditions</caption>
  <thead class="bg-gray-50 dark-disabled:bg-gray-800">
    <tr class="text-gray-600 dark-disabled:text-gray-400 text-sm leading-normal">
      <th class="px-6 py-3 text-center text-gray-600 dark-disabled:text-gray-400 uppercase tracking-wider">Description</th>
      <th class="px-6 py-3 text-center text-gray-600 dark-disabled:text-gray-400 uppercase tracking-wider" v-for="(vendorData,index) in (formData?.scmCsVendor)" :key="index">{{ vendorData[0].scmVendor?.name }}</th>       
    </tr> 
  </thead>
  <tbody>
    <tr v-if="form.purchase_center == 'Foreign'">
      <td>Origin</td>
      <td v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">{{ VendoData[0].origin }}</td>
    </tr>
    <tr v-if="form.purchase_center == 'Foreign'">
      <td>Brand</td>
      <td v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">{{ VendoData[0].brand }}</td>
    </tr>
    <tr>
      <td>Currency</td>
      <td v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">{{ VendoData[0].currency }}</td>
    </tr>

    <tr v-if="form.purchase_center == 'Foreign'">
      <td>Offer Price</td>
      <td v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">{{ VendoData[0].total_offered_price }}</td>
    </tr>
    <tr v-if="form.purchase_center == 'Foreign'">
      <td>Freight</td>
      <td v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">{{ VendoData[0].freight }}</td>
    </tr>
    <tr v-if="form.purchase_center == 'Foreign'">
      <td>Total Offer Price</td>
      <td v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">{{ VendoData[0].grand_total_offered_price }}</td>
    </tr>
    <tr v-if="form.purchase_center == 'Foreign'">
      <td>Negotiated Price</td>
      <td v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">{{ VendoData[0].total_negotiated_price }}</td>
    </tr>
    
    <tr>
      <td>Quotation Ref</td>
      <td v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">{{ VendoData[0].quotation_ref }}</td>
    </tr>
    <tr>
      <td>Quotation Received Date</td>
      <td v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">{{ formatDate(VendoData[0].quotation_received_date) }}</td>
    </tr>
    <tr>
      <td>Offer Validity</td>
      <td v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">{{ VendoData[0].quotation_validity }}</td>
    </tr>
    <tr>
      <td>Sourcing History</td>
      <td v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">{{ VendoData[0].sourcing }}</td>
    </tr>
    <tr>
      <td>Date Of RFQ</td>
      <td v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">{{ formatDate(VendoData[0].date_of_rfq) }}</td>
    </tr>
    <tr>
      <td>Party Type</td>
      <td v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">{{ VendoData[0].scmVendor.product_source_type }}</td>
    </tr>
    <tr>
      <td>Delivery Time</td>
      <td v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">{{ VendoData[0].delivery_time }}</td>
    </tr>
    <tr v-if="form.purchase_center == 'Foreign'">
      <td>Estimated Shipment Date</td>
      <td v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">{{ formatDate(VendoData[0].quotation_shipment_date) }}</td>
    </tr>
    <tr v-if="form.purchase_center == 'Foreign'">
      <td>Port Of Loading</td>
      <td v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">{{ VendoData[0].port_of_loading }}</td>
    </tr>
    <tr v-if="form.purchase_center == 'Foreign'">
      <td>Port Of Discharge</td>
      <td v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">{{ VendoData[0].port_of_discharge }}</td>
    </tr>
    <tr v-if="form.purchase_center == 'Foreign'">
      <td>Mode Of Shipment</td>
      <td v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">{{ VendoData[0].mode_of_shipment }}</td>
    </tr>
    
    <tr>
      <td>Remarks</td>
      <td v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">{{ VendoData[0].remarks }}</td>
    </tr>
    <tr>
      <td>Terms and Condition</td>
      <td v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">{{ VendoData[0].terms_and_condition }}</td>
    </tr>
    <tr v-if="form.purchase_center != 'Foreign'">
      <td>VAT</td>
      <td v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">{{ VendoData[0].vat }}</td>
    </tr>
    <tr v-if="form.purchase_center != 'Foreign'">
      <td>AIT</td>
      <td v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">{{ VendoData[0].ait }}</td>
    </tr>
    <tr v-if="form.purchase_center != 'Foreign'">
      <td>Payment Method</td>
      <td v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">{{ VendoData[0].payment_method }}</td>
    </tr>
    <tr v-if="form.purchase_center != 'Foreign'">
      <td>Inventory Type</td>
      <td v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">{{ VendoData[0].stock_type }}</td>
    </tr>
    <tr v-if="form.purchase_center != 'Foreign'">
      <td>Manufacturing Days</td>
      <td v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">{{ VendoData[0].manufacturing_days }}</td>
    </tr>
    <tr v-if="form.purchase_center != 'Foreign'">
      <td>Credit Term</td>
      <td v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">{{ VendoData[0].credit_term }}</td>
    </tr>
    <tr>
      <td>Inco-term</td>
      <td v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">{{ VendoData[0].delivery_term }}</td>
    </tr> 
    <tr v-if="form.purchase_center != 'Foreign'">
      <td>Carring Charge Bear By</td>
      <td v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">{{ VendoData[0].carring_cost_bear_by }}</td>
    </tr>
    <tr v-if="form.purchase_center != 'Foreign'">
      <td>Unloading Charge Bear By</td>
      <td v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">{{ VendoData[0].unloading_cost_bear_by }}</td>
    </tr>
    
    
    <tr>
      <td>Technical Acceptance </td>
      <td v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">
            <select v-model="form.scmCsVendor[index][0].technical_acceptance" class="form-input">
              <option value="Accepted">Accepted</option>
              <option value="Rejected">Rejected</option>
            </select>
      </td>
    </tr>
    <tr>
      <td>Selected Vendor</td>
      <td v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">
        <input type="checkbox" :class="'menu_' + VendoData.scm_cs_id" class="text-purple-600 form-radio focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray" :value="VendoData?.id" v-model="form.scmCsVendor[index][0].is_selected">
      </td>
    </tr>
  </tbody>
  </table>  
  <div class="input-group">
    <label class="label-group">
        <span class="label-item-title">Selection Ground<span class="text-red-500">*</span></span>
          <v-select :options="selection_ground" placeholder="--Choose an option--" v-model="form.selection_ground" label="name" class="block form-input" :reduce="selection_ground => selection_ground.value">
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
        <span class="label-item-title">Auditor Remarks Date<span class="text-red-500">*</span></span>
          <VueDatePicker v-model="form.auditor_remarks_date" class="form-input" required auto-apply :enable-time-picker = "false" placeholder="dd-mm-yyyy" format="dd-MM-yyyy" model-type="yyyy-MM-dd"></VueDatePicker>
    </label>
  </div>
  <div class="input-group">
    <label class="label-group">
          <RemarksComponet v-model="form.auditor_remarks" :maxlength="300" :fieldLabel="'Auditor Remarks'" isRequired="true"></RemarksComponet>
    </label>
  </div>






  <div v-show="isModalOpen" class="fixed inset-0 z-30 flex items-end overflow-y-auto bg-black bg-opacity-50 sm:items-center sm:justify-center">
    <!-- Modal -->
    <form @submit.prevent="" style="position: absolute;top: 0;">
      <div class="w-full px-6 py-4 overflow-y-auto bg-white rounded-t-lg dark-disabled:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl" role="dialog" id="modal">
        <!-- Remove header if you don't want a close icon. Use modal body to place modal tile. -->
        <header class="flex justify-end">
          <button type="button"
                  class="inline-flex items-center justify-center w-6 h-6 mb-2 text-gray-400 transition-colors duration-150 rounded dark-disabled:hover:text-gray-200 hover: hover:text-gray-700"
                  aria-label="close" @click="closeModel">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" role="img" aria-hidden="true">
              <path
                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                  clip-rule="evenodd" fill-rule="evenodd"></path>
            </svg>
          </button>
        </header>
        <!-- Modal body -->
        <table class="w-full mb-2 whitespace-no-wrap border-collapse contract-assign-table table2">
          <thead v-once>
          <tr style="background-color: #04AA6D;color: white"
              class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b dark-disabled:border-gray-700 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
            <th colspan="5">Details</th>
            <!-- <th colspan="5">{{ details }}</th>
            <template v-for="(VendoData,name,index) in (formData?.scmCsVendor)" :key="index" v-if="index == 0">
                  <th colspan="5">{{ details[index] && details[index][0] && details[index][0].scmMaterial.name }}</th>
            </template> -->
          </tr>
          </thead>
        </table>

        <div class="dt-responsive table-responsive">
          <table id="dataTable" class="w-full table table-striped table-bordered">
            <thead>
              <tr>
                <th>Description</th>
                <th scope="col" class="px-6 py-3 text-left text-gray-600 dark-disabled:text-gray-400 uppercase tracking-wider" v-for="(vendorData,index) in (formData?.scmCsVendor)" :key="index">{{ vendorData[0].scmVendor?.name }}</th> 
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Brand</td>
                <template v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">
                  <td>{{ details[index] && details[index][0] && details[index][0].brand }}</td>
                </template>
              </tr>
              <tr>
                <td>Model</td>
                <template v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">
                  <td>{{ details[index] && details[index][0] && details[index][0].model }}</td>
                </template>
              </tr>
              <tr v-if="form.purchase_center == 'Foreign'">
                <td>Origin</td>
                <template v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">
                  <td>{{ details[index] && details[index][0] && details[index][0].origin }}</td>
                </template>
              </tr>
              <tr v-if="form.purchase_center == 'Foreign'">
                <td>Installation and Commission</td>
                <template v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">
                  <td>{{ details[index] && details[index][0] && details[index][0].installation_and_commission }}</td>
                </template>
              </tr>
              <tr v-if="form.purchase_center == 'Foreign'">
                <td>Certification</td>
                <template v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">
                  <td>{{ details[index] && details[index][0] && details[index][0].certification }}</td>
                </template>
              </tr>
              <tr>
                <td>Warranty</td>
                <template v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">
                  <td>{{ details[index] && details[index][0] && details[index][0].warranty_period }}</td>
                </template>
              </tr>
              <tr v-if="form.purchase_center == 'Foreign'">
                <td>Offer Price</td>
                <template v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">
                  <td>{{ details[index] && details[index][0] && details[index][0].offered_price }}</td>
                </template>
              </tr>
              <tr v-if="form.purchase_center == 'Foreign'">
                <td>Negotiated Price</td>
                <template v-for="(VendoData,index) in (formData?.scmCsVendor)" :key="index">
                  <td>{{ details[index] && details[index][0] && details[index][0]?.negotiated_price }}</td>
                </template>
              </tr>
            </tbody>
          </table>
        </div>

        <footer class="flex flex-col items-center justify-end px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark-disabled:bg-gray-800">
          <button type="button" @click="closeModel" style="color: #1b1e21"
                  class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 border border-gray-300 rounded-lg dark-disabled:text-gray-400 sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray">
            OK
          </button>
          <!-- <button type="button" @click="pushBunkerConsumption"
              class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            Submit
          </button> -->
        </footer>
      </div>
    </form>
    </div>

    <ErrorComponent :errors="errors"></ErrorComponent>

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
    import { merge ,cloneDeep} from 'lodash';
    import useStoreIssue from '../../../composables/supply-chain/useStoreIssue';
    import useStoreIssueReturn from '../../../composables/supply-chain/useStoreIssueReturn';
    import useVendor from '../../../composables/supply-chain/useVendor';
    import useMaterialCs from '../../../composables/supply-chain/useMaterialCs';
    import { useRoute } from 'vue-router';
    import { formatDate } from '../../../utils/helper';
    import RemarksComponet from '../../utils/RemarksComponent.vue';
    import ErrorComponent from '../../utils/ErrorComponent.vue';
    
    const { material, materials, getMaterials,searchMaterial } = useMaterial();
    const { warehouses, warehouse, getWarehouses, searchWarehouse } = useWarehouse();
    const { searchVendor, vendors, vendor, isLoading: vendorLoading } = useVendor();
    const { materialCs, showMaterialCs } = useMaterialCs();
    const props = defineProps({
      form: { type: Object, required: true },
      errors: { type: [Object, Array], required: false },
      formType: { type: String, required: false },
      lineObj: { type: Object, required: false },
      page: {
      required: false,
        default: {},
       },
       formData: {
        required: false,
        default: {},
      },
    }); 

    const selection_ground = [
      { value: 'Price', name: 'Price' },
      { value: 'Quality', name: 'Quality' },
      { value: 'Management Recommendation', name: 'Management Recommendation' },
      { value: 'Auditors Recommendation', name: 'Auditors Recommendation' },
    ]; 

//after mount
onMounted(() => {
  watchEffect(() => {
  console.log(props.formData);
});
});




const isModalOpen = ref(0);
const details = ref([]);
const currentIndex = ref(null);


function showModal(index1,name) {
  isModalOpen.value = 1
  currentIndex.value = index1 + "-" + name;
  if(props.formData?.scmCsMaterialVendor[index1][name]) {
    details.value = cloneDeep(props.formData?.scmCsMaterialVendor[index1][name])
  } else {
    details.value = []
  }
  console.log(details.value);
}

function closeModel() {
  isModalOpen.value = 0
  details.value = []
}



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