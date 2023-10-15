import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';

export default function useAgency() {
    const router = useRouter();
    const agencies = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const agency = ref( {
        name: '',
        legal_name: '',
        tax_identification: '',
        business_license_no: '',
        company_reg_no: '',
        address: '',
        phone: '',
        email: '',
        website: '',
        logo: '',
        country: '',
        crwAgencyContactPeople: [
            {
                name: '',
                contact_no: '',
                email: '',
                position: '',
                purpose: '',
            }
        ]
    });

    const errors = ref(null);
    const isLoading = ref(false);

    async function getAgencies(page) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/crw/crw-agencies',{
                params: {
                    page: page || 1,
                },
            });
            agencies.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function storeAgency(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/crw/crw-agencies', form);
            agency.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.agencies.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showAgency(agencyId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/crw/crw-agencies/${agencyId}`);
            agency.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateAgency(form, agencyId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/crw/crw-agencies/${agencyId}`,
                form
            );
            agency.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.agencies.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteAgency(agencyId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/crw/crw-agencies/${agencyId}`);
            notification.showSuccess(status);
            await getAgencies();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        agencies,
        agency,
        getAgencies,
        storeAgency,
        showAgency,
        updateAgency,
        deleteAgency,
        isLoading,
        errors,
    };
}
