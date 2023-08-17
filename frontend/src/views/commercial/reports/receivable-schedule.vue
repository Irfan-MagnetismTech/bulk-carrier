<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Receivable Schedule</h2>
    </div>
    <!-- Table -->
    <div class="w-full overflow-hidden border shadow-xs dark:border-0">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap" id="table">
                <thead v-once>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th>Invoice No.</th>
                        <th>B/L No.</th>
                        <th>Customer Name</th>
                        <th>Customer Code</th>
                        <th>POL</th>
                        <th>POD</th>
                        <th>Lane</th>
                        <th>Sailing Date(POL)</th>
                        <th>Invoice Issue Date</th>
                        <th>Due Date</th>
                        <th>Vessel</th>
                        <th>VOY</th>
                        <th>Curr</th>
                        <th>Invoice Amount</th>
                        <th>TTL O/S days</th>
                        <th>1-15 Dys</th>
                        <th>16-30 Dys</th>
                        <th>31-45 Dys</th>
                        <th>46-60 Dys</th>
                        <th>60-90 Dys</th>
                        <th>>90 Dys</th>
                        <th>TTL Collect</th>
                        <th>Remark 1</th>
                        <th>Remark 2</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr>
                        <td>Hello</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script setup>

</script>
<style lang="postcss" scoped>
#table,
#table th,
#table td {
    @apply border border-collapse text-center text-gray-500 px-1;
}
</style>