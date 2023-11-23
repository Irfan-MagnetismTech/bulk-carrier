import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api';
import Error from '../../services/error';
import useNotification from '../useNotification.js';

export default function useChartererProfile() {
	const router = useRouter();
	const chartererProfiles = ref([]);
	const $loading = useLoading();
	const notification = useNotification();

	const chartererBankAccountObject = {
		bank_name: '',
		bank_branch_name: '',
		account_name: '',
		account_no: '',
		swift_code: '',
		routing_no: '',
		currency: '',
		country: '',
		state_division: '',
		city: '',
	}

	const chartererProfile = ref({
		company_legal_name: '',
		name: '',
		owner_code: '',
		country: '',
		contact_no: '',
		address: '',
		billing_address: '',
		billing_email: '',
		email: '',
		website: '',
		opsChartererBankAccounts: [{...chartererBankAccountObject}]
	});
	const errors = ref(null);
	const isLoading = ref(false);

	const indexPage = ref(null);
	const indexBusinessUnit = ref(null);
	const filterParams = ref(null);
	const isTableLoading = ref(false);

	async function getChartererProfiles(filterOptions) {
		let loader = null;

		if (!filterOptions.isFilter) {
			loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
			isLoading.value = true;
			isTableLoading.value = false;
		} else {
			isTableLoading.value = true;
			isLoading.value = false;
			loader?.hide();
		}

		indexPage.value = filterOptions.page;
		indexBusinessUnit.value = filterOptions.business_unit;
		filterParams.value = filterOptions;

		try {
			const { data, status } = await Api.get('/ops/charterer-profiles', {
				params: {
					page: filterOptions.page,
					items_per_page: filterOptions.items_per_page,
					data: JSON.stringify(filterOptions)
				}
			});
			chartererProfiles.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			//notification.showError(status);
		} finally {
			if (!filterOptions.isFilter) {
				loader?.hide();
				isLoading.value = false;
			} else {
				isTableLoading.value = false;
				loader?.hide();
			}
		}
	}

	async function storeChartererProfile(form) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.post('/ops/charterer-profiles', form);
			chartererProfile.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.charterer-profiles.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function showChartererProfile(chartererProfileId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/ops/charterer-profiles/${chartererProfileId}`);
			chartererProfile.value = data.value;
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

	async function updateChartererProfile(form, chartererProfileId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.put(
				`/ops/charterer-profiles/${chartererProfileId}`,
				form
			);
			chartererProfile.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.charterer-profiles.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function deleteChartererProfile(chartererProfileId) {
		
		//NProgress.start();
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/ops/charterer-profiles/${chartererProfileId}`);
			notification.showSuccess(status);
			await getChartererProfiles(filterParams.value);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function searchChartererProfiles(searchParam, loading) {
		//NProgress.start();

		try {
			const { data, status } = await Api.get(`/ops/search-charterer-profiles?name_or_code=${searchParam}`);
			chartererProfiles.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			loading(false)
			//NProgress.done();
		}
	}


	async function getAllChartererProfiles(searchParam, loading) {
		//NProgress.start();

		try {
			const { data, status } = await Api.get(`/ops/search-charterer-profiles`);
			chartererProfiles.value = data.value;
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
		chartererProfiles,
		chartererProfile,
		chartererBankAccountObject,
		getChartererProfiles,
		storeChartererProfile,
		showChartererProfile,
		updateChartererProfile,
		deleteChartererProfile,
		searchChartererProfiles,
		getAllChartererProfiles,
		isLoading,
		isTableLoading,
		errors,
	};
}
