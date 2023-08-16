import NProgress from "nprogress";
import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../useNotification.js';

export default function useBankAccount() {
    const router = useRouter();
    const bankAccounts = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const bankAccount = ref( {
        account_name: '',
        account_number: '',
        routing_number: '',
        bank_name: '',
        branch_name: '',
        account_holder: '',
        contact_number: '',
        opening_date: '',
        opening_balance: '',
    });

    const errors = ref('');
    const isLoading = ref(false);

    async function getBankAccounts() {
        //NProgress.start();
        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const {data, status} = await Api.get('/configuration/bank-accounts');
            bankAccounts.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
            //NProgress.done();
        }
    }

    async function storeBankAccount(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/configuration/bank-accounts', form);
            bankAccount.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "configuration.bank-account.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showBankAccount(bankAccountId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/configuration/bank-accounts/${bankAccountId}`);
            bankAccount.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function updateBankAccount(form, bankAccountId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/configuration/bank-accounts/${bankAccountId}`,
                form
            );
            bankAccount.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "configuration.bank-account.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteBankAccount(bankAccountId) {

        if (!confirm('Are you sure you want to delete this bankAccount?')) {
            return;
        }

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#0F6B61'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/configuration/bank-accounts/${bankAccountId}`);
            notification.showSuccess(status);
            await getBankAccounts();
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    return {
        bankAccounts,
        bankAccount,
        getBankAccounts,
        storeBankAccount,
        showBankAccount,
        updateBankAccount,
        deleteBankAccount,
        isLoading,
        errors,
    };
}
