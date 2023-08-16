import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function useCustomerVehicle() {
    const router = useRouter();
    const customerVehicles = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const customerVehicle = ref( {
        customer: '',
        customer_id: '',
        customer_name: '',
        vehicle_type: '',
        vehicle_number: '',
        assigned_person: '',
        assigned_person_contact: '',
        credit_limit: '',
        status: '',
    });

    const errors = ref('');
    const isLoading = ref(false);

    async function getCustomerVehicles(customerId) {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get(`/configuration/get-customer-vehicles/${customerId}`);
            customerVehicles.value = data.value;
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

    async function storeCustomerVehicle(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/configuration/customer-vehicles', form);
            customerVehicle.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "configuration.customer-vehicle.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showCustomerVehicle(customerVehicleId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/configuration/customer-vehicles/${customerVehicleId}`);
            customerVehicle.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateCustomerVehicle(form, customerVehicleId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/configuration/customer-vehicles/${customerVehicleId}`,
                form
            );
            customerVehicle.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "configuration.customer-vehicle.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteCustomerVehicle(customerVehicleId) {

        if (!confirm('Are you sure you want to delete this customerVehicle?')) {
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/configuration/customer-vehicles/${customerVehicleId}`);
            notification.showSuccess(status);
            await getCustomerVehicles();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        customerVehicles,
        customerVehicle,
        getCustomerVehicles,
        storeCustomerVehicle,
        showCustomerVehicle,
        updateCustomerVehicle,
        deleteCustomerVehicle,
        isLoading,
        errors,
    };
}
