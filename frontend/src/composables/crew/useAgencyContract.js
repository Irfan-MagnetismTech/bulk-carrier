import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';

export default function useAgencyContract() {
    const router = useRouter();
    const agencyContracts = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const agencyContract = ref( {
        crw_agency_id: '',
        billing_cycle: '',
        billing_currency: '',
        validity_from: '',
        validity_till: '',
        service_offered: '',
        terms_and_conditions: '',
        remarks: '',
        attachment: '',
        account_holder_name: '',
        bank_name: '',
        bank_address: '',
        account_no: '',
        swift_code: '',
        business_unit: '',
    });

    const indexPage = ref(null);
    const indexBusinessUnit = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);

    async function getAgencyContracts(page,businessUnit) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        indexPage.value = page;
        indexBusinessUnit.value = businessUnit;

        try {
            const {data, status} = await Api.get('/crw/crw-agency-contracts',{
                params: {
                    page: page || 1,
                    business_unit: businessUnit,
                },
            });
            agencyContracts.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function storeAgencyContract(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        let formData = new FormData();
        formData.append('attachment', form.attachment);
        formData.append('data', JSON.stringify(form));

        try {
            const { data, status } = await Api.post('/crw/crw-agency-contracts', formData);
            agencyContract.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.agencyContracts.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showAgencyContract(agencyContractId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/crw/crw-agency-contracts/${agencyContractId}`);
            agencyContract.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateAgencyContract(form, agencyContractId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        let formData = new FormData();
        formData.append('attachment', form.attachment);
        formData.append('data', JSON.stringify(form));
        formData.append('_method', 'PUT');

        try {
            const { data, status } = await Api.post(
                `/crw/crw-agency-contracts/${agencyContractId}`,
                formData
            );
            agencyContract.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.agencyContracts.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteAgencyContract(agencyContractId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/crw/crw-agency-contracts/${agencyContractId}`);
            notification.showSuccess(status);
            await getAgencyContracts(indexPage.value, indexBusinessUnit.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        agencyContracts,
        agencyContract,
        getAgencyContracts,
        storeAgencyContract,
        showAgencyContract,
        updateAgencyContract,
        deleteAgencyContract,
        isLoading,
        errors,
    };
}
