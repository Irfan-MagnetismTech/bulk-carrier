<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import useMaterialCs from "../../../composables/supply-chain/useMaterialCs";
import MaterialCsForm from "../../../components/supply-chain/material-cs/MaterialCsForm.vue";
import ForeignQuotationForm from "../../../components/supply-chain/quotations/ForeignQuotationForm.vue";
import LocalQuotationForm from "../../../components/supply-chain/quotations/LocalQuotationForm.vue";
import useQuotation from "../../../composables/supply-chain/useQuotation";
import { useRoute } from 'vue-router';

const { getMaterialCs, showMaterialCs, materialCs, updateMaterialCs, errors, isLoading } = useMaterialCs();
const { updateQuotations, quotation, localQuotationLines, foreignQuotationLines,showQuotation } = useQuotation();

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { setTitle } = Title();
const route = useRoute();
const quotationId = route.params.quotationId;
const csId = route.params.csId;
const formType = 'edit';

setTitle('Update Quotation');

onMounted(() => {
    showQuotation(csId,quotationId);
    showMaterialCs(csId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Edit Material CS</h2>
        <default-button :title="'Quotation List'" :to="{ name: 'scm.quotations.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="updateQuotations(quotation, csId, quotationId)">
            <template v-if="materialCs.purchase_center == 'Foreign'">
                <foreign-quotation-form v-model:form="quotation" :errors="errors" :page="page" :lineObj="foreignQuotationLines"></foreign-quotation-form>
            </template>
            <template v-if="materialCs.purchase_center == 'Local' || materialCs.purchase_center == 'Plant'">
                <local-quotation-form v-model:form="quotation" :errors="errors" :page="page" :lineObj="localQuotationLines"></local-quotation-form>
            </template>
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>
