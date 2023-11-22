import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';

export default function useAgencyBill() {
    const router = useRouter();
    const agencyBills = ref([]);
    const isTableLoading = ref(false);
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

    const filterParams = ref(null);
    const errors = ref(null);
    const isLoading = ref(false);

    async function getAgencyBills(filterOptions) {

        let loader = null;
        if (!filterOptions.isFilter) {
            loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
            isLoading.value = true;
            isTableLoading.value = false;
        }
        else {
            isTableLoading.value = true;
            isLoading.value = false;
            loader?.hide();
        }

        filterParams.value = filterOptions;

        try {
            const {data, status} = await Api.get('/crw/crw-agency-bills',{
                params: {
                    page: filterOptions.page,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
                },
            });
            agencyBills.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            if (!filterOptions.isFilter) {
                loader?.hide();
                isLoading.value = false;
            }
            else {
                isTableLoading.value = false;
                loader?.hide();
            }
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
            const { data, status } = await Api.put(
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
            await getAgencyBills(filterParams.value);
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
        isTableLoading,
        isLoading,
        errors,
    };
}
