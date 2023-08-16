import { useTitle } from '@vueuse/core';
import { ref } from 'vue';

export default function Title() {
    const title = useTitle('');
    const divider = ref(" | ");
    const suffix = ref('QC Trading Limited');

    function setTitle(value) {
        if(value === null || value === undefined || value === '') {
            title.value = suffix.value;
        } else {
            title.value = value + divider.value + suffix.value;
        }
    }

    return {
        title,
        setTitle,
    }
}