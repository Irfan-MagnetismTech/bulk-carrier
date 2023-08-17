<script setup>
import Error from "../Error.vue";
import Editor from '@tinymce/tinymce-vue';
import usePort from '../../composables/usePort';
import { ref, watch, onMounted } from 'vue';

const { portName, getPortsByNameOrCode } = usePort();
const props = defineProps({
    form: { type: Object, required: true },
    errors: { type: [Object, Array], required: false },
});

function fetchOptions(search, loading) { 
    getPortsByNameOrCode(search, props.form.service_id);
}

watch(() => props.form.port_origin_name, (value) => {
  if (value) {
    props.form.port = value.code;
  }
}, {deep: true});

props.form.status = 'inactive';
props.form.type = 'departure';
</script>

<template>
    <div class="input-group">
        <label class="label-group">
            <span class="label-item-title">
                Type
                <span class="text-red-500">*</span>
            </span>
            <select v-model="form.type" required class="label-item-input">
                <option value="departure">Export Advisory</option>
                <option value="arrival">Import Advisory</option>
                <option value="invoice">Invoice Notification</option>
            </select>
            <Error v-if="errors?.type" :errors="errors.type" />
        </label>
        <label class="label-group">
            <span class="label-item-title">
                Status
                <span class="text-red-500">*</span>
            </span>
            <select class="label-item-input" v-model="form.status">
                <option value="inactive">Inactive</option>
                <option value="active">Active</option>
            </select>
            <Error v-if="errors?.status" :errors="errors.status" />
        </label>
        <label class="label-group">
            <span class="label-item-title">Port <span class="text-red-500">*</span></span>
            <v-select v-model="form.port_origin_name" :id="'port' + index" @search="fetchOptions" value="id" :options="portName" label="code_name" placeholder="Enter Port Code or Name" class="mt-1 placeholder-gray-600 w-full"></v-select>
            <input type="hidden" v-model="form.port" name="port_code" :id="'port_code' + index" />
          </label>
    </div>
    <div class="input-group">
        <label class="label-group">
            <span class="label-item-title">
                Title
                <span class="text-red-500">*</span>
            </span>
            <input type="text" v-model="form.title" required placeholder="Enter Title" class="label-item-input" />
            <Error v-if="errors?.title" :errors="errors.title" />
        </label>
    </div>
    <div class="input-group">
        <label class="label-group">
            <span class="label-item-title">
                Template Body
                <span class="text-red-500">*</span>
            </span>
            <editor v-model="form.body" class="block w-full text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" api-key="wljvu7gtfjb8h5ou2rcxw8d5tykej98zy10x8ot83jclsm3o" />
            <Error v-if="errors?.body" :errors="errors.body" />
        </label>
    </div>
</template>

<style lang="postcss" scoped>
.input-group {
    @apply flex flex-col justify-center w-full h-full md:flex-row md:gap-2;
}
.label-group {
    @apply block w-full mt-3 text-sm;
}
.label-item-title {
    @apply text-gray-700 dark:text-gray-300;
}
.label-item-input {
    @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
}
.form-input {
    @apply block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
}
</style>