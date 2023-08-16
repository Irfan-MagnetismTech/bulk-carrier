<script setup>
import Title from "../services/title";
const { setTitle } = Title();
setTitle('Dashboard');

</script>
<template>
    <div class="grid gap-4 md:grid-cols-3 mt-2">
      TSLL - TOGGI SHIPPING & LOGISTICS LTD
    </div>
</template>
<!--<style lang="postcss" scoped>-->
<!--    .input-group {-->
<!--        @apply flex flex-col justify-center w-full md:flex-row md:gap-2;-->
<!--    }-->
<!--    .label-group {-->
<!--        @apply block w-full mt-3 text-sm font-semibold;-->
<!--    }-->
<!--    .label-item-title {-->
<!--        @apply text-gray-700 dark:text-gray-300;-->
<!--    }-->
<!--    .label-item-input {-->
<!--        @apply bg-gray-100 block w-full mt-1 p-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;-->
<!--    }-->
<!--    .form-input {-->
<!--        @apply block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;-->
<!--    }-->
<!--    .vs__selected{-->
<!--    display: none !important;-->
<!--    }-->
<!--    .required-style{-->
<!--        @apply text-red-400 font-semibold-->
<!--    }-->

<!--    table, tr, td, th {-->
<!--        @apply border-->
<!--    }-->
<!--    th, td {-->
<!--        @apply text-center-->
<!--    }-->
<!--    .chart {-->
<!--        position: absolute;-->
<!--        top: 50%;-->
<!--        left: 50%;-->
<!--        transform: translate(-50%, -50%);-->
<!--    }-->

<!--    #dashboard_material_summary_head{-->
<!--      background-color: #389890;-->
<!--      color: #fff;-->
<!--    }-->
<!--    .scroll-container {-->
<!--      max-height: 300px; /* Set the desired fixed height */-->
<!--      overflow-y: auto; /* Enable vertical scrollbar */-->
<!--    }-->

<!--    /* Customize the scrollbar's appearance */-->
<!--    .scroll-container::-webkit-scrollbar {-->
<!--      width: 4px; /* Set the width of the scrollbar for Webkit-based browsers */-->
<!--    }-->

<!--    .scroll-container::-webkit-scrollbar-thumb {-->
<!--      background-color: #969696; /* Set the color of the scrollbar thumb */-->
<!--      border-radius: 2px;-->
<!--    }-->

<!--    .scroll-container::-webkit-scrollbar-thumb:hover {-->
<!--      background-color: #a4a3a3; /* Set the color of the scrollbar thumb on hover */-->
<!--    }-->

<!--    table th,tr,td{-->
<!--        border: 1px solid grey;-->
<!--    }-->

<!--</style>-->