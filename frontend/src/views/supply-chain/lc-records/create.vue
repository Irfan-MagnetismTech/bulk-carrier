<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import useLcRecord from "../../../composables/supply-chain/useLcRecord";
import useHelper from "../../../composables/useHelper.js";
import LcRecordForm from "../../../components/supply-chain/lc-records/LcRecordForm.vue";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import { useRoute } from 'vue-router';

const icons = useHeroIcon();
const route = useRoute();

const { getLcRecord, lcRecord, storeLcRecord,materialObject,termsObject, errors, isLoading } = useLcRecord();
const page = ref('create');
const { setTitle } = Title();



setTitle('Create LC Record');
</script>
<template>
    <!-- Heading -->
    
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Create LC Records</h2>
        <default-button :title="'PO List'" :to="{ name: 'scm.lc-records.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800 overflow-hidden">
        <form @submit.prevent="storeLcRecord(lcRecord)">
          <lc-record-form v-model:form="lcRecord" :errors="errors" :page="page"></lc-record-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm text-white bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
        </form>
        
    </div>
</template>