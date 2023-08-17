import nprogress from "nprogress";
import NProgress from "nprogress";
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import Error from "../../services/error";

export default function useSlotUsesStatementReport() {
    const router = useRouter();
    const customerInfo = ref({});
    const voyages = ref({});
    const error = ref([]);
    const isLoading = ref(false);

    const formParams = ref( {
        customer_id: '',
        voyage_no: '',
    });

    async function slotUsesStatementReport(form) {
        NProgress.start();
        isLoading.value = true;
        try {
            let { data } = await Api.post(
                "commercial/slot-uses-statement",
                form
            );
            customerInfo.value = await data;
        } catch {
            error.value = Error.showError(error);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    return {
        formParams,
        customerInfo,
        slotUsesStatementReport,
        voyages,
        isLoading,
        error,
    };
}
