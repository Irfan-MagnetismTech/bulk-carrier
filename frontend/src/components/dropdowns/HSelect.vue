<script setup>
import { ref, toRefs } from 'vue';
import { onClickOutside } from '@vueuse/core'

const props = defineProps({
    options: {
        type: Array,
        default: () => ['Canada', 'United States']
    },
    modelValue: {
        type: String,
        default: ''
    },
});

const emit = defineEmits(['update:modelValue']);
const propsData = toRefs(props);
const selected = ref('');
const select = ref(null);
const isItemListShowing = ref(false);

// Emit update once changes are made
function onChanged(e) {
    emit('update:modelValue', e.currentTarget.value);
}

// Select item with value by index
function selectItem(index) {
    if (index != -1) {
        const item = propsData.options.value[index]['title'];
        selected.value = item;
    } else {
        selected.value = '';
        propsData.modelValue.value = '';
    }

    hideItemList();
}

// Clear selected item
function clearItemList() {
    selected.value = null;
    hideItemList();
}

// Toggle show-hide options
function toggleItemList() {
    isItemListShowing.value = !isItemListShowing.value;
}

// Hide the item list
function hideItemList() {
    isItemListShowing.value = false;
}

// Hide item list when clicked outside
onClickOutside(select, () => hideItemList());
</script>

<template>
    <section class="select-wrapper" ref="select">
        <div class="select" @click="toggleItemList()">
            <!-- Placeholder -->
            <div>
                <span v-if="propsData.modelValue.value !== null" class="text-gray-600 dark-disabled:text-gray-200">{{ selected }}</span>
                <span v-else class="dark-disabled:text-slate-500">-- Please select an option --</span>
            </div>
            <!-- Icons -->
            <div class="ml-2 flex items-center justify-center">
                <button v-if="selected" @click="clearItemList()" class="rounded-md focus:outline-none focus:shadow-outline-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>
        <!-- Search & Items -->
        <div class="select-item-wrapper" v-show="isItemListShowing">
            <!-- Search -->
            <input type="search" class="select-search" placeholder="Search..." />
            <!-- Items -->
            <label @click="selectItem(-1)" for="-1" class="select-item">
                <input type="radio" id="-1" @input="onChanged" name="option" v-model="modelValue" :value="''" class="appearance-none" />
                <span class="text-xs">-- Select an item --</span>
            </label>
            <label v-for="(option, index) in options" :for="index" :key="index" class="select-item">
                <input type="radio" :id="index" @input="onChanged" name="option" v-model="modelValue" :value="option.id" class="hidden" />
                <div class="text-xs w-full py-1" @click="selectItem(index)">{{ option.title }}</div>
            </label>
        </div>
    </section>
</template>

<style lang="postcss" scoped>
@tailwind components;

@layer components {
    .select-wrapper {
        @apply relative block overflow-hidden rounded cursor-default border;
    }
    .select {
        @apply flex items-center justify-between p-2 text-xs text-gray-500 border rounded select-none dark-disabled:text-gray-400 cursor-default;
    }
    .select-item-wrapper {
        @apply flex flex-col max-h-64 gap-0.5 p-2 overflow-y-auto border rounded mt-1 cursor-default;
    }
    .select-search {
        @apply w-full p-2 px-2 mb-1 text-xs border rounded focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 dark-disabled:focus:ring-offset-gray-900 focus:ring-indigo-500 dark-disabled:bg-gray-800 dark-disabled:text-gray-200 transition-colors duration-75;
    }
    .select-item {
        @apply p-1 w-full text-gray-700 transition-colors duration-75 rounded cursor-pointer dark-disabled:text-gray-200 hover:bg-indigo-500 hover:text-white dark-disabled:hover:bg-gray-700 select-none;
    }
}
</style>