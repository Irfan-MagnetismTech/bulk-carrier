<script setup>
    import { ref, watch, onMounted } from 'vue';
    import Error from "../../Error.vue";
    import useMaterial from "../../../composables/supply-chain/useMaterial.js";
    import useWarehouse from "../../../composables/supply-chain/useWarehouse.js";
    import BusinessUnitInput from "../../input/BusinessUnitInput.vue";
    
    const { material, materials, getMaterials,searchMaterial } = useMaterial();
    const { warehouses,warehouse,getWarehouses,searchWarehouse } = useWarehouse();
    
    const props = defineProps({
      form: { type: Object, required: true },
      errors: { type: [Object, Array], required: false },
      materialObject: { type: Object, required: false },
    });

    function addRow() {
      props.form.scmOpeningStockLines.push(props.materialObject);
    }

    function removeRow(index){
      props.form.scmOpeningStockLines.splice(index, 1);
    }

    function setMaterialOtherData(datas,index){
      console.log(datas);
      props.form.scmOpeningStockLines[index].unit = datas.unit;
      props.form.scmOpeningStockLines[index].scm_material_id = datas.id;
    }


  function fetchMaterials(search, loading) {
    loading(true);
    searchMaterial(search, loading)
  }

  function fetchWarehouse(search, loading) {
    loading(true);
    console.log(props.form.business_unit);
    searchWarehouse(search, loading,props.form.business_unit);
  }

  watch(() => props.form.scmWarehouse, (value) => {
        props.form.scm_warehouse_id = value?.id;
    });
</script>
<template>
  <!-- Basic information -->
  <business-unit-input v-model="form.business_unit"></business-unit-input>
  <div class="input-group !w-1/2">
      <label class="label-group">
          <span class="label-item-title">Date<span class="text-red-500">*</span></span>
          <input type="date" v-model="form.date" class="form-input" name="date" :id="'date'" />
          <Error v-if="errors?.date" :errors="errors.date"  />
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
  </div> 
  <!-- CS Materials -->
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300">Materials <span class="text-red-500">*</span></legend>
    <table class="w-full whitespace-no-wrap" id="table">
      <thead>
      <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th class="px-4 py-3 align-bottom">Material <br/> <span class="text-[10px]">Material - Code</span></th>
        <th class="px-4 py-3 align-bottom">Unit</th>
        <th class="px-4 py-3 align-bottom">Quantity</th>
        <th class="px-4 py-3 align-bottom">Rate</th>
        <th class="px-4 py-3 text-center align-bottom">Action</th>
      </tr>
      </thead>

      <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
      <tr class="text-gray-700 dark:text-gray-400" v-for="(scmOpeningStockLine, index) in form.scmOpeningStockLines" :key="index">
        <td>
          <v-select :options="materials" placeholder="--Choose an option--" @search="fetchMaterials" v-model="form.scmOpeningStockLines[index].scmMaterial" label="material_name_and_code" class="block form-input" @change="setMaterialOtherData(form.scmOpeningStockLines[index].scmMaterial,index)">
                <template #search="{attributes, events}">
                    <input
                        class="vs__search"
                        :required="!form.scmOpeningStockLines[index].scmMaterial"
                        v-bind="attributes"
                        v-on="events"
                        />
                </template>
            </v-select>
        </td>
        <td>
          <input type="text" v-model="form.scmOpeningStockLines[index].unit" readonly class="vms-readonly-input block w-full form-input">
        </td>
        <td>
          <input type="text" v-model="form.scmOpeningStockLines[index].quantity" class="block w-full form-input">
        </td>
        <td>
          <input type="text" v-model="form.scmOpeningStockLines[index].rate" class="block w-full form-input">
        </td>
        <td class="px-1 py-1 text-center">
          <button v-if="index!=0" type="button" @click="removeRow(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
            </svg>
          </button>
          <button v-else type="button" @click="addRow()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
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