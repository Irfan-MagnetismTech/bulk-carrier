<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import useMovementIn from "../../../composables/supply-chain/useMovementIn";
import MovementInForm from "../../../components/supply-chain/movement-ins/MovementInForm.vue";
import { useRoute } from 'vue-router';

const { getMovementIn, showMovementIn, movementIn, updateMovementIn,materialObject, errors, isLoading } = useMovementIn();

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { setTitle } = Title();
const route = useRoute();
const movementInId = route.params.movementInId;
const formType = 'edit';

setTitle('Update Movement In');

onMounted(() => {
    showMovementIn(movementInId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Edit Movement Out</h2>
        <default-button :title="'Opening Stock List'" :to="{ name: 'scm.movement-requisitions.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="updateMovementIn(movementIn, movementInId)">
            <movement-in-form :form="movementIn" :page="formType" :errors="errors" :formType="formType" :materialObject="materialObject"></movement-in-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>
