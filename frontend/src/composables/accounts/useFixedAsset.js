import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api.js";
import useNotification from '../useNotification.js';

export default function useFixedAsset() {
    const router = useRouter();
    const loans = ref([]);
    const $loading = useLoading();
    const notification = useNotification();

    const fixedAsset = ref( {
        acc_cost_center_id : '',
        scm_mrr_id : '',
        scm_material_id : '',
        brand : '',
        model : '',
        serial : '',
        acc_parent_account_id : '',
        acc_account_id : '',
        asset_tag : '',
        useful_life : '',
        depreciation_rate : '',
        location : '',
        acquisition_date : '',
        acquisition_cost : '',
        business_unit : '',
        fixedAssetCosts: [
            {
                particular: '',
                remarks: '',
                amount: '',
            }
        ]
    });

    // const indexPage = ref(null);
    // const indexBusinessUnit = ref(null);
    const filterParams = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);
    const isTableLoading = ref(false);

    async function getLoans(filterOptions) {

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
            const {data, status} = await Api.get('/acc/acc-loans',{
                params: {
                    page: filterOptions.page || 1,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
                }
            });
            loans.value = data.value;
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

    async function storeLoan(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/acc/acc-loans', form);
            loan.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "acc.loans.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showLoan(loanId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/acc/acc-loans/${loanId}`);
            loan.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateLoan(form, loanId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(`/acc/acc-loans/${loanId}`, form);
            loan.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "acc.loans.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteLoan(loanId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/acc/acc-loans/${loanId}`);
            notification.showSuccess(status);
            await getLoans(filterParams.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        loans,
        loan,
        getLoans,
        storeLoan,
        showLoan,
        updateLoan,
        deleteLoan,
        isLoading,
        isTableLoading,
        errors,
    };
}
