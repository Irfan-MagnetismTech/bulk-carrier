import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api.js';
import Error from '../../services/error.js';
import useNotification from '../useNotification.js';

export default function useVoyageBudget() {
	const router = useRouter();
	const voyageBudgets = ref([]);
	const $loading = useLoading();
    const isTableLoading = ref(false);
	const notification = useNotification();

	const expenseHeadObject = {
		ops_expense_head_id: '',
		quantity: '',
		amount: '',
		amount_usd: '',
		amount_bdt: ''
	}

	const voyageBudget = ref( {
		business_unit: null,
		opsVessel: null,
		ops_vessel_id: null,
		opsVoyage: null,
		ops_voyage_id: null,
        name: '',
		currency: '',
		opsVoyageBudgetEntries: [
			{...expenseHeadObject}
		],
    });
    const errors = ref('');
    const isLoading = ref(false);
    const filterParams = ref(null);

	async function getVoyageBudgets(filterOptions) {
		//NProgress.start();
		let loader = null;
        if (!filterOptions.isFilter) {
            loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
            isLoading.value = true;
            isTableLoading.value = false;
        }
        else {
            isTableLoading.value = true;
            isLoading.value = false;
            loader?.hide();
        }
        filterParams.value = filterOptions;

		try {
			const {data, status} = await Api.get(`/ops/voyage-budgets`,{
                params: {
                   page: filterOptions.page,
                   items_per_page: filterOptions.items_per_page,
                   data: JSON.stringify(filterOptions)
                }
            });
			voyageBudgets.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			if (!filterOptions.isFilter) {
                loader?.hide();
                isLoading.value = false;
            }
            else {
                isTableLoading.value = false;
                loader?.hide();
            }
		}
	}

	async function storeVoyageBudget(form) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {

			const { data, status } = await Api.post('/ops/voyage-budgets', form);
			notification.showSuccess(status);
			router.push({ name: 'ops.voyage-budgets.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function showVoyageBudget(voyageBudgetId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/ops/voyage-budgets/${voyageBudgetId}`);
			voyageBudget.value = data.value;
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

	async function updateVoyageBudget(form, voyageBudgetId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {

			const { data, status } = await Api.put(
				`/ops/voyage-budgets/${voyageBudgetId}`,
				form
			);
			// VoyageBudget.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.voyage-budgets.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}


	async function deleteVoyageBudget(voyageBudgetId) {
		
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/ops/voyage-budgets/${voyageBudgetId}`);
			notification.showSuccess(status);
			await getVoyageBudgets(filterParams.value);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function searchVoyageBudgets(searchParam, loading) {
		//NProgress.start();

		try {
			const { data, status } = await Api.get(`/ops/search-voyage-budgets?name=${searchParam}`);
			voyageBudgets.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			loading(false)
			//NProgress.done();
		}
	}

	return {
		expenseHeadObject,
		voyageBudgets,
		voyageBudget,
		getVoyageBudgets,
		storeVoyageBudget,
		showVoyageBudget,
		updateVoyageBudget,
		deleteVoyageBudget,
		searchVoyageBudgets,
		isTableLoading,
		isLoading,
		errors,
	};
}
