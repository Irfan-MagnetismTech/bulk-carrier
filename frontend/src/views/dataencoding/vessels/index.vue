
<script setup>
import { onMounted, watchEffect } from 'vue';
import Title from '../../../services/title';
import useVessel from '../../../composables/dataencoding/useVessel';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Heading from '../../../components/Heading.vue';
import Paginate from '../../../components/utils/paginate.vue';

const props = defineProps({
    page: {
        type: Number,
        default: 1,
    },
})
const { vessels, isLoading, getVessels, deleteVessel } = useVessel();
const { setTitle } = Title();

setTitle('Vessels');

onMounted(() => {
    watchEffect(() => {
        getVessels(props.page);
    });
});
</script>
<template>
    <!-- Heading -->
    <heading label="Vessels" type="create" :to="{ name: 'dataencoding.vessels.create' }"></heading>
    <!-- Table -->
    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Ownership</th>
                        <th>IMO/Lloyd Number</th>
                        <th>Year Built</th>
                        <th>Call Sign</th>
                        <th>Capacity Tues</th>
                        <th>Classification</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody v-if="vessels?.data?.length">
                    <tr v-for="(vessel, index) in vessels.data" :key="vessel?.id">
                        <td>{{ vessels.from + index }}</td>
                        <td>{{ vessel?.name }}</td>
                        <td>{{ vessel?.code }}</td>
                        <td>{{ vessel?.ownership }}</td>
                        <td>{{ vessel?.imo_number }}</td>
                        <td>{{ vessel?.year_built }}</td>
                        <td>{{ vessel?.call_sign }}</td>
                        <td>{{ vessel?.capacity_tues }}</td>
                        <td>{{ vessel?.classification }}</td>
                        <td class="items-center justify-center space-x-2 text-gray-600">
                            <action-button :action="'show'" :to="{ name: 'dataencoding.vessels.show', params: { vesselId: vessel.id } }"></action-button>
                            <action-button :action="'edit'" :to="{ name: 'dataencoding.vessels.edit', params: { vesselId: vessel.id } }"></action-button>
                            <action-button @click="deleteVessel(vessel.id)" :action="'delete'"></action-button>
                            <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: vessel.subject_type,subject_id: vessel.id } }"></action-button>
                        </td>
                    </tr>
                </tbody>
                <tfoot v-if="!vessels?.data?.length" class="bg-white dark:bg-gray-800">
                    <tr v-if="isLoading">
                        <td colspan="10">Loading...</td>
                    </tr>
                    <tr v-else-if="!vessels?.data?.length">
                        <td colspan="10">No vessels found.</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- Pagination -->
        <Paginate :data="vessels" to="dataencoding.vessels.index" :page="page"></Paginate>
    </div>
</template>
<style lang="postcss" scoped>
@tailwind components;

@layer components {
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
}
</style>
