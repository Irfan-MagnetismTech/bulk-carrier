import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api';
import Error from '../../services/error';
import useNotification from '../useNotification.js';

export default function useVesselParticular() {
	const router = useRouter();
	const vesselParticulars = ref([]);
	const $loading = useLoading();
	const notification = useNotification();
	const vesselParticularLineObject = {
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
	const vesselParticular = ref({
		tariff_name: '',
		ops_vessel_id: '',
		loading_point: '',
		unloading_point: '',
		ops_cargo_type_id: '',
		currency: '',
		status: '',
		opsVesselParticularLines: [
			{ ...vesselParticularLineObject }
		]
	});
	const errors = ref(null);
	const isLoading = ref(false);

	async function getVesselParticulars(page,columns = null, searchKey = null, table = null) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get('/ops/vessel-particulars', {
				params: {
					page: page || 1,
					columns: columns || null,
					searchKey: searchKey || null,
					table: table || null,
				},
			});
			vesselParticulars.value = data.value;
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

	async function storeVesselParticular(form) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.post('/ops/vessel-particulars', form);
			vesselParticular.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.configurations.vessel-particulars.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function showVesselParticular(vesselParticularId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/ops/vessel-particulars/${vesselParticularId}`);
			vesselParticular.value = data.value;
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

	async function updateVesselParticular(form, vesselParticularId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.put(
				`/ops/vessel-particulars/${vesselParticularId}`,
				form
			);
			vesselParticular.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.configurations.vessel-particulars.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function deleteVesselParticular(vesselParticularId) {
		
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/ops/vessel-particulars/${vesselParticularId}`);
			notification.showSuccess(status);
			await getVesselParticulars();
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
	async function getVesselParticularsByNameOrCode(name_or_code, service = null) {
		NProgress.start();
		//const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data } = await Api.post(
				'dataencoding/ports/get-ports-by-name-or-code',
				{ name_or_code , service }
			);
			vesselParticulars.value = data.value;
			vesselParticular.value = data.value;
		} catch (error) {
			error.value = Error.showError(error);
		} finally {
			//loader.hide();
			isLoading.value = false;
			NProgress.done();
		}
	}

	return {
		vesselParticularLineObject,
		vesselParticulars,
		vesselParticular,
		getVesselParticulars,
		storeVesselParticular,
		showVesselParticular,
		updateVesselParticular,
		deleteVesselParticular,
		getVesselParticularsByNameOrCode,
		isLoading,
		errors,
	};
}