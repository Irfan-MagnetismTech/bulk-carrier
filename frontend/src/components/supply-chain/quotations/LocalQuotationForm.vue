<template>
  <!-- Basic information -->
  <div class="flex flex-col justify-center w-1/4 md:flex-row md:gap-2">
    <!-- <business-unit-input :page="{edit}" v-model="form.business_unit"></business-unit-input> -->
  </div>
  <div class="flex flex-col justify-center w-1/4 md:flex-row md:gap-2">
 
    <label class="label-group">
          <span class="label-item-title">CS Ref<span class="text-red-500">*</span></span>
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
        <span class="label-item-title">Vendor Name<span class="text-red-500">*</span></span>
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
        <span class="label-item-title">Vendor Contact No<span class="text-red-500">*</span></span>
        <input
          type="text"
          :value="form.scmVendor?.scmVendorContactPerson?.phone"
          class="form-input"
          name="date"
          :id="'expire_date'" />
    </label>
    <label class="label-group">
        <span class="label-item-title">Product Source Type<span class="text-red-500">*</span></span>
        <input
          type="text"
          :value="form.scmVendor?.product_source_type"
          class="form-input"
          name="date"
          :id="'expire_date'" />
    </label>
    <label class="label-group">
        <span class="label-item-title">Sourcing<span class="text-red-500">*</span></span>
        <select v-model="form.sourcing" class="form-input">
            <option value="Existing">Existing</option>
            <option value="New">New</option>
        </select>
    </label>   
  </div>
  <div class="input-group">
      <label class="label-group">
        <span class="label-item-title">Date Of RFQ<span class="text-red-500">*</span></span>
          <input type="date" v-model="form.date_of_rfq" class="form-input " name="scm_department_id" :id="'scm_department_id'" />
          <!-- <Error v-if="errors?.scm_department_id" :errors="errors.scm_department_id" /> -->
      </label>
      <label class="label-group">
        <span class="label-item-title">Quotation Received Date<span class="text-red-500">*</span></span>
          <!-- <input type="text" readonly :value="form.purchase_center" required class="form-input " name="scm_department_id" :id="'scm_department_id'" /> -->
          <!-- <Error v-if="errors?.scm_department_id" :errors="errors.scm_department_id" /> -->
          <input type="date" v-model="form.quotations_received_date" class="form-input " name="scm_department_id" :id="'scm_department_id'" />
      </label>
      <label class="label-group">
        <span class="label-item-title">Vendor Quotation No<span class="text-red-500">*</span></span>
          <input type="text" v-model="form.quotation_ref" class="form-input" name="scm_department_id" :id="'scm_department_id'" min=1/>
          <!-- <Error v-if="errors?.scm_department_id" :errors="errors.scm_department_id" /> -->
      </label>
      <label class="label-group">
        <span class="label-item-title">Vendor Quotation Date<span class="text-red-500">*</span></span>
          <input type="date" v-model="form.quotation_date" class="form-input" name="scm_department_id" :id="'scm_department_id'" min=1/>
          <!-- <Error v-if="errors?.scm_department_id" :errors="errors.scm_department_id" /> -->
      </label>
  </div>

  <div class="input-group">
      <label class="label-group">
        <span class="label-item-title">Quotation Validity<span class="text-red-500">*</span></span>
          <input type="number" v-model="form.quotation_validity" class="form-input " name="scm_department_id" :id="'scm_department_id'" />
          <!-- <Error v-if="errors?.scm_department_id" :errors="errors.scm_department_id" /> -->
      </label>
      <label class="label-group">
        <span class="label-item-title">Payment Method<span class="text-red-500">*</span></span>
          <!-- <input type="text" readonly :value="form.purchase_center" required class="form-input " name="scm_department_id" :id="'scm_department_id'" /> -->
          <!-- <Error v-if="errors?.scm_department_id" :errors="errors.scm_department_id" /> -->
         <!-- <input type="text" readonly :value="form.payment_method" class="form-input " name="scm_department_id" :id="'scm_department_id'" /> -->
         <select v-model="form.payment_method" class="form-input">
            <option value="Cash">Cash</option>
            <option value="Credit">Credit</option>
        </select>
      </label>
      <label class="label-group">
        <span class="label-item-title">Currency<span class="text-red-500">*</span></span>
          <input type="text" v-model="form.currency" class="form-input" name="scm_department_id" :id="'scm_department_id'" min=1/>
          <!-- <Error v-if="errors?.scm_department_id" :errors="errors.scm_department_id" /> -->
      </label>
      <label class="label-group">
        <span class="label-item-title">Carrying Charge Bear By<span class="text-red-500">*</span></span>
          <input type="text" v-model="form.carring_cost_bear_by" required class="form-input" name="scm_department_id" :id="'scm_department_id'" min=1/>
          <!-- <Error v-if="errors?.scm_department_id" :errors="errors.scm_department_id" /> -->
      </label>
  </div>

  
  <div class="input-group">
      <label class="label-group">
        <span class="label-item-title">Unloading Charge Bear By<span class="text-red-500">*</span></span>
          <input type="text" v-model="form.unloading_cost_bear_by" required class="form-input " name="scm_department_id" :id="'scm_department_id'" />
          <!-- <Error v-if="errors?.scm_department_id" :errors="errors.scm_department_id" /> -->
      </label>
      <label class="label-group">
        <span class="label-item-title">VAT<span class="text-red-500">*</span></span>
          <!-- <input type="text" readonly :value="form.purchase_center" required class="form-input " name="scm_department_id" :id="'scm_department_id'" /> -->
          <!-- <Error v-if="errors?.scm_department_id" :errors="errors.scm_department_id" /> -->
          <input type="text" v-model="form.vat" required class="form-input " name="scm_department_id" :id="'scm_department_id'" />
      </label>
      <label class="label-group">
        <span class="label-item-title">AIT<span class="text-red-500">*</span></span>
          <input type="text" v-model="form.ait" required class="form-input" name="scm_department_id" :id="'scm_department_id'" min=1/>
          <!-- <Error v-if="errors?.scm_department_id" :errors="errors.scm_department_id" /> -->
      </label>
      <label class="label-group">
        <span class="label-item-title">Delivery Term<span class="text-red-500">*</span></span>
          <input type="text" v-model="form.delivery_term" required class="form-input" name="scm_department_id" :id="'scm_department_id'" min=1/>
          <!-- <Error v-if="errors?.scm_department_id" :errors="errors.scm_department_id" /> -->
      </label>
  </div>

  
  <div class="input-group">
    <label class="label-group">
        <span class="label-item-title">Credit Term<span class="text-red-500">*</span></span>
          <input type="text" v-model="form.credit_term" required class="form-input" name="scm_department_id" :id="'scm_department_id'" min=1/>
          <!-- <Error v-if="errors?.scm_department_id" :errors="errors.scm_department_id" /> -->
      </label>
      <label class="label-group">
        <span class="label-item-title">Terms & Conditions<span class="text-red-500">*</span></span>
          <input type="text" v-model="form.terms_and_condition" required class="form-input " name="scm_department_id" :id="'scm_department_id'" />
          <!-- <Error v-if="errors?.scm_department_id" :errors="errors.scm_department_id" /> -->
      </label>
  </div>

  <div class="input-group !w-3/4">
    <label class="label-group">
          <span class="label-item-title">Remarks
             <span class="text-red-500">*</span></span>
          <textarea
            v-model="form.remarks"
            class="block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray form-input"></textarea>
         
    </label>
  </div>  

  <div id="">
    <div id="">
      <div class="table-responsive min-w-screen pb-20">
        <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
          <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Materials <span class="text-red-500">*</span></legend>
          <table class="whitespace-no-wrap">
            <thead>
            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
              <th class="py-3 align-center">PR No </th>
              <th class="py-3 align-center">Material Name </th>
              <th class="py-3 align-center">Unit</th>
              <th class="py-3 align-center">Brand</th>
              <th class="py-3 align-center">Model</th>
              <th class="py-3 align-center">Price</th>
            </tr>
            </thead>

            <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
              
              <template v-for="(lines, indexa) in form.scmCsMaterialVendors" :key="indexa">
                <template v-for="(scmSrLine, index) in lines" :key="index">
                  
                  <tr v-if="index != 0">
                    
                    <td>{{ scmSrLine?.scmPr?.ref_no }}</td>
                    <td>{{ scmSrLine?.scmMaterial?.unit }}</td>
                    <td>{{ scmSrLine?.brand }}</td>
                    <td>{{ scmSrLine?.model }}</td>
                    <td v-if="form.scmCsMaterialVendors[indexa][index]"><input type="number" v-model="form.scmCsMaterialVendors[indexa][index].negotiated_price"/></td>
                  </tr>
                  <tr class="text-gray-700 dark-disabled:text-gray-400" v-else>
                    <td :rowspan="lines.length">{{ first(values(lines))?.scmMaterial?.name }}</td>
                    <td>{{ scmSrLine?.scmPr?.ref_no }}</td>
                    <td>{{ scmSrLine?.scmMaterial?.unit }}</td>
                    <td>{{ scmSrLine?.brand }}</td>
                    <td>{{ scmSrLine?.model }}</td>
                    <td v-if="form.scmCsMaterialVendors[indexa][index]"><input type="number" v-model="form.scmCsMaterialVendors[indexa][index].negotiated_price"/></td>
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
    // import cloneDeep from 'lodash/cloneDeep';
    import { merge ,cloneDeep,groupBy,first, values} from 'lodash';
    import useStoreIssue from '../../../composables/supply-chain/useStoreIssue';
    import useStoreIssueReturn from '../../../composables/supply-chain/useStoreIssueReturn';
    import useVendor from '../../../composables/supply-chain/useVendor';
    import useMaterialCs from '../../../composables/supply-chain/useMaterialCs';
    import { useRoute } from 'vue-router';
    
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
  console.log(materialCs.value.scmCsMaterials);
      props.form.scmCs = newVal;
      props.form.scm_cs_id = newVal?.id;

      if (props.formType != 'edit') {
        props.form.scmCsMaterialVendors = [];
       
        materialCs.value.scmCsMaterials.map((lines, index) => {
          lines.map((line, index) => {
            line['negotiated_price'] = '';
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

// function fetchStoreIssue(search, loading = false) {
//     // if (search.length > 0) {
//     //   loading(true);
//       searchStoreIssue(search, /*loading,*/ props.form.business_unit);
//     // }
//   }

// function setStoreIssueOtherData(datas) {
//       props.form.scm_si_id = datas.id;
//       props.form.si_no = datas.ref_no;
//       props.form.acc_cost_center_id = datas.acc_cost_center_id;
//       props.form.scm_warehouse_id = datas.scm_warehouse_id;
//       props.form.scmWarehouse = datas.scmWarehouse;
//       props.form.scm_warehouse_name = datas.scmWarehouse.name;
//       props.form.scm_department_id = datas.scm_department_id;
//       getSiWiseSir(datas.id);    
// }



// watch(() => props.form.scmSi, (new Val,oldVal) => {
//       props.form.scm_si_id = newVal?.id;
//       props.form.si_no = newVal?.ref_no;
//       props.form.acc_cost_center_id = newVal?.acc_cost_center_id;
//       props.form.scm_warehouse_id = newVal?.scm_warehouse_id;
//       props.form.scmWarehouse = newVal?.scmWarehouse;
//       props.form.scm_department_id = newVal?.scm_department_id;
//       filteredStoreIssues.value = []; 
//       getSiWiseSir(newVal?.id);    
// });
// watch(() => props.form.scmDepartment, (value) => {
//   if (value) {
//     props.form.scm_department_id = value?.id;
//   }
// });


// watch(() => props.form.scmWarehouse, (value) => {
//   if (value) {
//     props.form.scm_warehouse_name = value?.scmWarehouse?.name;
//   }
// });



// watch(() => props.form.scm_si_id, (value) => {
//   if (value) {

//   }
// });

// watchEffect filteredStoreIssueReturnLines
// watchEffect(() => {
//   props.form.scmSirLines = filteredStoreIssueReturnLines.value;
// });
//watch filteredStoreIssueReturnLines
// watch(() => filteredStoreIssueReturnLines.value, (newVal, oldVal) => {
//   props.form.scmSirLines = newVal;
// });
// function setMaterialOtherData(datas, index) {
//       props.form.scmSirLines[index].unit = datas.unit;
//       props.form.scmSirLines[index].scm_material_id = datas.id;
          
// }

// const previousLines = ref(cloneDeep(props.form.scmSrLines));

// watch(() => props.form.scmSirLines, (newLines) => {
//   newLines.forEach((line, index) => {
//     // const previousLine = previousLines.value[index];

//     if (line.scmMaterial) {
//       const selectedMaterial = materials.value.find(material => material.id === line.scmMaterial.id);
//       if (selectedMaterial) {
//         if ( line.scm_material_id !== selectedMaterial.id
//         ) {
//           props.form.scmSirLines[index].unit = selectedMaterial.unit;
//           props.form.scmSirLines[index].scm_material_id = selectedMaterial.id;
//         }
//       }
//     }
//   });
//   // previousLines.value = cloneDeep(newLines);
// }, { deep: true });


  //   function fetchMaterials(search, loading) {
  //   loading(true);
  //   searchMaterial(search, loading)
  // }


  //   function fetchMaterials(search, loading = false) {
  //     // loading(true);
  //     fetchSiWiseMaterials(props.form.scm_si_id);
  // }

  // watch(() => props.form.scmSi, (newVal, oldVal) => {
  //   fetchMaterials(newVal?.id)
  //   props.form.department_id = newVal?.department_id;
  //   props.form.scm_warehouse_id = newVal?.scm_warehouse_id;
  //   props.form.scmWarehouse = newVal?.scmWarehouse;
  // });

//   watch(() => props.form.business_unit, (newValue, oldValue) => {
//    if(newValue !== oldValue && oldValue != ''){
//     props.form.scm_warehouse_id = '';
//     props.form.acc_cost_center_id = '';
//     props.form.scmWarehouse = null;
//     props.form.scmSi = null;
//     props.form.scm_si_id = null;
//     props.form.si_no = null,
//     props.form.scmDepartment= null,
//     props.form.scm_department_id = null,
//     props.form.scmSirLines = [];
//     filteredStoreIssues.value = [];
//    } 
//      fetchStoreIssue('');
// });

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