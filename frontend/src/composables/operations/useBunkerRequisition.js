import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api.js';
import Error from '../../services/error.js';
import useNotification from '../useNotification.js';

export default function useBunkerRequisition() {
	const router = useRouter();
	const bunkerRequisitions = ref([]);
	const $loading = useLoading();
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

	const bunkerRequisition = ref({
		
		business_unit: '',
		ops_vessel_id: '',
		ops_voyage_id: '',
		requisition_no: '',
		created_by: '',
		water_density: '',
		remarks: '',
		status: '',
		opsVoyage: '',
		opsBunkers: [],
	});

	const filterParams = ref(null);
	const errors = ref(null);
	const isLoading = ref(false);
	const isTableLoading = ref(false);

	async function getBunkerRequisitions(filterOptions) {
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
			const { data, status } = await Api.get('/ops/bunker-requisitions', {
				params: {
					page: filterOptions.page,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
				}
			});
			bunkerRequisitions.value = data.value;
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

	async function storeBunkerRequisition(form) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.post('/ops/bunker-requisitions', form);
			notification.showSuccess(status);
			await router.push({ name: 'ops.bunker-requisitions.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function showBunkerRequisition(bunkerRequisitionId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/ops/bunker-requisitions/${bunkerRequisitionId}`);
			bunkerRequisition.value = data.value;
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

	async function updateBunkerRequisition(form, bunkerRequisitionId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {

			let formData = new FormData();
			
			// form.opsVoyageBoatNoteLines.map((element, index) => {
			// 	formData.append('attachments['+index+']', element.attachment ?? null);
			// 	element.attachment = null;
			// })


			// formData.append('info', JSON.stringify(form));
			formData.append('_method', 'PUT');

			const { data, status } = await Api.post(
				`/ops/bunker-requisitions/${bunkerRequisitionId}`,
				formData
			);

			notification.showSuccess(status);
			await router.push({ name: 'ops.bunker-requisitions.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function deleteBunkerRequisition(bunkerRequisitionId) {
		
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/ops/bunker-requisitions/${bunkerRequisitionId}`);
			notification.showSuccess(status);
			await getBunkerRequisitions(filterParams.value);
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

	async function searchBunkerRequisitions(searchParam, loading) {
		//NProgress.start();

		try {
			const { data, status } = await Api.get(`/ops/search-bunker-requisitions?requisition_no=${searchParam}`);
			bunkerRequisitions.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			loading(false);
			//NProgress.done();
		}
	}

	return {
		bunkerRequisitions,
		bunkerRequisition,
		bunkerObject,
		getBunkerRequisitions,
		storeBunkerRequisition,
		showBunkerRequisition,
		updateBunkerRequisition,
		deleteBunkerRequisition,
		searchBunkerRequisitions,
		isLoading,
		isTableLoading,
		errors,
	};
}
