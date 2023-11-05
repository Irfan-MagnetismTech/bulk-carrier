import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';

export default function useTransaction() {
    const router = useRouter();
    const transactions = ref([]);
    const $loading = useLoading();
    const bgColor = ref('');
    const notification = useNotification();
    const transaction = ref( {
        acc_cost_center_id: '',
        acc_cost_center_name: '',
        voucher_type: '',
        transactionable_id: '',
        transactionable_type: '',
        transaction_date: '',
        bill_no: '',
        mr_no: '',
        narration: '',
        instrument_type: '',
        instrument_no: '',
        instrument_date: '',
        business_unit: '',
        total_debit_amount: 0.0,
        total_credit_amount: 0.0,
        ledgerEntries: [
            {
                acc_transaction_id: '',
                acc_balance_and_income_line_id: '',
                acc_cost_center_id: '',
                acc_cost_center_name: '',
                acc_account_id: '',
                acc_account_name: '',
                dr_amount: 0.0,
                cr_amount: 0.0,
                ref_bill: '',
                bill_status: '',
                purpose: '',
                remarks: '',
            }
        ]
    });

    const indexPage = ref(null);
    const indexBusinessUnit = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);

    async function getTransactions(page,businessUnit) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        indexPage.value = page;
        indexBusinessUnit.value = businessUnit;

        try {
            const {data, status} = await Api.get('/acc/acc-transactions',{
                params: {
                    page: page || 1,
                    business_unit: businessUnit,
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

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/acc/acc-transactions', form);
            transaction.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "acc.transactions.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showTransaction(transactionId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/acc/acc-transactions/${transactionId}`);
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

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/acc/acc-transactions/${transactionId}`,
                form
            );
            transaction.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "acc.transactions.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteTransaction(transactionId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/acc/acc-transactions/${transactionId}`);
            notification.showSuccess(status);
            await getTransactions(indexPage.value,indexBusinessUnit.value);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        transactions,
        transaction,
        getTransactions,
        storeTransaction,
        showTransaction,
        updateTransaction,
        deleteTransaction,
        bgColor,
        isLoading,
        errors,
    };
}