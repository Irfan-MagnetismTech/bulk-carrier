<script setup>
    import { ref, watch, onMounted } from 'vue';
    import Error from "../../Error.vue";
    import useMaterial from "../../../composables/scm/useMaterial.js";
    import useVendor from "../../../composables/configuration/useVendor";

    const { vendors, getVendors } = useVendor();
    const { material, materials, getMaterials } = useMaterial();
    
    const props = defineProps({
      form: { type: Object, required: true },
      errors: { type: [Object, Array], required: false },
    });

    function addCsMaterial() {
      let obj = {
        cs_id: '',
        comparative_statement_id: '',
        material_id: '',
      };
      props.form.comparative_statement_materials.push(obj);

      let obj2 = {
        comparative_statement_id: '',
        supplier_name: '',
        supplier_id: [],
        material_name: '',
        material_id: '',
        price: [],
      };
      props.form.comparative_statement_rates.push(obj2);

      processCsRates();

    }

    function removeCsMaterial(index){
      props.form.comparative_statement_materials.splice(index, 1);
      props.form.comparative_statement_rates.splice(index, 1);
    }

    function addCsSupplier() {
      let obj =  {
        comparative_statement_id: '',
        supplier_id: '',
        collection_way: '',
        grade: '',
        is_vat_included: '',
        is_tax_included: '',
        credit_period: '',
        material_availability: '',
        delivery_condition: '',
        required_date: '',
        is_selected: false,
      }
      props.form.comparative_statement_suppliers.push(obj);
      processCsRates();
    }

    function processCsRates(){
      //loop through comparative_statement_rates and add empty array for each supplier
      props.form.comparative_statement_rates.forEach((rate,index) => {
        let obj = {
          price: '',
        };
        props.form.comparative_statement_rates[index].price = [];
        props.form.comparative_statement_suppliers.forEach((supplier,supplierIndex) => {
          props.form.comparative_statement_rates[index].price.push(obj);
        });
      });
    }

    function removeCsSupplier(index){
      props.form.comparative_statement_suppliers.splice(index, 1);
    }

    function setMaterialUnit(index){
      let material = materials.value.find((material) => material.id === props.form.comparative_statement_materials[index].material_id);
      props.form.comparative_statement_materials[index].unit = material.unit;
      setMaterialRateColumn(index, material);
    }

    function setMaterialRateColumn(index, material){
      props.form.comparative_statement_rates[index].material_name = material.name;
      props.form.comparative_statement_rates[index].material_id = material.id;
    }

    function setSupplier(index){
      let vendor = vendors.value.find((vendor) => vendor.id === props.form.comparative_statement_suppliers[index].supplier_id);
      props.form.comparative_statement_suppliers[index].supplier_name = vendor.name;
    }

    function toggleSelectionStatus(index){
      props.form.comparative_statement_suppliers[index].is_selected = !props.form.comparative_statement_suppliers[index].is_selected;
    }

    function toggleAllSelectionStatus(){
      props.form.comparative_statement_suppliers.forEach((supplier,index) => {
        props.form.comparative_statement_suppliers[index].is_selected = !props.form.comparative_statement_suppliers[index].is_selected;
      });
    }

    onMounted(() => {
      getMaterials();
      getVendors();
    });

</script>
<template>
  <!-- Basic information -->
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-3 text-sm md:w-2/6">
      <span class="text-gray-700 dark:text-gray-300">Reference No <span class="text-red-500">*</span></span>
      <input type="text" v-model="form.reference_no" required placeholder="Ref No" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
      <Error v-if="errors?.reference_no" :errors="errors.reference_no" />
    </label>
    <label class="block w-full mt-3 text-sm md:w-4/6">
      <span class="text-gray-700 dark:text-gray-300">Effective Date <span class="text-red-500">*</span></span>
      <input type="date" v-model="form.effective_date" required placeholder="Effective Date" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
      <Error v-if="errors?.effective_date" :errors="errors.effective_date" />
    </label>
    <label class="block w-full mt-3 text-sm md:w-4/6">
      <span class="text-gray-700 dark:text-gray-300">Expiry Date <span class="text-red-500">*</span></span>
      <input type="date" v-model="form.expiry_date" required placeholder="Expiry Date" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
      <Error v-if="errors?.expiry_date" :errors="errors.expiry_date" />
    </label>
    <label class="block w-full mt-3 text-sm md:w-4/6">
      <span class="text-gray-700 dark:text-gray-300">Status <span class="text-red-500">*</span></span>
      <select v-model="form.status" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" name="" id="">
        <option value="" disabled selected>Select</option>
        <option value="Pending">Pending</option>
        <option value="Approved">Approved</option>
      </select>
      <Error v-if="errors?.status" :errors="errors.status" />
    </label>
  </div>
  <div class="justify-center w-full">
    <label class="block w-full mt-3 text-sm w-2/6">
      <span class="text-gray-700 dark:text-gray-300">Remarks</span>
      <textarea v-model="form.remarks" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></textarea>
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
        <th class="px-4 py-3 align-bottom">Unit</th>
        <th class="px-4 py-3 text-center align-bottom">Action</th>
      </tr>
      </thead>

      <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
      <tr class="text-gray-700 dark:text-gray-400" v-for="(csMaterial, csMaterialIndex) in form.comparative_statement_materials" :key="csMaterialIndex">
        <td class="w-4/6">
          <select v-model="form.comparative_statement_materials[csMaterialIndex].material_id" @change="setMaterialUnit(csMaterialIndex)" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            <option value="" disabled selected>Select</option>
            <option :value="material.id" v-for="(material,index) in materials">{{ material.name }}</option>
          </select>
        </td>
        <td>
          <input type="text" v-model="form.comparative_statement_materials[csMaterialIndex].unit" readonly class="vms-readonly-input block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        </td>
        <td class="px-1 py-1 text-center">
          <button v-if="csMaterialIndex!=0" type="button" @click="removeCsMaterial(csMaterialIndex)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
            </svg>
          </button>
          <button v-else type="button" @click="addCsMaterial()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
          </button>
        </td>
      </tr>
      </tbody>
    </table>
  </fieldset>

  <!-- CS Supplier -->
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300">Suppliers <span class="text-red-500">*</span></legend>
    <table class="w-full whitespace-no-wrap" id="table">
      <thead>
      <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th class="px-4 py-3 align-bottom text-center">
          <input type="checkbox" class="form-input mx-auto" @click="toggleAllSelectionStatus">
        </th>
        <th class="px-4 py-3 align-bottom">Supplier</th>
        <th class="px-4 py-3 align-bottom">Collection Way</th>
        <th class="px-4 py-3 align-bottom">Grade</th>
        <th class="px-4 py-3 align-bottom">Vat Include</th>
        <th class="px-4 py-3 align-bottom">Tax Include</th>
        <th class="px-4 py-3 align-bottom">Credit Period</th>
        <th class="px-4 py-3 align-bottom">Material Availability</th>
        <th class="px-4 py-3 align-bottom">Delivery Condition</th>
        <th class="px-4 py-3 align-bottom">Required Time</th>
        <th class="px-4 py-3 text-center align-bottom">Action</th>
      </tr>
      </thead>

      <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
      <tr class="text-gray-700 dark:text-gray-400" v-for="(csSupplier, csSupplierIndex) in form.comparative_statement_suppliers" :key="csSupplierIndex">
        <td>
          <input type="checkbox" class="form-input mx-auto" @click="toggleSelectionStatus(csSupplierIndex)" :checked="csSupplier?.is_selected">
        </td>
        <td>
          <select v-model="form.comparative_statement_suppliers[csSupplierIndex].supplier_id" @change="setSupplier(csSupplierIndex)" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            <option value="" disabled selected>Select</option>
            <option :value="vendor.id" v-for="(vendor,index) in vendors">{{ vendor.name }}</option>
          </select>
        </td>
        <td>
          <input type="text" v-model="form.comparative_statement_suppliers[csSupplierIndex].collection_way" placeholder="collection way" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        </td>
        <td>
          <input type="text" v-model="form.comparative_statement_suppliers[csSupplierIndex].grade" placeholder="grade" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        </td>
        <td>
          <select v-model="form.comparative_statement_suppliers[csSupplierIndex].is_vat_included" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            <option value="" disabled selected>Select</option>
            <option value="1">Included</option>
            <option value="0">Excluded</option>
          </select>
        </td>
        <td>
          <select v-model="form.comparative_statement_suppliers[csSupplierIndex].is_tax_included" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            <option value="" disabled selected>Select</option>
            <option value="1">Included</option>
            <option value="0">Excluded</option>
          </select>
        </td>
        <td>
          <input type="text" v-model="form.comparative_statement_suppliers[csSupplierIndex].credit_period" placeholder="credit period" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        </td>
        <td>
          <input type="text" v-model="form.comparative_statement_suppliers[csSupplierIndex].material_availability" placeholder="Availability" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        </td>
        <td>
          <select v-model="form.comparative_statement_suppliers[csSupplierIndex].delivery_condition" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            <option value="" disabled selected>Select</option>
            <option value="with carrying">With Carrying</option>
            <option value="without carrying">Without Carrying</option>
          </select>
        </td>
        <td>
          <input type="date" v-model="form.comparative_statement_suppliers[csSupplierIndex].required_date" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        </td>
        <td class="px-1 py-1 text-center">
          <button v-if="csSupplierIndex!==0" type="button" @click="removeCsSupplier(csSupplierIndex)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
            </svg>
          </button>
          <button v-else type="button" @click="addCsSupplier()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
          </button>
        </td>
      </tr>
      </tbody>
    </table>
  </fieldset>

  <!-- CS Rates -->
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300">Rates <span class="text-red-500">*</span></legend>
    <table class="w-full whitespace-no-wrap" id="table">
      <thead>
      <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th class="px-4 py-3 align-bottom">Materials</th>
        <th class="px-4 py-3 align-bottom" v-for="(csSupplier, csSupplierIndex) in form.comparative_statement_suppliers" :key="csSupplierIndex">{{ form.comparative_statement_suppliers[csSupplierIndex].supplier_name ?? 'Select a Supplier' }}</th>
      </tr>
      </thead>

      <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
      <tr class="text-gray-700 dark:text-gray-400" v-for="(csMaterialRate, csMaterialRateIndex) in form.comparative_statement_rates" :key="csMaterialRateIndex">
        <td>
          <input type="text" v-model="form.comparative_statement_rates[csMaterialRateIndex].material_name" readonly class="vms-readonly-input block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          <input type="hidden" v-model="form.comparative_statement_rates[csMaterialRateIndex].material_id">
        </td>
        <td :key="csPriceIndex" v-for="(csPrice,csPriceIndex) in csMaterialRate.price">
          <input type="number" v-model="form.comparative_statement_rates[csMaterialRateIndex].price[csPriceIndex]" placeholder="rate" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
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