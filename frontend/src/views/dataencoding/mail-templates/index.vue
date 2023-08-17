
<script setup>
import { onMounted, watchEffect } from 'vue';
import Title from '../../../services/title';
import useMailTemplate from '../../../composables/dataencoding/useMailTemplate';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Heading from '../../../components/Heading.vue';

const { setTitle } = Title();

setTitle('E-mail Templates');

const { isLoading, templates, getTemplate, deleteTemplate } = useMailTemplate();

onMounted(() => {
    getTemplate();
}); 
</script>
<template>
    <!-- Heading -->
    <heading label="Email Templates" type="create" :to="{ name: 'dataencoding.mail-templates.create' }"></heading>
    <!-- Table -->
    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Port</th>
                        <th>Type</th>
                        <th>Title</th>
                        <th>Updated By</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody v-if="templates?.length">
                    <tr v-for="(template, index) in templates" :key="template?.id">
                        <td>{{ index+1 }}</td>
                        <td>{{ template?.port }}</td>
                        <td class="capitalize">{{ template?.type.replace(/_/gi," ") }}</td>
                        <td>{{ template?.title }}</td>
                        <td class="capitalize">{{ template?.user?.name }}</td>
                        <td class="capitalize">{{ template?.status }}</td>
                        <td class="items-center justify-center space-x-2 text-gray-600">
                            <action-button :action="'show'" :to="{ name: 'dataencoding.mail-templates.show', params: { templateId: template.id } }"></action-button>
                            <action-button :action="'edit'" :to="{ name: 'dataencoding.mail-templates.edit', params: { templateId: template.id } }"></action-button>
                            <action-button @click="deleteTemplate(template.id)" :action="'delete'"></action-button>
                          <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: template.subject_type,subject_id: template.id } }"></action-button>
                        </td>
                    </tr>
                </tbody>
                <tfoot v-if="!templates?.length" class="bg-white dark:bg-gray-800">
                    <tr v-if="isLoading">
                        <td colspan="8">Loading...</td>
                    </tr>
                    <tr v-else-if="!templates?.length">
                        <td colspan="8">No E-mail templates found.</td>
                    </tr>
                </tfoot>
            </table>
        </div>
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
