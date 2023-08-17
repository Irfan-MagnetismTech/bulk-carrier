
<script setup>
import { onMounted, watchEffect } from 'vue';
import Title from '../../../services/title';
import useAdvisory from '../../../composables/operation/useAdvisory';
import Heading from '../../../components/Heading.vue';


const { externalEmail, isLoading, updateHrlinesCC, getHrlinesCC, errors } = useAdvisory();
const { setTitle } = Title();

setTitle('Update External Email');

onMounted(() => {
    getHrlinesCC();
});


</script>
<template>
    <!-- Heading -->
    <heading label="Update Emails" type="list" :to="{ name: 'operation.mlo.agents.external-email' }"></heading>
    <code class="mb-2">
        Note: This action will update all the HRLines CC Email addresses of every MLO Local Agents.
    </code>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="updateHrlinesCC(externalEmail)">
            <label for="">HRLines CC</label>
            <textarea v-model="externalEmail.hrlines_cc_email_advisory" class="form-input h-32"></textarea>

            <label for="">External Export Email</label>
            <textarea v-model="externalEmail.external_export_receiver" class="form-input h-32"></textarea>

            <label for="">External Import Email</label>
            <textarea v-model="externalEmail.external_import_receiver" class="form-input h-32"></textarea>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="btn-submit-default">Update</button>
        </form>
    </div>
</template>
<style lang="postcss" scoped>
.form-input {
  @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
}
</style>