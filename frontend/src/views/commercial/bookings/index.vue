<script setup>

import { onMounted } from '@vue/runtime-core';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useBooking from '../../../composables/commercial/useBooking';
import {watchEffect, watch} from "vue";
import Title from "../../../services/title";
import useDebouncedRef from "../../../composables/useDebouncedRef";
import Paginate from '../../../components/utils/paginate.vue';

const { bookings, getBookings, deleteBooking, isLoading } = useBooking();

const props = defineProps({
  page: {
    type: Number,
    default: 1
  },
});

const { setTitle } = Title();
setTitle('Bookings');

// Code for global search starts here
const columns = ["client_name", "client_contact", "ordered_tues", "departure_port_code", "destination_port_code"];
const searchKey = useDebouncedRef('', 800);
const table = "bookings";

watch(searchKey, newQuery => {
  getBookings(props.page, columns, searchKey.value, table);
});
// Code for global search end here

onMounted(() => {
  watchEffect(() => {
    getBookings(props.page, columns, searchKey.value, table);
  });
});
</script>

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
  <div class="flex items-center justify-between mb-2 select-none">
    <span class="w-full text-xs font-medium text-gray-500 whitespace-no-wrap">Showing {{ bookings?.from }}-{{ bookings?.to }} of {{ bookings?.total }}</span>
    <!-- Search -->
    <div class="relative w-full">
      <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-0 w-5 h-5 mr-2 text-gray-500 bottom-2" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
      </svg>
      <input type="text"  v-model.trim="searchKey" placeholder="Search with voyage no..." class="search" />
    </div>
  </div>
    <!-- Table -->
    <div class="w-full overflow-hidden">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead v-once>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                      <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Client name</th>
                        <th class="px-4 py-3">mobile</th>
                        <th class="px-4 py-3">Ordered tues</th>
                        <th class="px-4 py-3">Departure port</th>
                        <th class="px-4 py-3">Destination port</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr class="text-gray-700 dark:text-gray-400" v-for="(booking,index) in bookings.data" :key="index">
                        <td class="px-4 py-3 text-sm">{{ bookings.from + index }}</td>
                        <td class="px-4 py-3 text-sm">{{ booking.client_name }}</td>
                        <td class="px-4 py-3 text-sm">{{ booking.client_contact }}</td>
                        <td class="px-4 py-3 text-sm">{{ booking.ordered_tues }}</td>
                        <td class="px-4 py-3 text-sm">{{ booking.departure_port_code }}</td>
                        <td class="px-4 py-3 text-sm">{{ booking.destination_port_code }}</td>
                        <td class="items-center justify-center px-4 py-3 space-x-2 text-sm text-gray-600">
                            <action-button :action="'edit'" :to="{ name: 'commercial.bookings.edit', params: { bookingId: booking.id } }"></action-button>
                            <action-button :action="'show'" :to="{ name: 'commercial.bookings.show', params: { bookingId: booking.id } }"></action-button>
                            <action-button @click="deleteBooking(booking.id)" :action="'delete'"></action-button>
                          <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: booking.subject_type,subject_id: booking.id } }"></action-button>
                        </td>
                    </tr>
                </tbody>
                <tfoot v-if="!bookings?.data?.length" class="bg-white dark:bg-gray-800">
                <tr v-if="isLoading">
                  <td colspan="7">Loading...</td>
                </tr>
                <tr v-else-if="!bookings?.data?.length">
                  <td colspan="7">No bookings found.</td>
                </tr>
                </tfoot>
            </table>
        </div>
      <!-- Pagination -->
      <Paginate :data="bookings" to="commercial.bookings.index" :page="page"></Paginate>
    </div>
</template>

<style lang="postcss" scoped>
@tailwind components;

@layer components {
  .paginate-btn {
    @apply px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple;
  }
  .paginate-active {
    @apply text-white transition-colors duration-150 bg-purple-600 border border-r-0 border-purple-600;
  }
  .tab {
    @apply p-2.5 text-xs;
  }
  thead tr {
    @apply font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800;
  }
  th {
    @apply tab text-center;
  }
  tbody {
    @apply bg-white divide-y dark:divide-gray-700 dark:bg-gray-800;
  }
  tbody tr {
    @apply text-gray-700 dark:text-gray-400;
  }
  tbody tr td {
    @apply tab text-center;
  }
  tfoot td {
    @apply tab text-center;
  }
  table, th,td{
    @apply border border-collapse;
  }
  .search-result {
    @apply px-4 py-3 text-sm text-center text-gray-600 dark:text-gray-300;
  }
  .search {
    @apply float-right  pr-10 text-sm border border-gray-300 rounded dark:bg-gray-800 dark:text-gray-200 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray dark:border-0;
  }
}
</style>