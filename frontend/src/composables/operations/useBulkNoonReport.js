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
	const bunkerConsumptionHeads = ref([]);
	const engineTemparatureTypes = ref([]);
	
	const portObject = {
		last_port: null,
		next_port: null,
		eta: null,
		distance_run: null,
		dtg: null,
		remarks: null
	}

	const cargoTankObject = {
		cargo_tanks: null,
		liq_level: null,
		pressure: null,
		vapor_temp: null,
		liq_temp: null,
		quantity_mt: null
	}

	const engineObject = {
		type: null,
		engine_unit: null,
		pco: null,
		rack: null,
		exh_temp: null,
		business_unit: null
	}

	const bulkNoonReport = ref({
		ops_vessel_id: '',
		ops_voyage_id: '',
		ship_master: '',
		chief_engineer: '',
		wind_condition: '',
		type: '',
		date_time: '',
		gmt_time: '',
		location: '',
		latitude: '',
		longitude: '',
		fuel_figures_from: '',
		fw_last_day_noon_rob: '',
		fw_production: '',
		fw_consumption: '',
		fw_today_noon_rob: '',
		remarks: '',
		status: '',
		sea_condition: '',
		business_unit: '',
		opsVoyage: '',
		opsVessel: '',
		opsBulkNoonReportPorts: [{...portObject}],
		opsBulkNoonReportCargoTanks: [{...cargoTankObject}],
		opsBulkNoonReportDistance: {
			cp_ordered_speed: null,
			reported_speed: null,
			observed_distance: null,
			engine_distance: null,
			main_engine_revs: null,
			slip_percent: null,
			salinity: null,
			ballast: null,
			average_rpm: null,
			fwd_draft: null,
			mild_draft: null,
			aft_draft: null,
			heading: null,
			steaming_hours: null,
			s_dwt: null,
			s_displacement: null,
			status: null,
			business_unit: null
		},
		opsBulkNoonReportEngineInputs: [{...engineObject}],
		opsBunkers: [],
		opsBulkNoonReportConsumptions: []

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

	async function getBunkerConsumptionHeadList() {
		//NProgress.start();

		try {
			const { data, status } = await Api.get(`/ops/bunker-consumption-heads`);
			bunkerConsumptionHeads.value = data;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			// loading(false)
			//NProgress.done();
		}
	}

	async function getEngineTemparatureTypeList() {
		//NProgress.start();

		try {
			const { data, status } = await Api.get(`/ops/engine-temparature-types`);
			engineTemparatureTypes.value = data;
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
		bulkNoonReports,
		bulkNoonReport,
		portObject,
		cargoTankObject,
		engineObject,
		getBunkerConsumptionHeadList,
		getEngineTemparatureTypeList,
		bunkerConsumptionHeads,
		engineTemparatureTypes,
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
