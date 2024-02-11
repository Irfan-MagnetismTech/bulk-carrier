<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import useMaterialCosting from "../../../composables/supply-chain/useMaterialCosting";
import useHelper from "../../../composables/useHelper.js";
import MaterialCostingForm from "../../../components/supply-chain/material-costings/MaterialCostingForm.vue";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';


const icons = useHeroIcon();

const { getMaterialCosting, materialCosting, excelExportData, storeMaterialCosting,materialObject, errors, isLoading } = useMaterialCosting();
const page = ref('create');
const { setTitle } = Title();
const formType = 'create';

setTitle('Create Material Costing');
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Create Store Requisition</h2>
        <default-button :title="'Material Costing List'" :to="{ name: 'scm.material-costings.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="storeMaterialCosting(materialCosting)">
          <material-costing-form v-model:form="materialCosting" :errors="errors" :materialObject="materialObject" :page="page" :formType="formType"></material-costing-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
        </form>
        
    </div>
</template>
