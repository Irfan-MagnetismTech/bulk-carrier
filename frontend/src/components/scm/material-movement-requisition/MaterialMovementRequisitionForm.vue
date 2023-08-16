<script setup>
    import { ref, watch, onMounted } from 'vue';
    import Error from "../../Error.vue";
    import useMaterial from "../../../composables/scm/useMaterial.js";
    import useVendor from "../../../composables/configuration/useVendor";
    import useWarehouse from '../../../composables/configuration/useWarehouse';

    const { vendors, getVendors } = useVendor();
    const { material, materials, getMaterials } = useMaterial();
    const { warehouses, searchWarehouse, getWarehouses } = useWarehouse();

    const props = defineProps({
      form: { type: Object, required: true },
      errors: { type: [Object, Array], required: false },
    });

    function fetchWarehouse(search, loading) {
      loading(true);
      searchWarehouse(search, loading)
    }

    function addMaterial() {
      let obj = {
        material_id: '',
        size: '',
        unit: '',
        quantity: null,
      };
      props.form.materials.push(obj);
    }

    function removeMaterial(index){
      props.form.materials.splice(index, 1);
    }

    function setMaterialOtherData(index){
      let material = materials.value.find((material) => material.id === props.form.materials[index].material_id);
      props.form.materials[index].unit = material.unit;
    }

    onMounted(() => {
      getMaterials();
      getWarehouses('Warehouse');
    });

</script>
<template>
  <!-- Basic information -->
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-3 text-sm md:w-1/6">
      <span class="text-gray-700 dark:text-gray-300">Date <span class="text-red-500">*</span></span>
      <input type="date" v-model="form.requisition_date" required class="block w-full form-input" />
      <Error v-if="errors?.requisition_date" :errors="errors.requisition_date" />
    </label>
    <label class="block w-full mt-3 text-sm md:w-2/6">
      <span class="text-gray-700 dark:text-gray-300">From Warehouse <span class="text-red-500">*</span></span>
      <v-select :options="warehouses" placeholder="--Choose an option--" @search="fetchWarehouse" v-model="form.from_warehouse_id" label="name" :reduce="warehouse => warehouse.id" class="block form-input">
          <template #search="{attributes, events}">
              <input
                  class="vs__search"
                  :required="!form.from_warehouse_id"
                  v-bind="attributes"
                  v-on="events"
                  />
          </template>
      </v-select>
    </label>
    <label class="block w-full mt-3 text-sm md:w-2/6">
      <span class="text-gray-700 dark:text-gray-300">To Warehouse <span class="text-red-500">*</span></span>
      <v-select :options="warehouses" placeholder="--Choose an option--" @search="fetchWarehouse" v-model="form.to_warehouse_id" label="name" :reduce="warehouse => warehouse.id" class="block form-input">
          <template #search="{attributes, events}">
              <input
                  class="vs__search"
                  :required="!form.to_warehouse_id"
                  v-bind="attributes"
                  v-on="events"
                  />
          </template>
      </v-select>
    </label>
    <label class="block w-full mt-3 text-sm md:w-3/6">
      <span class="text-gray-700 dark:text-gray-300">Remarks</span>
      <input type="text" v-model="form.remarks" class="block w-full form-input" />
      <Error v-if="errors?.remarks" :errors="errors.remarks" />
    </label>
  </div>

  <!-- CS Materials -->
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300">Materials <span class="text-red-500">*</span></legend>
    <table class="w-full whitespace-no-wrap" id="table">
      <thead>
      <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th class="px-4 py-3 align-bottom">Material</th>
        <th class="px-4 py-3 align-bottom">Size</th>
        <th class="px-4 py-3 align-bottom">Unit</th>
        <th class="px-4 py-3 align-bottom">Quantity</th>
        <th class="px-4 py-3 text-center align-bottom">Action</th>
      </tr>
      </thead>

      <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
      <tr class="text-gray-700 dark:text-gray-400" v-for="(material, index) in form.materials" :key="index">
        <td class="w-1/6">
          <select v-model="form.materials[index].material_id" @change="setMaterialOtherData(index)" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            <option value="" disabled selected>Select</option>
            <option :value="material.id" v-for="(material,index) in materials">{{ material.name }}</option>
          </select>
        </td>
        <td>
          <input type="text" v-model="form.materials[index].size" class="block w-full form-input">
        </td>
        <td>
          <input type="text" readonly v-model="form.materials[index].unit" class="vms-readonly-input block w-full form-input">
        </td>
        <td>
          <input type="text" v-model="form.materials[index].quantity" class="block w-full form-input">
        </td>
        
        <td class="px-1 py-1 text-center">
          <button v-if="index!=0" type="button" @click="removeMaterial(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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