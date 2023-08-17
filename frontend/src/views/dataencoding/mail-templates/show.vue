
<script setup>
import { onMounted, watchEffect } from 'vue';
import { useRoute } from 'vue-router';
import Title from '../../../services/title';
import useMailTemplate from '../../../composables/dataencoding/useMailTemplate';
import Heading from '../../../components/Heading.vue';
import MailTemplateForm from '../../../components/dataencoding/MailTemplateForm.vue';

const { setTitle } = Title();

setTitle('View E-mail Template');

const route = useRoute();
const templateId = route.params.templateId;
const { mailTemplate, isLoading, showMailTemplate, updateMailTemplate, errors } = useMailTemplate();

onMounted(() => {
    showMailTemplate(templateId);
});
</script>
<template>
    <!-- Heading -->
    <heading label="View E-mail Template" type="list" :to="{ name: 'dataencoding.mail-templates.index' }"></heading>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800" v-html="mailTemplate.body">
    </div>
</template>