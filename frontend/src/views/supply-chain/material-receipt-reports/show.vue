<script setup>
// import {ref} from "vue";
// import { onMounted } from '@vue/runtime-core';
// import env from '../../../config/env';
// import Title from "../../../services/title";
// import useMaterialRequisition from "../../../composables/scm/useMaterialRequisition.js";
// import useHelper from "../../../composables/useHelper.js";
// import MaterialRequisitionForm from "../../../components/scm/material-requisition/MaterialRequisitionForm.vue";
// import { useRoute } from 'vue-router';
// import moment from 'moment';

// const { materialRequisition, showMaterialRequisition  } = useMaterialRequisition();
// const { getAllUnit, numberFormat } = useHelper();
// const { setTitle } = Title();
// const route = useRoute();
// const materialRequisitionId = route.params.materialRequisitionId;
// setTitle('Material Requisition Details');

// onMounted(() => {
//     showMaterialRequisition(materialRequisitionId);
// });
</script>
<template>
    
</template>
<style lang="postcss" scoped>
    .input-group {
        @apply flex flex-col justify-center w-full md:flex-row md:gap-2;
    }
    .label-group {
        @apply block w-full mt-3 text-sm font-semibold;
    }
    .label-item-title {
        @apply text-gray-700 dark-disabled:text-gray-300 text-sm;
    }
    .label-item-input {
        @apply bg-gray-100 block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark-disabled:disabled:bg-gray-900;
    }
    .form-input {
        @apply block mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray;
    }
    .vs__selected{
    display: none !important;
    }
    .required-style{
        @apply text-red-400 font-semibold
    }

    span.label-item-input {
        @apply bg-gray-100 py-2 h-9 flex items-center border border-black px-3 w-full mt-1 text-xs rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray
    }

    table th,tr,td{
      border: 1px solid grey;
    }

</style>