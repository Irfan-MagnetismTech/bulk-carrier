import NProgress from 'nprogress';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api';
import Error from '../../services/error';
import useNotification from '../useNotification.js';

export default function useVoyageBudget() {
    const router = useRouter();
    const expenses = ref([]);
    const budgetPresets = ref([]);
    const entryType = ref('');
    const notification = useNotification();
	const isLoading = ref(false);
	const errors = ref(null);
    const categories = ref([]);
    const globalHeads = ref();
    const budget = ref({
        title: null,
        is_active: null,
        service_id: null,
        vessel_id: null,
        start_date: null,
        end_date: null,
    });
    const budgetDetails = ref({
        exchange_rate : 0,
        budgets: {},
    })
    const budgets = ref([]);

    const expense = ref( {
        voyage_id: '',
        port: null,
        date: null,
        type: 'expense',
        entries: [
            // {
            //     voyage_expenditure_sub_head: '',
            //     voyage_expenditure_sub_head_id: '',
            //     invoice_no: '',
            //     invoice_date: '',
            //     currency: '',
            //     amount: '',
            //     remarks: '',
            // }
        ],
    });

    const headWiseExpenseFormParams = ref( {
        port: null,
        port_code_name: null,
        entries: [
            {
                expense_head: '',
                expense_head_id: '',
                voyage_id: '',
                invoice_date: '',
                invoice_no: '',
                currency: 'USD',
                amount: '',
                remarks: ''
            }
        ],
    });
    

    async function storeBudget(form) {
        NProgress.start();
        isLoading.value = true;

        try {
            const { data, status } = await Api.post('/finance/voyage-budgets', form);
            budget.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "finance.budget.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function updateBudget(form, budgetId) {
        NProgress.start();
        isLoading.value = true;

        try {
            const { data, status } = await Api.put('/finance/voyage-budgets/'+budgetId, form);
            budget.value = data.value;
            notification.showSuccess(status);
            router.push({ name: "finance.budget.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function getAllBudgets() 
    {
        NProgress.start();
        isLoading.value = true;

        try {
            const { data, status } = await Api.get('/finance/voyage-budgets');
            budgets.value = data.value.data;
            notification.showSuccess(status);
            // router.push({ name: "finance.budget.index" });
        } catch (error) {
            const { data, status } = error.response;
            errors.value = notification.showError(status, data);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function showBudget(budgetId) {
        NProgress.start();
        isLoading.value = true;
        try {
            const { data, status } = await Api.get(`/finance/voyage-budgets/${budgetId}`);
            budget.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function searchBudget(searchTerm) {
        NProgress.start();
        isLoading.value = true;
        try {
            const { data, status } = await Api.get(`/finance/search-budgets?title=${searchTerm}`);
            budgets.value = data.value;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function getBudgetPreset(ports, budgetId) {
        NProgress.start();
        isLoading.value = true;
        try {
            const { data, status } = await Api.post('/finance/budget-presets', { ports, budgetId });
            budgetPresets.value = data.value;
            globalHeads.value = data.globalHeads;
            budgetDetails.value.exchange_rate = data.exchange_rate;
            notification.showSuccess(status);
        } catch (error) {
            const { data, status } = error.response;
            notification.showError(status);
        } finally {
            isLoading.value = false;
            NProgress.done();
        }
    }

    async function deleteBudget(budgetId) {
		if (!confirm('Are you sure you want to delete this budget?')) {
			return;
		}
		NProgress.start();
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/finance/voyage-budgets/${budgetId}`);
			notification.showSuccess(status);
			await getAllBudgets();
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			isLoading.value = false;
			NProgress.done();
		}
	}

    async function cloneBudget(budgetId) {
        if (!confirm('Are you sure you want to clone this budget?')) {
			return;
		}
		NProgress.start();
		isLoading.value = true;

		try {
			const { data, status } = await Api.get( `/finance/clone-voyage-budget/${budgetId}`);
			notification.showSuccess(status);
			// await getAllBudgets();
            let newBudgetId = data.newBudget.id
            router.push({ name: "finance.budget.edit", params: {budgetId: newBudgetId} });

		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			isLoading.value = false;
			NProgress.done();
		}
    }

    async function assignBudget(voyagePairId, budgetId) {
        NProgress.start();
		isLoading.value = true;

		try {
			const { data, status } = await Api.post( `/finance/assign-voyage-budget`, { voyagePairId, budgetId });
			notification.showSuccess(status);
			// await getAllBudgets();
            // let newBudgetId = data.newBudget.id
            router.push({ name: "finance.voyage-pairing.index"});

		} catch (error) {
			const { data, status } = error.response;
            notification.showError(status, data.message);
            errors.value = error.response.data

		} finally {
			isLoading.value = false;
			NProgress.done();
		}
    }

    async function storeBudgetEntry(budgetDetails) {
        NProgress.start();
		isLoading.value = true;

        console.log(budgetDetails)

        try {
			const { data, status } = await Api.post( `/finance/store-voyage-budget-entries`, budgetDetails);
			notification.showSuccess(status);
			// await getAllBudgets();
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			isLoading.value = false;
			NProgress.done();
		}
    }


    return {
        notification,
        budget,
        errors,
        storeBudget,
        updateBudget,
        budgets,
        getAllBudgets,
        showBudget,
        getBudgetPreset,
        budgetPresets,
        globalHeads,
        deleteBudget,
        budgetDetails,
        storeBudgetEntry,
        cloneBudget,
        searchBudget,
        assignBudget
    }
}