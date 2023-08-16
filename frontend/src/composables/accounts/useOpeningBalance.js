import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function useOpeningBalance() {
    const router = useRouter();
    const $loading = useLoading();
    const notification = useNotification();
    const openingBalances = ref([]);
    const openingBalance = ref( {
        account: null,
        account_id: '',
        date: '',
        dr_amount: '',
        cr_amount: '',
    });

    const errors = ref('');
    const isLoading = ref(false);

    async function getOpeningBalances(page,isPaginate = 0) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/accounts/account-opening-balances',{
                params: {
                    page: page || 1,
                    is_paginate: isPaginate || 0,
                },
            });
            openingBalances.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function storeOpeningBalance(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/accounts/account-opening-balances', form);
            openingBalance.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "accounts.opening-balances.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showOpeningBalance(openingBalanceId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/accounts/account-opening-balances/${openingBalanceId}`);
            openingBalance.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateOpeningBalance(form, openingBalanceId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(`/accounts/account-opening-balances/${openingBalanceId}`, form);
            openingBalance.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "accounts.opening-balances.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteOpeningBalance(openingBalanceId) {

        if (!confirm('Are you sure you want to delete?')) {
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/accounts/account-opening-balances/${openingBalanceId}`);
            notification.showSuccess(status);
            await getOpeningBalances();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        openingBalance,
        openingBalances,
        getOpeningBalances,
        storeOpeningBalance,
        showOpeningBalance,
        updateOpeningBalance,
        deleteOpeningBalance,
        isLoading,
        errors,
    };
}
