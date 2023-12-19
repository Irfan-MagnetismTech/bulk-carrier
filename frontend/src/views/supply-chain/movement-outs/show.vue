<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import useMovementOut from "../../../composables/supply-chain/useMovementOut";
import MovementOutShow from "../../../components/supply-chain/movement-outs/MovementOutShow.vue";
import { useRoute } from 'vue-router';

const { getMovementOut, showMovementOut, movementOut, updateMovementOut,materialObject, errors, isLoading } = useMovementOut();

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { setTitle } = Title();
const route = useRoute();
const movementOutId = route.params.movementOutId;
const formType = 'edit';

setTitle('Show Movement Out');

onMounted(() => {
    showMovementOut(movementOutId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Movement Out Details</h2>
        <default-button :title="'Movement Out List'" :to="{ name: 'scm.movement-outs.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
            <movement-out-show :form="movementOut"></movement-out-show>
    </div>
</template>
