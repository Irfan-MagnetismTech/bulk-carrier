import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api.js';
import Error from '../../services/error.js';
import useNotification from '../useNotification.js';

export default function useVesselExpenseHead() {
	const router = useRouter();
	const vesselExpenseHeads = ref([]);
	const $loading = useLoading();
    const isTableLoading = ref(false);
	const notification = useNotification();
	const isExpenseHeadLoading = ref(false);

	const vesselExpenseHead = ref( {
		business_unit: null,
		opsVessel: null,
		ops_vessel_id: null,
        name: '',
		heads: [],
    });
    const errors = ref('');
    const isLoading = ref(false);
    const filterParams = ref(null);

	async function getVesselExpenseHeads(filterOptions) {
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
			const {data, status} = await Api.get(`/ops/vessel-expense-heads`,{
                params: {
                   page: filterOptions.page,
                   items_per_page: filterOptions.items_per_page,
                   data: JSON.stringify(filterOptions)
                }
            });
			vesselExpenseHeads.value = data.value;
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

	async function storeVesselExpenseHead(form) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {

			const { data, status } = await Api.post('/ops/vessel-expense-heads', form);
			notification.showSuccess(status);
			router.push({ name: 'ops.vessel-expense-heads.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function showVesselExpenseHead(vesselExpenseHeadId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/ops/vessel-expense-heads/${vesselExpenseHeadId}`);
			vesselExpenseHead.value = data.value;
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

	async function updateVesselExpenseHead(form, vesselExpenseHeadId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;
		form.type = 'update';

		try {

			const { data, status } = await Api.put(
				`/ops/vessel-expense-heads/${vesselExpenseHeadId}`,
				form
			);
			// VesselExpenseHead.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.vessel-expense-heads.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}


	async function deleteVesselExpenseHead(vesselExpenseHeadId) {
		
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/ops/vessel-expense-heads/${vesselExpenseHeadId}`);
			notification.showSuccess(status);
			await getVesselExpenseHeads(filterParams.value);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function searchVesselExpenseHeads(searchParam, loading) {
		//NProgress.start();

		try {
			const { data, status } = await Api.get(`/ops/search-vessel-expense-heads?name=${searchParam}`);
			vesselExpenseHeads.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			loading(false)
			//NProgress.done();
		}
	}

	async function showVesselWiseExpenseHead(ops_vessel_id) {
		//NProgress.start();
		// const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		// isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/ops/show-vessel-expense-heads?ops_vessel_id=${ops_vessel_id}`);
			vesselExpenseHeads.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			// loader.hide();
			// isLoading.value = false;
			//NProgress.done();
		}
	}

	async function showFlatVesselExpenseHead(ops_vessel_id) {
		//NProgress.start();
		// const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		// isLoading.value = true;
		isExpenseHeadLoading.value = true;
		try {
			const { data, status } = await Api.get(`/ops/show-flatten-vessel-expense-heads?ops_vessel_id=${ops_vessel_id}`);
			vesselExpenseHeads.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			// loader.hide();
			// isLoading.value = false;
			//NProgress.done();
			isExpenseHeadLoading.value = false;
		}
	}

	return {
		vesselExpenseHeads,
		vesselExpenseHead,
		getVesselExpenseHeads,
		storeVesselExpenseHead,
		showVesselExpenseHead,
		showVesselWiseExpenseHead,
		showFlatVesselExpenseHead,
		updateVesselExpenseHead,
		deleteVesselExpenseHead,
		searchVesselExpenseHeads,
		isTableLoading,
		isLoading,
		isExpenseHeadLoading,
		errors,
	};
}
