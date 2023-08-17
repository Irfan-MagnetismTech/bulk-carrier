<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Receivable Schedule</h2>
    </div>
    <!-- Table -->
    <div class="w-full overflow-hidden border shadow-xs dark:border-0">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap" id="table">
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr class="">
                        <td colspan="2"> <strong> BILL OF LADING </strong> </td>
                        <td colspan="4"> NOT NEGOTIABLE UNLESS CONSIGNED "TO ORDER"	</td>
                    </tr>
                    <tr>
                        <td>
                            <p class="field_name">Shipper</p>
                            <p>
                                The Agent for Yangming Marine Transport Corporation, <br>
                                c/o Yangming Singapore Pte Ltd, Singapore, 169877"
                            </p>

                        </td>
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
    font-family: Calibri;
    font-size: 13.33px;
    text-align: left;
    vertical-align: top;
}
.field_name{
    font-size: 10.66px;
}
</style>