import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api';
import Error from '../../services/error';
import useNotification from '../useNotification.js';

export default function useCargoTariff() {
	const router = useRouter();
	const cargoTariffs = ref([]);
	const $loading = useLoading();
	const notification = useNotification();
	const cargoTariffLineObject = {
		particular: '',
		unit: '',
		jan: '',
		feb: '',
		mar: '',
		apr: '',
		may: '',
		jun: '',
		jul: '',
		aug: '',
		sep: '',
		oct: '',
		nov: '',
		dec: '',
	};
	const cargoTariff = ref({
		tariff_name: '',
		ops_vessel_id: '',
		loading_point: '',
		unloading_point: '',
		ops_cargo_type_id: '',
		currency: '',
		status: '',
		opsCargoTariffLines: [
			{ ...cargoTariffLineObject }
		]
	});
	const errors = ref(null);
	const isLoading = ref(false);

	async function getCargoTariffs(page, businessUnit) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get('/ops/cargo-tariffs', {
				params: {
					page: page || 1,
					business_unit: businessUnit
				},
			});
			cargoTariffs.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			//notification.showError(status);
		} finally {
			//NProgress.done();
			loader.hide();
			isLoading.value = false;
		}
	}

	async function storeCargoTariff(form) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.post('/ops/cargo-tariffs', form);
			cargoTariff.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.configurations.cargo-tariffs.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function showCargoTariff(cargoTariffId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/ops/cargo-tariffs/${cargoTariffId}`);
			cargoTariff.value = data.value;
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

	async function updateCargoTariff(form, cargoTariffId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.put(
				`/ops/cargo-tariffs/${cargoTariffId}`,
				form
			);
			cargoTariff.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.configurations.cargo-tariffs.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function deleteCargoTariff(cargoTariffId) {
		
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/ops/cargo-tariffs/${cargoTariffId}`);
			notification.showSuccess(status);
			await getCargoTariffs();
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	// Get ports by name or code
	async function searchCargoTariffs(searchParam, business_unit, loading) {
		// NProgress.start();
		//const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		// isLoading.value = true;

		try {
			const { data } = await Api.get(
				'ops/search-cargo-tariffs?tariff_name='+searchParam+'&business_unit='+business_unit,
			);
			cargoTariffs.value = data.value;
			cargoTariff.value = data.value;
		} catch (error) {
			error.value = Error.showError(error);
		} finally {
			//loader.hide();
			loading(false)
		}
	}

	return {
		cargoTariffLineObject,
		cargoTariffs,
		cargoTariff,
		getCargoTariffs,
		storeCargoTariff,
		showCargoTariff,
		updateCargoTariff,
		deleteCargoTariff,
		searchCargoTariffs,
		isLoading,
		errors,
	};
}
