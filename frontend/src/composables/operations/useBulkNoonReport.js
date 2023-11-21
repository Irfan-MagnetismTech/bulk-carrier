import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api.js';
import Error from '../../services/error.js';
import useNotification from '../useNotification.js';

export default function useBulkNoonReport() {
	const router = useRouter();
	const bulkNoonReports = ref([]);
	const $loading = useLoading();
	const notification = useNotification();

	const bulkNoonReport = ref({
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

	async function getBulkNoonReports(page, businessUnit) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get('/ops/bulk-noon-reports', {
				params: {
					page: page || 1,
					business_unit: businessUnit
				}
			});
			bulkNoonReports.value = data.value;
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

	async function storeBulkNoonReport(form) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {

			const { data, status } = await Api.post('/ops/bulk-noon-reports', form);
			notification.showSuccess(status);
			router.push({ name: 'ops.bulk-noon-reports.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function showBulkNoonReport(bulkNoonReportId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/ops/bulk-noon-reports/${bulkNoonReportId}`);
			bulkNoonReport.value = data.value;
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

	async function updateBulkNoonReport(form, bulkNoonReportId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {

			const { data, status } = await Api.put(
				`/ops/bulk-noon-reports/${bulkNoonReportId}`,
				form
			);
			
			// bulkNoonReport.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.bulk-noon-reports.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function deleteBulkNoonReport(bulkNoonReportId) {
		
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/ops/bulk-noon-reports/${bulkNoonReportId}`);
			notification.showSuccess(status);
			await getBulkNoonReports();
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function searchBulkNoonReports(searchParam, loading) {
		//NProgress.start();

		try {
			const { data, status } = await Api.get(`/ops/search-bulk-noon-reports?name=${searchParam}`);
			bulkNoonReports.value = data.value;
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
		bulkNoonReports,
		bulkNoonReport,
		getBulkNoonReports,
		storeBulkNoonReport,
		showBulkNoonReport,
		updateBulkNoonReport,
		deleteBulkNoonReport,
		searchBulkNoonReports,
		isLoading,
		errors,
	};
}
