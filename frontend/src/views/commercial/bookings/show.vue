<template>
    <div class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2" v-once>
        <span>Booking Details:</span>
        <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700">#{{ bookingId }}</span>
    </div>
    <!-- Table -->
    <div class="w-full overflow-hidden border rounded-lg shadow-xs dark:border-0">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead v-once>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">Field Name</th>
                        <th class="px-4 py-3">Data</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr class="text-gray-700 dark:text-gray-400">
                        <td class="px-4 py-3">Client name</td>
                        <td class="px-4 py-3 text-sm">{{ booking.client_name }}</td>
                    </tr>
                    <tr class="text-gray-700 dark:text-gray-400">
                        <td class="px-4 py-3">mobile</td>
                        <td class="px-4 py-3 text-sm">{{ booking.client_contact }}</td>
                    </tr>
                    <tr class="text-gray-700 dark:text-gray-400">
                        <td class="px-4 py-3">Ordered tues</td>
                        <td class="px-4 py-3 text-sm">{{ booking.ordered_tues }}</td>
                    </tr>
                    <tr class="text-gray-700 dark:text-gray-400">
                        <td class="px-4 py-3">Departure port</td>
                        <td class="px-4 py-3 text-sm">{{ booking.departure_port_code }}</td>
                    </tr>
                    <tr class="text-gray-700 dark:text-gray-400">
                        <td class="px-4 py-3">Destination port</td>
                        <td class="px-4 py-3 text-sm">{{ booking.destination_port_code }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script setup>
import { onMounted } from '@vue/runtime-core';
import { useRoute } from 'vue-router';
import useBooking from '../../../composables/commercial/useBooking';
import Title from "../../../services/title";

const route = useRoute();
const bookingId = route.params.bookingId;
const { booking, showBooking } = useBooking();

const { setTitle } = Title();

setTitle('Booking Details');

onMounted(() => {
    showBooking(bookingId);
});
</script>