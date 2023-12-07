<script setup>

const props = defineProps({
    allJobs: {
        required: false,
        default: {}
    }
});

</script>

<template>
    <div class="w-full my-2 overflow-hidden border shadow-xs dark:border-0 px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <h1 class="py-1 font-bold text-center text-white bg-green-500 text-capitalize"> All Job sdfsdfdsfdfdss </h1>
            <div class="w-full">
                
                <template v-for="(job, index) in allJobs" :key="index" class="bg-red-500">
                    <p class="mb-2">{{ job  }}</p>
                </template>
                <!-- <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                            <td> Item Code </td>
                            <td> Item </td>
                            <td> Item Run Hrs. </td>
                            <td> Description </td>
                            <td> Cycle </td>
                            <td> Next Due Hrs. </td>
                            <td> Last Done </td>
                        </tr>
                    </thead>

                        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                            <tr>
                                <td> 20' </td>
                                <td> {{ voyageInfo?.gp_20_ldn_rate }} </td>
                                <td> {{ voyageInfo?.gp_20_ldn_quantity }} </td>
                                <td> {{ voyageInfo?.gp_20_ldn_amount }} </td>
                            </tr>
                        </tbody>
                        
                    </table> -->
                </div>
            </div>
</template>