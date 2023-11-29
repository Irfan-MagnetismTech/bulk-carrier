<script setup>
    import { ref, watch, onMounted,watchEffect,computed, onUpdated, watchPostEffect } from 'vue';
    import Error from "../../Error.vue";
    import DropZone from "../../DropZone.vue";
    import useMaterial from "../../../composables/supply-chain/useMaterial.js";
    import useBusinessInfo from "../../../composables/useBusinessInfo.js";
    import useWarehouse from "../../../composables/supply-chain/useWarehouse.js";
    import useStockLedger from '../../../composables/supply-chain/useStockLedger';
    import BusinessUnitInput from "../../input/BusinessUnitInput.vue";
    import DropZoneV2 from '../../DropZoneV2.vue';
    import ErrorComponent from "../../utils/ErrorComponent.vue";
    import {useStore} from "vuex";
    import env from '../../../config/env';
    import cloneDeep from 'lodash/cloneDeep';
    
    const { material, materials, getMaterials,searchMaterial,isLoading: materialLoading } = useMaterial();
    const { warehouses,warehouse,getWarehouses,searchWarehouse ,isLoading:warehouseLoading} = useWarehouse();
    const { getMaterialWiseCurrentStock,CurrentStock} =useStockLedger();
    const { getAllStoreCategories } = useBusinessInfo();
    const store_category = ref([]);
    const store = useStore();
    const dropZoneFile = ref(computed(() => store.getters.getDropZoneFile));

    const props = defineProps({
      form: { type: Object, required: true }
      
    });

    const customDataTableirf = ref(null);
    
    const purchase_center = ['Local', 'Foreign', 'Plant'];


    const tableScrollWidth = ref(null);
    const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;


  const dynamicMinHeight = ref(0);

  const setMinHeight = () => {
    dynamicMinHeight.value = customDataTableirf.value.offsetHeight + 100;
  
  };

  onMounted(() => {
    setMinHeight();
  });


</script>
<template>

  <!-- Basic information -->
  <div class="flex flex-col justify-center w-1/4 md:flex-row md:gap-2">
    <span class="show-block">{{ form.business_unit?.name }}</span>
  </div>
  <div class="input-group">
      <label class="label-group">
          <span class="label-item-title">PR Ref <span class="text-red-500">*</span></span>
          <span class="show-block">{{ form.ref_no }}</span>
      </label>
      <label class="label-group">
        <span class="label-item-title">Warehouse <span class="text-red-500">*</span></span>
          <span class="show-block">{{ form.scmWarehouse?.name }}</span>
      </label>
      <label class="label-group">
          <span class="label-item-title">Raised Date<span class="text-red-500">*</span></span>
          <span class="show-block">{{ form.raised_date }}</span>
      </label>
      <label class="label-group">
          <span class="label-item-title">Critical Spares<span class="text-red-500">*</span></span>
          <span class="show-block">{{ form.is_critical == 1 ? 'YES' : 'NO' }}</span>
      </label>
  </div>
  <div class="input-group !w-3/4">
    <label class="label-group">
        <span class="label-item-title">Attachment</span>
        <!-- <a v-if="form.attachment" :href="env.BASE_API_URL+form.attachment" target="_blank" rel="noopener noreferrer"/>
          <img :src="env.BASE_API_URL+form.attachment"  alt="" srcset="" class="w-12 mx-auto"> -->
    </label>
    <label class="label-group">
        <span class="label-item-title">Purchase Center <span class="text-red-500">*</span></span>
        <span class="show-block">{{ form.purchase_center }}</span>
    </label>
      <label class="label-group">
          <span class="label-item-title">Approved Date <span class="text-red-500">*</span></span>
          <span class="show-block">{{ form.approved_date }}</span>
      </label>
  </div>

  <div class="input-group !w-3/4">
    <label class="label-group">
          <span class="label-item-title">Remarks </span>
          <span class="show-block">{{ form.remarks }}</span>
    </label>
  </div>

  <!-- <div class="flex items-center w-full" v-if="formType != 'edit'">
    <div class="label-group">
          <div class="my-3 flex gap-6">
                <div class="flex items-center">
                    <input type="radio" value="0" v-model="form.entry_type" class="" name="entry_type" :id="'material_entry'" />
                    <label class="ml-2" for="material_entry">Material Entry</label>
                </div>
                <div class="flex items-center">
                    <input type="radio" value="1" v-model="form.entry_type" class="" name="entry_type" :id="'bulk_entry'" />
                    <label class="ml-2" for="bulk_entry">Bulk Entry</label>
                </div>
            </div>
        </div>
  </div> -->
  <div id="">

    <div id="customDataTable" ref="customDataTableirf" class="!max-w-screen overflow-x-scroll" :style="{ minHeight: dynamicMinHeight + 'px!important' }" > 
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
        <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Materials</legend>
        <div class=""> 
        <table class="table-auto">
          <thead>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
            <th class="whitespace-no-wrap py-3 align-center min-w-[200px] md:min-w-[250px] lg:min-w-[300px]">Material Name </th>
            <th class="whitespace-no-wrap py-3 align-center min-w-[100px] md:min-w-[125px] lg:min-w-[150px]">Unit</th>
            <th class="whitespace-no-wrap py-3 align-center min-w-[100px] md:min-w-[125px] lg:min-w-[150px]">Brand</th>
            <th class="whitespace-no-wrap py-3 align-center min-w-[100px] md:min-w-[125px] lg:min-w-[150px]">Model</th>
            <th class="whitespace-no-wrap py-3 align-center min-w-[200px] md:min-w-[250px] lg:min-w-[300px]">Specification</th>
            <th class="whitespace-no-wrap py-3 align-center min-w-[100px] md:min-w-[125px] lg:min-w-[150px]">Origin</th>
            <th class="whitespace-no-wrap py-3 align-center min-w-[100px] md:min-w-[125px] lg:min-w-[150px]">Sample</th>
            <th class="whitespace-no-wrap py-3 align-center min-w-[100px] md:min-w-[125px] lg:min-w-[150px]">Drawing No</th>
            <th class="whitespace-no-wrap py-3 align-center min-w-[100px] md:min-w-[125px] lg:min-w-[150px]">Part No</th>
            <th class="whitespace-no-wrap py-3 align-center min-w-[100px] md:min-w-[125px] lg:min-w-[150px]">ROB</th>
            <th class="whitespace-no-wrap py-3 align-center min-w-[100px] md:min-w-[125px] lg:min-w-[150px]">Qty</th>
            <th class="whitespace-no-wrap py-3 align-center min-w-[100px] md:min-w-[125px] lg:min-w-[150px]">Required Date</th>
          </tr>
          </thead>

          <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
          <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(ScmPrLine, index) in form.scmPrLines" :key="index">
            <td class="">
              <span class="show-block">{{ form.scmPrLines[index].scmMaterial?.material_name_and_code }}</span>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <span class="show-block">{{ form.scmPrLines[index].unit }}</span>
               </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <span class="show-block">{{ form.scmPrLines[index].brand }}</span>
               </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <span class="show-block">{{ form.scmPrLines[index].model }}</span>
               </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <span class="show-block">{{ form.scmPrLines[index].specification }}</span>
               </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <span class="show-block">{{ form.scmPrLines[index].origin }}</span>
               </label>
            </td>
            <td>
              <a v-if="form.scmPrLines[index].scmMaterial" :href="env.BASE_API_URL+form.scmPrLines[index].scmMaterial?.sample_photo" target="_blank" rel="noopener noreferrer">
                      <img :src="env.BASE_API_URL+form.scmPrLines[index].scmMaterial?.sample_photo"  alt="" srcset="" class="w-12 mx-auto">
               </a>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <span class="show-block">{{ form.scmPrLines[index].drawing_no }}</span>
              </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <span class="show-block">{{ form.scmPrLines[index].part_no }}</span>
              </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <span class="show-block">{{ form.scmPrLines[index].rob }}</span>
              </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <span class="show-block">{{ form.scmPrLines[index].quantity }}</span>
              </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <span class="show-block">{{ form.scmPrLines[index].required_date }}</span>
              </label>
            </td>
          </tr>
          </tbody>
        </table>
      </div>
      </fieldset>
    </div>
  </div>


  <!-- <div v-else>
    <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
        <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Download Excel <span class="text-red-500">*</span></legend>
        <div class="input-group !w-1/2">
                <label class="label-group">
                    <span class="label-item-title">Store Category<span class="text-red-500">*</span></span>
                    <v-select name="user" v-model="excelExportData.store_category_name" placeholder="--Choose an option--" label="Store Category" :options="store_category" class="block w-full mt-1 text-xs rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray form-input">
                    </v-select>
                </label>
                <label class="label-group">
                <button type="button" @click.prevent="downloadExcel" class="flex items-center justify-center px-4 py-2 mt-6 ml-6 text-sm leading-4 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium active:bg-purple-600  hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Download </button>
                </label>
            </div>
      </fieldset>
      <hr class="my-4"/>

      <DropZoneV2 :form="form" :page="page"></DropZoneV2>
  </div> -->
  <ErrorComponent :errors="errors"></ErrorComponent>  
</template>









<style lang="postcss" scoped>
.custom-v-select .vs__dropdown-menu {
  z-index: 9999!important;
  position: absoluteimportant; /* or fixed */
}

.your-parent-container {
  z-index: 1002; /* or any value higher than the z-index of other elements */
}
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

 

 
    #customDataTable::-webkit-scrollbar:horizontal {
      height: 1rem!important; 
    }
  
    #customDataTable::-webkit-scrollbar-thumb:horizontal{
      background-color: rgb(132, 109, 175); 
      border-radius: 12rem!important;
      width: 0.5rem!important;
      height: 0.5rem!important;
      border-radius: 12rem!important;
    }
  
    #customDataTable::-webkit-scrollbar-track:horizontal{
      background: rgb(148, 144, 155)!important; 
      border-radius: 12rem!important;
    }
  
    #customDataTable::-webkit-scrollbar-button:horizontal {
      background-color: rgb(0, 0, 0); 
      border-radius: 12rem!important;
      width: 1.3rem!important;
    }   

  
</style>