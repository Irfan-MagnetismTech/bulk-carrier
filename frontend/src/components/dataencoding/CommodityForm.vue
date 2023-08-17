<template>
    <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-3 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Code</span>
            <input type="text" v-model="form.code" required placeholder="Enter Port Code" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
        </label>
        <label class="block w-full mt-3 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Description</span>
            <input type="text" v-model="form.description" required placeholder="Enter Port Name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
        </label>
    </div>
</template>
<script setup>
const props = defineProps({
    form: {
        required: false,
        default: {
            code: '',
            descripton: '',
        }
    }
});
</script>