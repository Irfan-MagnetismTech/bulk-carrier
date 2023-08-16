import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function useCustomer() {
    const router = useRouter();
    const customers = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const customer = ref( {
        name: '',
        code: '',
        address: '',
        official_email: '',
        official_contact: '',
        billing_email: '',
        billing_address: '',
        contact_person: '',
        contact_person_mobile: '',
        opening_date: '',
        opening_due: '',
        deposited_amount: '',
        credit_limit: '',
        credit_days: '',
        remarks: '',
        is_corporate: '',
        status: 1,
        returned_amount: ''
    });
    const creditInfo = ref('');

    const errors = ref('');
    const isLoading = ref(false);
    const customerVehicles = ref();

    async function getCustomers() {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/configuration/customers');
            customers.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function storeCustomer(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/configuration/customers', form);
            customer.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "configuration.customer.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showCustomer(customerId) {

        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        // isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/configuration/customers/${customerId}`);
            customer.value = data.value;
            creditInfo.value = data.credit_info;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            // isLoading.value = false;
        }
    }

    async function updateCustomer(form, customerId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/configuration/customers/${customerId}`,
                form
            );
            customer.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "configuration.customer.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteCustomer(customerId) {

        if (!confirm('Are you sure you want to delete this customer?')) {
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/configuration/customers/${customerId}`);
            notification.showSuccess(status);
            await getCustomers();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function searchCustomer(searchParam, loading) {

        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        // isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/common/search-customer`, {params: { searchParam: searchParam }});
            customers.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            // isLoading.value = false;
            loading(false)
        }
    }

    async function searchCustomerByCode(searchParam, loading) {

        try {
            const { data, status } = await Api.get(`/common/search-customer-by-code`, {params: { searchParam: searchParam }});
            customers.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loading(false);
        }
    }

    async function getCustomerVehicles(customerId) {

        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        // isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/configuration/get-customer-vehicles/${customerId}`);
            customerVehicles.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loading(false);
            // isLoading.value = false;
        }
    }

    return {
        customers,
        customer,
        creditInfo,
        searchCustomer,
        searchCustomerByCode,
        getCustomers,
        storeCustomer,
        showCustomer,
        updateCustomer,
        deleteCustomer,
        customerVehicles,
        getCustomerVehicles,
        isLoading,
        errors,
    };
}
