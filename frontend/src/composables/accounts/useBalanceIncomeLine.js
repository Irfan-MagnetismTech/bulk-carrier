import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';

export default function useBalanceIncomeLine() {
    const router = useRouter();
    const balanceIncomeLines = ref([]);
    const $loading = useLoading();
    const notification = useNotification();
    const balanceIncomeLine = ref( {
        line_type: '',
        line_text: '',
        value_type: '',
        parent_id: '',
        visible_index: '',
        printed_no: '',
        _lft: '',
        _rgt: '',
        business_unit: '',
    });
    const indexPage = ref(null);
    const indexBusinessUnit = ref(null);

    const errors = ref(null);
    const isLoading = ref(false);

    async function getBalanceIncomeLines(filterOptions) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        indexPage.value = filterOptions.page;
        indexBusinessUnit.value = filterOptions.business_unit;

        try {
            const {data, status} = await Api.get('/acc/acc-balance-and-income-lines',{
                params: {
                    page: filterOptions.page || 1,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
                }
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

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/acc/acc-balance-and-income-lines', form);
            balanceIncomeLine.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "acc.balance-income-lines.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function showBalanceIncomeLine(balanceIncomeLineId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.get(`/acc/acc-balance-and-income-lines/${balanceIncomeLineId}`);
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

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.put(
                `/acc/acc-balance-and-income-lines/${balanceIncomeLineId}`,
                form
            );
            balanceIncomeLine.value = data.value;
            notification.showSuccess(status);
            await router.push({ name: "acc.balance-income-lines.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            loader.hide();
            isLoading.value = false;
        }
    }

    async function deleteBalanceIncomeLine(balanceIncomeLineId) {

        const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        try {
            const { data, status } = await Api.delete( `/acc/acc-balance-and-income-lines/${balanceIncomeLineId}`);
            notification.showSuccess(status);
            await getBalanceIncomeLines(indexPage.value,indexBusinessUnit.value);
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
