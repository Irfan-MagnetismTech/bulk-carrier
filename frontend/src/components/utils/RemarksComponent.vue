<script setup>

defineProps(['modelValue', 'maxlength', 'fieldLabel'])
defineEmits(['update:modelValue'])
</script>
<template>
    <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">{{ fieldLabel }} </span> 
        (<span :class="{ 'text-red-600' : modelValue?.length == maxlength }" class="font-semibold">{{ (modelValue?.length) ? modelValue?.length : 0 }}</span>/{{ maxlength }} Characters)

        <textarea type="text" 
        :maxlength="maxlength"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        :placeholder="fieldLabel" 
        class="form-input" 
        autocomplete="off"></textarea>
        
    </label>
</template>