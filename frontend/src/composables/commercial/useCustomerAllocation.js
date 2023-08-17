import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import Error from "../../services/error";
import useNotification from "../useNotification";

export default function useCustomerAllocation() {
    const router = useRouter();
    const $loading = useLoading();
    const voyage = ref([]);
    const voyageCustomers = ref([]);
    const errors = ref(null);
    const isLoading = ref(false);
    const notification = useNotification();
    const formParams = ref( {
        voyage_id: '--Select an Option--',
    });
    const slotPartnerListParams = ref([]);

    async function getVoyageCustomer(form) {

        if(form.voyage_id === '--Select an Option--') {
            notification.showError(422,'','Please select a voyage');
            return;
        }

        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;
        try {
            await Api.post("commercial/get-voyage-customer-for-booking-allocation", form)
                .then((response) => {
                    voyageCustomers.value = response.data.value;
                })
        } catch {
            errors.value = Error.showError(error);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function assignSlotPartner(form) {
        NProgress.start();
        isLoading.value = true;
        try {
            const { data, status } = await Api.post('commercial/voyage-assign-customers', form);
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status,'', data.message);
           // errors.value = notification.showError(status, data);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function storeCustomerAllocation(form) {
        NProgress.start();
        isLoading.value = true;
        try {
            const { data, status } = await Api.post('commercial/store-customer-allocation', form);
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status,'', data.message);
            // errors.value = notification.showError(status, data);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    return {
        voyage,
        voyageCustomers,
        formParams,
        getVoyageCustomer,
        assignSlotPartner,
        storeCustomerAllocation,
        slotPartnerListParams,
        isLoading,
        errors,
    };
}
