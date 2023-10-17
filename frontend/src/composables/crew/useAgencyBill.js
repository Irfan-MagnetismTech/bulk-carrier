import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';

export default function useAgencyBill() {
    const router = useRouter();
    const agencyBills = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const agencyBill = ref( {
        business_unit: '',
        crw_agency_id: '',
        crw_agency_contract_id: '',
        applied_date: '',
        invoice_date: '',
        invoice_no: '',
        invoice_type: '',
        invoice_currency: '',
        invoice_amount: '',
        grand_total: '',
        discount: '',
        net_amount: '',
        remarks: '',
        crwAgencyBillLines: [
            {
                particular: '',
                description: '',
                per: '',
                quantity: '',
                rate: '',
                amount: '',
            }
        ]
    });

    const indexPage = ref(null);
    const indexBusinessUnit = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);

    async function getAgencyBills(page,businessUnit) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        indexPage.value = page;
        indexBusinessUnit.value = businessUnit;

        try {
            const {data, status} = await Api.get('/crw/crw-agency-bills',{
                params: {
                    page: page || 1,
                    business_unit: businessUnit,
                },
            });
            agencyBills.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function storeAgencyBill(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/crw/crw-agency-bills', form);
            agencyBill.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.agencyBills.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showAgencyBill(agencyBillId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/crw/crw-agency-bills/${agencyBillId}`);
            agencyBill.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateAgencyBill(form, agencyBillId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post(
                `/crw/crw-agency-bills/${agencyBillId}`,
                form
            );
            agencyBill.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "crw.agencyBills.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteAgencyBill(agencyBillId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/crw/crw-agency-bills/${agencyBillId}`);
            notification.showSuccess(status);
            await getAgencyBills(indexPage.value, indexBusinessUnit.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        agencyBills,
        agencyBill,
        getAgencyBills,
        storeAgencyBill,
        showAgencyBill,
        updateAgencyBill,
        deleteAgencyBill,
        isLoading,
        errors,
    };
}
