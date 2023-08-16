import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function useTransaction() {
    const router = useRouter();
    const $loading = useLoading();
    const notification = useNotification();
    const transactions = ref([]);
    const allAccount = ref([]);
    const bgColor = ref('');
    const transaction = ref( {
        voucher_type : '',
        transaction_date: '',
        instrument_type: '',
        instrument_no: '',
        instrument_date: '',
        total_debit_amount: 0.0,
        total_credit_amount: 0.0,
        ledger_entries: [
            {
                cost_center_id: '',
                account_id: null,
                balance_income_line_id: '',
                account: null,
                ref_bill: '',
                dr_amount: 0.0,
                cr_amount: 0.0,
                remarks: '',
            }
        ]
    });

    const errors = ref('');
    const isLoading = ref(false);

    async function getTransactions(page) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/accounts/transactions',{
                params: {
                    page: page || 1,
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

    async function storeTransaction(form) {

        if(form.total_credit_amount !== form.total_debit_amount) {
            alert('Total Credit Amount and Total Debit Amount should be equal.');
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/accounts/transactions', form);
            transaction.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "accounts.transactions.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showTransaction(transactionId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/accounts/transactions/${transactionId}`);
            transaction.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateTransaction(form, transactionId) {

        if(form.total_credit_amount !== form.total_debit_amount) {
            alert('Total Credit Amount and Total Debit Amount should be equal.');
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(`/accounts/transactions/${transactionId}`, form);
            transaction.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "accounts.transactions.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteTransaction(transactionId) {

        if (!confirm('Are you sure you want to delete?')) {
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/accounts/transactions/${transactionId}`);
            notification.showSuccess(status);
            await getTransactions();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function getAccount(account_name, loading) {
        //alert(account_name);
        NProgress.start();
        isLoading.value = true;

        let form = {
            account_name: account_name
        };

        try {
            const { data,status } = await Api.post('/accounts/get-accounts', form);
            allAccount.value = data.value;
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
        transaction,
        transactions,
        getTransactions,
        getAccount,
        allAccount,
        storeTransaction,
        showTransaction,
        updateTransaction,
        deleteTransaction,
        bgColor,
        isLoading,
        errors,
    };
}
