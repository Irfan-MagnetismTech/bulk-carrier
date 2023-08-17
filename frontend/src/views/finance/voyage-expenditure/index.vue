<script setup>
import { onMounted, computed, ref, watchEffect } from 'vue';
import { orderBy } from 'lodash';
import { onClickOutside } from '@vueuse/core';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import moment from 'moment';
import Heading from '../../../components/Heading.vue';
import useVoyageExpenditureHead from '../../../composables/finance/useVoyageExpenditureHead';
import useReport from '../../../composables/operation/useReport';
import Title from "../../../services/title";

const { heads, isLoading, getHeads, deleteHead, errors } = useVoyageExpenditureHead();

const { setTitle } = Title();

setTitle('Cost Heads');

onMounted(() => {
    getHeads();
});
</script>
<template>
    <!-- Heading -->

    <Heading :to="{ name: 'finance.voyageExpenditureHead.create' }" type="create" label="Cost Groups"></Heading>

    <!-- Table -->
    <!-- <div class="max-w-screen h-full overflow-x-auto bg-white overflow-y-auto mb-4">
    </div>-->
    <div class="w-full">
        <div class="w-full">
            <table class="w-full whitespace-no-wrap mb-5">
                <thead v-once>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                        <th class="text-center">#</th>
                        <th class="text-center">Group</th>
                        <th>Head</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr class="text-gray-700 dark:text-gray-400" v-for="(head, index) in heads" :key="head.id">
                        <td class="px-1 text-center">{{ index + 1 }}</td>
                        <td class="font-semibold text-left dark:text-gray-300 ">
                            {{ head?.name }}
                        
                            <span v-if="head?.is_global == 1" class="relative -top-1 w-2 h-2 bg-green-500 rounded-full inline-block">
                            </span>
                        </td>
                        <td>
                            <ul class="flex flex-col">
                                <li v-for="(subhead, index) in head?.subheads" class="font-semibold bg-gray-300 m-1 p-1 rounded-md inline-block">
                                    {{ subhead?.name }}
                                    
                                </li>
                            </ul>
                        </td>
                        <td class=" text-gray-600">
                            <div class="flex items-center justify-center">
                                <action-button :action="'edit'" :to="{ name: 'finance.voyage-expenditure-heads.edit', params: { headId: head?.id ?? -1 } }"></action-button>
                                <action-button @click="deleteHead(head.id)" :action="'delete'"></action-button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="heads?.length === 0">
                        <td colspan="8" class="text-center dark:text-gray-400">
                            <span v-if="!isLoading">No records available.</span>
                            <span v-else-if="isLoading" class="flex items-center justify-center w-full">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="w-5 h-5 iconify iconify--eos-icons" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                    <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8a8 8 0 0 1-8 8z" opacity=".5" fill="currentColor" />
                                    <path d="M20 12h2A10 10 0 0 0 12 2v2a8 8 0 0 1 8 8z" fill="currentColor">
                                        <animateTransform attributeName="transform" type="rotate" from="0 12 12" to="360 12 12" dur="1s" repeatCount="indefinite" />
                                    </path>
                                </svg>
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<style lang="postcss" scoped>
th {
    @apply px-3 py-2;
}

td {
    @apply px-3 py-2 text-xs;
}

.dropdown-btn {
    @apply bg-gray-900 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 focus:ring-offset-gray-50 text-white font-semibold py-2 px-3 rounded-lg w-full flex items-center justify-center gap-1.5 sm:w-auto dark:bg-blue-500 dark:hover:bg-blue-400;
}

table, th,td{
  @apply border border-collapse;
}
</style>