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
        balance_income: '',
        account_code: '',
        account_id: '',
        account_name: '',
        parent_account: '',
        account_type: '',
        group: '',
        business_unit: '',
    });
    const indexPage = ref(null);
    const indexBusinessUnit = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);

    async function getChartOfAccounts(page,businessUnit) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        indexPage.value = page;
        indexBusinessUnit.value = businessUnit;

        try {
            const {data, status} = await Api.get('/acc/acc-cost-centers',{
                params: {
                    page: page || 1,
                    business_unit: businessUnit,
                },
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
            const { data, status } = await Api.post('/acc/acc-cost-centers', form);
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
            const { data, status } = await Api.get(`/acc/acc-cost-centers/${chartOfAccountId}`);
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
                `/acc/acc-cost-centers/${chartOfAccountId}`,
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
            const { data, status } = await Api.delete( `/acc/acc-cost-centers/${chartOfAccountId}`);
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
