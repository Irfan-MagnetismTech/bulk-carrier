import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api.js';
import Error from '../../services/error.js';
import useNotification from '../useNotification.js';

export default function useLighterNoonReport() {
	const router = useRouter();
	const lighterNoonReports = ref([]);
	const $loading = useLoading();
	const notification = useNotification();

	const lighterNoonReport = ref({
		opsVoyage: '',
		opsVessel: '',
		ops_vessel_id: '',
		ops_voyage_id: '',
		ship_master: '',
		chief_engineer: '',
		noon_position: '',
		status: '',
		engine_running_hours: '',
		lat_long: '',
		date: '',
		last_port: '',
		next_port: '',
		business_unit: '',
		remarks: '',
	});
	const errors = ref(null);
	const isLoading = ref(false);
	const indexPage = ref(null);
	const indexBusinessUnit = ref(null);
    const filterParams = ref(null);
	const isTableLoading = ref(false);

	async function getLighterNoonReports(filterOptions) {
		//NProgress.start();
		let loader = null;

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
			const { data, status } = await Api.get('/ops/lighter-noon-reports', {
				params: {
					page: filterOptions.page,
					items_per_page: filterOptions.items_per_page,
					data: JSON.stringify(filterOptions)
				}
			});
			lighterNoonReports.value = data.value;
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

	async function storeLighterNoonReport(form) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {

			const { data, status } = await Api.post('/ops/lighter-noon-reports', form);
			notification.showSuccess(status);
			router.push({ name: 'ops.lighter-noon-reports.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function showLighterNoonReport(lighterNoonReportId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/ops/lighter-noon-reports/${lighterNoonReportId}`);
			lighterNoonReport.value = data.value;
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

	async function updateLighterNoonReport(form, lighterNoonReportId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {

			const { data, status } = await Api.put(
				`/ops/lighter-noon-reports/${lighterNoonReportId}`,
				form
			);
			
			// lighterNoonReport.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.lighter-noon-reports.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function deleteLighterNoonReport(lighterNoonReportId) {
		
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/ops/lighter-noon-reports/${lighterNoonReportId}`);
			notification.showSuccess(status);
			await getLighterNoonReports();
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function searchLighterNoonReports(searchParam, loading) {
		//NProgress.start();

		try {
			const { data, status } = await Api.get(`/ops/search-lighter-noon-reports?name=${searchParam}`);
			lighterNoonReports.value = data.value;
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
		lighterNoonReports,
		lighterNoonReport,
		getLighterNoonReports,
		storeLighterNoonReport,
		showLighterNoonReport,
		updateLighterNoonReport,
		deleteLighterNoonReport,
		searchLighterNoonReports,
		isLoading,
		isTableLoading,
		errors,
	};
}
