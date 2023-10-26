import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';

export default function useAccountCommonApiRequest() {
    const router = useRouter();
    const balanceIncomeLineLists = ref([]);
    const balanceIncomeAccountLists = ref([]);
    const allAccountLists = ref([]);
    const $loading = useLoading();
    const notification = useNotification();

    const errors = ref(null);
    const isLoading = ref(false);

    async function getBalanceIncomeLineLists(businessUnit) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        let form = {
            'business_unit': businessUnit,
        }

        try {
            const { data, status } = await Api.post('/acc/get-balance-income-lines-only', form);
            balanceIncomeLineLists.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function getBalanceIncomeAccountLists(businessUnit,acc_balance_and_income_line_id) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        let form = {
            'business_unit': businessUnit,
            'acc_balance_and_income_line_id': acc_balance_and_income_line_id,
        }

        try {
            const { data, status } = await Api.post('/acc/get-balance-income-accounts', form);
            balanceIncomeAccountLists.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function getAccount(account_name, loading) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        let form = {
            account_name: account_name
        };

        try {
            const { data,status } = await Api.post('/accounts/get-accounts', form);
            allAccountLists.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            //loader.hide();
            isLoading.value = false;
            NProgress.done();
        }

    }

    return {
        balanceIncomeLineLists,
        balanceIncomeAccountLists,
        allAccountLists,
        getBalanceIncomeLineLists,
        getBalanceIncomeAccountLists,
        getAccount,
        isLoading,
        errors,
    };
}
