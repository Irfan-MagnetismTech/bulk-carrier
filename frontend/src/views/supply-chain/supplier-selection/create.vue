<script setup>
import {ref,onMounted, watch} from "vue";

import Title from "../../../services/title";
import useMaterialCs from "../../../composables/supply-chain/useMaterialCs";
import useHelper from "../../../composables/useHelper.js";
import MaterialCsForm from "../../../components/supply-chain/material-cs/MaterialCsForm.vue";
import ForeignQuotationForm from "../../../components/supply-chain/quotations/ForeignQuotationForm.vue";
import LocalQuotationForm from "../../../components/supply-chain/quotations/LocalQuotationForm.vue";
import SupplierSelectionForm from "../../../components/supply-chain/supplier-selection/SupplierSelectionForm.vue";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import useQuotation from "../../../composables/supply-chain/useQuotation";
import useSupplierSelection from "../../../composables/supply-chain/useSupplierSelection";
import { useRouter, useRoute } from 'vue-router';
import { createEventHook } from "@vueuse/core";

const icons = useHeroIcon();
const route = useRoute();
const CSID = route.params.csId;



const { CsData, create , quotation,store} = useSupplierSelection();
const page = ref('create');
const { setTitle } = Title();


onMounted(() => {
    create(CSID);
 }); 

 watch(() => CsData.value, (value) => {
    if(Object.entries(value).length !== 0) {
        quotation.value = value;
        quotation.value.supplier_selection = [];
        Object.entries(value.scmCsVendor).forEach(([key, data]) => {
            quotation.value.supplier_selection.push({ 'is_selected': false, 'scm_cs_vendor_id': data[0].id, 'scm_vendor_id': data[0].scm_vendor_id});
        });
    }
  });
setTitle('Select Supplier');
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Select Supplier</h2>
        <!-- <default-button :title="'Quotations List'" :to="{ name: 'scm.quotations.index' }" :icon="icons.DataBase"></default-button> -->
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="store(quotation)">
          <!-- <material-cs-form v-model:form="quotation" :errors="errors" :page="page"></material-cs-form> -->
            <!-- Submit button -->
           <supplier-selection-form v-model:form="quotation" :errors="errors" :page="page" :materialObj="materialObj" :formData="CsData"></supplier-selection-form>
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
        </form>
        
    </div>
</template>
