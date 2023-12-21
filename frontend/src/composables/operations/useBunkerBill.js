import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api.js';
import Error from '../../services/error.js';
import useNotification from '../useNotification.js';
import Swal from "sweetalert2";

export default function useBunkerBill() {
	const router = useRouter();
	const bunkerBills = ref([]);
	const $loading = useLoading();
	const notification = useNotification();
	// const bunkerObject = {
    //     pr_no : null,
    //     description: null,
    //     // amount: null,
    //     // exchange_rate_bdt: null,
    //     // exchange_rate_usd: null,
    //     amount_usd: null,
    //     amount_bdt: null,
    //     sub_total: null,
	// }

	

	const bunkerObjectItem = {
		currency: '',
        particular: null,
		quantity : null,
		rate : null,
        amount_usd: null,
        amount_bdt: null,
	}

	const bunkerObject = {
		currency: '',
        pr_no : null,
        description: null,
        // amount: null,
        exchange_rate_bdt: null,
        exchange_rate_usd: null,
        amount_usd: null,
        amount_bdt: null,
		opsBunkerBillLineItems:[],
		requisitionBunkers: []
	}

	const bunkerBill = ref({	
		business_unit: null,
		date :null,
        scm_vendor_id : null,
        vendor_bill_no : null,
        remarks : null,
        attachment : null,
        smr_file_path : null,
        sub_total : null,
        discount : null,
        grand_total : null,
		grand_total_bdt: null,
		sub_total_bdt: null,
		discount_bdt: null,
		scmVendor: [],
		opsBunkerBillLines: [{...bunkerObject}],
	});

	const filterParams = ref(null);
	const errors = ref(null);
	const isLoading = ref(false);
	const isTableLoading = ref(false);

	async function getBunkerBills(filterOptions) {
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
			const { data, status } = await Api.get('/ops/bunker-bills', {
				params: {
					page: filterOptions.page,
                    items_per_page: filterOptions.items_per_page,
                    data: JSON.stringify(filterOptions)
				}
			});
			bunkerBills.value = data.value;
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

	async function storeBunkerBill(form) {
		if (!checkUniqueArray(form)) return;

		let showAlert = false;
		form.opsBunkerBillLines.reduce((acc, billLine) => {
			return acc + billLine.opsBunkerBillLineItems.reduce((innerAcc, lineItem) => {
			  if(!(lineItem.amount_bdt > 0) || !(lineItem.amount_usd > 0)) {
				
				showAlert = true;
			  }
			}, 0);
		  }, 0);

		  if (showAlert) {
			Swal.fire({
				icon: "",
				title: "Correct Please!",
				html: `Exchange rate and BDT Amount is required.`,
				customClass: "swal-width",
			});
			return;
		  } 

		//NProgress.start();


		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;
	
		let formData = new FormData();
		formData.append('attachment', form.attachment);
		formData.append('smr_file_path', form.smr_file_path);
		formData.append('info', JSON.stringify(form));

		try {
			const { data, status } = await Api.post('/ops/bunker-bills', formData);
			notification.showSuccess(status);
			await router.push({ name: 'ops.bunker-bills.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function showBunkerBill(bunkerBillId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/ops/bunker-bills/${bunkerBillId}`);
			bunkerBill.value = data.value;
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

	async function updateBunkerBill(form, bunkerBillId) {
		if (!checkUniqueArray(form)) return;

		let showAlert = false;
		form.opsBunkerBillLines.reduce((acc, billLine) => {
			return acc + billLine.opsBunkerBillLineItems.reduce((innerAcc, lineItem) => {
			  if(!(lineItem.amount_bdt > 0) || !(lineItem.amount_usd > 0)) {
				
				showAlert = true;
			  }
			}, 0);
		  }, 0);

		  if (showAlert) {
			Swal.fire({
				icon: "",
				title: "Correct Please!",
				html: `Exchange rate and BDT Amount is required.`,
				customClass: "swal-width",
			});
			return;
		  } 

		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		let formData = new FormData();
		formData.append('attachment', form.attachment);
		formData.append('smr_file_path', form.smr_file_path);
		formData.append('info', JSON.stringify(form));		
        formData.append('_method', 'PUT');

		try {
			const { data, status } = await Api.post(
				`/ops/bunker-bills/${bunkerBillId}`,
				formData
			);

			notification.showSuccess(status);
			await router.push({ name: 'ops.bunker-bills.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}


	async function approvedBunkerBill(form, bunkerBillId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.put(
				`/ops/bunker-bills-approved/${bunkerBillId}`,
				form
			);

			notification.showSuccess(status);
			await router.push({ name: 'ops.bunker-bills.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}
	

	async function deleteBunkerBill(bunkerBillId) {
		
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/ops/bunker-bills/${bunkerBillId}`);
			notification.showSuccess(status);
			await getBunkerBills(filterParams.value);
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

	async function searchBunkerBills(searchParam, loading) {
		//NProgress.start();

		try {
			const { data, status } = await Api.get(`/ops/search-bunker-bills?vendor_bill_no=${searchParam}`);
			bunkerBills.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			isLoading.value = false;
			//NProgress.done();
		}
	}

	function checkUniqueArray(form) {
		// console.log(form);
		// return false;

        let isHasError = false;
        const messages = ref([]);
        const hasDuplicates = form.opsBunkerBillLines.some((opsBunkerBillLine, index) => {

            if (form.opsBunkerBillLines.filter(val => val.ops_bunker_requisition_id === opsBunkerBillLine.ops_bunker_requisition_id)?.length > 1) {
                let data = `Duplicate Requisition [requisition data record no: ${index + 1}]`;
                messages.value.push(data);
            }

			const hasChildError = form.opsBunkerBillLines[index].opsBunkerBillLineItems.some((bunkerLineItem, lineIndex) => {
				if (form.opsBunkerBillLines[index].opsBunkerBillLineItems.filter(val => val.requisition_material === bunkerLineItem.requisition_material)?.length > 1) {
					let data = `Duplicate Requisition Material [requisition data record no: ${index + 1} and bunker record no: ${lineIndex + 1}]`;
					messages.value.push(data);
				} 
			});


		});

		if (messages.value.length > 0) {
            let rawHtml = ` <ul class="text-left list-disc text-red-500 mb-3 px-5 text-base"> `;
            if (Object.keys(messages.value).length) {
                for (const property in messages.value) {
                    rawHtml += `<li> ${messages.value[property]} </li>`
                }
                rawHtml += `</ul>`;

                Swal.fire({
                    icon: "",
                    title: "Correct Please!",
                    html: `
                ${rawHtml}
                        `,
                    customClass: "swal-width",
                });
                return false;
            }
        } else {
            return true;
        }
	}

	return {
		bunkerBills,
		bunkerBill,
		getBunkerBills,
		storeBunkerBill,
		showBunkerBill,
		updateBunkerBill,
		deleteBunkerBill,
		searchBunkerBills,
		approvedBunkerBill,
		bunkerObject,
		bunkerObjectItem,
		isLoading,
		isTableLoading,
		errors,
	};
}