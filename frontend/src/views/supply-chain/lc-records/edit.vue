<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import useLcRecord from "../../../composables/supply-chain/useLcRecord";
import LcRecordForm from "../../../components/supply-chain/lc-records/LcRecordForm.vue";
import { useRoute } from 'vue-router';

const { getLcRecord, showLcRecord, lcRecord, updateLcRecord,materialObject, errors, isLoading } = useLcRecord();

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { setTitle } = Title();
const route = useRoute();
const lcRecordId = route.params.lcRecordId;
const formType = 'edit';

setTitle('Update LC Records');

onMounted(() => {
    showLcRecord(lcRecordId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update LC Records</h2>
        <default-button :title="'Opening Stock List'" :to="{ name: 'scm.lc-records.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="updateLcRecord(lcRecord, lcRecordId)">
            <lc-record-form :form="lcRecord" :errors="errors" :formType="formType" :page="formType"></lc-record-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>
