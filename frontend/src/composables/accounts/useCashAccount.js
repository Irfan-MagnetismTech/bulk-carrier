import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function useCashAccount() {
    const router = useRouter();
    const $loading = useLoading();
    const notification = useNotification();
    const cashAccounts = ref([]); 
    const cashAccount = ref( {
        name : '',
    });

    const errors = ref('');
    const isLoading = ref(false);

    async function getCashAccounts(page,isPaginate = 0) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/accounts/cash-accounts',{
                params: {
                    page: page || 1,
                    is_paginate: isPaginate || 0,
                },
            });
            cashAccounts.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function storeCashAccount(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/accounts/cash-accounts', form);
            cashAccount.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "accounts.cash-accounts.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showCashAccount(cashAccountId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/accounts/cash-accounts/${cashAccountId}`);
            cashAccount.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateCashAccount(form, cashAccountId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(`/accounts/cash-accounts/${cashAccountId}`, form);
            cashAccount.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "accounts.cash-accounts.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteCashAccount(cashAccountId) {

        if (!confirm('Are you sure you want to delete?')) {
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/accounts/cash-accounts/${cashAccountId}`);
            notification.showSuccess(status);
            await getCashAccounts();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        cashAccount,
        cashAccounts,
        getCashAccounts,
        storeCashAccount,
        showCashAccount,
        updateCashAccount,
        deleteCashAccount,
        isLoading,
        errors,
    };
}
