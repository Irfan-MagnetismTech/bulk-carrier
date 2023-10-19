import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api';
import Error from '../../services/error';
import useNotification from '../useNotification.js';

export default function useVessel() {
	const router = useRouter();
	const vessels = ref([]);
	const $loading = useLoading();
	const vesselName = ref([]);
	const voyageVessels = ref([]);
	const notification = useNotification();
	const vessel = ref({
		vessel_type: '',
		name: '',
		previous_name: '',
		short_code: '',
		call_sign: '',
		owner_name: '',
		manager: '',
		classification: '',
		flag: '',
		previous_flag: '',
		port_of_registry: '',
		delivery_date: '',
		nrt: '',
		dwt: '',
		imo: '',
		grt: '',
		official_number: '',
		keel_laying_date: '',
		launching_date: '',
		mmsi: '',
		overall_length: '',
		overall_width: '',
		year_built: '',
		capacity: '',
		total_cargo_hold: '',
		live_tracking_config: '',
		remarks: '',
		opsVesselCertificates: [],
		opsBunkers: []
	});
	const maritimeCertificateObject = {
		ops_vessel_certificate_id: '',
		certificate_type: '',
		validity: ''
	}
	const errors = ref(null);
	const isLoading = ref(false);

	async function getVessels(page,columns = null, searchKey = null, table = null) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get('/ops/vessels', {
				params: {
					page: page || 1,
					columns: columns || null,
					searchKey: searchKey || null,
					table: table || null,
				},
			});
			vessels.value = data.value;
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

	async function storeVessel(form) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		form.opsVesselCertificates.map((element) => {
			element.ops_maritime_certification_id = element.id
			element.business_unit = form.business_unit
		})

		try {
			const { data, status } = await Api.post('/ops/vessels', form);
			vessel.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.configurations.vessels.index' });
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
			const { data, status } = await Api.get(`/ops/vessels/${vesselId}`);
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

		form.opsVesselCertificates.map((element) => {
			element.ops_maritime_certification_id = element.id
			element.business_unit = form.business_unit
		})
		
		try {
			const { data, status } = await Api.put(
				`/ops/vessels/${vesselId}`,
				form
			);
			vessel.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.configurations.vessels.index' });
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
		
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/ops/vessels/${vesselId}`);
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

	// Get vessels by name or code
	async function getVesselsByNameOrCode(name_or_code, service = null) {
		NProgress.start();
		//const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data } = await Api.post(
				'dataencoding/vessels/get-vessels-by-name-or-code',
				{ name_or_code , service }
			);
			vessels.value = data.value;
			vesselName.value = data.value;
		} catch (error) {
			error.value = Error.showError(error);
		} finally {
			//loader.hide();
			isLoading.value = false;
			NProgress.done();
		}
	}

	async function getVesselsByVoyage(voyage_id) {
		NProgress.start();
		//const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/common/voyage-vessels/${voyage_id}`);
			voyageVessels.value = data;
			notification.showSuccess(status);
		} catch (error) {
			error.value = Error.showError(error);
		} finally {
			//loader.hide();
			isLoading.value = false;
			NProgress.done();
		}
	}

	async function searchVessels(searchParam, loading) {
		//NProgress.start();

		try {
			const { data, status } = await Api.get(`/ops/search-vessels?name=${searchParam}`);
			vessels.value = data.value;
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
		vessels,
		vessel,
		vesselName,
		getVessels,
		maritimeCertificateObject,
		storeVessel,
		showVessel,
		updateVessel,
		deleteVessel,
		searchVessels,
		getVesselsByNameOrCode,
		voyageVessels,
		getVesselsByVoyage,
		isLoading,
		errors,
	};
}
