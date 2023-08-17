<script setup>
import Error from "../Error.vue";

const props = defineProps({
    form: { type: Object, required: true },
    errors: { type: [Object, Array], required: false },
});
</script>

<template>
    <!-- Name, Call Sign & IMO number -->
    <div class="input-group">
        <label class="label-group">
            <span class="label-item-title">
                Code
                <span class="text-red-500">*</span>
            </span>
            <input type="text" v-model.trim="form.code" required placeholder="Enter Currency Code" class="label-item-input" />
            <Error v-if="errors?.code" :errors="errors.code" />
        </label>
        <label class="label-group">
            <span class="label-item-title">
                Name
                <span class="text-red-500">*</span>
            </span>
            <input type="text" v-model.number="form.name" required placeholder="Enter Currency Name" class="label-item-input" />
            <Error v-if="errors?.name" :errors="errors.name" />
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