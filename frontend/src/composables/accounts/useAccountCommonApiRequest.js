import {useLoading} from 'vue-loading-overlay';
import { ref } from "vue";
import { useRouter } from "vue-router";
import Api from "../../apis/Api";
import useNotification from '../../composables/useNotification.js';

export default function useAccountCommonApiRequest() {
    const router = useRouter();
    const balanceIncomeLineLists = ref([]);
    const balanceIncomeAccountLists = ref([]);
    const allAccountLists = ref([]);
    const allCostCenterLists = ref([]);
    const allLoanLists = ref([]);
    const allBankLists = ref([]);
    const allFixedAssetLists = ref([]);
    const allFixedAssetCategoryList = ref([]);
    const allCashRequisitionLists = ref([]);
    const allSalaryHeadLists = ref([]);
    const generatedAccountCode = ref('');
    const $loading = useLoading();
    const notification = useNotification();

    const errors = ref(null);
    const isLoading = ref(false);

    async function getBalanceIncomeLineLists(businessUnit) {

        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        let form = {
            'business_unit': businessUnit,
        }

        try {
            const { data, status } = await Api.post('/acc/get-balance-income-lines-only', form);
            balanceIncomeLineLists.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            // loader.hide();
            isLoading.value = false;
        }
    }

    async function getBalanceIncomeAccountLists(businessUnit,acc_balance_and_income_line_id) {

        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        let form = {
            'business_unit': businessUnit,
            'acc_balance_and_income_line_id': acc_balance_and_income_line_id,
        }

        try {
            const { data, status } = await Api.post('/acc/get-balance-income-accounts', form);
            balanceIncomeAccountLists.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            // loader.hide();
            isLoading.value = false;
        }
    }

    async function getAccount(account_name=null, businessUnit, loading=null) {

        //const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        let form = {
            account_name: account_name,
            business_unit: businessUnit,
        };

        try {
            const { data,status } = await Api.post('/acc/get-accounts', form);
            allAccountLists.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            //loader.hide();
            isLoading.value = false;
        }

    }

    async function getCostCenter(name=null, businessUnit, loading=null) {

        //const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        let form = {
            name: name,
            business_unit: businessUnit,
        };

        try {
            const { data,status } = await Api.post('/acc/get-cost-centers', form);
            allCostCenterLists.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            //loader.hide();
            isLoading.value = false;
        }

    }

    async function getGeneratedAccountCode(balanceIncomeLineId, loading=null) {

        //const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        isLoading.value = true;

        let form = {
            balance_and_income_line_id: balanceIncomeLineId,
        };

        try {
            const { data,status } = await Api.post('/acc/generate-account-code', form);
            generatedAccountCode.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            //loader.hide();
            isLoading.value = false;
        }

    }

    async function getLoan(name=null, businessUnit, loading=null) {
        isLoading.value = true;

        let form = {
            name: name,
            business_unit: businessUnit,
        };

        try {
            const { data,status } = await Api.post('/acc/get-loans', form);
            allLoanLists.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            isLoading.value = false;
        }
    }

    async function getBank(name=null, businessUnit, loading=null) {
        isLoading.value = true;

        let form = {
            name: name,
            business_unit: businessUnit,
        };

        try {
            const { data,status } = await Api.post('/acc/get-banks', form);
            allBankLists.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            isLoading.value = false;
        }
    }

    async function getFixedAssetCategory(name=null, businessUnit, loading=null) {
        isLoading.value = true;

        let form = {
            name: name,
            business_unit: businessUnit,
        };

        try {
            const { data,status } = await Api.post('/acc/get-fixed-asset-categories', form);
            allFixedAssetCategoryList.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            isLoading.value = false;
        }
    }

    async function getCashRequisition(name=null, businessUnit, loading=null) {

        isLoading.value = true;

        let form = {
            name: name,
            business_unit: businessUnit,
        };

        try {
            const { data,status } = await Api.post('/acc/get-cash-requisitions', form);
            allCashRequisitionLists.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            //loader.hide();
            isLoading.value = false;
        }

    }

    async function getSalaryHead(name=null, businessUnit, loading=null) {

        isLoading.value = true;

        let form = {
            name: name,
            business_unit: businessUnit,
        };

        try {
            const { data,status } = await Api.post('/acc/get-salary-heads', form);
            allSalaryHeadLists.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            //loader.hide();
            isLoading.value = false;
        }
    }

    async function getFixedAsset(name=null, businessUnit, loading=null) {

        isLoading.value = true;

        let form = {
            name: name,
            business_unit: businessUnit,
        };

        try {
            const { data,status } = await Api.post('/acc/get-fixed-assets', form);
            allFixedAssetLists.value = data.value;
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            //loader.hide();
            isLoading.value = false;
        }
    }

    return {
        balanceIncomeLineLists,
        balanceIncomeAccountLists,
        allAccountLists,
        allCostCenterLists,
        allLoanLists,
        allBankLists,
        allFixedAssetCategoryList,
        allFixedAssetLists,
        allCashRequisitionLists,
        allSalaryHeadLists,
        generatedAccountCode,
        getBalanceIncomeLineLists,
        getBalanceIncomeAccountLists,
        getAccount,
        getCostCenter,
        getLoan,
        getBank,
        getFixedAssetCategory,     
        getGeneratedAccountCode,
        getCashRequisition,
        getSalaryHead,
        getFixedAsset,
        isLoading,
        errors,
    };
}
