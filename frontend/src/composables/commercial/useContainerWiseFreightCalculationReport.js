import nprogress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import Error from "../../services/error";
import useNotification from "../useNotification";

export default function useContainerWiseFreightCalculationReport() {
    const router = useRouter();
    const freightCalculation = ref({});
    const $loading = useLoading();
    const notification = useNotification();
    const voyages = ref({});
    const errors = ref(null);
    const isLoading = ref(false);

    const formParams = ref( {
        voyage_id: '',
        customer_id: '',
    });

    async function getFreightCalculation(formParams) {
        //NProgress.start();
        if(!formParams.voyage_id || !formParams.customer_id) {
            alert("Please select voyage and customer");
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            let { data } = await Api.post("finance/customer-each-container-amount", formParams);
            freightCalculation.value = await data;
        } catch (error) {
            const { data, status } = error.response;
            freightCalculation.value = null;
            errors.value = notification.showError(status, data, "No container found");
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    return {
        formParams,
        freightCalculation,
        getFreightCalculation,
        voyages,
        isLoading,
        errors,
    };
}
