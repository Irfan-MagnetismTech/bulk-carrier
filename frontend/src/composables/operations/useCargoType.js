import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api';
import Error from '../../services/error';
import useNotification from '../useNotification.js';

export default function useCargoType() {
	const router = useRouter();
	const cargoTypes = ref([]);
	const $loading = useLoading();
	const notification = useNotification();
	const cargoType = ref({
		cargo_type: '',
		description: ''
	});
	const errors = ref(null);
	const isLoading = ref(false);

	const indexPage = ref(null);
	const indexBusinessUnit = ref(null);

	async function getCargoTypes(filterOptions) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;
		indexPage.value = filterOptions.page;
		indexBusinessUnit.value = filterOptions.business_unit;
		try {
			const { data, status } = await Api.get('/ops/cargo-types', {
				params: {
					page: filterOptions.page,
					items_per_page: filterOptions.items_per_page,
					data: JSON.stringify(filterOptions)
				 }
			});
			cargoTypes.value = data.value;
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

	async function storeCargoType(form) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.post('/ops/cargo-types', form);
			cargoType.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.configurations.cargo-types.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function showCargoType(cargoTypeId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/ops/cargo-types/${cargoTypeId}`);
			cargoType.value = data.value;
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

	async function updateCargoType(form, cargoTypeId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.put(
				`/ops/cargo-types/${cargoTypeId}`,
				form
			);
			cargoType.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.configurations.cargo-types.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function deleteCargoType(cargoTypeId) {
		
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/ops/cargo-types/${cargoTypeId}`);
			notification.showSuccess(status);
			await getCargoTypes(filterParams.value);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function searchCargoTypes(searchParam, loading) {
		//NProgress.start();

		try {
			const { data, status } = await Api.get(`/ops/search-cargo-types?cargo_type=${searchParam}`);
			cargoTypes.value = data.value;
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
		cargoTypes,
		cargoType,
		getCargoTypes,
		storeCargoType,
		showCargoType,
		updateCargoType,
		deleteCargoType,
		searchCargoTypes,
		isLoading,
		errors,
	};
}
