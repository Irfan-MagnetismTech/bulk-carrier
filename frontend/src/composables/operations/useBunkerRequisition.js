import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api.js';
import Error from '../../services/error.js';
import useNotification from '../useNotification.js';
import Swal from "sweetalert2";

export default function useBunkerRequisition() {
	const router = useRouter();
	const bunkerRequisitions = ref([]);
	const $loading = useLoading();
	const notification = useNotification();

	const bunkerRequisition = ref({		
		business_unit: null,
		ops_vessel_id: null,
		ops_voyage_id: null,
		requisition_no: null,
		created_by: null,
		approved_by: null,
		water_density: null,
		remarks: null,
		note: null,
		status: null,
		opsVoyage: null,
		opsBunkers: [],
	});
	const requisitionBunker = ref(null);
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
		

		let total = form.opsBunkers.reduce((accumulator, currentObject) => {
			return accumulator + (currentObject.quantity ? parseFloat(currentObject.quantity) : 0);
		  }, 0)

		  if (total<1) {
			Swal.fire({
				icon: "",
				title: "Correct Please!",
				html: `You must provide quantity for at leat one time. 
					`,
				customClass: "swal-width",
			});
			return;
		  } 


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
			const { data, status } = await Api.put(
				`/ops/bunker-requisitions/${bunkerRequisitionId}`,
				form
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

	async function approvedBunkerRequisition(form, bunkerRequisitionId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.put(
				`/ops/bunker-requisitions-approved/${bunkerRequisitionId}`,
				form
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

	async function searchBunkerRequisitions(searchParam, business_unit, loading) {
		//NProgress.start();
        isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/ops/search-bunker-requisitions?requisition_no=${searchParam}&business_unit=${business_unit}`);
			
			bunkerRequisitions.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			isLoading.value = false;
			//NProgress.done();
		}
	}


	async function searchBunkerRequisitionsByVendor(searchParam) {
		//NProgress.start();
		try {
			const { data, status } = await Api.get(`/ops/search-bunker-requisitions-by-vendor?scm_vendor_id=${searchParam}`);
			bunkerRequisitions.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function searchBunkersByPrNo(searchParam) {
		//NProgress.start();
		try {
			const { data, status } = await Api.get(`/ops/search-bunker-requisitions-by-pr-no?pr_no=${searchParam}`);
			
			requisitionBunker.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			isLoading.value = false;
			//NProgress.done();
		}
	}

	return {
		bunkerRequisitions,
		bunkerRequisition,
		requisitionBunker,
		getBunkerRequisitions,
		storeBunkerRequisition,
		showBunkerRequisition,
		updateBunkerRequisition,
		deleteBunkerRequisition,
		searchBunkerRequisitions,
		approvedBunkerRequisition,
		searchBunkerRequisitionsByVendor,
		searchBunkersByPrNo,
		isLoading,
		isTableLoading,
		errors,
	};
}
