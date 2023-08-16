import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function useAccount() {
    const router = useRouter();
    const accounts = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const balanceIncomeLinesOnly = ref([]); 
    const balanceIncomeAccounts = ref([]); 
    const generatedAccountCode = ref(''); 
    const account = ref({
        balance_and_income_line_id : '',
        equity_column_id : '',
        parent_account_id : '',
        account_name : '',
        account_code : '',
        account_type : '',
        accountable_type : '',
        accountable_id : '',
        official_code : '',
        loan_type : '',
        group : '',
        is_archived : ''
    });

    const errors = ref('');
    const isLoading = ref(false);

    async function getAccounts(page,isPaginate = 0) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/accounts/accounts',{
                params: {
                    page: page || 1,
                    is_paginate: isPaginate || 0,
                },
            });
            accounts.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function storeAccount(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/accounts/accounts', form);
            account.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "accounts.accounts.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showAccount(accountId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/accounts/accounts/${accountId}`);
            account.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateAccount(form, accountId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/accounts/accounts/${accountId}`,
                form
            );
            account.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "accounts.accounts.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteAccount(accountId) {

        if (!confirm('Are you sure you want to delete?')) {
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/accounts/accounts/${accountId}`);
            notification.showSuccess(status);
            await getAccounts();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function getBalanceIncomeLinesOnly() {
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/accounts/get-balance-income-lines-only');
            balanceIncomeLinesOnly.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function getBalanceIncomeAccounts(balance_and_income_line_id) {
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/accounts/get-balance-income-accounts/'+balance_and_income_line_id);
            balanceIncomeAccounts.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function getGeneratedAccountCode(balance_and_income_line_id) {
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/accounts/generate-account-code/'+balance_and_income_line_id);
            generatedAccountCode.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        } 
    }

    return {
        accounts,
        account,
        balanceIncomeLinesOnly,
        balanceIncomeAccounts,
        generatedAccountCode,
        getAccounts,
        storeAccount,
        showAccount,
        updateAccount,
        deleteAccount,
        getBalanceIncomeLinesOnly,
        getBalanceIncomeAccounts, 
        getGeneratedAccountCode,
        isLoading,
        errors,
    };
}
