import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api.js";
import useNotification from '../useNotification.js';

export default function useCashRequisition() {
    const router = useRouter();
    const cashRequisitions = ref([]);
    const $loading = useLoading();
    const notification = useNotification();

    const cashRequisition = ref( {
        costCenter : '', 
        acc_cost_center_name : '', 
        acc_cost_center_id : '',

        scmPr : '', 
        scm_pr_ref_no : '', 
        scm_pr_id : '',

        applied_date : '',
        total_amount : 0,
        purpose : '',
        business_unit : '',

        accCashRequisitionLines: [
            {
                particular: '',
                amount: '',
                remarks: '',
            }
        ]
    });

    // const indexPage = ref(null);
    // const indexBusinessUnit = ref(null);
    const filterParams = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);
    const isTableLoading = ref(false);

    async function getCashRequisitions(filterOptions) {

        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        // isLoading.value = true;
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

        // indexPage.value = filterOptions.page;
        // indexBusinessUnit.value = filterOptions.business_unit;
        filterParams.value = filterOptions;

        try {
            const {data, status} = await Api.get('/acc/acc-cash-requisitions',{
                params: {
                    page: filterOptions.page || 1,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
                }
            });
            cashRequisitions.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            // loader.hide();
            // isLoading.value = false;
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

    async function storeCashRequisition(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/acc/acc-cash-requisitions', form);
            cashRequisition.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "acc.cash-requisitions.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showCashRequisition(cashRequisitionId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/acc/acc-cash-requisitions/${cashRequisitionId}`);
            cashRequisition.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateCashRequisition(form, cashRequisitionId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(`/acc/acc-cash-requisitions/${cashRequisitionId}`, form);
            cashRequisition.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "acc.cash-requisitions.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteCashRequisition(cashRequisitionId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/acc/acc-cash-requisitions/${cashRequisitionId}`);
            notification.showSuccess(status);
            await getCashRequisitions(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        cashRequisitions,
        cashRequisition,
        getCashRequisitions,
        storeCashRequisition,
        showCashRequisition,
        updateCashRequisition,
        deleteCashRequisition,
        isLoading,
        isTableLoading,
        errors,
    };
}
