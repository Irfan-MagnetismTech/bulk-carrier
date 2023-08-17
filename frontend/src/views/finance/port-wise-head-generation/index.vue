<script setup>
import { onMounted } from 'vue';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Heading from '../../../components/Heading.vue';
import usePortExpenditureHead from "../../../composables/finance/usePortExpenditureHead";
import Title from "../../../services/title";

const { portHeads, getPortHeads, isLoading, deletePortHead, errors } = usePortExpenditureHead();

const { setTitle } = Title();

setTitle('Assign Cost Groups');

onMounted(() => {
  getPortHeads();
});
</script>
<template>
    <!-- Heading -->

    <Heading :to="{ name: 'finance.port-wise-head-generation.create' }" type="create" label="Assign Cost Groups"></Heading>

    <!-- Table -->
    <!-- <div class="max-w-screen h-full overflow-x-auto bg-white overflow-y-auto mb-4">
    </div>-->
    <div class="w-full">
        <div class="w-full">
            <table class="w-full whitespace-no-wrap mb-5">
                <thead v-once>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                        <th class="text-left">#</th>
                        <th class="text-left">Port</th>
                        <th class="text-left">Group</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr class="text-gray-700 dark:text-gray-400" v-for="(portHead, key, index) in portHeads" :key="portHead.id">
                        <td class="px-1 text-center" width="5%">{{ index +1  }}</td>
                        <td class="font-semibold text-left dark:text-gray-300" width="10%">{{ key }}</td>
                        <td>
                            <span v-for="(head, index) in portHead" class="font-semibold bg-gray-300 m-1 p-1 rounded-md inline-block">
                                {{ head?.voyage_expenditure_head?.name }}<span v-if="index !== key?.length - 1">, </span>
                            </span>
                        </td>
                        <td class=" text-gray-600">
                            <div class="flex items-center justify-center">
                                <action-button :action="'edit'" :to="{ name: 'finance.port-wise-head-generation.edit', params: { headId: key ?? -1 } }"></action-button>
                                <action-button @click="deletePortHead(key)" :action="'delete'"></action-button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="portHeads?.length === 0">
                        <td colspan="4" class="text-center dark:text-gray-400">
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