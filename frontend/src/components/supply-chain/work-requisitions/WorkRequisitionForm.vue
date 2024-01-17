<script setup>
    import { ref, watch, onMounted,watchEffect,computed, onUpdated, watchPostEffect } from 'vue';
    import Error from "../../Error.vue";
    import DropZone from "../../DropZone.vue";
    import useMaterial from "../../../composables/supply-chain/useMaterial.js";
    import useService from "../../../composables/supply-chain/useService.js";
    import useBusinessInfo from "../../../composables/useBusinessInfo.js";
    import useWarehouse from "../../../composables/supply-chain/useWarehouse.js";
    import useStockLedger from '../../../composables/supply-chain/useStockLedger';
    import BusinessUnitInput from "../../input/BusinessUnitInput.vue";
    import DropZoneV2 from '../../../components/DropZoneV2.vue';
    import ErrorComponent from "../../utils/ErrorComponent.vue";
    import RemarksComponet from '../../utils/RemarksComponent.vue';
    import {useStore} from "vuex";
    import env from '../../../config/env';
    import cloneDeep from 'lodash/cloneDeep';
import useHeroIcon from '../../../assets/heroIcon';
    
    const { material, materials, getMaterials,searchMaterial,isLoading: materialLoading } = useMaterial();
    const { services, searchService, isLoading:serviceLoading } = useService();
    const { warehouses,warehouse,getWarehouses,searchWarehouse ,isLoading:warehouseLoading} = useWarehouse();
    const { getMaterialWiseCurrentStock,CurrentStock} =useStockLedger();
    const { getAllStoreCategories } = useBusinessInfo();
    const store_category = ref([]);
    const store = useStore();
    const dropZoneFile = ref(computed(() => store.getters.getDropZoneFile));

    const props = defineProps({
      form: { type: Object, required: true },
      errors: { type: [Object, Array], required: false },
      excelExportData: { type: [Object, Array], required: false },
      downloadExcel: {type: Function },
      formType: { type: String, required : false },
      workObject: { type: Object, required: false },
      page: { required: false, default: {} },

    });

    const customDataTableirf = ref(null);
    const icons = useHeroIcon();

    
    const purchase_center = ['Local', 'Foreign', 'Plant'];
    function addMaterial() {
      const clonedObj = cloneDeep(props.workObject);
      props.form.scmPrLines.push(clonedObj);
      // setMinHeight();
    }

    function removeMaterial(index){
      props.form.scmPrLines.splice(index, 1);
      // setMinHeight();
    }

    function addWork(){
      props.form.scmWrLines.push(cloneDeep(props.workObject));
    }

    function removeWork(index){
      if(props.form.scmWrLines?.length > 1)
        props.form.scmWrLines.splice(index, 1);
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

    function handleAttachmentChange(e) {
      let fileData = e.target.files[0];
      props.form.attachment = fileData;
    }

    onMounted(() => {
      fetchAllStoreCategories();
      fetchMaterials('');
      searchService('', false);
      watchPostEffect(() => {
        fetchWarehouse('');
      });
    });


    // const setMinHeight = () => {
    //   const newMinHeight = customDataTableirf.value.offsetHeight + 10;
    //   console.log(newMinHeight);
    //   customDataTableirf.value.style.minHeight = newMinHeight + 'px' + '!important' + ';';
    // };

    // Use onUpdated to adjust min-height after the component updates
    // onUpdated(() => {
    //   setMinHeight();
    // });
    function fetchAllStoreCategories() {
      getAllStoreCategories().then(AllStoreCategories => {
        store_category.value = Object.values(AllStoreCategories);
        })
        .catch(error => {
            console.error('An error occurred:', error);
        });
    }


    const tableScrollWidth = ref(null);
    const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;


    // onMounted(() => {
    //   watchEffect(() => {
    //     if (props.form.scmPrLines) {
    //       const customDataTable = document.getElementById("customDataTable");
    //       if (customDataTable) {
    //         tableScrollWidth.value = customDataTable.scrollWidth;
    //       }
    //     }
// }, { deep: true });


    // });// Code for global search end here


    const downloadExcel = () => {
          if (typeof props.downloadExcel === 'function') {
            props.downloadExcel();
          }
        };


  //   function fetchWarehouse(search, loading) {
  //   loading(true);
  //   searchWarehouse(search, loading,props.form.business_unit);
// }
    function fetchWarehouse(search) {
    searchWarehouse(search, props.form.business_unit);
  }
  const dynamicMinHeight = ref(0);

  const setMinHeight = () => {
    // dynamicMinHeight.value = customDataTableirf.value.offsetHeight + 100;
  
  };

  onMounted(() => {
    // setMinHeight();
  });

// Use onUpdated to adjust min-height after the component updates
// onUpdated(() => {
//   setMinHeight();
// });

  
// function setMaterialOtherData(datas, index) {
//       props.form.scmPrLines[index].unit = datas.unit;
//       props.form.scmPrLines[index].scm_material_id = datas.id;
//       getMaterialWiseCurrentStock(datas.id,props.form.scm_warehouse_id);
//       props.form.scmPrLines[index].rob = CurrentStock ?? 0;
// }

// const previousLines = ref(cloneDeep(props.form.scmPrLines));

// watch(() => props.form.scmPrLines, (newLines) => {
//   let materialArray = [];
//   newLines.forEach((line, index) => {
//     let material_key = line.scm_material_id + "-" + line?.brand ?? + "-" + line?.model ?? '';
//     if (materialArray.indexOf(material_key) === -1) {
//       materialArray.push(material_key);
//     } else {
//       alert("Duplicate Material Found");
//       props.form.scmPrLines.splice(index, 1);
//     }

//     if (line.scmMaterial) {
//       const selectedMaterial = materials.value.find(material => material.id === line.scmMaterial.id);
//       if (selectedMaterial) {
//         if ( line.scm_material_id !== selectedMaterial.id
//         ) {
//           props.form.scmPrLines[index].unit = selectedMaterial.unit;
//           props.form.scmPrLines[index].scm_material_id = selectedMaterial.id;
//           getMaterialWiseCurrentStock(selectedMaterial.id,props.form.scm_warehouse_id).then(() => {
        
//             props.form.scmPrLines[index].rob = CurrentStock ?? 0;
//           });
//         }
//       }
//     }
//   });
  
//   if (props.form.scmPrLines.length === 0) {
//         addMaterial();
//   }
//   // previousLines.value = cloneDeep(newLines);
// }, { deep: true });


//   function fetchMaterials(search, loading) {
//   loading(true);
//   searchMaterial(search, loading)
// }
  function fetchMaterials(search) {
  searchMaterial(search)
}



  watch(dropZoneFile, (value) => {
    if (value !== null && value !== undefined) {
      props.form.excel = value;
    }
  });

  watch(() => props.form.scmWarehouse, (value) => {
        props.form.scm_warehouse_id = value?.id ?? null;
        props.form.acc_cost_center_id = value?.cost_center_id;
  });
    
  watch(() => props.form.business_unit, (newValue, oldValue) => {
   if(newValue !== oldValue && oldValue != ''){
    props.form.scmWarehouse = null;
  }
});

function warehouseChange(){
    // props.form.scm_warehouse_id = value?.id ?? null;
    props.form.scm_warehouse_id = props.form.scmWarehouse?.id;
    props.form.acc_cost_center_id = props.form.scmWarehouse?.cost_center_id;
}

function scmServiceChange(scmWrLine){
    scmWrLine.scm_service_id = scmWrLine.scmService?.id;
}


function tytyty(indx) {
  console.log(indx)
}



function tytytyasd(indx) {
  console.log(indx)
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
</script>
<template>

  <!-- Basic information -->
  
    <div class="justify-center w-full grid grid-cols-1 md:grid-cols-3 md:gap-2 ">
        <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>

        <label class="label-group col-start-1">
            <span class="label-item-title">Warehouse Name <span class="text-red-500">*</span></span>
            <!-- <v-select :options="warehouses" placeholder="--Choose an option--" @search="fetchWarehouse" v-model="form.scmWarehouse" label="name" class="block form-input"> -->
            <v-select :options="warehouses" placeholder="--Choose an option--" :loading="warehouseLoading" v-model="form.scmWarehouse" label="name" @update:modelValue="warehouseChange" class="block form-input">
            <template #search="{attributes, events}">
                <input
                    class="vs__search"
                    :required="!form.scmWarehouse"
                    v-bind="attributes"
                    v-on="events"
                />
            </template>
            </v-select>
            <input type="hidden" v-model="form.scm_warehouse_id">
            <Error v-if="errors?.scm_warehouse_id" :errors="errors.scm_warehouse_id" />
        </label>
        <label class="label-group">
            <span class="label-item-title">Raised Date<span class="text-red-500">*</span></span>
            <!-- <input type="date" v-model="form.raised_date" required class="form-input" name="raised" :id="'raised'" /> -->
            <VueDatePicker v-model="form.raised_date" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" ></VueDatePicker>
            <Error v-if="errors?.raised_date" :errors="errors.raised_date"  />
        </label>
        
        <label class="label-group">
            <span class="label-item-title">Approved Date<span class="text-red-500">*</span></span>
            <VueDatePicker v-model="form.approved_date" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :min-date="form.raised_date"></VueDatePicker>
            <Error v-if="errors?.approved_date" :errors="errors.approved_date"  />
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
        <RemarksComponet v-model="form.remarks" :maxlength="300" :fieldLabel="'Remarks'"></RemarksComponet>

        <div class="md:col-span-3">
            <!-- <table class="w-full whitespace-no-wrap" id="table">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-center text-gray-500  bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
                    <th class="w-3/12 px-4 align-center">Service Name <span class="text-red-500">*</span></th>
                    <th class="w-3/12 px-4 align-center">Description <span class="text-red-500">*</span></th>
                    <th class="w-2/12 px-4 align-center">Remarks <span class="text-red-500">*</span></th>
                    <th class="w-1/12 px-4 align-center">Quantity <span class="text-red-500">*</span></th>
                    <th class="w-2/12 px-4 align-center">Required Date <span class="text-red-500">*</span></th>
                    <th class="w-1/12 px-4 align-center text-center">
                        Action
                    </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
                    <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(scmWrLine, index) in form.scmWrLines" :key="index">
                    <td class="px-1 py-1">
                      <v-select :options="services" placeholder="--Choose an option--" :loading="serviceLoading" v-model="scmWrLine.scmService" label="name" class="block form-input" @update:modelValue="scmServiceChange(scmWrLine)">
                          <template #search="{attributes, events}">
                              <input
                                  class="vs__search"
                                  :required="!scmWrLine.scmService"
                                  v-bind="attributes"
                                  v-on="events"
                                  />
                          </template>
                      </v-select>
                    </td>
                    <td class="px-1 py-1">
                      <input type="text" :value="scmWrLine?.scmService?.description" placeholder="Description" class="form-input vms-readonly-input"  />
                    </td>
                    <td class="px-1 py-1">
                      <input type="text" v-model.trim="scmWrLine.remarks" placeholder="Remarks" class="form-input" required />
                    </td>
                    <td class="px-1 py-1">
                      <input type="text" v-model.trim="scmWrLine.quantity" placeholder="Quantity" class="form-input" required />
                    </td>
                    <td class="px-1 py-1">
                      <VueDatePicker v-model="form.required_date" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" ></VueDatePicker>
                    </td>
                    <td class="px-1 py-1">
                        <button v-if="index==0" type="button" class="bg-green-600 text-white px-3 py-2 rounded-md" @click="addWork"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg></button>
                        <button v-else type="button" class="bg-red-600 text-white px-3 py-2 rounded-md" @click="removeWork(index)" ><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg></button>
                </td>
                    </tr>
                </tbody>
            </table> -->
            <fieldset class="px-2 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400" >
              <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Services <span class="text-red-500">*</span></legend>
              <div v-for="(scmWrLine, index) in form.scmWrLines" :key="index" class="p-2 my-2 rounded-md border-2 border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 ">
                  <label class="block w-full mt-2 text-sm">
                    <span class="text-gray-700 dark-disabled:text-gray-300">Service Name <span class="text-red-500">*</span></span>
                    <div class="relative">
                      <v-select :options="services" placeholder="--Choose an option--" :loading="serviceLoading" v-model="scmWrLine.scmService" label="name" class="block form-input" @update:modelValue="scmServiceChange(scmWrLine)">
                      <template #search="{attributes, events}">
                          <input
                            class="vs__search"
                            :required="!scmWrLine.scmService"
                            v-bind="attributes"
                            v-on="events"
                            />
                      </template>
                    </v-select>
                    <span v-show="scmWrLine.isServiceDuplicate" class="text-yellow-600 pl-1 absolute top-2 right-10" title="Duplicate Service" v-html="icons.ExclamationTriangle"></span>
                    </div>
                  </label>
                
                  <label class="block w-full mt-2 text-sm">
                    <span class="text-gray-700 dark-disabled:text-gray-300">Description</span>
                    <input type="text" :value="scmWrLine.description = scmWrLine?.scmService?.description" placeholder="Description" class="form-input vms-readonly-input"  />
                  </label>
                  <template>{{ scmWrLine.business_unit = props.form.business_unit }}</template>
                
                  <label class="block w-full mt-2 text-sm">
                    <span class="text-gray-700 dark-disabled:text-gray-300">Remarks</span>
                    <input type="text" v-model.trim="scmWrLine.remarks" placeholder="Remarks" class="form-input"  />
                  </label>
                  
                  <label class="block w-full mt-2 text-sm">
                    <span class="text-gray-700 dark-disabled:text-gray-300">Quantity <span class="text-red-500">*</span></span>
                    <input type="number" step="0.01" v-model.trim="scmWrLine.quantity" placeholder="Quantity" class="form-input" min="1" required />
                  </label>
                  
                  <label class="block w-full mt-2 text-sm">
                    <span class="text-gray-700 dark-disabled:text-gray-300">Required Date <span class="text-red-500">*</span></span>
                    <VueDatePicker v-model="scmWrLine.required_date" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :min-date="form.approved_date"></VueDatePicker>
                  </label>
                </div>

                <div>
                  <button type="button" v-if="form?.scmWrLines?.length > 1" @click="removeWork(index)" class="px-3 py-2 mt-2 mx-auto  text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple flex w-32 justify-center text-center">
                      Remove
                  </button> 
                </div>
                
              </div>

              <button type="button" @click="addWork" class="px-3 py-2 mx-auto mt-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple flex w-32 justify-center text-center">
                    Add More
              </button>
              
              
            </fieldset>
        </div>

        

        <!-- *** ** -->

    </div>
  <!-- <div class="flex flex-col justify-center w-1/4 md:flex-row md:gap-2">
  </div>
  <div class="input-group">
      <label class="label-group">
          <span class="label-item-title">PR Ref <span class="text-red-500">*</span></span>
          <input type="text" readonly v-model="form.ref_no" required class="form-input vms-readonly-input" name="ref_no" :id="'ref_no'" />
      </label>
      
      <label class="label-group">
          <span class="label-item-title">Raised Date<span class="text-red-500">*</span></span>
          <input type="date" v-model="form.raised_date" required class="form-input" name="raised" :id="'raised'" />
      </label>
      <label class="label-group">
          <span class="label-item-title">Critical Spares<span class="text-red-500">*</span></span>
          <select v-model="form.is_critical" required class="block w-full mt-1 text-xs rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray form-input">
              <option value="1">YES</option>
              <option value="0">NO</option>
          </select>
      </label>
  </div>
  <div class="input-group !w-3/4">
    <label class="label-group">
        <span class="label-item-title">Attachment</span>
        <input type="file" class="form-input" @change="handleAttachmentChange" />
    </label>
    <label class="label-group">
        <span class="label-item-title">Purchase Center <span class="text-red-500">*</span></span>
        <v-select :options="purchase_center" placeholder="--Choose an option--" v-model="form.purchase_center" label="Product Source Type" class="block w-full mt-1 text-xs rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray form-input">
          <template #search="{attributes, events}">
              <input
                  class="vs__search"
                  :required="!form.purchase_center"
                  v-bind="attributes"
                  v-on="events"
              />  
          </template>        
        </v-select>
    </label>
      <label class="label-group">
          <span class="label-item-title">Approved Date <span class="text-red-500">*</span></span>
          <input type="date" v-model="form.approved_date" required class="form-input" name="approved_date" :id="'approved_date'" />
      </label>
  </div>

  <div class="input-group !w-3/4">
    <label class="label-group">
          
          <RemarksComponet v-model="form.remarks" :maxlength="300" :fieldLabel="'Remarks'"></RemarksComponet>
    </label>
  </div>

  <div class="flex items-center w-full" v-if="formType != 'edit'">
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
  </div>
  <div id="" v-if="form?.entry_type == '0' || formType == 'edit'">

    <div class="border border-gray-200 rounded-md my-3 p-2">
      <div class="">
        <h4 class="text-md font-semibold mb-2">Materials</h4>
        
        <div v-for="(ScmPrLine, index) in form.scmPrLines" :key="index" class="w-full mx-auto p-2 border rounded-mdborder-gray-400 mb-5 shadow-md">
          <label class="block w-1/2 mt-2 text-sm">

          <span class="text-gray-700 dark-disabled:text-gray-300">Material Name <span class="text-red-500">*</span></span>
         </label>
          <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
            <label class="block w-1/2 mt-2 text-sm">
              <v-select :options="materials" placeholder="--Choose an option--" :loading="materialLoading" v-model="form.scmPrLines[index].scmMaterial" label="material_name_and_code" class="block form-input">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.scmPrLines[index].scmMaterial"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
            </label>
          <label class="block w-full mt-2 text-sm">
                        <a v-if="form.scmPrLines[index].scmMaterial" :href="env.BASE_API_URL+form.scmPrLines[index].scmMaterial?.sample_photo" target="_blank" rel="noopener noreferrer">
                                  <img :src="env.BASE_API_URL+form.scmPrLines[index].scmMaterial?.sample_photo"  alt="" srcset="" class="w-12 mx-auto">
                          </a>
          </label>
          </div>
          <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
                    
                    <label class="block w-full mt-2 text-sm">
                      <span class="text-gray-700 dark-disabled:text-gray-300">Unit <span class="text-red-500">*</span></span>
                      <input type="text" readonly v-model="form.scmPrLines[index].unit" class="vms-readonly-input form-input">
                  </label>

                    <label class="block w-full mt-2 text-sm">
                      <span class="text-gray-700 dark-disabled:text-gray-300">Brand </span>
                      <input type="text" v-model="form.scmPrLines[index].brand" class="form-input">
                    </label>

                    <label class="block w-full mt-2 text-sm">
                      <span class="text-gray-700 dark-disabled:text-gray-300">Model </span>
                      <input type="text" v-model="form.scmPrLines[index].model" class="form-input">
                    </label>
                    <label class="block w-full mt-2 text-sm">
                      <span class="text-gray-700 dark-disabled:text-gray-300">Required Date <span class="text-red-500">*</span></span>
                      <input type="date" v-model="form.scmPrLines[index].required_date" required class="form-input">
                    </label>
          </div>
          <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">

                    <label class="block w-full mt-2 text-sm">
                      <span class="text-gray-700 dark-disabled:text-gray-300">Specification </span>

                      <input type="text" v-model="form.scmPrLines[index].specification" placeholder="" class="form-input text-right" autocomplete="off"/>
                    </label>
                    <label class="block w-full mt-2 text-sm">
                      <span class="text-gray-700 dark-disabled:text-gray-300">Origin </span>

                      <input type="text" v-model="form.scmPrLines[index].country_name" placeholder="" class="form-input text-right" autocomplete="off"/>
                    </label>
                   
                    <label class="block w-full mt-2 text-sm">
                      <span class="text-gray-700 dark-disabled:text-gray-300">Drawing No </span>

                      <input type="text" v-model="form.scmPrLines[index].drawing_no" placeholder="" class="form-input text-right" autocomplete="off"/>
                    </label>
                    <label class="block w-full mt-2 text-sm">
                      <span class="text-gray-700 dark-disabled:text-gray-300">Part No </span>

                      <input type="text" v-model="form.scmPrLines[index].part_no" placeholder="" class="form-input text-right" autocomplete="off"/>
                    </label>
                    
          </div>
          <div class="flex flex-col justify-center w-2/4 md:flex-row md:gap-2">

           
            <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Rob </span>

              <input type="number" v-model="form.scmPrLines[index].rob" readonly placeholder="" class="form-input text-right vms-readonly-input" autocomplete="off"/>
            </label>
            <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Quantity <span class="text-red-500">*</span></span>

              <input type="number" v-model="form.scmPrLines[index].quantity" required min="1" placeholder="" class="form-input text-right" autocomplete="off"/>
            </label>
            </div>
          <div class="flex justify-center items-center my-3">
                    <button type="button" @click="addMaterial()" class="px-3 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple flex w-32 justify-center text-center">
                      Add More
                    </button>
                    <button type="button" v-if="index>0" @click="removeMaterial(index)" class="px-3 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple flex w-32 justify-center text-center ml-3">
                      Remove
                    </button> 
          </div>
        </div>      
      </div>
    </div>
  </div>


  <div v-else>
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

 

 
    ::-webkit-scrollbar:horizontal {
      height: 1rem!important; 
    }
  
    ::-webkit-scrollbar-thumb:horizontal{
      background-color: rgb(132, 109, 175); 
      border-radius: 12rem!important;
      width: 0.5rem!important;
      height: 0.5rem!important;
      border-radius: 12rem!important;
    }
  
    ::-webkit-scrollbar-track:horizontal{
      background: rgb(148, 144, 155)!important; 
      border-radius: 12rem!important;
    }
  
    ::-webkit-scrollbar-button:horizontal {
      background-color: rgb(0, 0, 0); 
      border-radius: 12rem!important;
      width: 1.3rem!important;
    }   

  
</style>