<script setup>
import { computed, ref, watch, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useStore } from 'vuex';
import Title from '../../../services/title';
import useEdi from '../../../composables/operation/useEdi';
import useVoyage from '../../../composables/operation/useVoyage';
import DropZone from '../../../components/DropZone.vue';
import EdiTable from '../../../components/operation/EdiTable.vue';
import Heading from '../../../components/Heading.vue';

const route = useRoute();
const { setTitle } = Title();
const { voyage, showVoyage } = useVoyage();
const store = useStore();
const { edi, allEdi, process, isLoading, errors } = useEdi();
const dropZoneFile = ref(computed(() => store.getters.getDropZoneFile));
const title = 'EDI';

edi.voyage_id = route.params.voyageId;

watch(voyage, (value) => {
    if (value === null || value === undefined) {
        setTitle(title);
    } else {
        setTitle(`${value.voyage} - ${title}`);
    }
});

watch(dropZoneFile, (value) => {
    if (value !== null && value !== undefined) {
        edi.file = value;
    }
});

onMounted(() => {
    showVoyage(route.params.voyageId);
});
</script>

<template>
    <Heading :label="`${title}: #${voyage?.voyage}`" type="none"></Heading>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <DropZone></DropZone>
        <form v-if="dropZoneFile !== null" @submit.prevent="process(edi)" class="flex flex-col items-center justify-center w-full gap-2 mt-2" enctype="multipart/form-data">
            <button :disabled="isLoading" class="flex items-center justify-center px-3 py-1 text-gray-100 bg-purple-600 rounded">
                <span v-if="!isLoading">Process</span>
                <svg v-else xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="w-6 h-6 iconify iconify--eos-icons" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                    <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8a8 8 0 0 1-8 8z" opacity=".5" fill="currentColor" />
                    <path d="M20 12h2A10 10 0 0 0 12 2v2a8 8 0 0 1 8 8z" fill="currentColor">
                        <animateTransform attributeName="transform" type="rotate" from="0 12 12" to="360 12 12" dur="1s" repeatCount="indefinite" />
                    </path>
                </svg>
            </button>

            <ul class="text-red-400">
                <li class="ml-5 font-bold">{{ errors?.[0] }}</li>
                <li class="ml-5 font-bold" v-if="errors?.[1]">The following ISO Codes doesn't exist in our database. Please add these and try agian. </li>
                <li class="bold ml-10 font-semibold" v-for="(iso,index,keys) in errors?.[1]">{{ keys+1 }}. {{ iso }}</li>
                <li class="ml-5 font-bold" v-if="errors?.[1]">
                    <a href="/container-type/create" class=" items-center justify-center px-3 py-1 text-gray-100 bg-green-600 rounded" target="_blank">Add ISO</a>
                </li>
            </ul>
        </form>
    </div>
    <EdiTable :data="allEdi"></EdiTable>
</template>