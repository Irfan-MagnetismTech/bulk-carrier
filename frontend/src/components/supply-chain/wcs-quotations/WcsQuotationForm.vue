<template>
  <!-- Basic information -->
  <div class="justify-center w-full grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 md:gap-2 ">
    <label class="label-group">
          <span class="label-item-title">WCS Ref</span>
          <input
            type="text"
            readonly
            :value="form.scmWcs?.ref_no"
            required
            class="form-input vms-readonly-input"
          />
      </label>
      
    <label class="label-group">
          <span class="label-item-title">Quotation Ref. No <span class="text-red-500">*</span></span>
          <input
            type="text"
            v-model="form.quotation_ref_no"
            required
            placeholder="Quotation Ref. No"
            class="form-input"
          />
      </label>

      <label class="label-group">
        <span class="label-item-title">Quotation Date <span class="text-red-500">*</span></span>
          <VueDatePicker v-model="form.quotation_date" class="form-input" required auto-apply :enable-time-picker = "false" placeholder="dd-mm-yyyy" format="dd-MM-yyyy" model-type="yyyy-MM-dd"></VueDatePicker>
      </label>

      <label class="label-group">
        <span class="label-item-title">Quotation Validity <span class="text-red-500">*</span></span>
          <!-- <input type="number" v-model="form.quotation_validity" class="form-input" name="scm_department_id" :id="'scm_department_id'" required/> -->
          <VueDatePicker v-model="form.quotation_validity" class="form-input" required auto-apply :enable-time-picker = "false" placeholder="dd-mm-yyyy" format="dd-MM-yyyy" model-type="yyyy-MM-dd" :min-date="form.quotation_date"></VueDatePicker>
      </label>

      <label class="label-group">
        <span class="label-item-title">Supplier <span class="text-red-500">*</span></span>
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
        <span class="label-item-title">Payment Method <span class="text-red-500">*</span></span>
         <select v-model="form.payment_mode" class="form-input" required @change="paymentModeChange">
            <option value="" disabled selected>Select</option>
            <option value="Cash">Cash</option>
            <option value="Credit">Credit</option>
            <option value="Advance">Advance</option>
            <option value="Bank">Bank</option>
        </select>
      </label>
      <label class="label-group">
        <span class="label-item-title">Currency <span class="text-red-500">*</span></span>
          <!-- <input type="text" v-model="form.currency" class="form-input" required/> -->
          <select class="form-input" v-model.trim="form.currency" required>
          <option value="" disabled selected>Select</option>
          <option value="BDT">BDT</option>
          <option value="USD">USD</option>
        </select>
      </label>
      <label class="label-group" >
        <span class="label-item-title">Credit Term <span v-show="form.payment_mode != 'Cash'" class="text-red-500">*</span></span>
          <input type="text" v-model="form.credit_term" class="form-input" :class="{'vms-readonly-input': form.payment_mode == 'Cash'}" :required="form.payment_mode != 'Cash'" placeholder="Credit Term" :readonly="form.payment_mode == 'Cash'"/>
      </label>
      <label class="label-group">
        <span class="label-item-title">VAT</span>
          <input type="text"  v-model="form.vat" placeholder="VAT" class="form-input" />
      </label>
      <label class="label-group">
        <span class="label-item-title">AIT</span>
          <input type="text"  v-model="form.ait" placeholder="AIT" class="form-input" />
      </label>
      
      <label class="label-group">
        <span class="label-item-title">Security Money</span>
          <input type="number" step="0.01" min="0" v-model="form.security_money" class="form-input" />
      </label>
      <label class="label-group">
        <span class="label-item-title">Adjustment Policy</span>
          <input type="text" v-model="form.adjustment_policy" class="form-input" placeholder="Adjustment Policy" />
      </label>

      <label class="label-group">
            <span class="label-item-title">Attachment 
              <template v-if="form.attachment">
                    <a class="text-red-700" target="_blank" :href="env.BASE_API_URL+form?.attachment">{{
                        (typeof $props.form?.attachment === 'string')
                            ? '('+$props.form?.attachment.split('/').pop()+')'
                            : ''
                    }}</a>
              </template></span>
            <input type="file" class="form-input" @change="handleAttachmentChange" />
            <Error v-if="errors?.attachment" :errors="errors.attachment"  />
        </label>
        <RemarksComponent class="col-span-1 md:col-span-3 lg:col-span-4" v-model="form.terms_and_condition" :maxlength="300" :fieldLabel="'Terms & Conditions'" ></RemarksComponent>
        <RemarksComponent class="col-span-1 md:col-span-3 lg:col-span-4" v-model="form.remarks" :maxlength="300" :fieldLabel="'Remarks'" ></RemarksComponent>
  </div>

  <fieldset class=" grid grid-cols-1 px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400 ">
        <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Services</legend>
        <table class="w-full whitespace-no-wrap " id="table">
          <thead>
            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500  bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
              <th class="w-5/12 px-4 align-center">Service Name</th>
                <th class="w-3/12 px-4 align-center">WR No</th>
                <th class="w-2/12 px-4 align-center">Rate <span class="text-red-500">*</span></th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
            <template v-for="(lines, indexa) in form.scmWcsVendorServices" :key="indexa">
                <template v-for="(scmSrLine, index) in lines" :key="index">
                  <tr v-if="index != 0">
                    <td><nobr>{{ scmSrLine?.scmWr?.ref_no }}</nobr></td>
                  </tr>
                  <tr class="text-gray-700 dark-disabled:text-gray-400" v-else>
                    <td :rowspan="lines.length">{{ first(values(lines))?.scmService?.name }}</td>
                    <td><nobr>{{ scmSrLine?.scmWr?.ref_no }}</nobr></td>
                    <td v-if="form.scmWcsVendorServices[indexa][index]" :rowspan="lines.length">
                      <input type="number" v-model="form.scmWcsVendorServices[indexa][index].rate" class="form-input" min="1" required/>
                    </td>
                  </tr>
                </template>
              </template>
            <!-- <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(scmWcsVendorService, index) in form.scmWcsVendorServices" :key="index">
              <td class="px-1 py-1">
                <v-select :options="filteredWorkRequisitions" placeholder="--Choose an option--" :loading="isWorkRequisitionLoading" v-model="scmWcsService.scmWr" label="ref_no" class="block form-input" @update:modelValue="wrChange(index)">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!scmWcsService.scmWr"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
                </v-select>
              </td>
              <td class="px-1 py-1">
                <div class="relative">
                  <v-select :options="serviceList[index]" placeholder="--Choose an option--" :loading="isLoading" v-model="scmWcsService.scmService" label="service_name_and_code" class="block form-input" @update:modelValue="serviceChange(index)">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!scmWcsService.scmService"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
                </v-select>
                <span v-show="scmWcsService.isServiceDuplicate" class="text-yellow-600 pl-1 absolute top-2 right-10" title="Duplicate Service" v-html="icons.ExclamationTriangle"></span>
                </div>
              </td>
              <td class="px-1 py-1">
                <label class="block w-full mt-2 text-sm">
                   <input type="number" placeholder="Quantity" v-model="scmWcsService.quantity" class="form-input" min="1" required 
                   >
                </label>
              </td>
              

              <td class="px-1 py-1"> 
                <button type="button" v-if="index == 0" class="bg-green-600 text-white px-3 py-2 rounded-md" @click="addWork"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg></button>
                <button type="button" v-else class="bg-red-600 text-white px-3 py-2 rounded-md" @click="removeWork(index)" ><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg></button>
              </td>
            </tr> -->
          </tbody>
        </table>
      </fieldset>

  <!-- <div class="flex flex-col justify-center w-1/4 md:flex-row md:gap-2">
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
        <span class="label-item-title">Product Source Type <span class="text-red-500">*</span></span>
        <input
          type="text"
          :value="form.scmVendor?.product_source_type"
          class="form-input vms-readonly-input" readonly/>
    </label>
    <label class="label-group">
        <span class="label-item-title">Sourcing <span class="text-red-500">*</span></span>
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
      </label>
      <label class="label-group">
        <span class="label-item-title">PI Date <span class="text-red-500">*</span></span>
         <VueDatePicker v-model="form.quotation_date" class="form-input" required auto-apply :enable-time-picker = "false" placeholder="dd-mm-yyyy" format="dd-MM-yyyy" model-type="yyyy-MM-dd"></VueDatePicker>
      </label>
  </div>

  <div class="input-group">
      <label class="label-group">
        <span class="label-item-title">Quotation Validity (days) <span class="text-red-500">*</span></span>
          <input type="number" v-model="form.quotation_validity" class="form-input" name="scm_department_id" :id="'scm_department_id'" required/>
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

  
  <div class="input-group !w-2/4">
    
    <label class="label-group">
        <span class="label-item-title">Delivery Term <span class="text-red-500">*</span></span>
          <input type="text" v-model="form.delivery_term" class="form-input" required/>
      </label>
    <label class="label-group">
        <span class="label-item-title">Credit Term <span class="text-red-500">*</span></span>
          <input type="text" v-model="form.credit_term" class="form-input" required/>
      </label>
  </div>

  <div class="input-group !w-3/4">
    <label class="label-group">
            <RemarksComponent v-model="form.terms_and_condition" :maxlength="300" :fieldLabel="'Terms & Conditions'" isRequired="true"></RemarksComponent>
    </label>
  </div>  
  <div class="input-group !w-3/4">
    <label class="label-group">
            <RemarksComponent v-model="form.remarks" :maxlength="300" :fieldLabel="'Remarks'" isRequired="true"></RemarksComponent>
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
              <th class="py-3 align-center">Offer Price</th>
              <th class="py-3 align-center">Negotiated Price</th>
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
                      <input type="number" v-model="form.scmCsMaterialVendors[indexa][index].offered_price" class="form-input"/>
                    </td>
                    <td v-if="form.scmCsMaterialVendors[indexa][index]" :rowspan="lines.length">
                      <input type="number" v-model="form.scmCsMaterialVendors[indexa][index].negotiated_price" class="form-input" min="1"/>
                    </td>
                  </tr>
                </template>
              </template>
            </tbody>
          </table>
        </fieldset>
      </div>
    </div>
  </div> -->
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
    // import cloneDeep from 'lodash/cloneDeep';
    import { merge ,cloneDeep,groupBy,first, values} from 'lodash';
    import useStoreIssue from '../../../composables/supply-chain/useStoreIssue';
    import useStoreIssueReturn from '../../../composables/supply-chain/useStoreIssueReturn';
    import useVendor from '../../../composables/supply-chain/useVendor';
    import useMaterialCs from '../../../composables/supply-chain/useMaterialCs';
    import useWorkCs from '../../../composables/supply-chain/useWorkCs';
    import { useRoute } from 'vue-router';
    import RemarksComponent from '../../utils/RemarksComponent.vue';
import ErrorComponent from '../../utils/ErrorComponent.vue';
    
    const { material, materials, getMaterials,searchMaterial } = useMaterial();
    const { warehouses, warehouse, getWarehouses, searchWarehouse } = useWarehouse();
    const { searchVendor, vendors, vendor, isLoading: vendorLoading } = useVendor();
    const { materialCs, showMaterialCs, getCsData } = useMaterialCs();
    const { workCs, showWorkCs, getWcsData } = useWorkCs();
    
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
    const WCSID = route.params?.wcsId;

    watch(() => materialCs.value, (newVal, oldVal) => {
          props.form.scmCs = newVal;
          props.form.scm_cs_id = newVal?.id;

          if (props.formType != 'edit') {
            props.form.scmCsMaterialVendors = [];
          
            materialCs.value.scmCsMaterials.map((lines, index) => {
              lines.map((line, index) => {
                line['negotiated_price'] = null;
                line['brand'] = null;
                line['model'] = null;
              });
            });
            props.form.scmCsMaterialVendors = materialCs.value.scmCsMaterials;
          }
      });

    
    watch(() => workCs.value, (newVal, oldVal) => {
          props.form.scmWcs = newVal;
          props.form.scm_wcs_id = newVal?.id;//** */

          if (props.formType != 'edit') {
            // props.form.scmWcsVendorServices = [];
          
            // materialCs.value.scmCsMaterials.map((lines, index) => {
            //   lines.map((line, index) => {
            //     line['negotiated_price'] = null;
            //     line['brand'] = null;
            //     line['model'] = null;
            //   });
            // });
            props.form.scmWcsVendorServices = workCs.value.scmWcsServices;
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

    function paymentModeChange(){
      if(props.form.payment_mode == 'Cash')
        props.form.credit_term = '';
    }

  function setVendorOtherData() {
        props.form.scm_vendor_id = props.form.scmVendor?.id;
      }

      function handleAttachmentChange(e) {
      let fileData = e.target.files[0];
      props.form.attachment = fileData;
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
  // getCsData(CSID);
  getWcsData(WCSID)
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