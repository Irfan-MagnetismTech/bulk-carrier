import NProgress from 'nprogress';
import {useLoading} from 'vue-loading-overlay';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Api from '../../apis/Api';
import Error from '../../services/error';
import useNotification from '../../composables/useNotification.js';

export default function useSlotPartner() {
	const router = useRouter();
	const $loading = useLoading();
	const notification = useNotification();
	const partners = ref([]);
	const slotPartnerCode = ref([]);
	const partner = ref({
		code: '',
		name: '',
		principal_office_address: '',
		country: '',
		type: ''
	});
	const errors = ref(null);
	const isLoading = ref(false);

	async function getSlotPartner(page, form = null) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const {data, status} = await Api.get('/commercial/slot-partners', {
				params: {
					page: page || 1,
					code: form.code || null,
					type: form.type || null,
					page_view_type: form.page_view_type || null,
				},
			});
			partners.value = data.value;
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

	async function storeSlotPartner(form) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.post('/commercial/slot-partners', form);
			partners.value = data.value;
			notification.showSuccess(status);
			router.push({ name: 'dataencoding.slot-partner.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function showSlotPartner(partnerId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.get(`/commercial/slot-partners/${partnerId}`);
			partner.value = data.value;
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

	async function updateSlotPartner(form, partnerId) {
		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.put(
				`/commercial/slot-partners/${partnerId}`,
				form
			);
			notification.showSuccess(status);
			router.push({ name: 'dataencoding.slot-partner.index' });
		} catch (error) {
			const { data, status } = error.response;
			errors.value = notification.showError(status, data);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	async function deleteSlotPartner(partnerId) {
		if (!confirm('Are you sure you want to delete this slot partner?')) {
			return;
		}

		//NProgress.start();
		const loader = $loading.show({'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2'});
		isLoading.value = true;

		try {
			const { data, status } = await Api.delete( `/commercial/slot-partners/${partnerId}`);
			notification.showSuccess(status);
			await getSlotPartner();
		} catch (error) {
			const { data, status } = error.response;
			notification.showError(status);
		} finally {
			loader.hide();
			isLoading.value = false;
			//NProgress.done();
		}
	}

	function getSlotPartnerWithoutPaginate() {
		NProgress.start();
		isLoading.value = true;

		Api.get("/commercial/slot-partners/without/paginate")
			.then((response) => {
				partners.value = response.data.value;
			})
			.catch((error) => {
				error.value = Error.showError(error);
			})
			.finally(() => {
				isLoading.value = false;
				NProgress.done();
			});
	}

	// Get ports by name or code
	async function getSlotPartnerByNameOrCode(name_or_code) {
		NProgress.start();
		isLoading.value = true;

		try {
			const { data } = await Api.post(
				'commercial/slot-partners/get-slot-partners-by-name-or-code',
				{ name_or_code }
			);
			slotPartnerCode.value = data.value;
			//console.log(data.message);
		} catch (error) {
			error.value = Error.showError(error);
		} finally {
			isLoading.value = false;
			NProgress.done();
		}
	}

	return {
		partners,
		partner,
		getSlotPartner,
		storeSlotPartner,
		slotPartnerCode,
		showSlotPartner,
		updateSlotPartner,
		deleteSlotPartner,
		getSlotPartnerWithoutPaginate,
		getSlotPartnerByNameOrCode,
		isLoading,
		errors,
	};
}