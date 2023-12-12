import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api.js';
import Error from '../../services/error.js';
import useNotification from '../useNotification.js';

export default function useMaritimeCertificate() {
	const router = useRouter();
	const maritimeCertificates = ref([]);
	const $loading = useLoading();
	const notification = useNotification();
	const maritimeCertificate = ref({
		name: null,
		type: '',
		validity: '',
		authority: null,
	});

	const filterParams = ref(null);
	const errors = ref(null);
	const isLoading = ref(false);
	const isTableLoading = ref(false);

	async function getMaritimeCertificates(filterOptions) {
		//NProgress.start();
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
			const { data, status } = await Api.get('/ops/maritime-certifications', {
				params: {
					page: filterOptions.page,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
				}
			});
			maritimeCertificates.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			//notification.showError(status);
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
			// loading(false)
			//NProgress.done();
		}
	}

	async function getMaritimeCertificateList() {
		//NProgress.start();

		try {
			const { data, status } = await Api.get(`/ops/get-search-maritime-certifications`);
			maritimeCertificates.value = data.value;
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
		maritimeCertificates,
		maritimeCertificate,
		getMaritimeCertificates,
		storeMaritimeCertificate,
		showMaritimeCertificate,
		updateMaritimeCertificate,
		deleteMaritimeCertificate,
		searchMaritimeCertificates,
		getMaritimeCertificateList,
		isLoading,
		isTableLoading,
		errors,
	};
}
