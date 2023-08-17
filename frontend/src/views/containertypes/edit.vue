<template>
    <div class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2" v-once>
        <span>Update Container Type:</span>
        <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700">#{{ containerTypeId }}</span>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="updateContainerType(containerType, containerTypeId)">
            <!-- Port Form -->
          <container-type-form v-model:form="containerType" :errors="errors"></container-type-form>
            <!-- Submit button -->
            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update Container Type</button>
        </form>
    </div>
</template>
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import ContainerTypeForm from "../../components/dataencoding/ContainerTypeForm.vue";
import useContainerType from "../../composables/useContainerType";
import Title from "../../services/title";


const route = useRoute();
const containerTypeId = route.params.containerTypeId;
const { containerType, showContainerType, updateContainerType, errors } = useContainerType();

const { setTitle } = Title();

setTitle('Edit Container Type');

onMounted(() => {
    showContainerType(containerTypeId);
});
</script>