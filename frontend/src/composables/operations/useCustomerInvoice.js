import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api.js';
import Error from '../../services/error.js';
import useNotification from '../useNotification.js';
import Swal from "sweetalert2";


export default function useCustomerInvoice() {
	const router = useRouter();
	const customerInvoices = ref([]);
	const $loading = useLoading();
    const isTableLoading = ref(false);
	const notification = useNotification();

	const customerInvoice = ref({
		business_unit: '',
		ops_customer_profile_id: null,
		opsCustomerProfile: null,
		ops_customer_contract_id: null,
		opsCustomerContract: null,
		ops_voyage_id: null,
		opsVoyage: null,
		contract_type: null,
		cargo_quantity: 0,
        bill_from: null,
        bill_till: null,
        total_days: null,
		total_amount: null,
		per_day_charge: 0,
        others_billable_amount: null,
		others_billable_amount_usd: null,
		sub_total_amount: null,
		sub_total_amount_usd: null,
		service_fee_deduction_amount: null,
		service_fee_deduction_amount_usd: null,
		discount_unit: null,
		discounted_amount: null,
		discounted_amount_usd: null,
		grand_total: null,
		grand_total_usd: null,
		opsCustomerInvoiceServices: [
			// {
			// 	charge_or_deduct: 'deduct',
			// 	particular: null,
			// 	cost_unit: null,
			// 	currency: null,
			// 	quantity: 0,
			// 	rate: 0,
			// 	exchange_rate_bdt: 0,
			// 	exchange_rate_usd: 0,
			// 	amount: 0,
			// 	amount_bdt: 0,
			// 	amount_usd: 0,
			// },
		],
		opsCustomerInvoiceOthers: [
			// {
			// 	charge_or_deduct: 'charge',
			// 	particular: null,
			// 	cost_unit: null,
			// 	currency: null,
			// 	quantity: 0,
			// 	rate: 0,
			// 	exchange_rate_bdt: 0,
			// 	exchange_rate_usd: 0,
			// 	amount: 0,
			// 	amount_bdt: 0,
			// 	amount_usd: 0,
			// },
		],
		opsCustomerInvoiceVoyages: [{
			'ops_voyage_id': null,
			'opsVoyage': null,
			'cargo_quantity': 0,
			'rate_per_mt': 0,
			'total_amount': 0,
		}],
	});


	const customerInvoiceVoyageObject = {
		'ops_voyage_id': null,
		'opsVoyage': null,
		'cargo_quantity': 0,
		'rate_per_mt': 0,
		'total_amount': 0,
	};

	const serviceObject = {
		charge_or_deduct: 'deduct',
		particular: null,
		cost_unit: null,
		currency: null,
		quantity: 0,
		rate: 0,
		exchange_rate_bdt: 0,
		exchange_rate_usd: 0,
		amount: 0,
		amount_bdt: 0,
		amount_usd: 0,
	};

	const otherObject = {
		charge_or_deduct: 'charge',
		particular: null,
		cost_unit: null,
		currency: null,
		quantity: 0,
		rate: 0,
		exchange_rate_bdt: 0,
		exchange_rate_usd: 0,
		amount: 0,
		amount_bdt: 0,
		amount_usd: 0,
	};

    const errors = ref('');
    const isLoading = ref(false);
    const filterParams = ref(null);

	async function getCustomerInvoices(filterOptions) {
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
			const {data, status} = await Api.get(`/ops/customer-invoices`,{
                params: {
                   page: filterOptions.page,
                   items_per_page: filterOptions.items_per_page,
                   data: JSON.stringify(filterOptions)
                }
            });
			customerInvoices.value = data.value;
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

	async function storeCustomerInvoice(form) {

		if (!checkUniqueArray(form)) return;
		//NProgress.start();
		var loader = null;

		try {

			let hasError = 0;

			form.opsCustomerInvoiceServices.map((element) => {
				if(element.amount_bdt == 0) hasError++;
			})

			form.opsCustomerInvoiceOthers.map((element) => {
				if(element.amount_bdt == 0) hasError++;
			})


			if(hasError > 0) {
				Swal.fire({
					icon: "",
					title: "Correct Please!",
					html: `BDT Amount is missing.`,
					customClass: "swal-width",
				});
				return false;
			}
			loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});

			isLoading.value = true;

			let formData = new FormData();

			formData.append('info', JSON.stringify(form));

			const { data, status } = await Api.post('/ops/customer-invoices', formData);
			notification.showSuccess(status);
			router.push({ name: 'ops.customer-invoices.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function showCustomerInvoice(customerInvoiceId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/ops/customer-invoices/${customerInvoiceId}`);
			customerInvoice.value = data.value;
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

	async function updateCustomerInvoice(form, customerInvoiceId) {
		if (!checkUniqueArray(form)) return;
		//NProgress.start();
		var loader = null;
		try {

			let hasError = 0;

			form.opsCustomerInvoiceServices.map((element) => {
				if(element.amount_bdt == 0) hasError++;
			})

			form.opsCustomerInvoiceOthers.map((element) => {
				if(element.amount_bdt == 0) hasError++;
			})


			if(hasError > 0) {
				Swal.fire({
					icon: "",
					title: "Correct Please!",
					html: `BDT Amount is missing.`,
					customClass: "swal-width",
				});
				return false;
			}

			loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});

			isLoading.value = true;

			let formData = new FormData();


			formData.append('info', JSON.stringify(form));
			formData.append('_method', 'PUT');

			const { data, status } = await Api.post(
				`/ops/customer-invoices/${customerInvoiceId}`,
				formData
			);
			// customerInvoice.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.customer-invoices.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function deleteCustomerInvoice(customerInvoiceId) {
		
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/ops/customer-invoices/${customerInvoiceId}`);
			notification.showSuccess(status);
			await getCustomerInvoices(filterParams.value);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function searchCustomerInvoices(searchParam, loading) {
		//NProgress.start();

		try {
			const { data, status } = await Api.get(`/ops/search-customer-invoices?name=${searchParam}`);
			customerInvoices.value = data.value;
			notification.showSuccess(status);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			loading(false)
			//NProgress.done();
		}
	}

	function checkUniqueArray(form) {
		// console.log(form);
		// return false;

        let isHasError = false;
        const messages = ref([]);
        const hasDuplicates = form.opsCustomerInvoiceVoyages.some((opsCustomerInvoiceVoyage, index) => {
            if (form.opsCustomerInvoiceVoyages.filter(val => val.ops_voyage_id === opsCustomerInvoiceVoyage.ops_voyage_id)?.length > 1) {
                let data = `Duplicate Voyage [Voyage Data line no: ${index + 1}]`;
                messages.value.push(data);
                form.opsCustomerInvoiceVoyages[index].isVoyageDuplicate = true;
            } else {
                form.opsCustomerInvoiceVoyages[index].isVoyageDuplicate = false;
            }
		});
		
		
        const hasDuplicates2 = form.opsCustomerInvoiceOthers.some((opsCustomerInvoiceOther, index) => {
            if (form.opsCustomerInvoiceOthers.filter(val => val.particular === opsCustomerInvoiceOther.particular)?.length > 1) {
                let data = `Duplicate particular [Other line no: ${index + 1}]`;
                messages.value.push(data);
                form.opsCustomerInvoiceOthers[index].isParticularDuplicate = true;
            } else {
                form.opsCustomerInvoiceOthers[index].isParticularDuplicate = false;
            }
		});

		
        const hasDuplicates3 = form.opsCustomerInvoiceServices.some((opsCustomerInvoiceService, index) => {
            if (form.opsCustomerInvoiceServices.filter(val => val.particular === opsCustomerInvoiceService.particular)?.length > 1) {
                let data = `Duplicate particular [Services Taken From Customer line no: ${index + 1}]`;
                messages.value.push(data);
                form.opsCustomerInvoiceServices[index].isParticularDuplicate = true;
            } else {
                form.opsCustomerInvoiceServices[index].isParticularDuplicate = false;
            }
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
	
	
	function checkOtherUniqueArray(form) {
		// console.log(form);
		// return false;

        let isHasError = false;
        const messages = ref([]);
        const hasDuplicates = form.opsCustomerInvoiceVoyages.some((opsCustomerInvoiceVoyage, index) => {
            if (form.opsCustomerInvoiceVoyages.filter(val => val.ops_voyage_id === opsCustomerInvoiceVoyage.ops_voyage_id)?.length > 1) {
                let data = `Duplicate Voyage [Voyage Data line no: ${index + 1}]`;
                messages.value.push(data);
                form.opsCustomerInvoiceVoyages[index].isVoyageDuplicate = true;
            } else {
                form.opsCustomerInvoiceVoyages[index].isVoyageDuplicate = false;
            }
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
		customerInvoices,
		customerInvoice,
		getCustomerInvoices,
		storeCustomerInvoice,
		showCustomerInvoice,
		updateCustomerInvoice,
		deleteCustomerInvoice,
		customerInvoiceVoyageObject,
		searchCustomerInvoices,
		serviceObject,
		isTableLoading,
		otherObject,
		isLoading,
		errors,
	};
}
