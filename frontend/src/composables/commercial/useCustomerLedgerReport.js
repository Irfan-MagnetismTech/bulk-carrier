import nprogress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import NProgress from "nprogress";
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import Error from "../../services/error";
import useNotification from "../useNotification";

export default function useCustomerLedgerReport() {
    const router = useRouter();
    const $loading = useLoading();
    const customerLedgers = ref({});
    const notification = useNotification();
    const voyages = ref({});
    const errors = ref(null);
    const isLoading = ref(false);

    const formParams = ref( {
        customer_id: null,
        from_date: null,
        till_date: null,
    });

    async function getCustomerLedger(formParams) {
        //console.log(formParams); 
        //NProgress.start();

        if(!formParams.customer_id) {
            alert("Please select a customer.");
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            let { data } = await Api.post("finance/customer-ledger", formParams);
            customerLedgers.value = await data;
        } catch (error) {
            const { data, status } = error.response;
            customerLedgers.value = null;
            errors.value = notification.showError(status, data, "No Customer Found.");
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    return {
        formParams,
        getCustomerLedger,
        customerLedgers,
        isLoading,
        errors,
    };
    
}
