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
        <v-select :options="vendors" placeholder="--Choose an option--" :loading="vendorLoading" v-model="form.scmVendor" label="name" class="block form-input" @search="searchVendor" @change="setVendorOtherData(form.scmVendor)" @update:modelValue="setVendorOtherData(form.scmVendor)">
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
          required
          class="form-input vms-readonly-input" readonly
          name="date"
          :id="'expire_date'" />
         
    </label>
    <label class="label-group">
        <span class="label-item-title">Product Source Type <span class="text-red-500">*</span></span>
        <input
          type="text"
          :value="form.scmVendor?.product_source_type"
          required
          class="form-input vms-readonly-input" readonly
          name="date"
          :id="'expire_date'" />
    </label>
    <label class="label-group">
        <span class="label-item-title">Sourcing <span class="text-red-500">*</span></span>
          <select v-model="form.sourcing" class="form-input">
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
          <VueDatePicker v-model="form.quotations_received_date" class="form-input" required auto-apply :enable-time-picker = "false" placeholder="dd-mm-yyyy" format="dd-MM-yyyy" model-type="yyyy-MM-dd"></VueDatePicker>
      </label>
      <label class="label-group">
        <span class="label-item-title">Vendor Quotation No <span class="text-red-500">*</span></span>
          <input type="text" v-model="form.quotation_ref" required class="form-input" name="scm_department_id" :id="'scm_department_id'" min=1/>
      </label>
      <label class="label-group">
        <span class="label-item-title">Vendor Quotation Date <span class="text-red-500">*</span></span>
          <VueDatePicker v-model="form.quotation_date" class="form-input" required auto-apply :enable-time-picker = "false" placeholder="dd-mm-yyyy" format="dd-MM-yyyy" model-type="yyyy-MM-dd"></VueDatePicker>
      </label>
  </div>

  <div class="input-group">
      <label class="label-group">
        <span class="label-item-title">Quotation Validity <span class="text-red-500">*</span></span>
          <input type="number" v-model="form.quotation_validity" required class="form-input " name="scm_department_id" :id="'scm_department_id'" />
      </label>
      <label class="label-group">
        <span class="label-item-title">Payment Method <span class="text-red-500">*</span></span>
         <input type="text" v-model="form.payment_method" required readonly class="form-input " name="scm_department_id" :id="'scm_department_id'" />
      </label>
      <label class="label-group">
        <span class="label-item-title">Currency <span class="text-red-500">*</span></span>
          <input type="text" v-model="form.currency" required class="form-input" name="scm_department_id" :id="'scm_department_id'" min=1/>
      </label>
      <label class="label-group">
        <span class="label-item-title">Estimated Shipment Date <span class="text-red-500">*</span></span>
          <VueDatePicker v-model="form.quotation_shipment_date" class="form-input" required auto-apply :enable-time-picker = "false" placeholder="dd-mm-yyyy" format="dd-MM-yyyy" model-type="yyyy-MM-dd"></VueDatePicker>
      </label>
  </div>

  
  <div class="input-group">
      <label class="label-group">
        <span class="label-item-title">Port Of Loading <span class="text-red-500">*</span></span>
          <input type="text" v-model="form.port_of_loading" required class="form-input " name="scm_department_id" :id="'scm_department_id'" />
      </label>
      <label class="label-group">
        <span class="label-item-title">Port Of Discharge <span class="text-red-500">*</span></span>
          <input type="text" v-model="form.port_of_discharge" required class="form-input " name="scm_department_id" :id="'scm_department_id'" />
      </label>
      <label class="label-group">
        <span class="label-item-title">Mode Of Shipment <span class="text-red-500">*</span></span>
          <select v-model="form.mode_of_shipment" class="form-input">
            <option value="Air">Air</option>
            <option value="Sea">Sea</option>
            <option value="Road">Road</option>
          </select>
      </label>
      <label class="label-group">
        <span class="label-item-title">Delivery Term <span class="text-red-500">*</span></span>
          <input type="text" v-model="form.delivery_term" required class="form-input" name="scm_department_id" :id="'scm_department_id'" min=1/>
      </label>
  </div>

  
  <div class="input-group">
      <label class="label-group">
          <RemarksComponet v-model="form.terms_and_condition" :maxlength="300" :fieldLabel="'Terms & Conditions'" isRequired="true"></RemarksComponet>
      </label>
  </div>

  <div class="input-group !w-3/4">
    <label class="label-group">
            <RemarksComponet v-model="form.remarks" :maxlength="300" :fieldLabel="'Remarks'" isRequired="true"></RemarksComponet>
         
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
              <th class="py-3 align-center">PR No </th>
              <th class="py-3 align-center">Unit</th>
              <th class="py-3 align-center">Brand</th>
              <th class="py-3 align-center">Model</th>
              <th class="py-3 align-center">Origin</th>
              <th class="py-3 align-center">Stock Type</th>
              <th class="py-3 align-center">Manufacturing Days</th>
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
                      <input type="text" v-model="form.scmCsMaterialVendors[indexa][index].brand" class="form-input"/>
                    </td>
                    <td v-if="form.scmCsMaterialVendors[indexa][index]" :rowspan="lines.length">
                      <input type="text" v-model="form.scmCsMaterialVendors[indexa][index].model" class="form-input"/>
                    </td>
                    <td v-if="form.scmCsMaterialVendors[indexa][index]" :rowspan="lines.length">
                      <input type="text" v-model="form.scmCsMaterialVendors[indexa][index].origin" class="form-input"/>
                    </td>
                    <td v-if="form.scmCsMaterialVendors[indexa][index]" :rowspan="lines.length">
                      <input type="text" v-model="form.scmCsMaterialVendors[indexa][index].stock_type" class="form-input"/>
                    </td>
                    <td v-if="form.scmCsMaterialVendors[indexa][index]" :rowspan="lines.length">
                      <input type="text" v-model="form.scmCsMaterialVendors[indexa][index].manufacturing_days" class="form-input"/>
                    </td>
                    <td v-if="form.scmCsMaterialVendors[indexa][index]" :rowspan="lines.length">
                      <input type="text" v-model="form.scmCsMaterialVendors[indexa][index].offered_price" class="form-input"/>
                    </td>
                    <td v-if="form.scmCsMaterialVendors[indexa][index]" :rowspan="lines.length">
                      <input type="number" v-model="form.scmCsMaterialVendors[indexa][index].negotiated_price" class="form-input" min="1"/>
                    </td>
                  </tr>
                  
                  <!-- <td class="!w-72">
                    <v-select :options="materials" placeholder="--Choose an option--" @search="fetchMaterials" v-model="form.scmSrLines[index].scmMaterial" label="material_name_and_code" class="block form-input" @change="setMaterialOtherData(form.scmSrLines[index].scmMaterial,index)"> -->
                  <!--   <v-select :options="materials" placeholder="--Choose an option--" :loading="materialLoading" v-model="form.scmCsMaterialVendors[index].scmPr" label="ref_no" class="block form-input" :disabled="true" @update:modelValue="changeMaterial(form.scmCsMaterialVendors[index].scmMaterial,index)">
                      <template #search="{attributes, events}">
                          <input
                              class="vs__search"
                              :required="!form.scmCsMaterialVendors[index].scmPr"
                              v-bind="attributes"
                              v-on="events"
                              />
                      </template>
                  </v-select>
                  </td>
                  <td class="!w-72">
                    <v-select :options="materials" placeholder="--Choose an option--" @search="fetchMaterials" v-model="form.scmSrLines[index].scmMaterial" label="material_name_and_code" class="block form-input" @change="setMaterialOtherData(form.scmSrLines[index].scmMaterial,index)"> -->
                   <!--  <v-select :options="materials" placeholder="--Choose an option--" :loading="materialLoading" v-model="form.scmCsMaterialVendors[index].scmMaterial" label="material_name_and_code" class="block form-input" :disabled="true" @update:modelValue="changeMaterial(form.scmCsMaterialVendors[index].scmMaterial,index)">
                      <template #search="{attributes, events}">
                          <input
                              class="vs__search"
                              :required="!form.scmCsMaterialVendors[index].scmMaterial"
                              v-bind="attributes"
                              v-on="events"
                              />
                      </template>
                  </v-select>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <input type="text" readonly v-model="form.scmCsMaterialVendors[index].unit" class=" form-input">
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <input type="text" v-model="form.scmCsMaterialVendors[index].brand" class="form-input">
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <input type="text" v-model="form.scmCsMaterialVendors[index].model" class="form-input">
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <input type="text" v-model="form.scmCsMaterialVendors[index].negotiated_price" class="form-input">
                    </label>
                  </td> -->
                </template>
              </template>
            <!-- <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(scmSrLine, index) in form.scmCsMaterialVendors" :key="index">
              <td class="!w-72">
                <v-select :options="materials" placeholder="--Choose an option--" @search="fetchMaterials" v-model="form.scmSrLines[index].scmMaterial" label="material_name_and_code" class="block form-input" @change="setMaterialOtherData(form.scmSrLines[index].scmMaterial,index)"> -->
                <!-- <v-select :options="materials" placeholder="--Choose an option--" :loading="materialLoading" v-model="form.scmCsMaterialVendors[index].scmMaterial" label="material_name_and_code" class="block form-input" :disabled="true" @update:modelValue="changeMaterial(form.scmCsMaterialVendors[index].scmMaterial,index)">
                  <template #search="{attributes, events}"> 
                      <input
                          class="vs__search"
                          :required="!form.scmCsMaterialVendors[index].scmMaterial"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="text" readonly v-model="form.scmCsMaterialVendors[index].unit" class=" form-input">
                </label>
                
              </td>
            
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="text" v-model="form.scmCsMaterialVendors[index].brand" class="form-input">
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="text" v-model="form.scmCsMaterialVendors[index].model" class="form-input">
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="text" v-model="form.scmCsMaterialVendors[index].origin" class="form-input">
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="text" v-model="form.scmCsMaterialVendors[index].stock_type" class="form-input">
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="text" v-model="form.scmCsMaterialVendors[index].manufacturing_days" class="form-input">
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="text" v-model="form.scmCsMaterialVendors[index].offered_price" class="form-input">
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="text" v-model="form.scmCsMaterialVendors[index].negotiated_price" class="form-input">
                </label>
              </td>
            </tr> -->
            </tbody>
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
    const { materialCs, showMaterialCs,getCsData } = useMaterialCs();
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

    const route = useRoute();
    const CSID = route.params.csId;
        
    // watch(() => materialCs.value, (newVal, oldVal) => {
    //   props.form.scmCs = newVal;
    //   props.form.scm_cs_id = newVal?.id;
    // });


    // watch(() => materialCs.value, (newVal, oldVal) => {
    //   props.form.scmCs = newVal;
    //   props.form.scm_cs_id = newVal?.id;
    //   if (props.formType != "edit") {
    //     props.form.scmCsMaterialVendors = [];
    //     materialCs.value.scmCsMaterials.forEach((line, index) => {
    //       const objLine = cloneDeep(props.lineObj); 
    //       let data = merge(objLine, {scmMaterial: line.scmMaterial,scm_material_id: line.scm_material_id, unit:line.unit,quantity:line.quantity})
    //       props.form.scmCsMaterialVendors.push(data);
    //       console.log(index, data);
    //     });
    //   }
    // });

    watch(() => materialCs.value, (newVal, oldVal) => {
      props.form.scmCs = newVal;
      props.form.scm_cs_id = newVal?.id;

      if (props.formType != 'edit') {
        props.form.scmCsMaterialVendors = [];
        materialCs.value.scmCsMaterials.map((lines, index) => {
          console.log(lines);
          lines.map((line, index) => {
            console.log(line);
            line['negotiated_price'] = '';
            line['brand'] = '';
            line['model'] = '';
            line['offered_price'] = '';
            line['manufacturing_days'] = '';
            line['stock_type'] = '';
            line['origin'] = '';
          });
        });
        props.form.scmCsMaterialVendors = materialCs.value.scmCsMaterials;
      }
    });

    function setVendorOtherData(){
      props.form.scm_vendor_id = props.form.scmVendor?.id ?? null;
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
  props.form.payment_method = 'LC';
  getCsData(CSID);
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