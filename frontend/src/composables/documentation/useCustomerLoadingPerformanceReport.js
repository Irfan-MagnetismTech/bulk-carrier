import nprogress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import NProgress from "nprogress";
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import Error from "../../services/error";

export default function useCustomerLoadingPerformanceReport() {
    const router = useRouter();
    const $loading = useLoading();
    const voyageInfo = ref({});
    const performanceReportInfo = ref({});
    const error = ref([]);
    const isLoading = ref(false);

    const formParams = ref( {
        customer_id: '',
        service_code: '',
        bound: '',
        vessel_name: '',
        voyage: '',
    });

    async function getCustomerLoadingPerformanceReport(form) {

        if(!form.customer_id || !form.service_code || !form.bound) {
            alert('Please select customer, service and bound');
            return;
        }

        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        try {
            let { data } = await Api.post(
                "documentation/customer-loading-performance",
                form
            );
            performanceReportInfo.value = await data.value;
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
        performanceReportInfo,
        getCustomerLoadingPerformanceReport,
        isLoading,
        error,
    };
}
