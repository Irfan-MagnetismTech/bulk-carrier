import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api.js";
import useNotification from '../useNotification.js';

export default function useBankReconciliation() {
    const router = useRouter();
    const bankReconciliations = ref([]);
    const isOpenReconciliationDateModal = ref(0);
    const $loading = useLoading();
    const notification = useNotification();
    const bankReconciliation = ref( {
        name: '',
        short_name: '',
        type: '',
        business_unit: '',
    });
    // const indexPage = ref(null);
    // const indexBusinessUnit = ref(null);
    const filterParams = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);
    const isTableLoading = ref(false);

    async function getBankReconciliations(filterOptions) {

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
            const {data, status} = await Api.get('/acc/acc-bank-reconciliations',{
                params: {
                    page: filterOptions.page || 1,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
                },
            });
            bankReconciliations.value = data.value;
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

    async function storeBankReconciliation(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/acc/acc-cost-centers', form);
            bankReconciliation.value = data.value;
            notification.showSuccess(status);
            isOpenReconciliationDateModal.value = false;
            await router.push({ name: "acc.cost-centers.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showBankReconciliation(bankReconciliationId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/acc/acc-cost-centers/${bankReconciliationId}`);
            bankReconciliation.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateBankReconciliation(form,bankReconciliations) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/acc/acc-bank-reconciliations', form);
            bankReconciliation.value = data.value;
            let singleData = bankReconciliations.find(doc => doc.id === form.acc_transaction_id);

            if (!singleData.bankReconciliation) {
                singleData.bankReconciliation = {};
            }

            if (singleData && singleData.bankReconciliation) {
                singleData.bankReconciliation.reconciliation_date = data.value.reconciliation_date;
                singleData.bankReconciliation.status = data.value.status;
            }
            notification.showSuccess(status);
            isOpenReconciliationDateModal.value = 0;
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteBankReconciliation(bankReconciliationId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/acc/acc-cost-centers/${bankReconciliationId}`);
            notification.showSuccess(status);
            await getBankReconciliations(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        bankReconciliations,
        bankReconciliation,
        getBankReconciliations,
        isOpenReconciliationDateModal,
        storeBankReconciliation,
        showBankReconciliation,
        updateBankReconciliation,
        deleteBankReconciliation,
        isLoading,
        isTableLoading,
        errors,
    };
}
