import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';

export default function useBankAccount() {
    const router = useRouter();
    const bankAccounts = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const bankAccount = ref( {
        bank_name: '',
        branch_name: '',
        account_type: '',
        account_name: '',
        account_number: '',
        routing_number: '',
        contact_number: '',
        opening_date: '',
        opening_balance: '',
        business_unit: '',
    });
    const indexPage = ref(null);
    const indexBusinessUnit = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);

    async function getBankAccounts(filterOptions) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        indexPage.value = filterOptions.page;
        indexBusinessUnit.value = filterOptions.business_unit;

        try {
            const {data, status} = await Api.get('/acc/acc-bank-accounts',{
                params: {
                    page: filterOptions.page || 1,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
                }
            });
            bankAccounts.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function storeBankAccount(form) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/acc/acc-bank-accounts', form);
            bankAccount.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "acc.bank-accounts.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showBankAccount(bankAccountId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/acc/acc-bank-accounts/${bankAccountId}`);
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

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/acc/acc-bank-accounts/${bankAccountId}`,
                form
            );
            bankAccount.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "acc.bank-accounts.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteBankAccount(bankAccountId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/acc/acc-bank-accounts/${bankAccountId}`);
            notification.showSuccess(status);
            await getBankAccounts(indexPage.value,indexBusinessUnit.value);
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
