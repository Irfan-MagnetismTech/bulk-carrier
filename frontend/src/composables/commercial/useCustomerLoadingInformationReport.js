import nprogress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import NProgress from "nprogress";
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import Error from "../../services/error";
import useNotification from "../useNotification";

export default function useCustomerLoadingInformationReport() {
    const router = useRouter();
    const $loading = useLoading();
    const customerLoadingInformations = ref({});
    const notification = useNotification();
    const voyages = ref({});
    const errors = ref(null);
    const isLoading = ref(false);

    const formParams = ref( {
        voyage_id: '',
        customer_id: '',
    });

    async function getCustomerLoadingInformation(formParams) {

        if (formParams.pol && !formParams.pod) {
            notification.showError(422,'', "Please select POD");
            return;
        }

        if (!formParams.pol && formParams.pod) {
            notification.showError(422,'', "Please select POL");
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        formParams.sector = formParams.pol+'-'+formParams.pod;

        if(!formParams.pol || !formParams.pod) {
            formParams.sector = '';
        }

        try {
            let { data } = await Api.post("commercial/master-report", formParams);
            customerLoadingInformations.value = await data;
        } catch (error) {
            const { data, status } = error.response;
            customerLoadingInformations.value = null;
            errors.value = notification.showError(status, data, "No Data Found");
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    return {
        formParams,
        customerLoadingInformations,
        getCustomerLoadingInformation,
        voyages,
        isLoading,
        errors,
    };
}
