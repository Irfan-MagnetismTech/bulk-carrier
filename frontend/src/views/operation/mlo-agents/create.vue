
<script setup>
import { onMounted, watchEffect } from 'vue';
import Title from '../../../services/title';
import useMloAgent from '../../../composables/operation/useMloAgent';
import Heading from '../../../components/Heading.vue';
import MloAgentForm from '../../../components/operation/MloAgentForm.vue';

const { agent, isLoading, storeAgent, errors } = useMloAgent();
const { setTitle } = Title();

setTitle('Create MLO Agents');
</script>
<template>
    <!-- Heading -->
    <heading label="Create MLO Agents" type="list" :to="{ name: 'operation.mlo.agents.index' }"></heading>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="storeAgent(agent)">
            <!-- Vessel Form -->
            <MloAgentForm v-model:form="agent" :errors="errors"></MloAgentForm>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="btn-submit-default">Create Agents</button>
        </form>
    </div>
</template>