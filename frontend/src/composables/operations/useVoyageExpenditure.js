import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api.js';
import Error from '../../services/error.js';
import useNotification from '../useNotification.js';
import Swal from "sweetalert2";

export default function useVoyageExpenditure() {
	const router = useRouter();
	const voyageExpenditures = ref([]);
	const $loading = useLoading();
    const isTableLoading = ref(false);
	const notification = useNotification();

	const expenseHeadObject = {
		ops_expense_head_id: '',
		quantity: '',
		amount: '',
		amount_usd: '',
		amount_bdt: ''
	}

	const voyageExpenditure = ref( {
		business_unit: null,
		opsVessel: null,
		ops_vessel_id: null,
		opsVoyage: null,
		ops_voyage_id: null,
        name: '',
		currency: '',
		exchange_rate_usd: '',
		exchange_rate_bdt: '',
		opsVoyageExpenditureEntries: [
			{...expenseHeadObject}
		],
    });
    const errors = ref('');
    const isLoading = ref(false);
    const filterParams = ref(null);

	async function getVoyageExpenditures(filterOptions) {
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
			const {data, status} = await Api.get(`/ops/voyage-expenditures`,{
                params: {
                   page: filterOptions.page,
                   items_per_page: filterOptions.items_per_page,
                   data: JSON.stringify(filterOptions)
                }
            });
			voyageExpenditures.value = data.value;
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

	async function storeVoyageExpenditure(form) {
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

		if (!checkUniqueArray(form)) return;

		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {

			const { data, status } = await Api.post('/ops/voyage-expenditures', form);
			notification.showSuccess(status);
			router.push({ name: 'ops.voyage-expenditures.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function showVoyageExpenditure(voyageExpenditureId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/ops/voyage-expenditures/${voyageExpenditureId}`);
			voyageExpenditure.value = data.value;
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

	async function updateVoyageExpenditure(form, voyageExpenditureId) {
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

		if (!checkUniqueArray(form)) return;

		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		

		try {

			const { data, status } = await Api.put(
				`/ops/voyage-expenditures/${voyageExpenditureId}`,
				form
			);
			// VoyageExpenditure.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'ops.voyage-expenditures.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}


	async function deleteVoyageExpenditure(voyageExpenditureId) {
		
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/ops/voyage-expenditures/${voyageExpenditureId}`);
			notification.showSuccess(status);
			await getVoyageExpenditures(filterParams.value);
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function searchVoyageExpenditures(searchParam, loading) {
		//NProgress.start();

		try {
			const { data, status } = await Api.get(`/ops/search-voyage-expenditures?name=${searchParam}`);
			voyageExpenditures.value = data.value;
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
        const hasDuplicates = form.opsVoyageExpenditureEntries.some((opsVoyageExpenseHead, index) => {
            if (form.opsVoyageExpenditureEntries.filter(val => val.ops_expense_head_id === opsVoyageExpenseHead.ops_expense_head_id)?.length > 1) {
                let data = `Duplicate Expense [Expense Data line no: ${index + 1}]`;
                messages.value.push(data);
                form.opsVoyageExpenditureEntries[index].isExpenseHeadDuplicate = true;
            } else {
                form.opsVoyageExpenditureEntries[index].isExpenseHeadDuplicate = false;
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
		expenseHeadObject,
		voyageExpenditures,
		voyageExpenditure,
		getVoyageExpenditures,
		storeVoyageExpenditure,
		showVoyageExpenditure,
		updateVoyageExpenditure,
		deleteVoyageExpenditure,
		searchVoyageExpenditures,
		isTableLoading,
		isLoading,
		errors,
	};
}
