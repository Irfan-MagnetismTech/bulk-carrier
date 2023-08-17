import nprogress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import NProgress from "nprogress";
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import Error from "../../services/error";

export default function useMonthWiseServiceReport() {
    const router = useRouter();
    const $loading = useLoading();
    const voyageInfo = ref({});
    const serviceLoadInfo = ref({});
    const error = ref([]);
    const isLoading = ref(false);

    const formParams = ref( {
        month: '',
        year: '',
        service: '',
    });

    async function monthWiseServiceReport(form) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        try {
            let { data } = await Api.post(
                "documentation/month-wise-service-report",
                form
            );
            serviceLoadInfo.value = await data.value;
        } catch {
            error.value = Error.showError(error);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    return {
        formParams,
        voyageInfo,
        serviceLoadInfo,
        monthWiseServiceReport,
        isLoading,
        error,
    };
}
