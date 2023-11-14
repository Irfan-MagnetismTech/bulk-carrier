import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';

export default function useChartOfAccount() {
    const router = useRouter();
    const chartOfAccounts = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const chartOfAccount = ref( {
        acc_balance_and_income_line_id: '',
        acc_balance_and_income_line_name: '',
        parent_account_id: '',
        parent_account_name: '',
        account_name: '',
        account_code: '',
        account_type: '',
        accountable_type: '',
        accountable_id: '',
        official_code: '',
        is_archived: 0,
        business_unit: '',
    });
    const indexPage = ref(null);
    const indexBusinessUnit = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);

    async function getChartOfAccounts(filterOptions) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        indexPage.value = filterOptions.page;
        indexBusinessUnit.value = filterOptions.business_unit;

        try {
            const filter_options = {
                ...filterOptions.filter_options
            }

            const {data, status} = await Api.get('/acc/acc-accounts',{
                params: {
                   page: filterOptions.page,
                   data: JSON.stringify(filterOptions)
                }
            });
            chartOfAccounts.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function storeChartOfAccount(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/acc/acc-accounts', form);
            chartOfAccount.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "acc.chart-of-accounts.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showChartOfAccount(chartOfAccountId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/acc/acc-accounts/${chartOfAccountId}`);
            chartOfAccount.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateChartOfAccount(form, chartOfAccountId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/acc/acc-accounts/${chartOfAccountId}`,
                form
            );
            chartOfAccount.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "acc.chart-of-accounts.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteChartOfAccount(chartOfAccountId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/acc/acc-accounts/${chartOfAccountId}`);
            notification.showSuccess(status);
            await getChartOfAccounts(indexPage.value,indexBusinessUnit.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        chartOfAccounts,
        chartOfAccount,
        getChartOfAccounts,
        storeChartOfAccount,
        showChartOfAccount,
        updateChartOfAccount,
        deleteChartOfAccount,
        isLoading,
        errors,
    };
}
