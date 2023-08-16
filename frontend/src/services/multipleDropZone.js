import { onMounted, ref, watch } from "vue";
import { useStore } from "vuex";

export default function useMultipleDropZone() {
    const store = useStore();
    const dragActive = ref(false);
    const droppedFile = ref([]);

    const toggleActive = () => {
        if (droppedFile.value == null) {
            dragActive.value = !dragActive.value;
        }
    };

    const drop = (event) => {

        //loop through all files
        for (let i = 0; i < event.dataTransfer.files.length; i++) {
            droppedFile.value.push(event.dataTransfer.files[i]);
        }

       // droppedFile.value = event.dataTransfer.files[0];
    };

    const selectedFile = (event) => {

       //loop through all files
        for (let i = 0; i < event.target.files.length; i++) {
            droppedFile.value.push(event.target.files[i]);
            dragActive.value = true;
            store.dispatch("addDropZoneFile", droppedFile.value);
        }

        // droppedFile.value = event.target.files[0];
        // dragActive.value = true;
        // store.dispatch("addDropZoneFile", droppedFile.value);
    };

    const clearDropped = (index) => {

        droppedFile.value.splice(index, 1);
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