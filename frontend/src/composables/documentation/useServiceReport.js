import nprogress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import NProgress from "nprogress";
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import Error from "../../services/error";

export default function useServiceReport() {
    const router = useRouter();
    const $loading = useLoading();
    const voyageInfo = ref({});
    const serviceReportInfo = ref({});
    const error = ref([]);
    const isLoading = ref(false);

    const formParams = ref( {
        service: '',
        vessel_id: '',
        voyage: '',
        duration: 7,
        type: '',
        from_date: '',
        till_date: '',
    });

    async function getServiceReport(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        try {
            let { data } = await Api.post(
                "documentation/service-report",
                form
            );
            serviceReportInfo.value = await data.value;
        } catch {
            error.value = Error.showError(error);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        formParams,
        voyageInfo,
        serviceReportInfo,
        getServiceReport,
        isLoading,
        error,
    };
}
