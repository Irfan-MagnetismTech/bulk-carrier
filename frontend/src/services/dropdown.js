import { onClickOutside, onKeyStroke } from '@vueuse/core';
import { useFuse } from '@vueuse/integrations/useFuse';
import { reactive, ref } from 'vue';

export default function useDropdown () {
    const isVisible = ref(false);
    const selected = reactive({ value: '', label: '' });
    const select = ref(null);
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
}