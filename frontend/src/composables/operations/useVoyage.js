import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api';
import Error from '../../services/error';
import useNotification from '../useNotification.js';

export default function useVoyage() {
	const router = useRouter();
	const voyages = ref([]);
	const $loading = useLoading();
	const voyageName = ref([]);
	const voyageVoyages = ref([]);
	const notification = useNotification();

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
		quantity: null
	}

	const portScheduleObject = {
		ops_voyage_id: '',
        port_code: '',
        eta: '',
        etb: '',
        etd: '',
        load_commence: '',
        load_complete: '',
        unload_commence: '',
        unload_complete: '',
        operation_type: ''
	}

	const voyageSectorObject = {
		ops_voyage_id: '',
        ops_cargo_tariff_id: '',
        loading_point: '',
        unloading_point: '',
        rate: '',
        initial_survey_qty: '',
        approx_amount: '',
        discount: '',
        discount_amount: '',
        approx_amount_after_disscount: '',
        final_survey_qty: '',
        final_received_qty: '',
        boat_note_qty: '',
        business_unit: ''
	}

	const voyage = ref({
		ops_customer_id: '',
		ops_vessel_id: '',
		mother_vessel: '',
		ops_cargo_type_id: '',
		voyage_no: '',
		route: '',
		load_port_distance: '',
		sail_date: '',
		transit_date: '',
		remarks: '',
		opsBunkers: [{...bunkerObject}],
		opsVoyageSectors: [{...voyageSectorObject}],
		opsVoyagePortSchedules: [{...portScheduleObject}]
	});
	
	const filterParams = ref(null);
	const errors = ref(null);
	const isLoading = ref(false);
	const isVoyageLoading = ref(false);
	const isTableLoading = ref(false);

	async function getVoyages(filterOptions) {
		//NProgress.start();
		// const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
        // isLoading.value = true;
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
			const { data, status } = await Api.get('/ops/voyages', {
				params: {
					page: filterOptions.page,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
				},
			});
			voyages.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			//notification.showError(status);
		} finally {
			//NProgress.done();
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

	async function storeVoyage(form) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		// form.opsVoyageCertificates.map((element) => {
		// 	element.ops_maritime_certification_id = element.id
		// 	element.business_unit = form.business_unit
		// })

		// form.opsBunkers.map((element) => {
		// 	element.scm_material_id = element.id
		// })

		try {
			const { data, status } = await Api.post('/ops/voyages', form);
			voyage.value = data.value;
			notification.showSuccess(status);
			await router.push({ name: 'ops.voyages.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function showVoyage(voyageId) {
		//NProgress.start();
		// const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2', 'is-full-page': false});
		isLoading.value = true;
		isTableLoading.value = true;

		try {
			const { data, status } = await Api.get(`/ops/voyages/${voyageId}`);
			voyage.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			// loader.hide();
			isLoading.value = false;
			isVoyageLoading.value = false;
			//NProgress.done();
		}
	}

	async function updateVoyage(form, voyageId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		// form.opsVoyageCertificates.map((element) => {
		// 	element.ops_maritime_certification_id = element.id
		// 	element.business_unit = form.business_unit
		// })

		// form.opsBunkers.map((element) => {
		// 	element.scm_material_id = element.id
		// })
		
		try {
			const { data, status } = await Api.put(
				`/ops/voyages/${voyageId}`,
				form
			);
			voyage.value = data.value;
			notification.showSuccess(status);
			await router.push({ name: 'ops.voyages.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function deleteVoyage(voyageId) {
		
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/ops/voyages/${voyageId}`);
			notification.showSuccess(status);
			await getVoyages(filterParams.value);
		} catch (error) {
			const { data, status } = error.response;
			// notification.showError(status);
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	// Get voyages by name or code
	async function getVoyagesByNameOrCode(name_or_code, service = null) {
		NProgress.start();
		//const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data } = await Api.post(
				'dataencoding/voyages/get-voyages-by-name-or-code',
				{ name_or_code , service }
			);
			voyages.value = data.value;
			voyageName.value = data.value;
		} catch (error) {
			error.value = Error.showError(error);
		} finally {
			//loader.hide();
			isLoading.value = false;
			NProgress.done();
		}
	}

	async function getVoyagesByVoyage(voyage_id) {
		NProgress.start();
		//const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/common/voyage-voyages/${voyage_id}`);
			voyageVoyages.value = data;
			notification.showSuccess(status);
		} catch (error) {
			error.value = Error.showError(error);
		} finally {
			//loader.hide();
			isLoading.value = false;
			NProgress.done();
		}
	}

	async function searchVoyages(searchParam, businessUnit, loading, vesselId = null) {
		//NProgress.start();
		isVoyageLoading.value = true;
		try {
			const { data, status } = await Api.get(`/ops/get-search-voyages?voyage_no=${searchParam}&business_unit=${businessUnit}&vessel_id=${vesselId}`);
			voyages.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			// loading(false)
			//NProgress.done();
			isVoyageLoading.value = false;
		}
	}
	

	async function getVoyagesWithoutPaginate(businessUnit) {
		NProgress.start();
		isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/ops/get-voyages?business_unit=${businessUnit}`);
			voyages.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			// loading(false)
			NProgress.done();
		}
	}

	async function getVoyageList(businessUnit, opsVesselId) {
		//NProgress.start();

		try {
			const { data, status } = await Api.get(`/ops/get-search-voyages?business_unit=${businessUnit}&ops_vessel_id=${opsVesselId}`);

			voyages.value = data?.value;

			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
			console.log(error)
		} finally {
			// loading(false)
			//NProgress.done();
		}
	}

	function checkValidation(openTab, tabNumber, props, requiredFields) {
        for (const field of requiredFields) {
            const element = document.getElementById(field);
            if (openTab.value === 1) {
                if(!props.form[field]){
                    notification.showError(422, '', 'Please fill all required fields');
                    return false;
                }
            }
            else if(openTab.value === 2){
                let sectorFieldStatus = true;
                props.form.opsVoyageSectors.forEach((value, index) => {
                    if(!props.form.opsVoyageSectors[index][field]){
                        sectorFieldStatus = false;
                    }
                });
                if(!sectorFieldStatus){
                    notification.showError(422, '', 'Please fill all required fields');
                    return false;
                }
			}
			
            else if(openTab.value === 4){
                let voyageScheduleFieldStatus = true;
                props.form.opsVoyagePortSchedules.forEach((value, index) => {
                    if(!props.form.opsVoyagePortSchedules[index][field]){
                        voyageScheduleFieldStatus = false;
                    }
                });
                if(!voyageScheduleFieldStatus){
                    notification.showError(422, '', 'Please fill all required fields');
                    return false;
                }
			}
			
            
        }

        return true;
    }


	return {
		voyages,
		voyage,
		voyageName,
		getVoyageList,
		getVoyages,
		portScheduleObject,
		voyageSectorObject,
		bunkerObject,
		storeVoyage,
		showVoyage,
		updateVoyage,
		deleteVoyage,
		searchVoyages,
		getVoyagesByNameOrCode,
		voyageVoyages,
		getVoyagesByVoyage,
		getVoyagesWithoutPaginate,
		checkValidation,
		isLoading,
		isVoyageLoading,
		isTableLoading,
		errors,
	};
}
