import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api';
import Error from '../../services/error';
import useNotification from "../useNotification";

export default function useVessel() {
	const router = useRouter();
	const notification = useNotification();
	const $loading = useLoading();
	const vessels = ref([]);
	const vesselName = ref([]);
	const vessel = ref({
		name: '',
		code: '',
		ownership: '',
		flag: 'BANGLADESH',
		year_built: '',
		call_sign: '',
		grt: '',
		nrt: '',
		dwt: '',
		speed: '',
		capacity_tues: '',
		reefer_capacity: '',
		imo_number: '',
		classification: '',
		available_stowages: '',
		tier_range: '',
		deck_range: '',
		remarks: '',
	});
	const errors = ref(null);
	const isLoading = ref(false);

	async function getVessels(page = 1) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get('/dataencoding/vessels', {
				params: {
					page: page || 1,
				},
			});
			vessels.value = data.value;
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

	async function getAllVessels(name) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data } = await Api.post(
				'dataencoding/vessel-search',
				{ name }
			);
			vessels.value = data.value;
			console.log(data.message);
		} catch (error) {
			error.value = Error.showError(error);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function storeVessel(form) {
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		//NProgress.start();
		isLoading.value = true;

		try {
			const { data, status } = await Api.post('/dataencoding/vessels', form);
			vessels.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'dataencoding.vessels.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function showVessel(vesselId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/dataencoding/vessels/${vesselId}`);
			vessel.value = data.value;
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

	async function updateVessel(form, vesselId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.put(
				`/dataencoding/vessels/${vesselId}`,
				form
			);
			notification.showSuccess(status);
			router.push({ name: 'dataencoding.vessels.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function deleteVessel(vesselId) {
        if (!confirm('Are you sure you want to delete this vessel?')) {
            return;
        }
        
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete(
				`/dataencoding/vessels/${vesselId}`
			);
			notification.showSuccess(status);
            await getVessels();
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	function getVesselName() {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		Api.get(`/dataencoding/get/vessel/name`)
			.then((response) => {
				//vessels.value = response.data.value;
				vesselName.value = response.data.value;
			})
			.catch((error) => {
				error.value = Error.showError(error);
			})
			.finally(() => {
				loader.hide();
				isLoading.value = false;
				//NProgress.done();
			});
	}

	function getVesselWithoutPaginate() {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		Api.get("dataencoding/vessels/without/paginate")
			.then((response) => {
				vessels.value = response.data.value;
			})
			.catch((error) => {
				error.value = Error.showError(error);
			})
			.finally(() => {
				loader.hide();
				isLoading.value = false;
				//NProgress.done();
			});
	}

	return {
		vessels,
		vessel,
		vesselName,
		getVessels,
		getAllVessels,
		getVesselWithoutPaginate,
		storeVessel,
		showVessel,
		updateVessel,
        deleteVessel,
		getVesselName,
		isLoading,
		errors,
	};
}
