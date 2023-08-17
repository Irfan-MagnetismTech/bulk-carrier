<script setup>
import { onMounted, watchEffect } from 'vue';
import Heading from '../../../components/Heading.vue';
import MailTemplateForm from '../../../components/dataencoding/MailTemplateForm.vue';
import useMailTemplate from '../../../composables/dataencoding/useMailTemplate';

const { notification, isLoading, errors, mailTemplate, storeMailTemplate } = useMailTemplate();
</script>

<template>
    <!-- Heading -->
    <heading label="Create Email Templates" type="list" :to="{ name: 'dataencoding.mail-templates.index' }"></heading>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="storeMailTemplate(mailTemplate)">
            <!-- Vessel Form -->
            <mail-template-form v-model:form="mailTemplate" :errors="errors"></mail-template-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="btn-submit-default">Create Email Template</button>
        </form>
    </div>
</template>