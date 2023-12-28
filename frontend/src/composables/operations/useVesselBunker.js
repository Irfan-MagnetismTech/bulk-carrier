import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api.js';
import Error from '../../services/error.js';
import useNotification from '../useNotification.js';
import Swal from "sweetalert2";

export default function useVesselBunker() {
	const router = useRouter();
	const vesselBunkers = ref([]);
	const $loading = useLoading();
    const isTableLoading = ref(false);
	const notification = useNotification();

	const vesselBunker = ref( {
		business_unit: null,
		opsVessel: null,
		ops_vessel_id: null,
		opsVoyage: null,
		ops_voyage_id: null,
        type: '',
        usage_type: '',
		currency: '',
		exchange_rate_usd: '',
		exchange_rate_bdt: '',
		opsBunkers: [
		],
		bunkerItems: []
    });
    const errors = ref('');
    const isLoading = ref(false);
    const filterParams = ref(null);

	async function getVesselBunkers(filterOptions) {
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
        filterParams.value = filterOptions;

		try {
			const {data, status} = await Api.get(`/ops/vessel-bunkers`,{
                params: {
                   page: filterOptions.page,
                   items_per_page: filterOptions.items_per_page,
                   data: JSON.stringify(filterOptions)
                }
            });
			vesselBunkers.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
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

	async function storeVesselBunker(form) {
		if((form.currency == 'USD' && form.exchange_rate_bdt == null) || form.currency == 'BDT' && form.exchange_rate_usd == null) {
			Swal.fire({
				icon: "",
				title: "Correct Please!",
				html: `Currency Info Mismatch
					`,
				customClass: "swal-width",
			});
			return;
		}

		// if (!checkUniqueArray(form)) return;

		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {

			const { data, status } = await Api.post('/ops/vessel-bunkers', form);
			notification.showSuccess(status);
			router.push({ name: 'ops.vessel-bunkers.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function showVesselBunker(vesselBunkerId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/ops/vessel-bunkers/${vesselBunkerId}`);
			vesselBunker.value = data.value;
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

	async function updateVesselBunker(form, vesselBunkerId) {
		//NProgress.start();
		if((form.currency == 'USD' && form.exchange_rate_bdt == null) || form.currency == 'BDT' && form.exchange_rate_usd == null) {
			Swal.fire({
				icon: "",
				title: "Correct Please!",
				html: `Currency Info Mismatch
					`,
				customClass: "swal-width",
			});
			return;
		}

		// if (!checkUniqueArray(form)) return;

		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		

		try {

			const { data, status } = await Api.put(
				`/ops/vessel-bunkers/${vesselBunkerId}`,
				form
			);
			// VesselBunker.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.vessel-bunkers.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}


	async function deleteVesselBunker(vesselBunkerId) {
		
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/ops/vessel-bunkers/${vesselBunkerId}`);
			notification.showSuccess(status);
			await getVesselBunkers(filterParams.value);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function searchVesselBunkers(searchParam, loading) {
		//NProgress.start();

		try {
			const { data, status } = await Api.get(`/ops/search-vessel-bunkers?name=${searchParam}`);
			vesselBunkers.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			loading(false)
			//NProgress.done();
		}
	}

	
	// function checkUniqueArray(form) {
	// 	// console.log(form);
	// 	// return false;

        // let isHasError = false;
        // const messages = ref([]);
        // const hasDuplicates = form.opsBunkers.some((opsVoyageExpenseHead, index) => {
        //     if (form.opsBunkers.filter(val => val.scm_material_id === opsVoyageExpenseHead.scm_material_id)?.length > 1) {
        //         let data = `Duplicate Expense [Expense Data line no: ${index + 1}]`;
        //         messages.value.push(data);
        //         form.opsBunkers[index].isExpenseHeadDuplicate = true;
        //     } else {
        //         form.opsBunkers[index].isExpenseHeadDuplicate = false;
        //     }
		// });


	// 	if (messages.value.length > 0) {
    //         let rawHtml = ` <ul class="text-left list-disc text-red-500 mb-3 px-5 text-base"> `;
    //         if (Object.keys(messages.value).length) {
    //             for (const property in messages.value) {
    //                 rawHtml += `<li> ${messages.value[property]} </li>`
    //             }
    //             rawHtml += `</ul>`;

    //             Swal.fire({
    //                 icon: "",
    //                 title: "Correct Please!",
    //                 html: `
    //             ${rawHtml}
    //                     `,
    //                 customClass: "swal-width",
    //             });
    //             return false;
    //         }
    //     } else {
    //         return true;
    //     }
	// }

	return {
		vesselBunkers,
		vesselBunker,
		getVesselBunkers,
		storeVesselBunker,
		showVesselBunker,
		updateVesselBunker,
		deleteVesselBunker,
		searchVesselBunkers,
		isTableLoading,
		isLoading,
		errors,
	};
}
