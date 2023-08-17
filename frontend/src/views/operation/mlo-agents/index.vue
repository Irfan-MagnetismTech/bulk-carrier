
<script setup>
import { onMounted, watchEffect, watch } from 'vue';
import Title from '../../../services/title';
import useMloAgent from '../../../composables/operation/useMloAgent';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Heading from '../../../components/Heading.vue';
import Paginate from '../../../components/utils/paginate.vue';
import useDebouncedRef from "../../../composables/useDebouncedRef";
import useReport from '../../../composables/operation/useReport';


const props = defineProps({
    page: {
        type: Number,
        default: 1,
    },
})
const { agents, isLoading, getAgents, deleteAgent } = useMloAgent();
const { downloadMloAgentList } = useReport();
const { setTitle } = Title();
setTitle('MLO Agents');

// Code for global search starts here
const columns = ["name", "code", "port", "mlos", "address", "export_email", "import_email"];
const searchKey = useDebouncedRef('', 800);
const table = "mlo_agents";

watch(searchKey, newQuery => {
  getAgents(props.page, columns, searchKey.value, table);
});
// Code for global search end here

onMounted(() => {
    watchEffect(() => {
        getAgents(props.page);
    });
});
</script>
<template>
    <!-- Heading -->
    <heading label="MLO Agents" type="create" :to="{ name: 'operation.mlo.agents.create' }"></heading>
    <div class="w-">
        <button @click="downloadMloAgentList()" class="text-slate-800 flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            Download Agent List
        </button>
    </div>
    <div class="flex items-center justify-between mb-2 select-none">
      <span class="w-full text-xs font-medium text-gray-500 whitespace-no-wrap">Showing {{ agents?.from }}-{{ agents?.to }} of {{ agents?.total }}</span>
      <!-- Search -->
      <div class="relative w-full">
        <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-0 w-5 h-5 mr-2 text-gray-500 bottom-2" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
        </svg>
        <input type="text"  v-model.trim="searchKey" placeholder="Search..." class="search" />
      </div>
    </div>
    <!-- Table -->
    <div class="w-full mb-8">
        <div class="w-full overflow-x-scroll">
            <table class="w-full overflow-x-scroll">
                <thead>
                    <tr>
                        <th class="w-12">#</th>
                        <th>Name</th>
                        <th class="w-16">Port</th>
                        <th>Code</th>
                        <th>MLOs</th>
                        <th>Address</th>
                        <th>Export Email</th>
                        <th>Import Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody v-if="agents?.data?.length">
                    <tr v-for="(agentInfo, index) in agents?.data" :key="agentInfo?.id">
                        <td>{{ index+1 }}</td>
                        <td>{{ agentInfo?.name }}</td>
                        <td>{{ agentInfo?.port }}</td>
                        <td>{{ agentInfo?.code }}</td>
                        <td>{{ agentInfo?.mlos }}</td>
                        <td>{{ agentInfo?.address }}</td>
                        <td>{{ agentInfo?.export_email }}</td>
                        <td>{{ agentInfo?.import_email }}</td>
                        <td class="w-36 items-center justify-center space-x-2 text-gray-600">
                            <action-button :action="'show'" :to="{ name: 'operation.mlo.agents.show', params: { agentId: agentInfo?.id } }"></action-button>
                            <action-button :action="'edit'" :to="{ name: 'operation.mlo.agents.edit', params: { agentId: agentInfo?.id } }"></action-button>
                            <action-button @click="deleteAgent(agentInfo?.id)" :action="'delete'"></action-button>
                            <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: agentInfo.subject_type,subject_id: agentInfo.id } }"></action-button>
                        </td>
                    </tr>
                </tbody>
                <tfoot v-if="!agents?.data?.length" class="bg-white dark:bg-gray-800">
                    <tr v-if="isLoading">
                        <td colspan="7">Loading...</td>
                    </tr>
                    <tr v-else-if="!agents?.data?.length">
                        <td colspan="7">No agents found.</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- Pagination -->
        <Paginate :data="agents" to="operation.mlo.agents.index" :page="page"></Paginate>
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
        @apply tab text-left break-all;
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
