<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Bookings</h2>
        <router-link :to="{ name: 'commercial.bookings.create' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
        </router-link>
    </div>
    <!-- Table -->
    <div class="w-full overflow-hidden border rounded-lg shadow-xs dark:border-0">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead v-once>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">Client name</th>
                        <th class="px-4 py-3">mobile</th>
                        <th class="px-4 py-3">Ordered tues</th>
                        <th class="px-4 py-3">Departure port</th>
                        <th class="px-4 py-3">Destination port</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr class="text-gray-700 dark:text-gray-400" v-for="booking in bookings" :key="booking.id" v-memo>
                        <td class="px-4 py-3 text-sm">{{ booking.client_name }}</td>
                        <td class="px-4 py-3 text-sm">{{ booking.client_contact }}</td>
                        <td class="px-4 py-3 text-sm">{{ booking.ordered_tues }}</td>
                        <td class="px-4 py-3 text-sm">{{ booking.departure_port_code }}</td>
                        <td class="px-4 py-3 text-sm">{{ booking.destination_port_code }}</td>
                        <td class="flex items-center justify-center px-4 py-3 space-x-2 text-sm text-gray-600">
                            <action-button :action="'edit'" :to="{ name: 'commercial.bookings.edit', params: { bookingId: booking.id } }"></action-button>
                            <action-button :action="'show'" :to="{ name: 'commercial.bookings.show', params: { bookingId: booking.id } }"></action-button>
                            <action-button @click="deleteBooking(booking.id)" :action="'delete'"></action-button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script setup>
import { onMounted } from '@vue/runtime-core';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useBooking from '../../../composables/commercial/useBooking';

const { bookings, getBookings, deleteBooking } = useBooking();

onMounted(() => {
    getBookings();
});
</script>