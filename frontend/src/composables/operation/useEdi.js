import NProgress from "nprogress";
import { reactive, ref } from "vue";
import Api from "../../apis/Api";
import Error from "../../services/error";
import useNotification from '../../composables/useNotification.js';


export default function useEdi() {
    const edi = reactive({
        voyage_id: null,
        file: null,
    });
    const allEdi = ref([]);
    const errors = ref(null);
    const isLoading = ref(false);
    const notification = useNotification();


    async function process(edi) {
        try {
            NProgress.start();
            isLoading.value = true;
            
            let formData = new FormData();
            
            formData.append("file", edi.file);
            formData.append("voyage_id", edi.voyage_id);

            const { data } = await Api.post("operation/read-edi", formData);
            allEdi.value = data.value.rows;
            console.log(data.message);
        } catch (error) {
            // error.value = Error.showError(error);
            const { data, status } = error.response;
            data.errors = [data.message,data.value];
            errors.value = notification.showError(status, data);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    return {
        edi,
        allEdi,
        process,
        isLoading,
        errors,
    };
}
