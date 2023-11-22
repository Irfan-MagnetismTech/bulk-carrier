<script setup>
    import { ref, watch, onMounted } from 'vue';
    import Error from "../../Error.vue";
    import useVendor from "../../../composables/supply-chain/useVendor.js";
    import useBusinessInfo from "../../../composables/useBusinessInfo.js";
    import ErrorComponent from "../../utils/ErrorComponent.vue";
    
    const props = defineProps({
        form: { type: Object, required: true },
        errors: { type: [Object, Array], required: false },
    });
    


    const product_source_type = ['Manufacturer', 'Dealer','Supplier'];
    const product_types = ref([]);
    const { getAllProductTypes } = useBusinessInfo();
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

// function fetchAllProductTypes() {
//     getAllProductTypes().then(AllStoreCategories => {
//         product_types.value = Object.values(AllStoreCategories);
//         })
//         .catch(error => {
//             console.error('An error occurred:', error);
//         });
//     }

function fetchAllProductTypes() {
    getAllProductTypes().then(AllProductTypes => {
        product_types.value = AllProductTypes;
        })
        .catch(error => {
            console.error('An error occurred:', error);
        });
    }
    onMounted(() => {
        fetchAllProductTypes();
    });
const vendor_type = ['Foreign', 'Local'];
</script>
<template>
    <div class="border-b border-gray-200  pb-5">
        
                        
            <div class="input-group">
                
                <label class="label-group">
                    <span class="label-item-title">Vendor Name <span class="required-style">*</span></span>
                    <input type="text" required v-model="form.name" class="form-input" name="name" :id="'name'" />
                    <!-- <Error v-if="errors?.name" :errors="errors.name" /> -->
                </label>
                <label class="label-group">
                    <span class="label-item-title">Address <span class="required-style">*</span></span>
                    <input type="text" required v-model="form.address" class="form-input" name="address" :id="'address'" />
                    <!-- <Error v-if="errors?.address" :errors="errors.address" />  -->
                </label>
                <label class="label-group">
                    <span class="label-item-title">Origin<span class="required-style">*</span></span>
                    <!-- <v-select :options="[]" placeholder="--Choose an option--" v-model="form.country_name" label="country_id" class="block w-full mt-1 text-xs rounded   focus:border-purple-400 focus:outline-none focus:shadow-outline-purple  form-input"></v-select> -->
                    <!-- <input type="hidden" v-model="form.country_id" class="label-item-input" name="country_id" :id="'country_id'" /> -->
                    <input type="text" required v-model="form.country_name" class="label-item-input" name="country_id" :id="'country_name'" />                   
                    <!-- <Error v-if="errors?.country_name" :errors="errors.country_name" /> -->
                </label>
                <label class="label-group">
                    <span class="label-item-title">Vendor Type <span class="required-style">*</span></span>
                    <!-- <select v-model="form.vendor_type" class="block w-full mt-1 text-xs rounded   focus:border-purple-400 focus:outline-none focus:shadow-outline-purple  form-input">
                    <option value="0">Foreign</option>
                    <option value="1">Local</option>
                    </select> -->
                   <v-select
                      :options="vendor_type"
                      placeholder="--Choose an option--"
                      v-model="form.vendor_type"
                      label="Vendor Type"
                      class="block w-full mt-1 text-xs rounded   focus:border-purple-400 focus:outline-none focus:shadow-outline-purple  form-input">
                      <template #search="{attributes, events}">
                            <input
                                class="vs__search"
                                :required="!form.vendor_type"
                                v-bind="attributes"
                                v-on="events"
                                />
                        </template>
                    </v-select>
                    
                    <!-- <Error v-if="errors?.vendor_type" :errors="errors.vendor_type" /> -->
                </label>
            </div>

            <div class="input-group !w-1/2">
                <label class="label-group">
                    <span class="label-item-title">Product Source Type <span class="required-style">*</span></span>
                    <v-select
                      :options="product_source_type"
                      placeholder="--Choose an option--"
                      v-model="form.product_source_type"
                      label="Product Source Type"
                      class="block w-full mt-1 text-xs rounded   focus:border-purple-400 focus:outline-none focus:shadow-outline-purple  form-input">
                      <template #search="{attributes, events}">
                            <input
                                class="vs__search"
                                :required="!form.product_source_type"
                                v-bind="attributes"
                                v-on="events"
                                />
                        </template>
                    </v-select>
                    <!-- <Error v-if="errors?.product_source_type" :errors="errors.product_source_type" /> -->
                </label>
                <label class="label-group">
                    <span class="label-item-title">Product Type <span class="text-red-500">*</span></span>
                   <v-select
                      name="user"
                      v-model="form.product_type"
                      placeholder="--Choose an option--"
                      label="Product Type"
                      :options="product_types"
                      class="block w-full mt-1 text-xs rounded   focus:border-purple-400 focus:outline-none focus:shadow-outline-purple  form-input">
                      <template #search="{attributes, events}">
                            <input
                                class="vs__search"
                                :required="!form.product_type"
                                v-bind="attributes"
                                v-on="events"
                                />
                        </template>
                    </v-select>
                    <!-- <Error v-if="errors?.product_type" :errors="errors.product_type" /> -->
                </label>
            </div>

            <hr class="my-5">
            <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded ">
            <legend class="px-2 text-gray-700 ">Contact Person <span class="text-red-500">*</span></legend>
            
            <div class="input-group">
                
                <label class="label-group">
                    <span class="label-item-title">Name<span class="required-style">*</span></span>
                    <input type="text" required v-model="form.scmVendorContactPersons[0].name" class="form-input" name="name" :id="'name'" />
                    <!-- <Error v-if="errors?.form.scmVendorContactPersons[0].name" :errors="errors.form.scmVendorContactPersons[0].name" /> -->
                </label>
                <label class="label-group">
                    <span class="label-item-title">Designation<span class="text-red-500">*</span></span>
                    <input type="text" required v-model="form.scmVendorContactPersons[0].designation" class="form-input" name="designation" :id="'designation'" />
                    <!-- <Error v-if="errors?.form.scmVendorContactPersons[0].designation" :errors="errors.form.scmVendorContactPersons[0].designation" /> -->
                </label>
                <label class="label-group">
                    <span class="label-item-title">Contact<span class="text-red-500">*</span></span>
                    <input type="text" required v-model="form.scmVendorContactPersons[0].phone" class="form-input" name="name" :id="'name'" />
                    <!-- <Error v-if="errors?.form.scmVendorContactPersons[0].phone" :errors="errors.form.scmVendorContactPersons[0].phone" /> -->
                </label>
                <label class="label-group">
                    <span class="label-item-title">Email <span class="required-style">*</span></span>
                    <input type="email" required v-model="form.scmVendorContactPersons[0].email" class="form-input" name="name" :id="'name'" />
                    <!-- <Error v-if="errors?.scmVendorContactPersons[0].email" :errors="errors.scmVendorContactPersons[0].email" /> -->
                </label>
            </div>
            </fieldset>
            
       
    </div>
    <ErrorComponent :errors="errors"></ErrorComponent>  
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
        @apply text-gray-700 ;
      }
      .label-item-input {
        @apply block w-full mt-1 text-sm rounded   focus:border-purple-400 focus:outline-none focus:shadow-outline-purple  disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed ;
      }
      .required-style {
        @apply text-red-500;
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