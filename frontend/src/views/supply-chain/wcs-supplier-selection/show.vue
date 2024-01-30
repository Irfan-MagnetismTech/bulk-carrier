<script setup>
import {ref, watch, onMounted,watchEffect,computed, toRefs} from "vue";

import Title from "../../../services/title";
import useMaterialCs from "../../../composables/supply-chain/useMaterialCs";
import useHelper from "../../../composables/useHelper.js";
import useHeroIcon from "../../../assets/heroIcon";
import useSupplierSelection from "../../../composables/supply-chain/useSupplierSelection";
import { useRouter, useRoute } from 'vue-router';
import useMaterial from "../../../composables/supply-chain/useMaterial.js";
import useWarehouse from "../../../composables/supply-chain/useWarehouse.js";
import {useStore} from "vuex";
import env from '../../../config/env';
import { merge ,cloneDeep} from 'lodash';
import useVendor from '../../../composables/supply-chain/useVendor';
import { formatDate } from '../../../utils/helper';
import RemarksComponent from "../../../components/utils/RemarksComponent.vue";
import useWcsSupplierSelection from "../../../composables/supply-chain/useWcsSupplierSelection";

const icons = useHeroIcon();
const route = useRoute();
const WCSID = route.params.wcsId;

const { WcsData, create , wcsQuotation, store, isLoading, errors} = useWcsSupplierSelection();

const { warehouses, warehouse, getWarehouses, searchWarehouse } = useWarehouse();
const { searchVendor, vendors, vendor, isLoading: vendorLoading } = useVendor();

const page = ref('create');
const { setTitle } = Title();


onMounted(() => {
    create(WCSID);
 }); 

 watch(() => WcsData.value, (value) => {
    if(Object.entries(value).length !== 0) {
        wcsQuotation.value = value;
        wcsQuotation.value.supplier_selection = [];
        Object.entries(value.scmWcsVendor).forEach(([key, data]) => {
            wcsQuotation.value.supplier_selection.push({ 'is_selected': false, 'scm_wcs_vendor_id': data[0]?.id, 'scm_vendor_id': data[0]?.scm_vendor_id});
        });
    }
  });
  
setTitle('Select Supplier');



const selection_ground = [
    { value: 'Price', name: 'Price' },
    { value: 'Quality', name: 'Quality' },
    { value: 'Management Recommendation', name: 'Management Recommendation' },
    { value: 'Auditors Recommendation', name: 'Auditors Recommendation' },
]; 


    









</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Select Supplier</h2>
        <!-- <default-button :title="'Quotations List'" :to="{ name: 'scm.quotations.index' }" :icon="icons.DataBase"></default-button> -->
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      
        <div class="input-group">
  <label class="label-group">
        <span class="label-item-title font-bold">WCS Ref<span class="text-red-500"> : </span></span>
        <span class="label-item-title"> {{ wcsQuotation.ref_no }}</span>
    </label>
    <label class="label-group">
        <span class="label-item-title font-bold">Warehouse<span class="text-red-500"> : </span></span>
        <span class="label-item-title"> {{ wcsQuotation.scmWarehouse?.name }}</span>
    </label>
    <label class="label-group">
        <span class="label-item-title font-bold">Expire Date<span class="text-red-500"> : </span></span>
        <span class="label-item-title"> {{ formatDate(wcsQuotation.expire_date) }}</span>
    </label>
  </div>

  <div class="input-group">
    <label class="label-group">
        <span class="label-item-title font-bold">Requirement Type<span class="text-red-500"> : </span></span>
        <span class="label-item-title"> {{ wcsQuotation.priority }}</span>
    </label>
    <label class="label-group">
        <span class="label-item-title font-bold">Required Days<span class="text-red-500"> : </span></span>
        <span class="label-item-title"> {{ wcsQuotation.required_days }}</span>
    </label>
    
    <label class="label-group">
        <span class="label-item-title font-bold">Effective Date<span class="text-red-500"> : </span></span>
        <span class="label-item-title"> {{ formatDate(wcsQuotation.effective_date) }}</span>
    </label>
  </div>

  <div class="input-group">
    <label class="label-group">
        <span class="label-item-title font-bold">Special Instruction<span class="text-red-500"> : </span></span>
        <span class="label-item-title"> {{ wcsQuotation.special_instructions }}</span>
    </label>
  </div>
  <div class="input-group">
    <label class="label-group">
        <span class="label-item-title font-bold">Selection Ground<span class="text-red-500"> : </span></span>
        <span class="label-item-title"> {{ wcsQuotation.selection_ground }}</span>
    </label>
  </div>
  <div class="input-group">
    <label class="label-group">
        <span class="label-item-title font-bold">Remarks Date<span class="text-red-500"> : </span></span>
        <span class="label-item-title"> {{ wcsQuotation.auditor_remarks_date }}</span>
    </label>
  </div>
  <div class="input-group">
    <label class="label-group">
        <span class="label-item-title font-bold">Auditors Remarks<span class="text-red-500"> : </span></span>
        <span class="label-item-title"> {{ wcsQuotation.auditor_remarks }}</span>
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
      <th colspan="2" scope="col" class="px-6 py-3 text-left text-gray-600 dark-disabled:text-gray-400 uppercase tracking-wider" v-for="(vendorData,index) in (WcsData?.scmWcsVendor)" :key="index">
        <nobr>{{ vendorData[0].scmVendor?.name ?? ''}}</nobr>
      </th>    
    </tr>
    <tr>
      <template v-for="(vendorData,index) in (WcsData?.scmWcsVendor)" :key="index">
          <th>Rate</th>
          <th>Amount</th>
        </template>
    </tr>
  </thead>
  <tbody>
    <template v-for="(serviceprData,index1) in (WcsData?.scmWcsService)" :key="index1">
      <template v-for="(serviceData,name,index) in (serviceprData)" :key="index">
      <tr v-if="index == 0">
        <td :rowspan="Object.keys(serviceprData).length">{{ serviceData[0].scmService.name }}</td>
        <!-- <td>{{ serviceData[0].scmPr.ref_no  }}</td> -->
        <td :rowspan="Object.keys(serviceprData).length">{{ serviceData[0].unit }}</td>
        <td>{{ serviceData[0].sum_quantity }}</td>
        <td>{{ WcsData?.latestPoItem && WcsData?.latestPoItem.length ? WcsData?.latestPoItem[index1].rate : "N/A" }}</td>
        <template v-for="(serviceVendorData,index11) in (WcsData?.scmWcsVendorService[index1][name])" :key="index11">
            <td>{{ serviceVendorData[0].negotiated_price }}</td>
            <td>{{ serviceVendorData[0].negotiated_price * serviceData[0].sum_quantity}}</td>
         </template>
      </tr>
      <!-- <tr v-else>
        <td>{{ serviceData[0].scmPr.ref_no  }}</td>
        <td>{{ serviceData[0].quantity }}</td>
        <td></td>
        <template v-for="(serviceVendorData,index11) in (WcsData?.scmWcsVendorService[index1][name])" :key="index11">
            <td>{{ serviceVendorData[0].negotiated_price }}</td>
            <td>{{ serviceVendorData[0].negotiated_price * serviceData[0].quantity}}</td>
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
      <th class="px-6 py-3 text-center text-gray-600 dark-disabled:text-gray-400 uppercase tracking-wider" v-for="(vendorData,index) in (WcsData?.scmWcsVendor)" :key="index">{{ vendorData[0].scmVendor?.name }}</th>       
    </tr> 
  </thead>
  <tbody>
    <tr>
      <td>Quotation Ref</td>
      <td v-for="(VendoData,index) in (WcsData?.scmWcsVendor)" :key="index">{{ VendoData[0].quotation_ref_no }}</td>
    </tr>
    <tr>
      <td>Quotation Received Date</td>
      <td v-for="(VendoData,index) in (WcsData?.scmWcsVendor)" :key="index">{{ formatDate(VendoData[0].quotation_date) }}</td>
    </tr>
    <tr>
      <td>Offer Validity</td>
      <td v-for="(VendoData,index) in (WcsData?.scmWcsVendor)" :key="index">{{ VendoData[0].quotation_validity }}</td>
    </tr>
    <tr>
      <td>Credit Term</td>
      <td v-for="(VendoData,index) in (WcsData?.scmWcsVendor)" :key="index">{{ VendoData[0].credit_term }}</td>
    </tr>
    <tr>
      <td>Payment Mode</td>
      <td v-for="(VendoData,index) in (WcsData?.scmWcsVendor)" :key="index">{{ formatDate(VendoData[0].payment_mode) }}</td>
    </tr>
    <tr>
      <td>Party Type</td>
      <td v-for="(VendoData,index) in (WcsData?.scmWcsVendor)" :key="index">{{ VendoData[0].scmVendor.product_source_type }}</td>
    </tr>
    <tr>
      <td>Delivery Time</td>
      <td v-for="(VendoData,index) in (WcsData?.scmWcsVendor)" :key="index">{{ VendoData[0].delivery_time }}</td>
    </tr>
    <tr>
      <td>Estimated Shipment Date</td>
      <td v-for="(VendoData,index) in (WcsData?.scmWcsVendor)" :key="index">{{ formatDate(VendoData[0].wcsQuotation_shipment_date) }}</td>
    </tr>
    <tr>
      <td>Port Of Loading</td>
      <td v-for="(VendoData,index) in (WcsData?.scmWcsVendor)" :key="index">{{ VendoData[0].port_of_loading }}</td>
    </tr>
    <tr>
      <td>Port Of Discharge</td>
      <td v-for="(VendoData,index) in (WcsData?.scmWcsVendor)" :key="index">{{ VendoData[0].port_of_discharge }}</td>
    </tr>
    <tr>
      <td>Mode Of Shipment</td>
      <td v-for="(VendoData,index) in (WcsData?.scmWcsVendor)" :key="index">{{ VendoData[0].mode_of_shipment }}</td>
    </tr>
    <tr>
      <td>Currency</td>
      <td v-for="(VendoData,index) in (WcsData?.scmWcsVendor)" :key="index">{{ VendoData[0].currency }}</td>
    </tr>
    <tr>
      <td>Remarks</td>
      <td v-for="(VendoData,index) in (WcsData?.scmWcsVendor)" :key="index">{{ VendoData[0].remarks }}</td>
    </tr>
    <tr>
      <td>Terms and Condition</td>
      <td v-for="(VendoData,index) in (WcsData?.scmWcsVendor)" :key="index">{{ VendoData[0].terms_and_condition }}</td>
    </tr>
    <tr v-if="wcsQuotation.purchase_center != 'Foreign'">
      <td>VAT</td>
      <td v-for="(VendoData,index) in (WcsData?.scmWcsVendor)" :key="index">{{ VendoData[0].vat }}</td>
    </tr>
    <tr v-if="wcsQuotation.purchase_center != 'Foreign'">
      <td>AIT</td>
      <td v-for="(VendoData,index) in (WcsData?.scmWcsVendor)" :key="index">{{ VendoData[0].ait }}</td>
    </tr>
    <tr v-if="wcsQuotation.purchase_center != 'Foreign'">
      <td>Payment Method</td>
      <td v-for="(VendoData,index) in (WcsData?.scmWcsVendor)" :key="index">{{ VendoData[0].payment_method }}</td>
    </tr>
    <tr v-if="wcsQuotation.purchase_center != 'Foreign'">
      <td>Inventory Type</td>
      <td v-for="(VendoData,index) in (WcsData?.scmWcsVendor)" :key="index">{{ VendoData[0].stock_type }}</td>
    </tr>
    <tr v-if="wcsQuotation.purchase_center != 'Foreign'">
      <td>Manufacturing Days</td>
      <td v-for="(VendoData,index) in (WcsData?.scmWcsVendor)" :key="index">{{ VendoData[0].manufacturing_days }}</td>
    </tr>
    <tr v-if="wcsQuotation.purchase_center != 'Foreign'">
      <td>Credit Term</td>
      <td v-for="(VendoData,index) in (WcsData?.scmWcsVendor)" :key="index">{{ VendoData[0].credit_term }}</td>
    </tr>
    <tr>
      <td>Inco-term</td>
      <td v-for="(VendoData,index) in (WcsData?.scmWcsVendor)" :key="index">{{ VendoData[0].delivery_term }}</td>
    </tr> 
    <tr v-if="wcsQuotation.purchase_center != 'Foreign'">
      <td>Carring Charge Bear By</td>
      <td v-for="(VendoData,index) in (WcsData?.scmWcsVendor)" :key="index">{{ VendoData[0].carring_cost_bear_by }}</td>
    </tr>
    <tr>
      <td>Unloading Charge Bear By</td>
      <td v-for="(VendoData,index) in (WcsData?.scmWcsVendor)" :key="index">{{ VendoData[0].unloading_cost_bear_by }}</td>
    </tr>
    <tr>
      <td>Unloading Charge Bear By</td>
      <td v-for="(VendoData,index) in (WcsData?.scmWcsVendor)" :key="index">{{ VendoData[0].unloading_cost_bear_by }}</td>
    </tr>
    <tr>
      <td>Offer Price</td>
      <td v-for="(VendoData,index) in (WcsData?.scmWcsVendor)" :key="index">{{ VendoData[0].total_offered_price }}</td>
    </tr>
    <tr>
      <td>Freight</td>
      <td v-for="(VendoData,index) in (WcsData?.scmWcsVendor)" :key="index">{{ VendoData[0].freight }}</td>
    </tr>
    <tr>
      <td>Total Offer Price</td>
      <td v-for="(VendoData,index) in (WcsData?.scmWcsVendor)" :key="index">{{ VendoData[0].grand_total_offered_price }}</td>
    </tr>
    <tr>
      <td>Negotiated Price</td>
      <td v-for="(VendoData,index) in (WcsData?.scmWcsVendor)" :key="index">{{ VendoData[0].total_negotiated_price }}</td>
    </tr>
    <tr>
      <td>Technical Acceptance </td>
      <td v-for="(VendoData,index) in (WcsData?.scmWcsVendor)" :key="index">
            <!-- <select v-model="wcsQuotation.scmWcsVendor[index][0].technical_acceptance" class="wcsQuotation-input">
              <option value="Accepted">Accepted</option>
              <option value="Rejected">Rejected</option>
            </select> -->
            {{ wcsQuotation.scmWcsVendor[index][0].technical_acceptance }}
      </td>
    </tr>
    <tr>
      <td>Selected Vendor</td>
      <td v-for="(VendoData,index) in (WcsData?.scmWcsVendor)" :key="index">
        <!-- <input type="checkbox" :class="'menu_' + VendoData.scm_cs_id" class="text-purple-600 wcsQuotation-radio focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray" :value="VendoData?.id" v-model="wcsQuotation.scmWcsVendor[index][0].is_selected"> -->
        {{ wcsQuotation.scmWcsVendor[index][0].is_selected ? 'Selected' : 'Not Selected' }}
      </td>
    </tr>
  </tbody>
  </table>  

  




    </div>
</template>
