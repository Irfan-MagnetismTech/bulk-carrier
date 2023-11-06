<script setup>
    import { ref, watch, onMounted,watchEffect,computed,toRefs } from 'vue';
    import {useStore} from "vuex";
    import Error from "../../Error.vue";
    import useMaterial from "../../../composables/supply-chain/useMaterial.js";
    import useBusinessInfo from "../../../composables/useBusinessInfo.js";
    import useWarehouse from "../../../composables/supply-chain/useWarehouse.js";
    import BusinessUnitInput from "../../input/BusinessUnitInput.vue";
    import env from '../../../config/env';
    import cloneDeep from 'lodash/cloneDeep';
    
    const { material, materials, getMaterials,searchMaterial } = useMaterial();
    const { warehouses,warehouse,getWarehouses,searchWarehouse } = useWarehouse();
    const store_category = ref([]);
    const firstInitiated = ref(false);

    const props = defineProps({
      form: { type: Object, required: true },
      errors: { type: [Object, Array], required: false },
      formType: { type: String, required : false },
      materialObject: { type: Object, required: false },
      page: {
      required: false,
      default: {}
    },

    });
    // const {form} = toRefs(props);
    function addMaterial() {
      const clonedObj = cloneDeep(props.materialObject);
      form.scmPrLines.push(clonedObj);
      const index = props.form.scmMrrLines.length - 1;
      watchQuantity(index);
    }

    function removeMaterial(index){
      props.form.scmPrLines.splice(index, 1);
    }
  
    const tableScrollWidth = ref(null);
    const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
    
    onMounted(() => {
      watchEffect(() => {
      if(firstInitiated.value == false){
          props.form.scmMrrLines.forEach((line, index) => {
          watchQuantity(index);
        });
        firstInitiated.value = true;
      }
    })
    });

    const watchQuantity = (index) => {
    const rate = computed(() => props.form.scmMrrLines[index].rate);
    watch(rate, (newVal, oldVal) => {
      if (newVal !== oldVal && oldVal !== null) {
        // props.form.scmMrrLines[index].net_rate = newVal;
        alert();
      }
    });
  };
</script>
<template>

  <!-- Basic information -->
  <div class="flex flex-col justify-center w-1/4 md:flex-row md:gap-2">
     <input type="text" readonly v-model="form.business_unit" required class="form-input vms-readonly-input" name="business_unit" :id="'business_unit'" />
  </div>
  <div class="input-group !w-1/4">
      <label class="label-group">
          <span class="label-item-title">MRR Ref<span class="text-red-500">*</span></span>
          <input type="text" readonly v-model="form.ref_no" required class="form-input vms-readonly-input" name="ref_no" :id="'ref_no'" />
          <Error v-if="errors?.ref_no" :errors="errors.ref_no"  />
      </label>
  </div>
  <div class="input-group-grid">
      <label class="label-group">
          <span class="label-item-title">Received Date<span class="text-red-500">*</span></span>
          <input type="date" v-model="form.date" required class="form-input" name="date" :id="'date'" />
          <Error v-if="errors?.date" :errors="errors.date"  />
      </label>
      <label class="label-group" v-if="form.type == 'FOREIGN'">
          <span class="label-item-title">LC Record No<span class="text-red-500">*</span></span>
          <input type="text" v-model="form.scmLcRecord" required class="form-input" name="scmLcRecord" :id="'scmLcRecord'" />
          <Error v-if="errors?.scmLcRecord" :errors="errors.scmLcRecord"  />
      </label>
      <label class="label-group" v-if="form.type !== 'CASH'">
          <span class="label-item-title">PO No<span class="text-red-500">*</span></span>
          <input type="text" v-model="form.scm_po_no" required readonly class="form-input vms-readonly-input" name="raised" :id="'raised'" />
          <Error v-if="errors?.scm_po_no" :errors="errors.scm_po_no"  />
      </label>
      <label class="label-group" v-if="form.type !== 'CASH'">
          <span class="label-item-title">PO Date<span class="text-red-500">*</span></span>
          <input type="date" v-model="form.po_date" required readonly class="form-input vms-readonly-input" name="scm_po_date" :id="'scm_po_date'" />
          <Error v-if="errors?.po_date" :errors="errors.po_date"  />
      </label>
      
      <label class="label-group">
          <span class="label-item-title">PR No<span class="text-red-500">*</span></span>
          <input type="text" v-model="form.scm_pr_no" required readonly class="form-input vms-readonly-input" name="scm_pr_no" :id="'scm_pr_no'" />
          <Error v-if="errors?.scm_pr_no" :errors="errors.scm_pr_no"  />
      </label>
      <label class="label-group" v-if="form.type !== 'CASH'">
          <span class="label-item-title">CS No<span class="text-red-500">*</span></span>
          <input type="text" v-model="form.scm_cs_no" required readonly class="form-input vms-readonly-input" name="raised" :id="'raised'" />
          <Error v-if="errors?.scm_cs_no" :errors="errors.scm_cs_no"  />
      </label>
      <label class="label-group" v-if="form.type == 'CASH'">
          <span class="label-item-title">IOU No<span class="text-red-500">*</span></span>
          <input type="text" v-model="form.scm_iou_no" required class="form-input" name="scm_iou_no" :id="'scm_iou_no'" />
          <Error v-if="errors?.scm_iou_no" :errors="errors.scm_iou_no"  />
      </label>
      <label class="label-group">
          <span class="label-item-title">Challan<span class="text-red-500">*</span></span>
          <input type="text" v-model="form.challan_no" required class="form-input" name="challan_no" :id="'challan_no'" />
          <Error v-if="errors?.challan_no" :errors="errors.challan_no"  />
      </label>
      <label class="label-group">
          <span class="label-item-title">Status<span class="text-red-500">*</span></span>
          <select v-model="form.is_completed" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
              <option value="1">Settled</option>
              <option value="0">Remaining</option>
          </select>
          <Error v-if="errors?.is_completed" :errors="errors.is_completed"  />
      </label>
  </div>
  <div class="input-group">
     
  </div>
  <div class="input-group !w-3/4">
    <label class="label-group">
          <span class="label-item-title">Remarks <span class="text-red-500">*</span></span>
          <textarea v-model="form.remarks" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></textarea>
          <Error v-if="errors?.remarks" :errors="errors.remarks" />
    </label>
  </div>
  <div class="input-group !w-3/4">
    <label class="label-group">
          <span class="label-item-title">QC Remarks <span class="text-red-500">*</span></span>
          <textarea v-model="form.qc_remarks" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></textarea>
          <Error v-if="errors?.qc_remarks" :errors="errors.qc_remarks" />
    </label>
  </div>
  <div id="">

    <div id="customDataTable" style="">
    <div class="table-responsive min-w-screen overflow-x-scroll">
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
        <legend class="px-2 text-gray-700 dark:text-gray-300">Materials <span class="text-red-500">*</span></legend>
        <table class="whitespace-no-wrap">
          <thead>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th style="" class="py-3 align-center">Material Name </th>
            <th style="" class="py-3 align-center">Unit</th>
            <th class="py-3 align-center">Brand</th>
            <th class="py-3 align-center">Model</th>
            <th class="py-3 align-center">PO Qty</th>
            <th class="py-3 align-center">PR Qty</th>
            <th class="py-3 align-center">Current Stock</th>
            <th class="py-3 align-center">Qty</th>
            <th class="py-3 align-center">Rate</th>
            <th class="py-3 text-center align-center">Action</th>
          </tr>
          </thead>

          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr class="text-gray-700 dark:text-gray-400" v-for="(scmMrrLine, index) in form.scmMrrLines" :key="index">
            <td class="!w-72">
              <v-select :options="materials" placeholder="--Choose an option--" v-model="form.scmMrrLines[index].scmMaterial" label="material_name_and_code" class="block form-input">
                <template #search="{attributes, events}">
                    <input
                        class="vs__search"
                        :required="!form.scmMrrLines[index].scmMaterial"
                        v-bind="attributes"
                        v-on="events"
                        />
                </template>
            </v-select>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <input type="text" readonly v-model="form.scmMrrLines[index].unit" class="vms-readonly-input form-input">
               </label>
              
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <input type="text" v-model="form.scmMrrLines[index].brand" class="form-input">
               </label>
              
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <input type="text" v-model="form.scmMrrLines[index].model" class="form-input">
               </label>
              
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <input type="text" v-model="form.scmMrrLines[index].po_qty" class="form-input">
              </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <input type="text" v-model="form.scmMrrLines[index].pr_qty" class="form-input">
              </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <input type="text" v-model="form.scmMrrLines[index].current_stock" class="form-input">
              </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <input type="text" v-model="form.scmMrrLines[index].quantity" class="form-input">
              </label>
            </td> 
            <td>
              <label class="block w-full mt-2 text-sm">
                 <input type="text" v-model="form.scmMrrLines[index].rate" class="form-input" :readonly="form.type !== 'CASH'" :class="{'vms-readonly-input': form.type !== 'CASH'}">
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
        </table>
      </fieldset>
    </div>
    </div>
  </div>

</template>

<style lang="postcss" scoped>
    .input-group {
        @apply flex flex-col justify-center w-full md:flex-row md:gap-2;
    }

    .input-group-grid {
        @apply grid grid-cols-1 md:grid-cols-4 md:gap-2 justify-center w-full;
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
    .vs__selected{
    display: none !important;
    }
    .required-style{
        @apply text-red-400 font-semibold
    }

 

</style>