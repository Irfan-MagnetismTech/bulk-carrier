<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import useMovementOut from "../../../composables/supply-chain/useMovementOut";
import MovementOutForm from "../../../components/supply-chain/movement-outs/MovementOutForm.vue";
import { useRoute } from 'vue-router';

const { getMovementOut, showMovementOut, movementOut, updateMovementOut,materialObject, errors, isLoading } = useMovementOut();

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { setTitle } = Title();
const route = useRoute();
const movementOutId = route.params.movementOutId;
const formType = 'edit';

setTitle('Update Movement Out');

onMounted(() => {
    showMovementOut(movementOutId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Edit Movement Out</h2>
        <default-button :title="'Movement Out List'" :to="{ name: 'scm.movement-outs.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="updateMovementOut(movementOut, movementOutId)">
            <movement-out-form :form="movementOut" :page="formType" :errors="errors" :formType="formType" :materialObject="materialObject"></movement-out-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>
