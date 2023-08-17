import nprogress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import NProgress from "nprogress";
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import Error from "../../services/error";
import useNotification from "../useNotification";

export default function useVoyageJobStatusReport() {
    const router = useRouter();
    const $loading = useLoading();
    const voyageJobStatus = ref({});
    const notification = useNotification();
    const voyages = ref({});
    const errors = ref(null);
    const isLoading = ref(false);

    const formParams = ref( {
        voyage_id: '',
    });

    async function getVoyageJobStatus(formParams) {
        //console.log(formParams); 
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            let { data } = await Api.post("finance/voyage-job-status", formParams);
            voyageJobStatus.value = await data;
        } catch (error) {
            const { data, status } = error.response;
            voyageJobStatus.value = null;
            errors.value = notification.showError(status, data, "No Voyage found");
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    return {
        formParams,
        getVoyageJobStatus,
        voyageJobStatus,
        isLoading,
        errors,
    };
}
