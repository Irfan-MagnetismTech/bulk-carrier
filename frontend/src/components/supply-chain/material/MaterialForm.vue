<script setup>
    import { ref, watch, onMounted ,computed} from 'vue';
    import Error from "../../Error.vue";
    import useMaterialCategory from "../../../composables/supply-chain/useMaterialCategory.js";
    import useUnit from "../../../composables/supply-chain/useUnit.js";    
    import useBusinessInfo from "../../../composables/useBusinessInfo.js";
    import DropZone from "../../DropZone.vue";
    import DropZoneV2 from '../../DropZoneV2.vue';
    import { useStore } from 'vuex';

    
    const { materialCategories, searchMaterialCategory } = useMaterialCategory();
    const { getAllStoreCategories } = useBusinessInfo();
    const { units, searchUnit } = useUnit();
    const store = useStore();
    const store_category = ref([]);

    const props = defineProps({
        form: {
             type: Object,
             required: true 
            },
        errors: { 
            type: [Object, Array], 
            required: false 
        },
        page: {
            required: false,
            default: {}
        },
    });
       

    function fetchCategory(query, loading) {
        searchMaterialCategory(query, loading);
        loading(true)
    }

    watch(() => props.form.scm_material_category_name, (value) => {
        props.form.scm_material_category_id = value?.id;
    });

    const dropZoneFile = ref(computed(() => store.getters.getDropZoneFile));

    watch(dropZoneFile, (value) => {
        console.log("dropZoneFile", value);
        if (value !== null && value !== undefined) {
            props.form.sample_photo = value;
        }
        });

    function fetchUnit(query, loading) {
        searchUnit(query, loading);
        loading(true)
    }
    function fetchAllStoreCategories() {
      getAllStoreCategories().then(AllStoreCategories => {
        store_category.value = Object.values(AllStoreCategories);
        })
        .catch(error => {
            console.error('An error occurred:', error);
        });
    }

    onMounted(() => {
      fetchAllStoreCategories();
    });

</script>
<template>                      
            <div class="input-group">
                
                <label class="label-group">
                    <span class="label-item-title">Material Name<span class="required-style">*</span></span>
                    <input type="text" required v-model="form.name" class="form-input" name="name" :id="'name'" />
                    <Error v-if="errors?.name" :errors="errors.name" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Material Code <span class="text-red-500">*</span></span>
                    <input type="text" v-model="form.material_code" class="form-input" name="material_code" :id="'material_code'" />
                    <Error v-if="errors?.material_code" :errors="errors.material_code" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Category Name</span>
                    <v-select :options="materialCategories" placeholder="--Choose an option--" @search="fetchCategory" v-model="form.scm_material_category_name" label="name" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
                    <input type="hidden" v-model="form.scm_material_category_id" class="label-item-input" name="parent_category" :id="'parent_category'" />
                    <Error v-if="errors?.scm_material_category_id" :errors="errors.scm_material_category_id" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Unit <span class="text-red-500">*</span></span>
                    <v-select :options="units" placeholder="--Choose an option--" @search="fetchUnit"  v-model="form.unit" label="name" :reduce="units => units.name" class="block form-input">
                    <template #search="{attributes, events}">
                        <input
                            class="vs__search"
                            :required="!form.unit"
                            v-bind="attributes"
                            v-on="events"
                        />
                    </template>
                    </v-select>
                    <Error v-if="errors?.unit" :errors="errors.unit" />
                </label>
            </div>
            <div class="input-group !w-3/4">
                <label class="label-group">
                    <span class="label-item-title">HS Code <span class="required-style">*</span></span>
                    <input type="text" required v-model="form.hs_code" class="form-input" name="hs_code" :id="'hs_code'" />
                    <Error v-if="errors?.hs_code" :errors="errors.hs_code" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Minimum Stock<span class="text-red-500">*</span></span>
                    <input type="text" v-model="form.minimum_stock" class="form-input" name="minimum_stock" :id="'minimum_stock'" />
                    <Error v-if="errors?.minimum_stock" :errors="errors.minimum_stock" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Store Category<span class="text-red-500">*</span></span>
                    <v-select name="user" v-model="form.store_category" placeholder="--Choose an option--" label="Store Category" :options="store_category" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                    </v-select>
                    <Error v-if="errors?.store_category" :errors="errors.store_category" />
                </label>
            </div>          
            <div class="input-group !w-1/2">
                <label class="label-group">
                    <span class="label-item-title">Description <span class="required-style">*</span></span>
                    <textarea v-model="form.description" class="form-input" name="description" :id="'description'"></textarea>
                    <Error v-if="errors?.description" :errors="errors.description" />
                </label>
            </div>
            <div class="input-group">
                <label class="label-group">
                <span class="label-item-title">
                    Sample Photo
                    <!-- <template v-if="form.sample_photo">
                    <a class="text-red-700" target="_blank" :href="env.BASE_API_URL+'/'+form?.sample_photo">{{
                        (typeof $props.form?.sample_photo === 'string')
                            ? '('+$props.form?.sample_photo.split('/').pop()+')'
                            : ''
                    }}</a>
                    </template> -->
                </span>
                <DropZoneV2 :form="form" :page="page"></DropZoneV2>
                </label>
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