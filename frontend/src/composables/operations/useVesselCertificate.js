import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api';
import Error from '../../services/error';
import useNotification from '../useNotification.js';

export default function useVesselCertificate() {
	const router = useRouter();
	const vesselCertificates = ref([]);
	const $loading = useLoading();
	const notification = useNotification();
	
	const vesselCertificate = ref({
		ops_vessel_id: '',
		ops_maritime_certification_id: '',
		issue_date: '',
		expire_date: '',
		attachment: '',
		status: '',
		reference_number: '',
		certificate_type: '',
		validity_period: ''
	});
	const errors = ref(null);
	const isLoading = ref(false);

	async function getVesselCertificates(page, businessUnit) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get('/ops/vessel-certificates', {
				params: {
					page: page || 1,
					business_unit: businessUnit,

				},
			});
			vesselCertificates.value = data.value;
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

	async function storeVesselCertificate(form) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		let formData = new FormData();
		formData.append('attachment', form.attachment);
		formData.append('info', JSON.stringify(form));

		try {
			const { data, status } = await Api.post('/ops/vessel-certificates', formData);
			vesselCertificate.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.vessel-certificates.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function showVesselCertificate(vesselCertificateId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/ops/vessel-certificates/${vesselCertificateId}`);
			vesselCertificate.value = data.value;
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

	async function updateVesselCertificate(form, vesselCertificateId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		let formData = new FormData();
		formData.append('attachment', form.attachment);
		formData.append('info', JSON.stringify(form));
        formData.append('_method', 'PUT');

		try {
			const { data, status } = await Api.post(
				`/ops/vessel-certificates/${vesselCertificateId}`,
				formData
			);
			vesselCertificate.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.vessel-certificates.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function deleteVesselCertificate(vesselCertificateId) {
		
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/ops/vessel-certificates/${vesselCertificateId}`);
			notification.showSuccess(status);
			await getVesselCertificates();
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
	async function getVesselCertificatesByNameOrCode(name_or_code, service = null) {
		NProgress.start();
		//const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data } = await Api.post(
				'dataencoding/ports/get-ports-by-name-or-code',
				{ name_or_code , service }
			);
			vesselCertificates.value = data.value;
			vesselCertificate.value = data.value;
		} catch (error) {
			error.value = Error.showError(error);
		} finally {
			//loader.hide();
			isLoading.value = false;
			NProgress.done();
		}
	}

	async function getVesselCertificateHistory(vesselId, certificateId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/ops/vessel-certificate-history?vessel_id=${vesselId}&certificate_id=${certificateId}`);
			vesselCertificates.value = data.value;
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

	async function getRenewableVesselCertificates(page, businessUnit, filterDays = 60) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get('/ops/vessel-certificates-renew', {
				params: {
					page: page || 1,
					business_unit: businessUnit,
					filter_days: filterDays
				},
			});
			vesselCertificates.value = data.value;
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

	return {
		vesselCertificates,
		vesselCertificate,
		getVesselCertificates,
		getRenewableVesselCertificates,
		storeVesselCertificate,
		showVesselCertificate,
		updateVesselCertificate,
		deleteVesselCertificate,
		getVesselCertificatesByNameOrCode,
		getVesselCertificateHistory,
		isLoading,
		errors,
	};
}
