<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import useMaterialCs from "../../../composables/supply-chain/useMaterialCs";
import useHelper from "../../../composables/useHelper.js";
import MaterialCsForm from "../../../components/supply-chain/material-cs/MaterialCsForm.vue";
import ForeignQuotationForm from "../../../components/supply-chain/quotations/ForeignQuotationForm.vue";
import LocalQuotationForm from "../../../components/supply-chain/quotations/LocalQuotationForm.vue";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import useQuotation from "../../../composables/supply-chain/useQuotation";
import { useRouter, useRoute } from 'vue-router';

const icons = useHeroIcon();
const route = useRoute();
const CSID = route.params.csId;
const { getMaterialCs, materialCs, storeMaterialCs, materialObject, errors, isLoading, getPrWiseCs,showMaterialCs} = useMaterialCs();


 onMounted(() => {
    showMaterialCs(CSID);
});

const { storeQuotations ,quotation,localQuotationLines,foreignQuotationLines} = useQuotation();
const page = ref('create');
const { setTitle } = Title();

const props = defineProps({
    pr_id: {
    type: Number,
    default: 1,
  },
});

// onMounted(() => {
//     getPrWiseCs(props.pr_id);
// }); 

setTitle('Create Material CS');
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Create Quotation</h2>
        <default-button :title="'Quotations List'" :to="{ name: 'scm.quotations.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800 overflow-hidden">
        <form @submit.prevent="storeQuotations(quotation,CSID)">
          <!-- <material-cs-form v-model:form="quotation" :errors="errors" :page="page"></material-cs-form> -->
            <!-- Submit button -->
            <template v-if="materialCs.purchase_center == 'Foreign'">
                <foreign-quotation-form v-model:form="quotation" :errors="errors" :page="page" :lineObj="foreignQuotationLines"></foreign-quotation-form>
            </template>
            <template v-if="materialCs.purchase_center == 'Local'">
                <local-quotation-form v-model:form="quotation" :errors="errors" :page="page" :lineObj="localQuotationLines"></local-quotation-form>
            </template>
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
        </form>
        
    </div>
</template>
