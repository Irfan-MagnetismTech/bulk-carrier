import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function useBalanceIncomeLine() {
    const router = useRouter();
    const balanceIncomeLines = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const balanceIncomeLine = ref( {
        line_text : '',
        value_type : '',
        line_type : '',
        parent_id : '',
    });

    const errors = ref('');
    const isLoading = ref(false);

    async function getBalanceIncomeLines(page,isPaginate = 0) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/accounts/balance-and-income-lines',{
                params: {
                    page: page || 1,
                    is_paginate: isPaginate || 0,
                },
            });
            balanceIncomeLines.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function storeBalanceIncomeLine(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/accounts/balance-and-income-lines', form);
            balanceIncomeLineId.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "accounts.balance-and-income-lines.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showBalanceIncomeLine(balanceIncomeLineId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/accounts/balance-and-income-lines/${balanceIncomeLineId}`);
            balanceIncomeLine.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateBalanceIncomeLine(form, balanceIncomeLineId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(`/accounts/balance-and-income-lines/${balanceIncomeLineId}`, form);
            balanceIncomeLine.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "accounts.balance-and-income-lines.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteBalanceIncomeLine(balanceIncomeLineId) {

        if (!confirm('Are you sure you want to delete?')) {
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/accounts/balance-and-income-lines/${balanceIncomeLineId}`);
            notification.showSuccess(status);
            await getBalanceIncomeLines();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        balanceIncomeLines,
        balanceIncomeLine,
        getBalanceIncomeLines,
        storeBalanceIncomeLine,
        showBalanceIncomeLine,
        updateBalanceIncomeLine,
        deleteBalanceIncomeLine,
        isLoading,
        errors,
    };
}
