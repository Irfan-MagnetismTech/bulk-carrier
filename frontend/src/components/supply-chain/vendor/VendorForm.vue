<script setup>
    import { ref, watch, onMounted } from 'vue';
    import Error from "../../Error.vue";
    import useVendor from "../../../composables/supply-chain/useVendor.js";
    
    const props = defineProps({
        form: { type: Object, required: true },
        errors: { type: [Object, Array], required: false },
    });
    


    const product_source_type = ['Manufacturer', 'Dealer','Supplier'];
    const product_type = ['Bunker', 'Lube Oil','Spare','Provision' ,'Service'];
//     const vendor_types = ref([
//       { value: 0, text: 'Foreign' },
//       { value: 1, text: 'Local' }
//     ]);


//     // Set the selected value when editing
// let selectedVendorType = ref(props.form.vendor_type);

// // Watch for changes in the form.vendor_type and update selectedVendorType
// watch(props.form, (newForm) => {
//   selectedVendorType.value = newForm.vendor_type;
// });
const vendor_type = ['Foreign', 'Local'];
</script>
<template>
    <div class="border-b border-gray-200 dark:border-gray-700 pb-5">
        
                        
            <div class="input-group">
                
                <label class="label-group">
                    <span class="label-item-title">Vendor Name <span class="required-style">*</span></span>
                    <input type="text" required v-model="form.name" class="form-input" name="name" :id="'name'" />
                    <Error v-if="errors?.name" :errors="errors.name" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Address <span class="text-red-500">*</span></span>
                    <input type="text" v-model="form.address" class="form-input" name="address" :id="'address'" />
                    <Error v-if="errors?.address" :errors="errors.address" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Origin</span>
                    <v-select :options="[]" placeholder="--Choose an option--" v-model="form.country_name" label="country_id" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
                    <input type="hidden" v-model="form.country_id" class="label-item-input" name="country_id" :id="'country_id'" />
                    <Error v-if="errors?.country_id" :errors="errors.country_id" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Vendor Type</span>
                    <!-- <select v-model="form.vendor_type" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                    <option value="0">Foreign</option>
                    <option value="1">Local</option>
                    </select> -->
                    <v-select :options="vendor_type" placeholder="--Choose an option--" v-model="form.vendor_type" label="Vendor Type" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
                    <Error v-if="errors?.vendor_type" :errors="errors.vendor_type" />
                </label>
            </div>

            <div class="input-group !w-1/2">
                <label class="label-group">
                    <span class="label-item-title">Product Source Type</span>
                    <v-select :options="product_source_type" placeholder="--Choose an option--" v-model="form.product_source_type" label="Product Source Type" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
                    <Error v-if="errors?.product_source_type" :errors="errors.product_source_type" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Product Type <span class="text-red-500">*</span></span>
                    <input type="text" v-model="form.short_code" class="form-input" name="short_code" :id="'short_code'" />
                    <Error v-if="errors?.short_code" :errors="errors.short_code" />
                </label>
            </div>

            <hr class="my-5">
            <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
            <legend class="px-2 text-gray-700 dark:text-gray-300">Contact Person <span class="text-red-500">*</span></legend>
            
            <div class="input-group">
                
                <label class="label-group">
                    <span class="label-item-title">Name<span class="required-style">*</span></span>
                    <input type="text" required v-model="form.scmVendorContactPersons[0].name" class="form-input" name="name" :id="'name'" />
                    <Error v-if="errors?.form.scmVendorContactPersons[0].name" :errors="errors.form.scmVendorContactPersons[0].name" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Contact<span class="text-red-500">*</span></span>
                    <input type="text" required v-model="form.scmVendorContactPersons[0].designation" class="form-input" name="designation" :id="'designation'" />
                    <Error v-if="errors?.form.scmVendorContactPersons[0].designation" :errors="errors.form.scmVendorContactPersons[0].designation" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Contact<span class="text-red-500">*</span></span>
                    <input type="text" required v-model="form.scmVendorContactPersons[0].phone" class="form-input" name="name" :id="'name'" />
                    <Error v-if="errors?.form.scmVendorContactPersons[0].phone" :errors="errors.form.scmVendorContactPersons[0].phone" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Email <span class="required-style">*</span></span>
                    <input type="text" required v-model="form.scmVendorContactPersons[0].email" class="form-input" name="name" :id="'name'" />
                    <Error v-if="errors?.scmVendorContactPersons[0].email" :errors="errors.scmVendorContactPersons[0].email" />
                </label>
            </div>
            </fieldset>
            
       
    </div>
</template>
<style lang="postcss" scoped>
     #table, #table th, #table td{
        @apply border border-collapse border-gray-400 text-center text-gray-700 px-1
      }
      
      .input-group {
        @apply flex flex-col justify-center w-full h-full md:flex-row md:gap-2;
      }
      
      .label-group {
        @apply block w-full mt-2 text-sm;
      }

      .label-item-title {
        @apply text-gray-700 dark:text-gray-300;
      }
      .label-item-input {
        @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
      }
      
      >>> {
        --vs-controls-color: #374151;
        --vs-border-color: #4b5563;
      
        --vs-dropdown-bg: #282c34;
        --vs-dropdown-color: #eeeeee;
        --vs-dropdown-option-color: #eeeeee;
      
        --vs-selected-bg: #664cc3;
        --vs-selected-color: #374151;
      
        --vs-search-input-color: #4b5563;
      
        --vs-dropdown-option--active-bg: #664cc3;
        --vs-dropdown-option--active-color: #eeeeee;
      }
</style>