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
		type: '',
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
		business_unit: 'PSML',
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

	const indexPage = ref(null);
	const indexBusinessUnit = ref(null);
    const filterParams = ref(null);
	const isTableLoading = ref(false);
	async function getBulkNoonReports(filterOptions) {
		//NProgress.start();
		let loader = null;
        // const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        // isLoading.value = true;

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
			const { data, status } = await Api.get('/ops/bulk-noon-reports', {
				params: {
					page: filterOptions.page,
					items_per_page: filterOptions.items_per_page,
					data: JSON.stringify(filterOptions)
				 }
			});
			bulkNoonReports.value = data.value;
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
			await getBulkNoonReports(filterParams.value);
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

	function checkValidation(openTab, tabNumber, props, requiredFields) {
        for (const field of requiredFields) {
            if (openTab.value === 1) {
                
				let sectorFieldStatus = true;

				if (typeof field === 'object') {
					console.log("obje", field)
					props.form.opsBulkNoonReportPorts.forEach((value, index) => {
						
						field.check.forEach((fieldToCheck) => {
							if (!props.form.opsBulkNoonReportPorts[index][fieldToCheck]) {
								sectorFieldStatus = false
							}
						})
					});
			
				} else if(!props.form[field]) {
					sectorFieldStatus = false
				}

				if(!sectorFieldStatus){
                    notification.showError(422, '', 'Please fill all required fields');
                    return false;
                }
            }
        }

        return true;
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
		checkValidation,
		isLoading,
		isTableLoading,
		errors,
	};
}
