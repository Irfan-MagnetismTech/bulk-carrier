import { onMounted, ref, watch } from "vue";
import { useStore } from "vuex";

export default function useDropZone() {
    const store = useStore();
    const dragActive = ref(false);
    const droppedFile = ref(null);

    const toggleActive = () => {
        if (droppedFile.value == null) {
            dragActive.value = !dragActive.value;
        }
    };

    const drop = (event) => {
        droppedFile.value = event.dataTransfer.files[0];
    };

    const selectedFile = (event) => {
        droppedFile.value = event.target.files[0];
        dragActive.value = true;
        store.dispatch("addDropZoneFile", droppedFile.value);
    };

    const clearDropped = () => {
        droppedFile.value.value = '';
        droppedFile.value = null;
    };

    onMounted(() => {
        store.dispatch("addDropZoneFile", null);
    });

    watch(droppedFile, () => {
        store.dispatch("addDropZoneFile", droppedFile.value);
      })

    return {
        dragActive,
        droppedFile,
        toggleActive,
        drop,
        selectedFile,
        clearDropped,
    }
}