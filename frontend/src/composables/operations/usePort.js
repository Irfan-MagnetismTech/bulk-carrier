import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api';
import Error from '../../services/error';
import useEncryptDecrypt from '../../services/useEncryptDecrypt.js';
import useNotification from '../../composables/useNotification.js';

export default function usePort() {
	const router = useRouter();
	const ports = ref([]);
	const $loading = useLoading();
	const portName = ref([]);
	const voyagePorts = ref([]);
	const notification = useNotification();
	const encryptDecrypt = useEncryptDecrypt();

	const port = ref({
		code : '',
		name : '',
		business_unit: ''
	});
	const errors = ref(null);
	const isLoading = ref(false);
	const isPortLoading = ref(false);
	const indexPage = ref(null);
    const indexBusinessUnit = ref(null);
    const filterParams = ref(null);
	const isTableLoading = ref(false);
	async function getPorts(filterOptions) {
		let loader = null;
        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        // isLoading.value = true;

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

		indexPage.value = filterOptions.page;
		indexBusinessUnit.value = filterOptions.business_unit;

        filterParams.value = filterOptions;

		try {
			const { data, status } = await Api.get('/ops/ports', {
				params: {
					page: filterOptions.page,
					items_per_page: filterOptions.items_per_page,
					data: JSON.stringify(filterOptions)
				 }
			});
			ports.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			//notification.showError(status);
		} finally {
			//NProgress.done();
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

	async function storePort(form) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.post('/ops/ports', form);
			port.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.configurations.ports.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function showPort(portId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;
		
		try {
			const { data, status } = await Api.get(`/ops/ports/${encryptDecrypt.decrypt(portId)}`);
			port.value = data.value;
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

	async function updatePort(form, portId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.put(
				`/ops/ports/${encryptDecrypt.decrypt(portId)}`,
				form
			);
			port.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.configurations.ports.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function deletePort(portId) {
		
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/ops/ports/${encryptDecrypt.decrypt(portId)}`);
			notification.showSuccess(status);
			await getPorts(filterParams.value);
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
	async function getPortsByNameOrCode(name_or_code, service = null) {
		NProgress.start();
		//const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data } = await Api.post(
				'dataencoding/ports/get-ports-by-name-or-code',
				{ name_or_code , service }
			);
			ports.value = data.value;
			portName.value = data.value;
		} catch (error) {
			error.value = Error.showError(error);
		} finally {
			//loader.hide();
			isLoading.value = false;
			NProgress.done();
		}
	}

	async function getPortsByVoyage(voyage_id) {
		NProgress.start();
		//const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/common/voyage-ports/${voyage_id}`);
			voyagePorts.value = data;
			notification.showSuccess(status);
		} catch (error) {
			error.value = Error.showError(error);
		} finally {
			//loader.hide();
			isLoading.value = false;
			NProgress.done();
		}
	}

	async function searchPorts(searchParam, business_unit = null, loading) {
		//NProgress.start();
		isPortLoading.value = true;
		try {
			const { data, status } = await Api.get(`/ops/search-ports?name_or_code=${searchParam}&business_unit=${business_unit}`);
			ports.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			// loading(false)
			isPortLoading.value = false;
			//NProgress.done();
		}
	}

	async function getPortList() {
		//NProgress.start();

		try {
			const { data, status } = await Api.get(`/ops/get-search-ports`);
			ports.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			// loading(false)
			//NProgress.done();
		}
	}

	return {
		ports,
		port,
		portName,
		getPorts,
		storePort,
		showPort,
		updatePort,
		deletePort,
		searchPorts,
		getPortList,
		getPortsByNameOrCode,
		voyagePorts,
		getPortsByVoyage,
		isLoading,
		isPortLoading,
		isTableLoading,
		errors,
	};
}
