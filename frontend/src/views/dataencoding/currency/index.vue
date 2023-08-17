
<script setup>
import { onMounted, watchEffect } from 'vue';
import Title from '../../../services/title';
import useCurrency from '../../../composables/dataencoding/useCurrency';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Heading from '../../../components/Heading.vue';
import Paginate from '../../../components/utils/paginate.vue';

const props = defineProps({
    page: {
        type: Number,
        default: 1,
    },
})
const { currencies, isLoading, getCurrency, deleteCurrency } = useCurrency();
const { setTitle } = Title();

setTitle('Currencies');

onMounted(() => {
    watchEffect(() => {
        getCurrency(props.page);
    });
});
</script>
<template>
    <!-- Heading -->
    <heading label="Currencies" type="create" :to="{ name: 'dataencoding.currency.create' }"></heading>
    <!-- Table -->
    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody v-if="currencies?.data?.length">
                    <tr v-for="(currency, index) in currencies.data" :key="currency?.id">
                        <td>{{ currencies.from + index }}</td>
                        <td>{{ currency?.code }}</td>
                        <td>{{ currency?.name }}</td>
                        <td class="items-center justify-center space-x-2 text-gray-600">
                            <action-button :action="'show'" :to="{ name: 'dataencoding.currency.show', params: { currencyId: currency.id } }"></action-button>
                            <action-button :action="'edit'" :to="{ name: 'dataencoding.currency.edit', params: { currencyId: currency.id } }"></action-button>
                            <action-button @click="deleteCurrency(currency.id)" :action="'delete'"></action-button>
                          <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: currency.subject_type,subject_id: currency.id } }"></action-button>
                        </td>
                    </tr>
                </tbody>
                <tfoot v-if="!currencies?.data?.length" class="bg-white dark:bg-gray-800">
                    <tr v-if="isLoading">
                        <td colspan="8">Loading...</td>
                    </tr>
                    <tr v-else-if="!currencies?.data?.length">
                        <td colspan="8">No currency found.</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- Pagination -->
        <Paginate :data="currencies" to="dataencoding.currency.index" :page="page"></Paginate>
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
