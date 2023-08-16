import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function useBankReconciliation() {
    const router = useRouter();
    const $loading = useLoading();
    const notification = useNotification();
    const transactions = ref([]); 
    const transaction = ref( {
        name : '',
        short_name : ''
    });

    const errors = ref('');
    const isLoading = ref(false);

    async function getBankTransactions(page,isPaginate = 0) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/accounts/bank-reconciliations',{
                params: {
                    page: page || 1,
                    is_paginate: isPaginate || 0,
                },
            });
            transactions.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function approveBankTransaction(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/accounts/bank-reconciliations', form);
            transaction.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "accounts.bank-reconciliations.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateBankReconciliation(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/accounts/bank-reconciliations', form);
            transaction.value = data.value;
            notification.showSuccess(status);
            router.go({ name: "accounts.bank-reconciliations.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }


    return {
        transaction,
        transactions,
        getBankTransactions,
        approveBankTransaction,
        updateBankReconciliation,
        isLoading,
        errors,
    };
}
