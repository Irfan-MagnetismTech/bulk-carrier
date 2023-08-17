
<script setup>
import { onMounted, watchEffect } from 'vue';
import { useRoute } from 'vue-router';
import Title from '../../../services/title';
import useMloAgent from '../../../composables/operation/useMloAgent';
import Heading from '../../../components/Heading.vue';
import MloAgentForm from '../../../components/operation/MloAgentForm.vue';

const route = useRoute();
const agentId = route.params.agentId;
const { agent, isLoading, showAgent, updateAgent, errors } = useMloAgent();
const { setTitle } = Title();

setTitle('Update MLO Agent');

onMounted(() => {
    showAgent(agentId);
});
</script>
<template>
    <!-- Heading -->
    <heading label="Update MLO Agent" type="list" :to="{ name: 'operation.mlo.agents.index' }"></heading>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="updateAgent(agent, agentId)">
            <!-- Vessel Form -->
            <MloAgentForm v-model:form="agent" :errors="errors"></MloAgentForm>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="btn-submit-default">Update Agent</button>
        </form>
    </div>
</template>