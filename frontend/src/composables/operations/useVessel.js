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

	const bunkerObject = {
		name: '',
        material_code: '',
        scm_material_category_id: '',
        scm_material_category_name: '',
        unit: '',
        hs_code: '',
        minimum_stock: 0,
        store_category: '',
        description: '',
        sample_photo: null,
		is_new: true
	}
	const errors = ref(null);
	const isLoading = ref(false);
	const isVesselLoading = ref(false);

	const indexPage = ref(null);
	const indexBusinessUnit = ref(null);
    const filterParams = ref(null);
	const isTableLoading = ref(false);

	async function getVessels(filterOptions) {
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
			const { data, status } = await Api.get('/ops/vessels', {
				params: {
					page: filterOptions.page,
					items_per_page: filterOptions.items_per_page,
					data: JSON.stringify(filterOptions)
				 }
			});
			vessels.value = data.value;
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

	async function storeVessel(form) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		form.opsVesselCertificates.map((element) => {
			element.ops_maritime_certification_id = element?.certificate?.id
			element.business_unit = form.business_unit
		})

		form.opsBunkers.map((element) => {
			element.scm_material_id = element.bunker?.id
		})

		try {
			const { data, status } = await Api.post('/ops/vessels', form);
			// vessel.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.vessels.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function showVessel(vesselId, LoadingStatus = true) {
		//NProgress.start();
		var loader = {};
		if(LoadingStatus) {
			// loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
			isLoading.value = LoadingStatus;
			isVesselLoading.value = true;
		}
		

		try {
			const { data, status } = await Api.get(`/ops/vessels/${vesselId}`);
			vessel.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			if(LoadingStatus) {
				// loader.hide();
				isLoading.value = false;
				isVesselLoading.value = false;
			}
			//NProgress.done();
		}
	}

	async function updateVessel(form, vesselId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		form.opsVesselCertificates.map((element) => {
			element.ops_maritime_certification_id = element?.certificate?.id
			element.business_unit = form.business_unit
		})

		form.opsBunkers.map((element) => {
			element.scm_material_id = element.bunker?.id
		})
		
		try {
			const { data, status } = await Api.put(
				`/ops/vessels/${vesselId}`,
				form
			);
			// vessel.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.vessels.index' });
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
		// const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/ops/vessels/${vesselId}`);
			notification.showSuccess(status);
			await getVessels(filterParams.value);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			// loader.hide();
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

	async function searchVessels(searchParam, businessUnit) {
		//NProgress.start();
		isVesselLoading.value = true;
		try {
			const { data, status } = await Api.get(`/ops/search-vessels?name_or_code=${searchParam}&business_unit=${businessUnit}`);
			vessels.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			//loading(false)
			//NProgress.done();
			isVesselLoading.value = false;
		}
	}

	async function getVesselsWithoutPaginate(businessUnit) {
		// NProgress.start();
		isLoading.value = true;
		isVesselLoading.value = true;

		try {
			const { data, status } = await Api.get(`/ops/get-vessels?business_unit=${businessUnit}`);
			vessels.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			// loading(false)
			// NProgress.done();
			isLoading.value = false;
			isVesselLoading.value = false;
		}
	}

	async function getVesselList(businessUnit) {
		//NProgress.start();

		try {
			const { data, status } = await Api.get(`/ops/get-search-vessels?business_unit=${businessUnit}`);

			vessels.value = data.value;
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
		vessels,
		vessel,
		vesselName,
		getVessels,
		maritimeCertificateObject,
		bunkerObject,
		storeVessel,
		showVessel,
		updateVessel,
		deleteVessel,
		searchVessels,
		getVesselList,
		getVesselsByNameOrCode,
		voyageVessels,
		getVesselsByVoyage,
		getVesselsWithoutPaginate,
		isLoading,
		isTableLoading,
		isVesselLoading,
		errors,
	};
}
