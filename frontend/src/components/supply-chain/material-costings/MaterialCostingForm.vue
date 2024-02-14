<template>

  <!-- Basic information -->
  <div class="flex flex-col justify-center w-1/3 md:flex-row md:gap-2">
    <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
  </div>
  <div class="input-group">
      
      <label class="label-group">
        <span class="label-item-title">PO Ref<span class="text-red-500">*</span></span>
          <!-- <v-select :options="warehouses" placeholder="--Choose an option--" @search="fetchWarehouse"  v-model="form.scmWarehouse" label="name" class="block form-input"> -->
          <v-select :options="filteredPurchaseOrders" placeholder="--Choose an option--" :loading="isLoading" v-model="form.scmPo" label="ref_no" class="block form-input" @update:modelValue="changePoRef">
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
      <label class="label-group">
          <span class="label-item-title">Purchase Center</span>
          <input type="text" readonly :value="form?.scmPo?.purchase_center" required class="form-input vms-readonly-input" name="ref_no" :id="'ref_no'"/>
      </label>
      <label class="label-group">
          <span class="label-item-title">Warehouse</span>
          <input type="text" readonly :value="form?.scmWarehouse?.name" required class="form-input vms-readonly-input" name="ref_no" :id="'ref_no'"/>
      </label>
      <label class="label-group">
          <span class="label-item-title">Date<span class="text-red-500">*</span></span>
          <VueDatePicker v-model="form.date" class="form-input" required auto-apply :enable-time-picker = "false" placeholder="dd-mm-yyyy" format="dd-MM-yyyy" model-type="yyyy-MM-dd"></VueDatePicker>
          <!-- <Error v-if="errors?.date" :errors="errors.date"  /> -->
      </label>
  </div>
  <!-- <ul class="flex flex-wrap -mb-px"  v-if="form.scmPo?.purchase_center == 'Foreign'">
    <template v-for="(scmSrLine, index ,key) in form.scmCostingLines" :key="index">
      <li class="mr-2" v-for="(lines,index1,key1) in scmSrLine" :key="index1">
        <a href="#" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark-disabled:text-gray-400 group" v-on:click="toggleTabs(index1)" v-bind:class="{'text-purple-600 bg-white': openTab !== index1, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 bg-gray-100': openTab === index1}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>{{ form.scmCostingLines[index][index1]['cfr'][0]['scmLcRecord']['lc_no']}}
        </a>
      </li>
    </template>
    </ul> -->
    <div class="flex justify-between items-center gap-1 pt-4" v-if="form.scmPo?.purchase_center == 'Foreign'">
        <ul class="flex flex-wrap gap-1 text-gray-700 dark-disabled:text-gray-300">
          <template v-for="(scmSrLine, index ,key) in form.scmCostingLines" :key="index">
            <li  v-for="(lines,index1,key1) in scmSrLine" :key="index1">
              <button type="button" class="px-3 py-1 md:rounded-l-sm bg-gray-200 hover:bg-purple-800 hover:text-white" :class="{ 'bg-purple-800 rounded-sm text-white' : openTab === index1 }" @click="toggleTabs(index1)">{{ form.scmCostingLines[index][index1]['cfr'][0]['scmLcRecord']['lc_no']}}</button>
            </li>
          </template>
        </ul>
    </div>
  <div id="">
    <div id="">
           <template v-if="form.scmPo?.purchase_center == 'Foreign'">
            <template class="text-gray-700 dark-disabled:text-gray-400" v-for="(scmSrLine, index ,key) in form.scmCostingLines" :key="index">
              <template v-for="(lines,index1,key1) in scmSrLine" :key="index1">
                <div class="table-responsive min-w-screen">
                  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400" v-bind:class="{'hidden': openTab !== index1, 'block': openTab === index1}">
                  <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Costs </legend>
                    <table class="">
                      <thead>
                        <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
                          <th class="py-3 align-center">Particular  <span class="text-red-500">*</span></th>
                          <th class="py-3 align-center">Exchange Rate</th>
                          <th class="py-3 align-center">USD</th>
                          <th class="py-3 align-center">BDT <span class="text-red-500">*</span></th>
                        </tr>
                      </thead>
                      <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
                          <template v-for="(LLNN,index2,key2) in lines.cfr">
                            <tr>
                              <td class="text-left">{{ LLNN.particulars }}</td>
                              <td><input type="number" class="form-input" v-model="form.scmCostingLines[index][index1]['cfr'][index2].exchange_rate"/></td>
                              <td><input type="number" class="form-input" v-model="form.scmCostingLines[index][index1]['cfr'][index2].usd_amount"/></td>
                              <td><input type="number" class="form-input" v-model="form.scmCostingLines[index][index1]['cfr'][index2].bdt_amount"/></td>
                            </tr>
                          </template>
                          <template v-for="(LLNN,index2,key2) in lines.total_cfr">
                              <tr>
                                <td class="text-right">{{ LLNN.particulars }}</td>
                                <td><input type="number" class="form-input vms-readonly-input" v-model="form.scmCostingLines[index][index1]['total_cfr'][index2].exchange_rate"/></td>
                                <td><input type="number" class="form-input vms-readonly-input" v-model="form.scmCostingLines[index][index1]['total_cfr'][index2].usd_amount"/></td>
                                <td><input type="number" class="form-input vms-readonly-input" v-model="form.scmCostingLines[index][index1]['total_cfr'][index2].bdt_amount"/></td>
                              </tr>
                          </template>
                          <template v-for="(LLNN,index2,key2) in lines.cif">
                            <tr>
                              <td class="text-left">{{ LLNN.particulars }}</td>
                              <td><input type="number" class="form-input" v-model="form.scmCostingLines[index][index1]['cif'][index2].exchange_rate"/></td>
                              <td><input type="number" class="form-input" v-model="form.scmCostingLines[index][index1]['cif'][index2].usd_amount"/></td>
                              <td><input type="number" class="form-input" v-model="form.scmCostingLines[index][index1]['cif'][index2].bdt_amount"/></td>
                            </tr>
                          </template>
                          <template v-for="(LLNN,index2,key2) in lines.total_cif">
                            <tr>
                              <td class="text-right">{{ LLNN.particulars }}</td>
                              <td><input type="number" class="form-input vms-readonly-input" v-model="form.scmCostingLines[index][index1]['total_cif'][index2].exchange_rate"/></td>
                              <td><input type="number" class="form-input vms-readonly-input" v-model="form.scmCostingLines[index][index1]['total_cif'][index2].usd_amount"/></td>
                              <td><input type="number" class="form-input vms-readonly-input" v-model="form.scmCostingLines[index][index1]['total_cif'][index2].bdt_amount"/></td>
                            </tr>
                          </template>
                          <template v-for="(LLNN,index2,key2) in lines.a">
                              <tr>
                                <td class="text-left">{{ LLNN.particulars }}</td>
                                <td><input type="number" class="form-input" v-model="form.scmCostingLines[index][index1]['a'][index2].exchange_rate"/></td>
                                <td><input type="number" class="form-input" v-model="form.scmCostingLines[index][index1]['a'][index2].usd_amount"/></td>
                                <td><input type="number" class="form-input" v-model="form.scmCostingLines[index][index1]['a'][index2].bdt_amount"/></td>
                              </tr>
                          </template>
                          <template v-for="(LLNN,index2,key2) in lines.total_assessable">
                              <tr>
                                <td class="text-right">{{ LLNN.particulars }}</td>
                                <td><input type="number" class="form-input vms-readonly-input" v-model="form.scmCostingLines[index][index1]['total_assessable'][index2].exchange_rate"/></td>
                                <td><input type="number" class="form-input vms-readonly-input" v-model="form.scmCostingLines[index][index1]['total_assessable'][index2].usd_amount"/></td>
                                <td><input type="number" class="form-input vms-readonly-input" v-model="form.scmCostingLines[index][index1]['total_assessable'][index2].bdt_amount"/></td>
                              </tr>
                          </template>
                          <template v-for="(LLNN,index2,key2) in lines.tti">
                                <tr>
                                  <td class="text-left">{{ LLNN.particulars }}</td>
                                  <td><input type="number" class="form-input" v-model="form.scmCostingLines[index][index1]['tti'][index2].exchange_rate"/></td>
                                  <td><input type="number" class="form-input" v-model="form.scmCostingLines[index][index1]['tti'][index2].usd_amount"/></td>
                                  <td><input type="number" class="form-input" v-model="form.scmCostingLines[index][index1]['tti'][index2].bdt_amount"/></td>
                                </tr>
                          </template>
                          <template v-for="(LLNN,index2,key2) in lines.total_tti">
                              <tr>
                                <td class="text-right">{{ LLNN.particulars }}</td>
                                <td><input type="number" class="form-input vms-readonly-input" v-model="form.scmCostingLines[index][index1]['total_tti'][index2].exchange_rate"/></td>
                                <td><input type="number" class="form-input vms-readonly-input" v-model="form.scmCostingLines[index][index1]['total_tti'][index2].usd_amount"/></td>
                                <td><input type="number" class="form-input vms-readonly-input" v-model="form.scmCostingLines[index][index1]['total_tti'][index2].bdt_amount"/></td>
                              </tr>
                          </template>
                          <template v-for="(LLNN,index2,key2) in lines.tc">
                                <tr>
                                  <td class="text-left">{{ LLNN.particulars }}</td>
                                  <td><input type="number" class="form-input" v-model="form.scmCostingLines[index][index1]['tc'][index2].exchange_rate"/></td>
                                  <td><input type="number" class="form-input" v-model="form.scmCostingLines[index][index1]['tc'][index2].usd_amount"/></td>
                                  <td><input type="number" class="form-input" v-model="form.scmCostingLines[index][index1]['tc'][index2].bdt_amount"/></td>
                                </tr>
                          </template>
                          <template v-for="(LLNN,index2,key2) in lines.total_tc">
                              <tr>
                                <td class="text-right">{{ LLNN.particulars }}</td>
                                <td><input type="number" class="form-input vms-readonly-input" v-model="form.scmCostingLines[index][index1]['total_tc'][index2].exchange_rate"/></td>
                                <td><input type="number" class="form-input vms-readonly-input" v-model="form.scmCostingLines[index][index1]['total_tc'][index2].usd_amount"/></td>
                                <td><input type="number" class="form-input vms-readonly-input" v-model="form.scmCostingLines[index][index1]['total_tc'][index2].bdt_amount"/></td>
                              </tr>
                          </template>
                          <template v-for="(LLNN,index2,key2) in lines.total_lc_cost">
                              <tr>
                                <td class="text-left">{{ LLNN.particulars }}</td>
                                <td><input type="number" class="form-input vms-readonly-input" v-model="form.scmCostingLines[index][index1]['total_lc_cost'][index2].exchange_rate"/></td>
                                <td><input type="number" class="form-input vms-readonly-input" v-model="form.scmCostingLines[index][index1]['total_lc_cost'][index2].usd_amount"/></td>
                                <td><input type="number" class="form-input vms-readonly-input" v-model="form.scmCostingLines[index][index1]['total_lc_cost'][index2].bdt_amount"/></td>
                              </tr>
                          </template>
                          <template v-for="(LLNN,index2,key2) in lines.grand_total">
                              <tr>
                                <td class="text-right">{{ LLNN.particulars }}</td>
                                <td><input type="number" class="form-input vms-readonly-input" v-model="form.scmCostingLines[index][index1]['grand_total'][index2].exchange_rate"/></td>
                                <td><input type="number" class="form-input vms-readonly-input" v-model="form.scmCostingLines[index][index1]['grand_total'][index2].usd_amount"/></td>
                                <td><input type="number" class="form-input vms-readonly-input" v-model="form.scmCostingLines[index][index1]['grand_total'][index2].bdt_amount"/></td>
                              </tr>
                          </template>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="3" class="text-right">Total Allocateable</td>
                          <td><input type="number" :value="form.total_allocateable" readonly class="form-input vms-readonly-input"/></td>
                        </tr>
                      </tfoot>
                    </table>
                  </fieldset>
                </div>
              </template>
            </template>
          </template>
          <template v-else-if="form.scmPo?.purchase_center == 'Local' || form.scmPo?.purchase_center == 'Plant'">
            <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
              <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Costs </legend>
              <table class="w-full">
                <thead>
                  <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
                      <th class="py-3 align-center">Cost Particulars  <span class="text-red-500">*</span></th>
                      <th class="py-3 align-center">Amount</th>
                      <th class="py-3 text-center align-center">Action </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
                  <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(scmCostingLine, index) in form.scmCostingLines" :key="index">
                    <td>
                      <label class="block w-full mt-2 text-sm">
                        <input type="number" v-model="form.scmCostingLines[index].particulars" class="form-input">
                      </label>
                    </td>
                    <td>
                      <label class="block w-full mt-2 text-sm">
                        <input type="number" v-model="form.scmCostingLines[index].bdt_amount" class="form-input">
                      </label>
                    </td>
                    <td class="px-1 py-1 text-center">
                      <button v-if="index!=0" type="button" @click="removeMaterial(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                      </button>
                      <button v-else type="button" @click="addMaterial()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                      </button>
                    </td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <td class="text-right">Total Allocateable</td>
                    <td><input type="number" :value="form.total_allocateable" readonly class="form-input vms-readonly-input"/></td>
                    <td></td>
                  </tr>
                </tfoot>
              </table>
            </fieldset>
          </template>
    </div>
  </div>

  <div id="" v-if="form.scmCostingAllocations?.length">
    <div id="">
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
              <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Allocation</legend>
              <table class="w-full">
                <thead>
                  <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
                      <th class="py-3 align-center">MRR Ref </th>
                      <th class="py-3 align-center">Total Value</th>
                      <th class="py-3 text-center align-center">Allocated Amount </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
                  <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(scmCostingAllocation, index) in form.scmCostingAllocations" :key="index">
                    <td>
                      <label class="block w-full mt-2 text-sm">
                        <input type="text" :value="form.scmCostingAllocations[index]?.scmMrr?.ref_no" readonly class="form-input vms-readonly-input">
                      </label>
                    </td>
                    <td>
                      <label class="block w-full mt-2 text-sm">
                        <input type="text" :value="form.scmCostingAllocations[index].value" readonly class="form-input vms-readonly-input">
                      </label>
                    </td>
                    <td>
                      <label class="block w-full mt-2 text-sm">
                        <input type="text" v-model="form.scmCostingAllocations[index].allocated_amount" readonly class="form-input vms-readonly-input">
                      </label>
                    </td>
                  </tr>
                </tbody>
              </table>
            </fieldset>
    </div>
  </div>
  <ErrorComponent :errors="errors"></ErrorComponent>  
</template>



<script setup>
    import { ref, watch, onMounted,watchEffect,computed, watchPostEffect } from 'vue';
    import Error from "../../Error.vue";
    import DropZone from "../../DropZone.vue";
    import useMaterial from "../../../composables/supply-chain/useMaterial.js";
    import useWarehouse from "../../../composables/supply-chain/useWarehouse.js";
    import BusinessUnitInput from "../../input/BusinessUnitInput.vue";
    import ErrorComponent from "../../utils/ErrorComponent.vue";
    import RemarksComponet from '../../utils/RemarksComponent.vue';
    import DropZoneV2 from '../../DropZoneV2.vue';
    import {useStore} from "vuex";
    import env from '../../../config/env';
    import { merge, cloneDeep, first} from "lodash";
    import usePurchaseOrder from '../../../composables/supply-chain/usePurchaseOrder';
    import useBusinessInfo from '../../../composables/useBusinessInfo';
    import { objectPick } from '@vueuse/core';
    import useMaterialCosting from '../../../composables/supply-chain/useMaterialCosting';
        
    const { materials, searchMaterial,isLoading: materialLoading } = useMaterial();
    const { warehouses, searchWarehouse,isLoading } = useWarehouse();
    const { searchPurchaseOrder,filteredPurchaseOrders,getPoWiseMrr } = usePurchaseOrder();
    const {fetchTry} = useMaterialCosting();
    const {getMaterialCostingHead} = useBusinessInfo ();
    const props = defineProps({
      form: { type: Object, required: true },
      errors: { type: [Object, Array], required: false },
      formType: { type: String, required : false },
      materialObject: { type: Object, required: false },
      page: {required: false, default: {} },

    });

    const DEPARTMENTS = ref(null);
    const editinitaiated = ref(false); 
    const editinitaiated2 = ref(false); 
    function addMaterial() {
      // const clonedObj = cloneDeep(props.materialObject);
      props.form.scmCostingLines.push({
        particulars: null,
        bdt_amount: null,
        type: 'general'
      });
    }

    function removeMaterial(index){
      props.form.scmCostingLines.splice(index, 1);
    }

    const openTab = ref(1);
    const toggleTabs = (tabNumber) => {
      openTab.value = tabNumber;
    };

    // function setMaterialOtherData(index){
    //   let material = materials.value.find((material) => material.id === props.form.materials[index].material_id);
    //   props.form.materials[index].unit = material.unit;
    //   props.form.materials[index].material_category_id = material.category.id;
    //   props.form.materials[index].material_category_name = material.category.name;
    // }


 

    const tableScrollWidth = ref(null);
    const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;



  //   function fetchWarehouse(search, loading) {
  //   loading(true);
  //   searchWarehouse(search, loading,props.form.business_unit);
  // }
  //   function fetchWarehouse(search, loading = false) {
  //   // loading(true);
  //   searchWarehouse(search, props.form.business_unit);
  // }

  // watch(() => props.form.scmWarehouse, (value) => {
  //       props.form.scm_warehouse_id = value?.id ?? null;
  //       props.form.acc_cost_center_id = value?.acc_cost_center_id ?? null;
  //   });

// function setMaterialOtherData(datas, index) {
//       props.form.scmCostingLines[index].unit = datas.unit;
//       props.form.scmCostingLines[index].scm_material_id = datas.id;
// }

// const previousLines = ref(cloneDeep(props.form.scmCostingLines));

// watch(() => props.form.scmCostingLines, (newLines) => {

//   const materialArray = [];
//   if (newLines) {
//   newLines.forEach((line, index) => {
//     // const previousLine = previousLines.value[index];
//     let material_key = line.scm_material_id;
//     if (materialArray.indexOf(material_key) === -1) {
//       materialArray.push(material_key);
//     } else {
//       alert("Duplicate Material Found");
//       props.form.scmCostingLines.splice(index, 1);
//     } 
//     if (line.scmMaterial) {
//       const selectedMaterial = materials.value.find(material => material.id === line.scmMaterial.id);
//       if (selectedMaterial) {
//         if ( line.scm_material_id !== selectedMaterial.id
//         ) {
//           props.form.scmCostingLines[index].unit = selectedMaterial.unit;
//           props.form.scmCostingLines[index].scm_material_id = selectedMaterial.id;
//         }
//       }
//     }
//   });
// }
//   // previousLines.value = cloneDeep(newLines);
// }, { deep: true });


  //   function fetchMaterials(search, loading) {
  //   loading(true);
  //   searchMaterial(search, loading)
  // }
    function fetchMaterials(search, loading = false) {
    // loading(true);
    searchMaterial(search)
  }

  watch(() => props.form?.scmCostingLines, (newValue, oldValue) => {
    if (props.formType === 'create') {
    editinitaiated.value = true;
  }
    if (editinitaiated.value) {
    if(props.form.purchase_center == 'Foreign'){

    var grand_allocateable = 0;
    props.form.scmCostingLines.forEach((element) => {
      // loop element
      Object.keys(element).forEach((key) => {
        var total_bdt_cfr = 0;
        var total_bdt_cif = 0;
        var total_bdt_assessable = 0;
        var total_bdt_tti = 0;
        var total_bdt_tc = 0;
        var total_usd_cfr = 0;
        var total_usd_cif = 0;
        var total_usd_assessable = 0;
        var total_usd_tti = 0;
        var total_usd_tc = 0;
        var total_lc_cost = 0;
        var grand_total = 0;
        element[key].cfr.forEach((element2) => {
          if(element2.usd_amount != null && element2.exchange_rate != null && element2.usd_amount != '' && element2.exchange_rate != '' && element2.usd_amount != 0 && element2.exchange_rate != 0){
            element2.bdt_amount = parseFloat(element2.usd_amount) * parseFloat(element2.exchange_rate);
          }
          total_usd_cfr += parseFloat(element2.usd_amount);
          total_bdt_cfr += parseFloat(element2.bdt_amount);
        });
        element[key].total_cfr[0].bdt_amount = total_bdt_cfr;
        element[key].cif.forEach((element2) => {
          if(element2.usd_amount != null && element2.exchange_rate != null && element2.usd_amount != '' && element2.exchange_rate != '' && element2.usd_amount != 0 && element2.exchange_rate != 0){
            element2.bdt_amount = parseFloat(element2.usd_amount) * parseFloat(element2.exchange_rate);
          }
          total_bdt_cif += parseFloat(element2.bdt_amount);
          total_usd_cif += parseFloat(element2.usd_amount);
        });
        element[key].total_cif[0].bdt_amount = total_bdt_cif + total_bdt_cfr;
        element[key].a.forEach((element2) => {
          if(element2.usd_amount != null && element2.exchange_rate != null && element2.usd_amount != '' && element2.exchange_rate != '' && element2.usd_amount != 0 && element2.exchange_rate != 0){
            element2.bdt_amount = parseFloat(element2.usd_amount) * parseFloat(element2.exchange_rate);
          }
          total_bdt_assessable += parseFloat(element2.bdt_amount);
          total_usd_assessable += parseFloat(element2.usd_amount);
        });
        element[key].total_assessable[0].bdt_amount = total_bdt_assessable + total_bdt_cif + total_bdt_cfr;
        element[key].tti.forEach((element2) => {
          if(element2.usd_amount != null && element2.exchange_rate != null && element2.usd_amount != '' && element2.exchange_rate != '' && element2.usd_amount != 0 && element2.exchange_rate != 0){
            element2.bdt_amount = parseFloat(element2.usd_amount) * parseFloat(element2.exchange_rate);
          }
          total_bdt_tti += parseFloat(element2.bdt_amount);
          total_usd_tti += parseFloat(element2.usd_amount);
        });
        element[key].total_tti[0].bdt_amount = total_bdt_tti + total_bdt_assessable + total_bdt_cif + total_bdt_cfr;
        element[key].tc.forEach((element2) => {
          if(element2.usd_amount != null && element2.exchange_rate != null && element2.usd_amount != '' && element2.exchange_rate != '' && element2.usd_amount != 0 && element2.exchange_rate != 0){
            element2.bdt_amount = parseFloat(element2.usd_amount) * parseFloat(element2.exchange_rate);
          }
          total_bdt_tc += parseFloat(element2.bdt_amount);
          total_usd_tc += parseFloat(element2.usd_amount);
        });
        element[key].total_tc[0].bdt_amount = total_bdt_tc + total_bdt_tti + total_bdt_assessable + total_bdt_cif + total_bdt_cfr;
          element[key].total_lc_cost.forEach((element2) => {
            total_lc_cost += parseFloat(element2.bdt_amount);
          }); 
        element[key].grand_total[0].bdt_amount = total_bdt_tc + total_bdt_tti + total_bdt_assessable + total_bdt_cif + total_bdt_cfr + total_lc_cost;
        // element[key].grand_total.forEach((element2) => {
        //   grand_total += parseFloat(element2.bdt_amount);
        // }); 

        grand_allocateable += total_bdt_tc + total_bdt_tti + total_bdt_assessable + total_bdt_cif + total_bdt_cfr + total_lc_cost;
      })
      });
      props.form.total_allocateable = grand_allocateable;
    }else if(props.form.purchase_center == 'Local' || props.form.purchase_center == 'Plant'){
      var total = 0;
      props.form.scmCostingLines.forEach((element) => {
        total += parseFloat(element.bdt_amount);
      });
      props.form.total_allocateable = total;
    }
  }else{
    if(props.form.purchase_center == 'Foreign'){
        props.form.scmCostingLines.forEach((element,index) => {
            Object.keys(element).forEach((key) => {
                if(index == 0){
                    openTab.value = (key).toString();
                }
            })
        })
    }

  }
  editinitaiated.value = true;
  }, { deep: true });


  function allocateAmount(){
    var grand_total_value = 0;
        props.form.scmCostingAllocations.forEach((element) => {
            grand_total_value += parseFloat(element.value);
            console.log(grand_total_value);
        });

        if (grand_total_value > 0) {
            props.form.scmCostingAllocations.forEach((element) => {
                element.allocated_amount = element.value / grand_total_value * props.form.total_allocateable;
            });
        }
  }
  watch(() => props.form.total_allocateable, (newValue, oldValue) => {
    if (props.formType === 'create') {
    editinitaiated2.value = true;
    }
    if (editinitaiated2.value) {
        allocateAmount();
    }
    editinitaiated2.value = true;
  });


  watch(() => props.form?.business_unit, (newValue, oldValue) => {
   if(newValue !== oldValue && oldValue != '' && oldValue != null){
    props.form.scmWarehouse = null;
  }
});

// function changePoRef(){
//   // if(props.form.scmPo.purchase_center == 'foreign'){
//   //   props.form.scmWarehouse = null;
//   // }
//   getMaterialCostingHead().then((data) => {
//     console.log( data);
//     var parentData = [];
//     props.form?.scmPo?.scmLcRecords.forEach(element => {
//       // let keyData = [];
//       let keyData = Object.keys(data).map((key) => {
//         let adada  = data[key].map(element2 => {
//             let lineData = {
//                   'particulars': element2,
//                   'exchange_rate': null,
//                   'usd_amount': null,
//                   'bdt_amount': null,
//                   'scmLcRecords' : element.value,
//                   'scm_lc_records_id' : element.id,
//                 };
//             return lineData;
//         });
//         return { [key] : adada };
//       });
//       parentData[element.id] = keyData;
//       });
//       parentData = parentData.filter((item) => item !== null && item !== undefined);
//       props.form.scmCostingLines = parentData;
//       console.log(parentData,parentData.length);
      
//   }).catch((error) => {
//     console.log(error);
//   });
// }

function changePoRef() {
  props.form.scm_po_id = props.form.scmPo?.id;
  props.form.scmWarehouse = props.form.scmPo?.scmWarehouse;
  props.form.scm_warehouse_id = props.form.scmPo?.scmWarehouse?.id;
  props.form.acc_cost_center_id = props.form.scmPo?.scmWarehouse?.acc_cost_center_id;
  props.form.purchase_center = props.form.scmPo?.purchase_center;
  props.form.business_unit = props.form.scmPo?.business_unit;
  props.form.scmCostingLines.splice(0, props.form.scmCostingLines.length);
    props.form.scmCostingAllocations.splice(0, props.form.scmCostingAllocations.length);
  if(props.form.scmPo?.purchase_center == 'Foreign'){
    getMaterialCostingHead().then((data) => {
    var parentData = [];
    props.form?.scmPo?.scmLcRecords.forEach((element,index) => {
      let mainData = {};
      let keyData = Object.keys(data).map((key) => {
        let adada = data[key].map((element2) => {
          var bdt_amount = 0;
          if (element2 != "Freight (Import)" && key == "cfr") {
            bdt_amount = element.lc_margin;
          }else if(key == 'total_lc_cost'){
            bdt_amount = element.total_cost;
          }
          let lineData = {
            particulars: element2,
            exchange_rate: 0,
            usd_amount: 0,
            bdt_amount: bdt_amount,
            scmLcRecord: element,
            scm_lc_record_id: element.id,
            type: key,
          };
          return lineData;
        });
        let innerObject = { [key]: adada };
        mainData[key] = adada;
        return innerObject;
      });
      props.form.scmCostingLines.push({ [element.id] : mainData });
      if(index == 0){
        openTab.value = (element.id).toString();
        }
    });

    }).catch((error) => {
      console.log(error);
    });
  }else if(props.form.scmPo?.purchase_center == 'Local' || props.form.scmPo?.purchase_center == 'Plant'){
    addMaterial();
  }
  getPoWiseMrr(props.form.scmPo?.id).then((data) => {
    if(data.length > 0){
      data.forEach((element) => {
        props.form.scmCostingAllocations.push({
          scm_mrr_id: element.id,
          scmMrr: element,
          value: element.total_value,
          allocated_amount: 0,
        });
      });
      allocateAmount();
    }
  }).catch((error) => {
    console.log(error);
  });

}

// function tableWidth() {
//   setTimeout(function() {
//     const customDataTable = document.getElementById("customDataTable");

//     if (customDataTable) {
//         tableScrollWidth.value = customDataTable.scrollWidth;

//       }

//     }, 10000);
// }
//after mount
// onMounted(() => {
//   tableWidth();
// });
// js private function 
function fetchPurchaseOrder() {
  searchPurchaseOrder('' ,props.form.business_unit);
}

onMounted(() => {
  watchPostEffect(() => {
    // fetchWarehouse("");
    fetchPurchaseOrder("");
    // fetchTry().then((data) => {
    //   DEPARTMENTS.value = data;
    //   console.log(data);
    // }).catch((error) => {
    //   console.log(error);
    // }); 
  });
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

