<script setup>
import { ref, reactive, toRefs } from 'vue';
import { onClickOutside, onKeyStroke, useVModel } from '@vueuse/core';
import { useFuse } from '@vueuse/integrations/useFuse';

const props = defineProps({
    options: {
        type: [Object, Array],
        required: true,
    },
    labelValue: {
        type: Object,
        required: true,
        default: () => ({
            label: '',
            value: '',
        }),
    },
    value: {
        type: [String, Number],
        required: false,
        default: 'Test',
    },
    searchableKeys: {
        type: Array,
        required: false,
        default: [],
    },
    placeholder: {
        type: String,
        required: false,
        default: '-- Select an option --',
    },
    disabled: {
        type: Boolean,
        required: false,
        default: false,
    },
    searchable: {
        type: Boolean,
        required: false,
        default: true,
    },
    searchPlaceholder: {
        type: String,
        required: false,
        default: 'Search',
    },
    resultLimit: {
        type: Number,
        required: false,
        default: 10,
    },
});
const propsData = toRefs(props);
const isVisible = ref(false);
const selected = reactive({ value: '', label: '' });
const select = ref(null);
const hiddenValue = ref('');
const search = ref('');
const { results } = useFuse(search, propsData.options, {
    fuseOptions: {
        keys: propsData.searchableKeys.value,
        threshold: 1,
        isCaseSensitive: false,
    },
    resultLimit: propsData.resultLimit,
    matchAllWhenSearchEmpty: true,
});

const toggleVisible = () => {
    isVisible.value = !isVisible.value;
}

const hide = () => {
    isVisible.value = false;
    select.value.blur();
};

const selectItem = (index) => {
    selected.label = results.value[index].item[propsData.labelValue.value.label];
    selected.value = results.value[index].item[propsData.labelValue.value.label];
    propsData.value.value = results.value[index].item[propsData.labelValue.value.value];
    hide();
}

const clearSelection = () => {
    selected.label = '';
    selected.value = '';
    hide();
};

onClickOutside(select, () => {
    hide();
});

onKeyStroke(['Enter', 'Escape', 'Tab'], () => {
    hide();
});
</script>

<template>
    <div class="wrapper" ref="select">
        <div class="flex items-center justify-between h-full px-3" @click="toggleVisible()">
            <p class="placeholder" :class="{ 'selected': selected.value }">{{ selected.value != "" ? selected.label : propsData.placeholder.value }}</p>
            <svg xmlns="http://www.w3.org/2000/svg" class="arrow" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </div>
        <div v-if="isVisible" class="list-wrapper">
            <input type="text" class="z-10 input" v-model="search" :placeholder="propsData.searchPlaceholder.value" />
            <div class="list">
                <div class="list-item" @click="clearSelection()">{{ propsData.placeholder.value }}</div>
                <div class="list-item" v-for="(result, index) in results" :key="result.item.id" @click="selectItem(index)">{{ result.item.title }}</div>
            </div>
        </div>
    </div>
</template>

<style lang="postcss" scoped>
.wrapper {
    @apply relative w-full py-2 mt-1 border border-gray-500 rounded cursor-pointer   focus:border-purple-400 focus:outline-none focus:shadow-outline-purple ;
}
.input {
    @apply w-full mb-3 text-sm rounded   focus:border-purple-400 focus:outline-none focus:shadow-outline-purple ;
}

.list-wrapper {
    @apply absolute w-full px-6 pt-4 top-9 z-50 border border-gray-500 rounded-b-lg shadow-xl bg-gray-50 ;
}

.list {
    @apply w-full overflow-y-auto divide-y rounded-b max-h-48 divide-double;
}

.list-item {
    @apply py-1.5 divide-gray-300 px-2 hover:bg-blue-500 hover:text-gray-100;
}

.arrow {
    @apply w-5 h-5 text-gray-500 opacity-90;
}

.placeholder {
    @apply max-w-md overflow-x-auto text-sm text-gray-500 select-none whitespace-nowrap;
}

.selected {
    @apply text-gray-800 ;
}
</style>