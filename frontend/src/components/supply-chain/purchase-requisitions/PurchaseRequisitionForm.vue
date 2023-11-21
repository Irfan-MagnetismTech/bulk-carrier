<script setup>
    import { ref, watch, onMounted,watchEffect,computed } from 'vue';
    import Error from "../../Error.vue";
    import DropZone from "../../DropZone.vue";
    import useMaterial from "../../../composables/supply-chain/useMaterial.js";
    import useBusinessInfo from "../../../composables/useBusinessInfo.js";
    import useWarehouse from "../../../composables/supply-chain/useWarehouse.js";
    import useStockLedger from '../../../composables/supply-chain/useStockLedger';
    import BusinessUnitInput from "../../input/BusinessUnitInput.vue";
    import DropZoneV2 from '../../../components/DropZoneV2.vue';
    import {useStore} from "vuex";
    import env from '../../../config/env';
    import cloneDeep from 'lodash/cloneDeep';
    
    const { material, materials, getMaterials,searchMaterial } = useMaterial();
    const { warehouses,warehouse,getWarehouses,searchWarehouse } = useWarehouse();
    const { getMaterialWiseCurrentStock,CurrentStock} =useStockLedger();
    const { getAllStoreCategories } = useBusinessInfo();
    const store_category = ref([]);


    const props = defineProps({
      form: { type: Object, required: true },
      errors: { type: [Object, Array], required: false },
      excelExportData: { type: [Object, Array], required: false },
      downloadExcel: {type: Function },
      formType: { type: String, required : false },
      materialObject: { type: Object, required: false },
      page: {
      required: false,
      default: {}
    },

    });

    const purchase_center = ['Local', 'Foreign', 'Plant'];
    function addMaterial() {
      const clonedObj = cloneDeep(props.materialObject);
      props.form.scmPrLines.push(clonedObj);
    }

    function removeMaterial(index){
      props.form.scmPrLines.splice(index, 1);
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
    });

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


    function fetchWarehouse(search, loading) {
    loading(true);
    searchWarehouse(search, loading,props.form.business_unit);
  }

  watch(() => props.form.scmWarehouse, (value) => {
        props.form.scm_warehouse_id = value?.id;
        props.form.acc_cost_center_id = value?.acc_cost_center_id;
    });

function setMaterialOtherData(datas, index) {
      console.log('change_event');
      props.form.scmPrLines[index].unit = datas.unit;
      props.form.scmPrLines[index].scm_material_id = datas.id;
      getMaterialWiseCurrentStock(datas.id,props.form.scm_warehouse_id);
      props.form.scmPrLines[index].rob = CurrentStock ?? 0;
}

// const previousLines = ref(cloneDeep(props.form.scmPrLines));

watch(() => props.form.scmPrLines, (newLines) => {
  newLines.forEach((line, index) => {
    // const previousLine = previousLines.value[index];

    if (line.scmMaterial) {
      const selectedMaterial = materials.value.find(material => material.id === line.scmMaterial.id);
      if (selectedMaterial) {
        if ( line.scm_material_id !== selectedMaterial.id
        ) {
          props.form.scmPrLines[index].unit = selectedMaterial.unit;
          props.form.scmPrLines[index].scm_material_id = selectedMaterial.id;
          getMaterialWiseCurrentStock(selectedMaterial.id,props.form.scm_warehouse_id);
          props.form.scmPrLines[index].rob = CurrentStock ?? 0;
        }
      }
    }
  });
  // previousLines.value = cloneDeep(newLines);
}, { deep: true });


    function fetchMaterials(search, loading) {
    loading(true);
    searchMaterial(search, loading)
  }

  const store = useStore();
const dropZoneFile = ref(computed(() => store.getters.getDropZoneFile));

  watch(dropZoneFile, (value) => {
    if (value !== null && value !== undefined) {
      props.form.excel = value;
    }
  });
  watch(() => props.form.business_unit, (newValue, oldValue) => {
   if(newValue !== oldValue && oldValue != ''){
    props.form.scm_warehouse_id = '';
    props.form.acc_cost_center_id = '';
    props.form.scmWarehouse = null;
  }
});

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
});
</script>
<template>

  <!-- Basic information -->
  <div class="flex flex-col justify-center w-1/4 md:flex-row md:gap-2">
    <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
  </div>
  <div class="input-group">
      <label class="label-group">
          <span class="label-item-title">PR Ref<span class="text-red-500">*</span></span>
          <input type="text" readonly v-model="form.ref_no" required class="form-input vms-readonly-input" name="ref_no" :id="'ref_no'" />
          <Error v-if="errors?.ref_no" :errors="errors.ref_no"  />
      </label>
      <label class="label-group">
        <span class="label-item-title">Warehouse <span class="text-red-500">*</span></span>
          <v-select :options="warehouses" placeholder="--Choose an option--" @search="fetchWarehouse"  v-model="form.scmWarehouse" label="name" class="block form-input">
          <template #search="{attributes, events}">
              <input
                  class="vs__search"
                  :required="!form.scmWarehouse"
                  v-bind="attributes"
                  v-on="events"
              />
          </template>
          </v-select>
          <Error v-if="errors?.unit" :errors="errors.unit" />
      </label>
      <label class="label-group">
          <span class="label-item-title">Raised Date<span class="text-red-500">*</span></span>
          <input type="date" v-model="form.raised_date" required class="form-input" name="raised" :id="'raised'" />
          <Error v-if="errors?.raised" :errors="errors.raised"  />
      </label>
      <label class="label-group">
          <span class="label-item-title">Critical Spares<span class="text-red-500">*</span></span>
          <select v-model="form.is_critical" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
              <option value="1">YES</option>
              <option value="0">NO</option>
          </select>
          <Error v-if="errors?.is_critical" :errors="errors.is_critical"  />
      </label>
  </div>
  <div class="input-group !w-3/4">
    <label class="label-group">
        <span class="label-item-title">Attachment<span class="text-red-500">*</span></span>
        <input type="file" class="form-input" @change="handleAttachmentChange" />
        <Error v-if="errors?.attachment" :errors="errors.attachment"  />
    </label>
    <label class="label-group">
        <span class="label-item-title">Purchase Center</span>
        <v-select :options="purchase_center" placeholder="--Choose an option--" v-model="form.purchase_center" label="Product Source Type" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
        <Error v-if="errors?.purchase_center" :errors="errors.purchase_center" />
    </label>
      <label class="label-group">
          <span class="label-item-title">Approved Date <span class="text-red-500">*</span></span>
          <input type="date" v-model="form.approved_date" required class="form-input" name="approved_date" :id="'approved_date'" />
          <Error v-if="errors?.approved_date" :errors="errors.approved_date"  />
      </label>
  </div>

  <div class="input-group !w-3/4">
    <label class="label-group">
          <span class="label-item-title">Remarks <span class="text-red-500">*</span></span>
          <textarea v-model="form.remarks" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></textarea>
          <Error v-if="errors?.remarks" :errors="errors.remarks" />
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

    <div id="customDataTable">
    <div class="table-responsive min-w-screen">
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
        <legend class="px-2 text-gray-700 dark:text-gray-300">Materials <span class="text-red-500">*</span></legend>
        <table class="whitespace-no-wrap overflow-x-auto">
          <thead>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th class="py-3 align-center w-10">Material Name </th>
            <th class="py-3 align-center">Unit</th>
            <th class="py-3 align-center">Brand</th>
            <th class="py-3 align-center">Model</th>
            <th class="py-3 align-center">Specification</th>
            <th class="py-3 align-center">Origin</th>
            <th class="py-3 align-center">Sample</th>
            <th class="py-3 align-center">Drawing No</th>
            <th class="py-3 align-center">Part No</th>
            <th class="py-3 align-center">ROB</th>
            <th class="py-3 align-center">Qty</th>
            <th class="py-3 align-center">Required Date</th>
            <th class="py-3 text-center align-center">Action</th>
          </tr>
          </thead>

          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr class="text-gray-700 dark:text-gray-400" v-for="(ScmPrLine, index) in form.scmPrLines" :key="index">
            <td class="">
              <v-select :options="materials" placeholder="--Choose an option--" @search="fetchMaterials" v-model="form.scmPrLines[index].scmMaterial" label="material_name_and_code" class="block form-input" @change="setMaterialOtherData(form.scmPrLines[index].scmMaterial,index)">
                <template #search="{attributes, events}">
                    <input
                        class="vs__search"
                        :required="!form.scmPrLines[index].scmMaterial"
                        v-bind="attributes"
                        v-on="events"
                        />
                </template>
            </v-select>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <input type="text" readonly v-model="form.scmPrLines[index].unit" class="vms-readonly-input form-input">
               </label>
              
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <input type="text" v-model="form.scmPrLines[index].brand" class="form-input">
               </label>
              
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <input type="text" v-model="form.scmPrLines[index].model" class="form-input">
               </label>
              
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <input type="text" v-model="form.scmPrLines[index].specification" class="form-input">
               </label>
              
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <input type="text" v-model="form.scmPrLines[index].origin" class="form-input">
               </label>
              
            </td>
            <td>
              <!--     <input type="text" v-model="form.scmPrLines[index].sample" class="form-input"> -->
              <a v-if="form.scmPrLines[index].scmMaterial" :href="env.BASE_API_URL+form.scmPrLines[index].scmMaterial?.sample_photo" target="_blank" rel="noopener noreferrer">
                      <img :src="env.BASE_API_URL+form.scmPrLines[index].scmMaterial?.sample_photo"  alt="" srcset="" class="w-12 mx-auto">
               </a>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <input type="text" v-model="form.scmPrLines[index].drawing_no" class="form-input">
              </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <input type="text" v-model="form.scmPrLines[index].part_no" class="form-input">
              </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <input type="text" v-model="form.scmPrLines[index].rob" class="form-input">
              </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <input type="text" v-model="form.scmPrLines[index].quantity" class="form-input">
              </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <input type="date" v-model="form.scmPrLines[index].required_date" class="form-input">
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


  <div v-else>
    <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
        <legend class="px-2 text-gray-700 dark:text-gray-300">Download Excel <span class="text-red-500">*</span></legend>
        <div class="input-group !w-1/2">
                <label class="label-group">
                    <span class="label-item-title">Store Category<span class="text-red-500">*</span></span>
                    <v-select name="user" v-model="excelExportData.store_category_name" placeholder="--Choose an option--" label="Store Category" :options="store_category" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                    </v-select>
                    <Error v-if="errors?.store_category" :errors="errors.store_category" />
                </label>
                <label class="label-group">
                <button type="button" @click.prevent="downloadExcel" class="flex items-center justify-center px-4 py-2 mt-6 ml-6 text-sm leading-4 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium active:bg-purple-600  hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Download </button>
                </label>
            </div>
      </fieldset>
      <hr class="my-4"/>

      <DropZoneV2 :form="form" :page="page"></DropZoneV2>
  </div>
</template>









<style lang="postcss" scoped>
    .input-group {
        @apply flex flex-col justify-center w-full md:flex-row md:gap-2;
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