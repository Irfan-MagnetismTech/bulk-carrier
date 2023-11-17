<script setup>

defineProps(['modelValue', 'maxlength'])
defineEmits(['update:modelValue'])
</script>
<template>
    <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Remarks </span>
        <textarea type="text" 
        :maxlength="maxlength"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        placeholder="Remarks" 
        class="form-input" 
        autocomplete="off"></textarea>
        <p class="my-1">
            <span :class="{ 'text-red-600' : modelValue.length == maxlength }" class="font-semibold">{{ modelValue.length }}</span>/{{ maxlength }} Characters
        </p>
    </label>
</template>