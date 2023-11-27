<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import useMovementOut from "../../../composables/supply-chain/useMovementOut";
import useHelper from "../../../composables/useHelper.js";
import MovementOutForm from "../../../components/supply-chain/movement-outs/MovementOutForm.vue";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { getMovementOut, movementOut, excelExportData, getStoreCategoryWiseExcel, storeMovementOut,materialObject, errors, isLoading } = useMovementOut();
const page = ref('create');
const { setTitle } = Title();

setTitle('Create Movement Out');
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Create Movement Out</h2>
        <default-button :title="'PR List'" :to="{ name: 'scm.movement-outs.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800 overflow-hidden">
        <form @submit.prevent="storeMovementOut(movementOut)">
          <movement-out-form v-model:form="movementOut" v-model:excelExportData="excelExportData" :downloadExcel="getStoreCategoryWiseExcel" :errors="errors" :materialObject="materialObject" :page="page"></movement-out-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
        </form>
        
    </div>
</template>
