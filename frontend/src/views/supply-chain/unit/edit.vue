<script setup>
import UnitForm from '../../../components/supply-chain/unit/UnitForm.vue';
import {ref} from "vue";
import { onMounted } from '@vue/runtime-core';
import Title from "../../../services/title";
import useUnit from "../../../composables/supply-chain/useUnit.js";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();
import { useRoute } from 'vue-router';
import { useStore } from "vuex";
const store = useStore();

const { unit, showUnit, updateUnit,isLoading } = useUnit();
const { setTitle } = Title();
const route = useRoute();
const unitId = route.params.unitId;


setTitle('Edit Unit');

onMounted(() => {
    showUnit(unitId);
    
});

</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Edit Unit</h2>
        <default-button :title="'Unit List'" :to="{ name: 'scm.units.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="updateUnit(unit, unitId)">
            <unit-form v-model:form="unit" :errors="errors"></unit-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600  hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>
