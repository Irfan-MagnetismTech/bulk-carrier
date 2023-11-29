import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api';
import Error from '../../services/error';
import useNotification from '../useNotification.js';

export default function useVesselParticular() {
	const router = useRouter();
	const vesselParticulars = ref([]);
	const $loading = useLoading();
	const notification = useNotification();
	
	const vesselParticular = ref({
		ops_vessel_id: null,
		vessel_type: '',
		class_no: '',
		loa: '',
		depth: null,
		ecdis_type: '',
		maker_ssas: '',
		engine_type: '',
		name: '',
		previous_name: '',
		short_code: '',
		manager: '',
		delivery_date: '',
		call_sign: '',
		owner_name: '',
		classification: '',
		flag: '',
		previous_flag: '',
		port_of_registry: '',
		nrt: '',
		dwt: '',
		imo: '',
		grt: '',
		official_number: '',
		keel_laying_date: '',
		launching_date: '',
		mmsi: '',
		year_built: '',
		capacity: null,
		total_cargo_hold: null,
		tues_capacity: null,
		overall_length: null,
		overall_width: null,
		depth_moulded: null,
		bhp: '',
		email: '',
		lbc: '',
		attachment: '',
		remarks: '',
		business_unit: '',
	});
	const errors = ref(null);
	const isLoading = ref(false);
	const isTableLoading = ref(false);


	const indexPage = ref(null);
	const indexBusinessUnit = ref(null);

    const filterParams = ref(null);

	async function getVesselParticulars(filterOptions) {
		//NProgress.start();
		// const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        // isLoading.value = true;
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
			const { data, status } = await Api.get('/ops/vessel-particulars', {
				params: {
					page: filterOptions.page,
					items_per_page: filterOptions.items_per_page,
					data: JSON.stringify(filterOptions)
				 }
			});
			vesselParticulars.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			//notification.showError(status);
		} finally {
			//NProgress.done();
			// loader.hide();
			// isLoading.value = false;
			// loader.hide();
            // isLoading.value = false;
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

	async function storeVesselParticular(form) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		let formData = new FormData();
		formData.append('attachment', form.attachment);
		formData.append('info', JSON.stringify(form));

		try {
			const { data, status } = await Api.post('/ops/vessel-particulars', formData);
			// vesselParticular.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.vessel-particulars.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function showVesselParticular(vesselParticularId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/ops/vessel-particulars/${vesselParticularId}`);
			vesselParticular.value = data.value;
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

	async function updateVesselParticular(form, vesselParticularId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		let formData = new FormData();
		formData.append('attachment', form.attachment);
		formData.append('info', JSON.stringify(form));
        formData.append('_method', 'PUT');

		try {
			const { data, status } = await Api.post(
				`/ops/vessel-particulars/${vesselParticularId}`,
				formData
			);
			// vesselParticular.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.vessel-particulars.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function deleteVesselParticular(vesselParticularId) {
		
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/ops/vessel-particulars/${vesselParticularId}`);
			notification.showSuccess(status);
			await getVesselParticulars(filterParams.value);
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
	async function getVesselParticularsByNameOrCode(name_or_code, service = null) {
		NProgress.start();
		//const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data } = await Api.post(
				'dataencoding/ports/get-ports-by-name-or-code',
				{ name_or_code , service }
			);
			vesselParticulars.value = data.value;
			vesselParticular.value = data.value;
		} catch (error) {
			error.value = Error.showError(error);
		} finally {
			//loader.hide();
			isLoading.value = false;
			NProgress.done();
		}
	}

	async function downloadGeneralParticular(vesselName, vesselParticularId) {
		isTableLoading.value = true
		axios({
            url: 'ops/export-particular-report?id=' + vesselParticularId,
            method: 'GET',
            responseType: 'blob', // important
        }).then((response) => {

            //stream pdf file to new tab start
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', vesselName + " - Particulars" + ".xlsx");
            document.body.appendChild(link);
            link.click();
            //stream pdf file to new tab end
        }).catch((error) => {
            if (error.response.status === 422) {
                const reader = new FileReader();
                reader.onload = function() {
                    const data = JSON.parse(reader.result);
                    const message = data.message;
                    console.log("Response message: " + message);
                    notification.showError(error.response.status, '', message);
                }
                reader.readAsText(error.response.data);
            } else {
                notification.showError(error.response.status, '', error.response.statusText);
            }
        }).finally(() => {
            // NProgress.done();
            // isLoading.value = false;
			isTableLoading.value = false

        });
	}

	async function downloadChartererParticular(vesselName, vesselParticularId) {
		isTableLoading.value = true

		axios({
            url: 'ops/particular-charterer-download?id=' + vesselParticularId,
            method: 'GET',
            responseType: 'blob', // important
        }).then((response) => {
            let dateTime = new Date();

            //stream pdf file to new tab start
			const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', vesselName + " - Charterer Particulars" + ".xlsx");
            document.body.appendChild(link);
            link.click();
            //stream pdf file to new tab end
        }).catch((error) => {
            if (error.response.status === 422) {
                const reader = new FileReader();
                reader.onload = function() {
                    const data = JSON.parse(reader.result);
                    const message = data.message;
                    console.log("Response message: " + message);
                    notification.showError(error.response.status, '', message);
                }
                reader.readAsText(error.response.data);
            } else {
                notification.showError(error.response.status, '', error.response.statusText);
            }
        }).finally(() => {
            // NProgress.done();
            // isLoading.value = false;
			isTableLoading.value = false

        });
	}

	return {
		vesselParticulars,
		vesselParticular,
		getVesselParticulars,
		storeVesselParticular,
		showVesselParticular,
		updateVesselParticular,
		deleteVesselParticular,
		getVesselParticularsByNameOrCode,
		downloadGeneralParticular,
		downloadChartererParticular,
		isLoading,
		isTableLoading,
		errors,
	};
}
