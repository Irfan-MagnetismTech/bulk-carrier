import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api';
import Error from '../../services/error';
import useNotification from '../useNotification.js';

export default function useMaritimeCertificate() {
	const router = useRouter();
	const maritimeCertificates = ref([]);
	const $loading = useLoading();
	const notification = useNotification();
	const maritimeCertificate = ref({
		name: '',
		type: '',
		validity: '',
		authority: '',
	});
	const errors = ref(null);
	const isLoading = ref(false);

	async function getMaritimeCertificates(page,columns = null, searchKey = null, table = null) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get('/ops/maritime-certifications', {
				params: {
					page: page || 1,
					columns: columns || null,
					searchKey: searchKey || null,
					table: table || null,
				},
			});
			maritimeCertificates.value = data.value;
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

	async function storeMaritimeCertificate(form) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.post('/ops/maritime-certifications', form);
			maritimeCertificate.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.maritime-certifications.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function showMaritimeCertificate(maritimeCertificateId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/ops/maritime-certifications/${maritimeCertificateId}`);
			maritimeCertificate.value = data.value;
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

	async function updateMaritimeCertificate(form, maritimeCertificateId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.put(
				`/ops/maritime-certifications/${maritimeCertificateId}`,
				form
			);
			maritimeCertificate.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.maritime-certifications.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function deleteMaritimeCertificate(maritimeCertificateId) {
		
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/ops/maritime-certifications/${maritimeCertificateId}`);
			notification.showSuccess(status);
			await getMaritimeCertificates();
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function searchMaritimeCertificates(searchParam, loading) {
		//NProgress.start();

		try {
			const { data, status } = await Api.get(`/ops/search-maritime-certifications?name=${searchParam}`);
			maritimeCertificates.value = data.value;
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
		maritimeCertificates,
		maritimeCertificate,
		getMaritimeCertificates,
		storeMaritimeCertificate,
		showMaritimeCertificate,
		updateMaritimeCertificate,
		deleteMaritimeCertificate,
		searchMaritimeCertificates,
		isLoading,
		errors,
	};
}
