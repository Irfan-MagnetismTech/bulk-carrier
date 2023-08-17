import nprogress from "nprogress";
import NProgress from "nprogress";
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import Error from "../../services/error";
import useNotification from '../../composables/useNotification.js';

export default function useParticularCustomerReport() {
    const router = useRouter();
    const voyageInfo = ref({});
    const customerLoadInfo = ref({});
    const notification = useNotification();
    const errors = ref([]);
    const isLoading = ref(false);

    const formParams = ref( {
        customer_id: null,
        service_code: '',
        bound: '',
        vessel_name: null,
        voyage: null,
    });

    async function particularCustomerReport(form) {
        NProgress.start();
        isLoading.value = true;
        try {
            let {data, status} = await Api.post(
                "documentation/particular-customer-report",
                form
            );
            customerLoadInfo.value = await data.value;
            if (status === 204) {
                notification.showError(status);
            }

        } catch (error) {
            const { data, status } = error.response;
            //notification.showError(status);
            errors.value = notification.showError(status, data);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    return {
        formParams,
        voyageInfo,
        customerLoadInfo,
        particularCustomerReport,
        isLoading,
        errors,
    };
}
