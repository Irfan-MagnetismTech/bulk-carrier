import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api.js';
import Error from '../../services/error.js';
import useNotification from '../useNotification.js';

export default function useContractAssign() {
	const router = useRouter();
	const contractAssigns = ref([]);
	const contractTariff = ref({});
	const $loading = useLoading();
	const notification = useNotification();

	const contractAssign = ref({
		assign_date: null,
		ops_vessel_id: null,
		opsVessel : null,
		ops_voyage_id: null,
		opsVoyage: null,
		ops_cargo_tariff_id: null,
		opsCargoTariff: null,
		ops_customer_id: null,
		opsCustomer: null,
		ops_charterer_profile_id: null,
		opsChartererProfile: null,
		ops_charterer_contract_id: null,
		opsChartererContract: null,
        remarks: null,
		business_unit: null, 
		
	});


	const filterParams = ref(null);
	const errors = ref(null);
	const isLoading = ref(false);
	const isTableLoading = ref(false);

	async function getContractAssigns(filterOptions) {
		//NProgress.start();
		// const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        // isLoading.value = true;
        let loader = null;
        if (!filterOptions.isFilter) {
            loader = $loading.show({ 'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2' });
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
			const { data, status } = await Api.get('/ops/contract-assigns', {
				params: {
					page: filterOptions.page,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
				}
			});
			contractAssigns.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			//notification.showError(status);
		} finally {
			//NProgress.done();
			// loader.hide();
			// isLoading.value = false;
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

	async function storeContractAssign(form) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			let formData = new FormData();
			formData.append('info', JSON.stringify(form));

			const { data, status } = await Api.post('/ops/contract-assigns', formData);
			notification.showSuccess(status);
			await router.push({ name: 'ops.contract-assigns.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function showContractAssign(contractAssignId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/ops/contract-assigns/${contractAssignId}`);
			contractAssign.value = data.value;
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

	async function updateContractAssign(form, contractAssignId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {

			let formData = new FormData();


			formData.append('info', JSON.stringify(form));
			formData.append('_method', 'PUT');

			const { data, status } = await Api.post(
				`/ops/contract-assigns/${contractAssignId}`,
				formData
			);
			// contractAssign.value = data.value;
			notification.showSuccess(status);
			await router.push({ name: 'ops.contract-assigns.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function deleteContractAssign(contractAssignId) {
		
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/ops/contract-assigns/${contractAssignId}`);
			notification.showSuccess(status);
			await getContractAssigns(filterParams.value);
		} catch (error) {
			const { data, status } = error.response;
			// notification.showError(status);
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function searchContractAssigns(searchParam, loading) {
		//NProgress.start();

		try {
			const { data, status } = await Api.get(`/ops/search-contract-assigns?name=${searchParam}`);
			contractAssigns.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			loading(false)
			//NProgress.done();
		}
	}

	async function getContractTariffByVoyage(opsVoyageId) { //Cr: Delowar Vai
		//NProgress.start();

		try {
			const { data, status } = await Api.get(`/ops/get-contract-tariff-by-voyage?ops_voyage_id=${opsVoyageId}`);

			contractTariff.value = data?.value;

			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
			console.log(error)
		} finally {
			// loading(false)
			//NProgress.done();
		}
	}

	return {
		contractAssigns,
		contractAssign,
		contractTariff,
		getContractAssigns,
		storeContractAssign,
		showContractAssign,
		updateContractAssign,
		deleteContractAssign,
		searchContractAssigns,
		getContractTariffByVoyage,
		isLoading,
		isTableLoading,
		errors,
	};
}
