import NProgress from "nprogress";
import { reactive, ref } from "vue";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';


export default function useCopino() {
    const copino = reactive({
        file: null,
        start: 12,
        voyage_id: null,
    });
    const allCopinos = ref([]);
    const notification = useNotification();
    const errors = ref(null);
    const isLoading = ref(false);

    async function process(copino) {
        NProgress.start();
        isLoading.value = true;

        try {
            let formData = new FormData();
            formData.append("file", copino.file);
            formData.append("start", copino.start);
            formData.append("voyage_id", copino.voyage_id);

            const { data, status } = await Api.post('/operation/copino', formData);
            allCopinos.value = data.value;
            notification.showSuccess(status,"Copino file uploaded successfully");
        } catch (error) {
            const { data, status } = error.response;
            data.errors = [data.message];
            errors.value = notification.showError(status, data);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }

        // Api.post("operation/copino", formData)
        //     .then((response) => {
        //         console.log(response.data);
        //         allCopinos.value = response.data.value;
        //     })
        //     .catch((error) => {
        //         error.value = Error.showError(error);
        //     })
        //     .finally(() => {
        //         isLoading.value = false;
        //         NProgress.done();
        //     });
    }

    return {
        copino,
        allCopinos,
        process,
        isLoading,
        errors,
    };
}
