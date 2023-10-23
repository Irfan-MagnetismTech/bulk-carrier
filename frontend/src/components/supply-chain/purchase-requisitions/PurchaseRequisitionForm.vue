<script setup>
    import { ref, watch, onMounted,watchEffect } from 'vue';
    import Error from "../../Error.vue";
    import DropZone from "../../DropZone.vue";
    import useMaterial from "../../../composables/supply-chain/useMaterial.js";
    import useVendor from '../../../composables/supply-chain/useVendor';

    const { vendors, getVendors } = useVendor();
    const { material, materials, getMaterials } = useMaterial();
    
    const props = defineProps({
      form: { type: Object, required: true },
      errors: { type: [Object, Array], required: false },
    });

    function addMaterial() {
      let obj = {
        material_id: '',
        code: '',
        unit: '',
        quantity: 0.0,
        rate: 0.0,
      };
      props.form.materials.push(obj);
    }

    function removeMaterial(index){
      props.form.materials.splice(index, 1);
    }

    function setMaterialOtherData(index){
      let material = materials.value.find((material) => material.id === props.form.materials[index].material_id);
      props.form.materials[index].unit = material.unit;
      props.form.materials[index].material_category_id = material.category.id;
      props.form.materials[index].material_category_name = material.category.name;
    }

    //watch props.form.materials find amount from unit_price and quantity with parseFloat and toFixed 2
    watch(() => props?.form?.materials, (newVal, oldVal) => {
      let total = 0.0;
      newVal?.forEach((material, index) => {
        props.form.materials[index].amount = parseFloat((material?.unit_price * material?.quantity).toFixed(2));
        total += props.form.materials[index].amount;
      });
      props.form.total_amount = parseFloat(total.toFixed(2));
    }, { deep: true });

    watch(() => props?.form?.status, (newVal, oldVal) => {
      props?.form?.status == props?.form?.status;
    })
    function handleAttachmentChange(e) {
      let fileData = e.target.files[0];
      props.form.attachment = fileData;
    }

    onMounted(() => {
      getMaterials();
    });

    
const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;

onMounted(() => {
  watchEffect(() => {
    if (props.form.materials) {
      const customDataTable = document.getElementById("customDataTable");
      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
    }
}, { deep: true });

});// Code for global search end here

</script>
<template>

  <!-- Basic information -->
  <business-unit-input v-model="form.business_unit"></business-unit-input>
  <div class="input-group">
      <label class="label-group">
          <span class="label-item-title">PR Ref<span class="text-red-500">*</span></span>
          <input type="text" readonly v-model="form.date" required class="form-input" name="date" :id="'date'" />
          <Error v-if="errors?.date" :errors="errors.date"  />
      </label>
      <label class="label-group">
          <span class="label-item-title">Warehouse Name <span class="text-red-500">*</span></span>
          <input type="text" readonly v-model="form.date" required class="form-input" name="date" :id="'date'" />
          <Error v-if="errors?.date" :errors="errors.date"  />
      </label>
      <label class="label-group">
          <span class="label-item-title">Raised Date<span class="text-red-500">*</span></span>
          <input type="text" readonly v-model="form.date" required class="form-input" name="date" :id="'date'" />
          <Error v-if="errors?.date" :errors="errors.date"  />
      </label>
      <label class="label-group">
          <span class="label-item-title">Critical Spares<span class="text-red-500">*</span></span>
          <input type="text" readonly v-model="form.date" required class="form-input" name="date" :id="'date'" />
          <Error v-if="errors?.date" :errors="errors.date"  />
      </label>
  </div> 
  <div class="input-group">
      <label class="label-group md:w-4/6">
          <span class="label-item-title">Purchase Center<span class="text-red-500">*</span></span>
          <input type="text" readonly v-model="form.date" required class="form-input" name="date" :id="'date'" />
          <Error v-if="errors?.date" :errors="errors.date"  />
      </label>
      <label class="label-group md:w-4/6">
          <span class="label-item-title">Approved Date <span class="text-red-500">*</span></span>
          <input type="text" readonly v-model="form.date" required class="form-input" name="date" :id="'date'" />
          <Error v-if="errors?.date" :errors="errors.date"  />
      </label>
      <label class="label-group md:w-4/6">
          <span class="label-item-title">Raised Date<span class="text-red-500">*</span></span>
          <input type="file" class="form-input" @change="handleAttachmentChange" />
          <Error v-if="errors?.date" :errors="errors.date"  />
      </label>
  </div> 

  <div class="input-group">
    <label class="label-group md:w-4/6">
          <span class="label-item-title">Remarks <span class="text-red-500">*</span></span>
          <textarea v-model="form.remarks" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></textarea>
          <Error v-if="errors?.remarks" :errors="errors.remarks" />
    </label>
  </div>

  <div class="justify-center w-full">
    <div class="label-group">
          <span class="label-item-title">Status <span class="required-style">*</span></span>
          <div class="my-3 flex">
                <div class="flex items-center">
                    <input type="radio" value="1" v-model="form.status" class="" name="status" :id="'not_active'" />
                    <label class="ml-2" for="not_active">Active</label>
                </div>
                <div class="flex items-center ml-8">
                    <input type="radio" value="0" v-model="form.status" class="" name="status" :id="'active'" />
                    <label class="ml-2" for="active">Inactive</label>
                </div>
            </div>
        </div>
  </div>


  <div id="customDataTable" v-if="form.status == '1'">
    <div  class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
        <legend class="px-2 text-gray-700 dark:text-gray-300">Materials <span class="text-red-500">*</span></legend>
        <table class="w-full whitespace-no-wrap" id="table">
          <thead>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th class="px-4 py-3 align-bottom">Material Name <br/> <span class="!text-[8px]">Material - Code</span></th>
            <th class="px-4 py-3 align-bottom">Unit</th>
            <th class="px-4 py-3 align-bottom">Brand</th>
            <th class="px-4 py-3 align-bottom">Model</th>
            <th class="px-4 py-3 align-bottom">Specification</th>
            <th class="px-4 py-3 align-bottom">Origin</th>
            <th class="px-4 py-3 align-bottom">Sample</th>
            <th class="px-4 py-3 align-bottom">Drawing No</th>
            <th class="px-4 py-3 align-bottom">Part No</th>
            <th class="px-4 py-3 align-bottom">ROB</th>
            <th class="px-4 py-3 align-bottom">Qty</th>
            <th class="px-4 py-3 align-bottom">Unit Price</th>
            <th class="px-4 py-3 text-center align-bottom">Action</th>
          </tr>
          </thead>

          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr class="text-gray-700 dark:text-gray-400" v-for="(material, index) in form.materials" :key="index">
            <td class="w-1/6">
              <!-- <select v-model="form.materials[index].material_id" @change="setMaterialOtherData(index)" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                <option value="" disabled selected>Select</option>
                <option :value="material.id" v-for="(material,index) in materials">{{ material.name }}</option>
              </select> -->
            </td>
            <td>
              <input type="text" readonly v-model="form.materials[index].unit" class="vms-readonly-input form-input">
            </td>
            <td>
              <input type="text" v-model="form.materials[index].brand" class="form-input">
            </td>
            <td>
              <input type="text" v-model="form.materials[index].model" class="form-input">
            </td>
            <td>
              <input type="text" v-model="form.materials[index].specification" class="form-input">
            </td>
            <td>
              <input type="text" v-model="form.materials[index].origin" class="form-input">
            </td>
            <td>
              <input type="text" v-model="form.materials[index].sample" class="form-input">
            </td>
            <td>
              <input type="text" v-model="form.materials[index].drawing_no" class="form-input">
            </td>
            <td>
              <input type="text" v-model="form.materials[index].part_no" class="form-input">
            </td>
            <td>
              <input type="text" v-model="form.materials[index].rob" class="form-input">
            </td>
            <td>
              <input type="text" v-model="form.materials[index].quantity" class="form-input">
            </td>
            <td>
              <input type="text" v-model="form.materials[index].required_date" class="form-input">
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
  
  
  <fieldset v-else>
    <drop-zone></drop-zone>
    <label class="block w-full mt-3 text-sm md:w-4/6">
      <span class="text-gray-700 dark:text-gray-300">Attachment</span>
      <input type="file" class="block w-full form-input" @change="handleAttachmentChange" />
      <Error v-if="errors?.attachment" :errors="errors.attachment" />
    </label>
  </fieldset>
</template>









<style lang="postcss" scoped>
    .input-group {
        @apply flex flex-col justify-center w-full md:flex-row md:gap-2;
    }
    .label-group {
        @apply block w-full mt-3 text-sm font-semibold;
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

    table tr,td,th {
        @apply border border-gray-300
    }

</style>